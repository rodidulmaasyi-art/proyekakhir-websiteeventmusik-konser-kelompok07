<?php
include 'config/database.php';
require_login();

$id_event = (int)($_GET['event'] ?? $_POST['id_event'] ?? 0);
$id_ticket_selected = (int)($_GET['ticket'] ?? $_POST['id_ticket'] ?? 0);
$event = db_one("SELECT e.*, v.venue_name, v.city FROM events e JOIN venues v ON e.id_venue=v.id_venue WHERE e.id_event=$id_event");

if (!$event) {
    set_flash('error','Event tidak ditemukan.');
    header('Location: '.url('events.php'));
    exit;
}

$tickets = db_all("SELECT * FROM ticket_types WHERE id_event=$id_event ORDER BY FIELD(ticket_name,'Regular','VIP','VVIP','Backstage Pass')");
$errors = [];
$fieldErrors = [];
$qty = 1;
$buyer_name = $_SESSION['name'] ?? '';
$userEmail = db_one('SELECT email FROM users WHERE id_user='.current_user_id())['email'] ?? '';
$buyer_email = $userEmail;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ticket_selected = (int)($_POST['id_ticket'] ?? 0);
    $qty = (int)($_POST['quantity'] ?? 0);
    $buyer_name = trim($_POST['buyer_name'] ?? '');
    $buyer_email = trim($_POST['buyer_email'] ?? '');
    $ticket = db_one("SELECT * FROM ticket_types WHERE id_ticket=$id_ticket_selected AND id_event=$id_event");

    if (!$ticket) {
        $fieldErrors['id_ticket'] = 'Jenis tiket tidak valid.';
    }
    if ($qty < 1 || $qty > 10) {
        $fieldErrors['quantity'] = 'Jumlah tiket harus 1 sampai 10.';
    }
    if (strlen($buyer_name) < 3) {
        $fieldErrors['buyer_name'] = 'Nama pembeli minimal 3 karakter.';
    }
    if (!filter_var($buyer_email, FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['buyer_email'] = 'Email pembeli tidak valid.';
    }
    if ($ticket && ($ticket['quota'] - $ticket['sold']) < $qty) {
        $fieldErrors['quantity'] = 'Kuota tiket tidak mencukupi.';
    }

    $errors = array_values($fieldErrors);

    if (!$errors) {
        $total = $ticket['price'] * $qty;
        $qr = generate_qr_code();
        $stmt = mysqli_prepare($conn, "INSERT INTO purchases (id_user,id_event,id_ticket,quantity,total_price,buyer_name,buyer_email,status,qr_code) VALUES (?,?,?,?,?,?,?,'paid',?)");
        $uid = current_user_id();
        mysqli_stmt_bind_param($stmt,'iiiidsss',$uid,$id_event,$id_ticket_selected,$qty,$total,$buyer_name,$buyer_email,$qr);

        if (mysqli_stmt_execute($stmt)) {
            $purchase_id = mysqli_insert_id($conn);
            mysqli_query($conn, "UPDATE ticket_types SET sold=sold+$qty WHERE id_ticket=$id_ticket_selected");
            mysqli_query($conn, "UPDATE events SET seat_available=GREATEST(0,seat_available-$qty) WHERE id_event=$id_event");
            $code = 'MID-' . str_pad($purchase_id, 5, '0', STR_PAD_LEFT);
            mysqli_query($conn, "INSERT INTO payments (id_purchase,provider,transaction_code,amount,status,paid_at) VALUES ($purchase_id,'Midtrans Demo','$code',$total,'settlement',NOW())");
            set_flash('success','Booking berhasil. E-ticket sudah tersedia.');
            header('Location: '.url('user/purchases.php'));
            exit;
        } else {
            $errors[] = 'Booking gagal.';
        }
    }
}

$page_title='Booking Ticket';
include 'includes/header.php';
?>
<section class="section">
    <div class="section-head">
        <div>
            <p class="eyebrow">Digital Ticketing</p>
            <h2>Book <?= e($event['title']); ?></h2>
        </div>
        <p><?= e($event['venue_name']); ?> · <?= e($event['city']); ?></p>
    </div>

    <form method="POST" class="form-card" data-validate-form novalidate>
        <?php validation_box($errors); ?>

        <input type="hidden" name="id_event" value="<?= (int)$id_event; ?>">

        <div class="form-grid">
            <div data-field-wrap>
                <label>Ticket Type</label>
                <select class="<?= e(control_class('select', $fieldErrors, 'id_ticket')); ?>" name="id_ticket" data-validate data-required="true" data-label="Jenis tiket">
                    <?php foreach($tickets as $t): ?>
                    <option value="<?= (int)$t['id_ticket']; ?>" <?= $id_ticket_selected==(int)$t['id_ticket']?'selected':''; ?>>
                        <?= e($t['ticket_name']); ?> - <?= rupiah($t['price']); ?> - sisa <?= (int)($t['quota']-$t['sold']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php field_error($fieldErrors, 'id_ticket'); ?>
            </div>

            <div data-field-wrap>
                <label>Quantity</label>
                <input class="<?= e(control_class('input', $fieldErrors, 'quantity')); ?>" type="number" min="1" max="10" name="quantity" value="<?= (int)$qty; ?>" data-validate data-required="true" data-min-number="1" data-max-number="10" data-label="Jumlah tiket">
                <?php field_error($fieldErrors, 'quantity'); ?>
            </div>

            <div data-field-wrap>
                <label>Buyer Name</label>
                <input class="<?= e(control_class('input', $fieldErrors, 'buyer_name')); ?>" name="buyer_name" value="<?= e($buyer_name); ?>" data-validate data-required="true" data-min="3" data-label="Nama pembeli">
                <?php field_error($fieldErrors, 'buyer_name'); ?>
            </div>

            <div data-field-wrap>
                <label>Buyer Email</label>
                <input class="<?= e(control_class('input', $fieldErrors, 'buyer_email')); ?>" type="email" name="buyer_email" value="<?= e($buyer_email); ?>" data-validate data-required="true" data-email="true" data-label="Email pembeli">
                <?php field_error($fieldErrors, 'buyer_email'); ?>
            </div>
        </div>

        <div class="glass-card p-5 mt-5">
            <p class="eyebrow">Payment Gateway</p>
            <p class="meta mt-2">Integrasi Midtrans disiapkan sebagai demo. Status transaksi otomatis paid untuk kebutuhan project lokal.</p>
        </div>

        <button class="primary-btn mt-5">Pay with Midtrans Demo</button>
    </form>
</section>
<?php include 'includes/footer.php'; ?>
