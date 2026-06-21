<?php
include 'config/database.php';
$page_title = 'Events';
$active = 'events';
$status = $_GET['status'] ?? '';
$search = trim($_GET['q'] ?? '');
$where = "WHERE 1=1";
if ($status && in_array($status, ['upcoming','popular','featured','completed'], true)) {
    $where .= " AND e.status='" . mysqli_real_escape_string($conn, $status) . "'";
}
if ($search !== '') {
    $s = mysqli_real_escape_string($conn, $search);
    $where .= " AND (e.title LIKE '%$s%' OR e.category LIKE '%$s%' OR v.city LIKE '%$s%')";
}
$events = db_all("SELECT e.*, v.venue_name, v.city FROM events e JOIN venues v ON e.id_venue=v.id_venue $where ORDER BY e.event_date ASC");
include 'includes/header.php';
?>
<section class="section">
    <div class="section-head">
        <div>
            <p class="eyebrow">Events</p>
            <h2>Discover the future lineup.</h2>
        </div>
        <form method="GET" class="newsletter-form" style="max-width:460px">
            <input name="q" value="<?= e($search); ?>" placeholder="Search event, category, city..." data-search>
            <button>⌕</button>
        </form>
    </div>

    <div class="grid-3">
        <?php foreach ($events as $ev): ?>
            <article class="event-card" data-card>
                <img class="event-img" src="<?= e(filter_var($ev['image_url'], FILTER_VALIDATE_URL) ? $ev['image_url'] : url($ev['image_url'])); ?>" alt="<?= e($ev['title']); ?>">
                <div class="event-body">
                    <span class="badge"><?= e($ev['status']); ?> · <?= e($ev['category']); ?></span>
                    <h3><?= e($ev['title']); ?></h3>
                    <p class="meta"><?= date('d M Y', strtotime($ev['event_date'])); ?> · <?= e($ev['venue_name']); ?>, <?= e($ev['city']); ?></p>
                    <div class="progress mt-4"><i style="width:<?= max(5, min(100, round(($ev['seat_available']/$ev['seat_total'])*100))); ?>%"></i></div>
                    <p class="meta mt-2">Live seat: <?= (int)$ev['seat_available']; ?> / <?= (int)$ev['seat_total']; ?></p>
                    <div class="card-actions">
                        <a class="primary-btn small" href="<?= url('booking.php?event='.(int)$ev['id_event']); ?>">Buy Ticket</a>
                        <a class="outline-btn" href="<?= url('event_detail.php?id='.(int)$ev['id_event']); ?>">Details</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
