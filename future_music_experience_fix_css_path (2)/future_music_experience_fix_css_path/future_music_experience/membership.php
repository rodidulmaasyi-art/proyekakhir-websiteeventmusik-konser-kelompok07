<?php
include 'config/database.php';
$page_title = 'Membership';
$active = 'membership';
include 'includes/header.php';
?>
<section class="section">
    <div class="section-head">
        <div><p class="eyebrow">Membership</p><h2>Unlock premium access.</h2></div>
        <p>Membership meningkatkan prioritas ticketing, lounge, presale, dan rekomendasi AI personal.</p>
    </div>
    <div class="grid-4">
        <?php 
        $plans = [
            ['Free','0','Basic access, wishlist event, public ticket'],
            ['Neon','49K','Presale access, AI recommendation, promo ticket'],
            ['Galaxy','99K','VIP queue, member lounge, priority booking'],
            ['Legend','199K','Backstage priority, exclusive merch, concierge AI']
        ];
        foreach($plans as $p): ?>
        <article class="ticket-card">
            <span class="badge"><?= e($p[0]); ?></span>
            <div class="price"><?= e($p[1]); ?></div>
            <ul><?php foreach(explode(',', $p[2]) as $b): ?><li><?= e(trim($b)); ?></li><?php endforeach; ?></ul>
            <a class="primary-btn mt-6 w-full" href="<?= is_logged_in()?url('user/membership.php?plan='.urlencode($p[0])):url('auth/login.php'); ?>">Choose Plan</a>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
