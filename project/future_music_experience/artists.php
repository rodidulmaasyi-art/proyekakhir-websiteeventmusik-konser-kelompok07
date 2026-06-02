<?php
include 'config/database.php';
$page_title = 'Artists';
$active = 'artists';
$artists = db_all("SELECT * FROM artists ORDER BY artist_name ASC");
include 'includes/header.php';
?>
<section class="section">
    <div class="section-head">
        <div><p class="eyebrow">Artists</p><h2>Profiles, genre, and social links.</h2></div>
        <input class="input" style="max-width:380px" placeholder="Live search artist..." data-search>
    </div>
    <div class="grid-4">
        <?php foreach($artists as $a): ?>
        <article class="artist-card" data-card>
            <img src="<?= e($a['image_url']); ?>" alt="<?= e($a['artist_name']); ?>">
            <div class="content">
                <span class="badge"><?= e($a['genre']); ?></span>
                <h3><?= e($a['artist_name']); ?></h3>
                <p class="meta"><?= e($a['bio']); ?></p>
                <div class="socials">
                    <span><?= e($a['instagram']); ?></span>
                    <span>YouTube</span>
                    <span>Spotify</span>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
