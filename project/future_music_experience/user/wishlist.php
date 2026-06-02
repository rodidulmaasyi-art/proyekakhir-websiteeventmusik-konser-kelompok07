<?php
include '../config/database.php';
require_login();
$page_title='Wishlist';
$user_active='wishlist';
$uid=current_user_id();
$events = db_all("SELECT e.*, w.id_wishlist FROM wishlist w JOIN events e ON w.id_event=e.id_event WHERE w.id_user=$uid ORDER BY w.created_at DESC");
include '../includes/header.php';
?>
<div class="dashboard-grid">
    <?php include '_user_nav.php'; ?>
    <section class="dashboard-panel">
        <div class="section-head"><div><p class="eyebrow">Wishlist Event</p><h2>Saved events.</h2></div></div>
        <div class="grid-3">
            <?php foreach($events as $ev): ?>
            <article class="event-card">
                <img class="event-img" src="<?= e($ev['image_url']); ?>">
                <div class="event-body"><span class="badge"><?= e($ev['category']); ?></span><h3><?= e($ev['title']); ?></h3><a class="primary-btn small" href="<?= url('booking.php?event='.(int)$ev['id_event']); ?>">Buy Ticket</a></div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>
<?php include '../includes/footer.php'; ?>
