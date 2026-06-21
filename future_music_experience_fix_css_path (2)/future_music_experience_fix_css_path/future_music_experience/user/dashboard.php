<?php
include '../config/database.php';
require_login();
$page_title='User Dashboard';
$user_active='dashboard';
$uid=current_user_id();
$totalBuy = db_one("SELECT COUNT(*) total FROM purchases WHERE id_user=$uid")['total'] ?? 0;
$totalWish = db_one("SELECT COUNT(*) total FROM wishlist WHERE id_user=$uid")['total'] ?? 0;
$totalSpent = db_one("SELECT COALESCE(SUM(total_price),0) total FROM purchases WHERE id_user=$uid AND status='paid'")['total'] ?? 0;
$reco = db_all("SELECT * FROM events WHERE category IN ('EDM','Synthwave','Techno','Pop') ORDER BY FIELD(status,'featured','popular','upcoming') LIMIT 3");
include '../includes/header.php';
show_flash();
?>
<div class="dashboard-grid">
    <?php include '_user_nav.php'; ?>
    <section class="dashboard-panel">
        <p class="eyebrow">Member Dashboard</p>
        <h1 class="text-5xl font-black tracking-tight">Halo, <?= e($_SESSION['name']); ?></h1>
        <p class="meta mt-2">Membership: <?= e($_SESSION['membership'] ?? 'Free'); ?></p>
        <div class="stat-row">
            <div class="stat-card"><strong><?= (int)$totalBuy; ?></strong><span>Pembelian</span></div>
            <div class="stat-card"><strong><?= (int)$totalWish; ?></strong><span>Wishlist</span></div>
            <div class="stat-card"><strong><?= rupiah($totalSpent); ?></strong><span>Total Paid</span></div>
            <div class="stat-card"><strong>AI</strong><span>Recommendation</span></div>
        </div>

        <div class="section-head mt-10"><div><p class="eyebrow">AI Event Recommendation</p><h2>For your next stage.</h2></div></div>
        <div class="grid-3">
        <?php foreach($reco as $ev): ?>
            <article class="event-card">
                <img class="event-img" src="<?= e(filter_var($ev['image_url'], FILTER_VALIDATE_URL) ? $ev['image_url'] : url($ev['image_url'])); ?>">
                <div class="event-body"><span class="badge"><?= e($ev['category']); ?></span><h3><?= e($ev['title']); ?></h3><a class="primary-btn small" href="<?= url('booking.php?event='.(int)$ev['id_event']); ?>">Book</a></div>
            </article>
        <?php endforeach; ?>
        </div>
    </section>
</div>
<?php include '../includes/footer.php'; ?>
