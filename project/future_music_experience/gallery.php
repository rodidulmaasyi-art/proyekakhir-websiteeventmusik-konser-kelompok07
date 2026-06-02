<?php
include 'config/database.php';
$page_title = 'Gallery';
$active = 'gallery';
$imgs = [
'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1200&q=80',
'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?auto=format&fit=crop&w=900&q=80',
'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=900&q=80',
'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=900&q=80',
'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=900&q=80'
];
include 'includes/header.php';
?>
<section class="section">
    <div class="section-head"><div><p class="eyebrow">Gallery</p><h2>Cyber festival moments.</h2></div><p>Visual galeri untuk menggambarkan pengalaman konser futuristik.</p></div>
    <div class="gallery-grid">
        <?php foreach($imgs as $img): ?><img src="<?= e($img); ?>" alt="Festival gallery"><?php endforeach; ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
