<?php
include '../config/database.php';
require_login();
$page_title='Riwayat Pembelian';
$user_active='purchases';
$uid=current_user_id();

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['delete'])) {
    $id=(int)$_POST['id_purchase'];
    mysqli_query($conn, "DELETE FROM purchases WHERE id_purchase=$id AND id_user=$uid");
    set_flash('success','Data pembelian berhasil dihapus.');
    header('Location: '.url('user/purchases.php')); exit;
}
$purchases = db_all("SELECT p.*, e.title, t.ticket_name FROM purchases p JOIN events e ON p.id_event=e.id_event JOIN ticket_types t ON p.id_ticket=t.id_ticket WHERE p.id_user=$uid ORDER BY p.created_at DESC");
include '../includes/header.php';
show_flash();
?>
<style>
html[data-theme="light"] .action-link {
    color: #111827 !important;
    background: rgba(15, 23, 42, 0.08) !important;
    border-color: rgba(15, 23, 42, 0.2) !important;
}
html[data-theme="light"] .action-link:hover {
    background: rgba(15, 23, 42, 0.15) !important;
    color: #000000 !important;
}
</style>
<div class="dashboard-grid">
    <?php include '_user_nav.php'; ?>
    <section class="dashboard-panel">
        <div class="section-head"><div><p class="eyebrow">CRUD Pembelian Ticket</p><h2>Riwayat Pembelian</h2></div><a class="primary-btn" href="<?= url('events.php'); ?>">Booking Baru</a></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Event</th><th>Ticket</th><th>Qty</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach($purchases as $p): ?>
                    <tr>
                        <td><?= e($p['title']); ?></td>
                        <td><?= e($p['ticket_name']); ?></td>
                        <td><?= (int)$p['quantity']; ?></td>
                        <td><?= rupiah($p['total_price']); ?></td>
                        <td><span class="badge"><?= e($p['status']); ?></span></td>
                        <td>
                            <a class="action-link" href="<?= url('ticket_pdf.php?id='.(int)$p['id_purchase']); ?>">Download E-Ticket PDF</a>
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="id_purchase" value="<?= (int)$p['id_purchase']; ?>">
                                <button class="action-link" name="delete" data-confirm="Hapus data pembelian ini?">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php include '../includes/footer.php'; ?>
