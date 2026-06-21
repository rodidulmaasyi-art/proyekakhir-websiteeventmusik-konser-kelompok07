<?php
include 'config/database.php';
$page_title = 'Home';
$active = 'home';
$featured = db_one("SELECT e.*, v.venue_name, v.city FROM events e JOIN venues v ON e.id_venue=v.id_venue WHERE e.status='featured' ORDER BY e.event_date ASC LIMIT 1");
$events = db_all("SELECT e.*, v.city FROM events e JOIN venues v ON e.id_venue=v.id_venue ORDER BY e.event_date ASC LIMIT 6");
$popular = db_all("SELECT e.*, v.city FROM events e JOIN venues v ON e.id_venue=v.id_venue WHERE e.status IN ('popular','featured') ORDER BY e.event_date ASC LIMIT 3");
$artists = db_all("SELECT * FROM artists ORDER BY id_artist DESC LIMIT 4");
include 'includes/header.php';
show_flash();
?>
<section class="hero">
    <?php if (!empty($featured['video_url'])): ?>
        <video autoplay muted loop playsinline data-parallax>
            <source src="<?= e($featured['video_url']); ?>" type="video/mp4">
        </video>
    <?php endif; ?>
    <div class="video-fallback" data-parallax></div>
    <div class="hero-content">
        <p class="eyebrow">Future Music Experience</p>
        <h1>Music festival from tomorrow.</h1>
        <p>Platform festival musik cyber-modern dengan AI recommendation, ticketing digital, live seat availability, QR e-ticket, membership, dan pengalaman interaktif yang responsif.</p>
        <div class="hero-actions">
            <a href="<?= url('booking.php?event='.(int)$featured['id_event']); ?>" class="primary-btn">Buy Ticket</a>
            <a href="<?= url('events.php'); ?>" class="outline-btn">Explore Events</a>
        </div>
        <div class="stat-row">
            <div class="stat-card"><strong>10+</strong><span>Festival</span></div>
            <div class="stat-card"><strong>40K</strong><span>Seats</span></div>
            <div class="stat-card"><strong>AI</strong><span>Assistant</span></div>
            <div class="stat-card"><strong>QR</strong><span>E-Ticket</span></div>
        </div>
    </div>
    <div class="hero-glass">
        <p class="eyebrow">Featured Festival</p>
        <h3 class="text-2xl font-black mt-2"><?= e($featured['title'] ?? 'Future Music Experience 2026'); ?></h3>
        <p class="meta mt-2"><?= e($featured['city'] ?? 'Jakarta'); ?> · <?= e($featured['event_date'] ?? date('Y-m-d')); ?></p>
        <div class="countdown" data-countdown="<?= e(($featured['event_date'] ?? date('Y-m-d')) . ' ' . ($featured['start_time'] ?? '18:00:00')); ?>">
            <div><strong>00</strong><span>Days</span></div>
            <div><strong>00</strong><span>Hours</span></div>
            <div><strong>00</strong><span>Min</span></div>
            <div><strong>00</strong><span>Sec</span></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div>
            <p class="eyebrow">Upcoming Events</p>
            <h2>Explore neon stages.</h2>
        </div>
        <p>Temukan konser, festival, dan event populer dengan sistem wishlist dan digital ticketing.</p>
    </div>
    <div class="grid-3">
        <?php foreach ($events as $ev): ?>
            <article class="event-card" data-card>
                <img class="event-img" src="<?= e(filter_var($ev['image_url'], FILTER_VALIDATE_URL) ? $ev['image_url'] : url($ev['image_url'])); ?>" alt="<?= e($ev['title']); ?>">
                <div class="event-body">
                    <span class="badge"><?= e($ev['category']); ?></span>
                    <h3><?= e($ev['title']); ?></h3>
                    <p class="meta"><?= date('d M Y', strtotime($ev['event_date'])); ?> · <?= e($ev['city']); ?></p>
                    <div class="progress mt-4"><i style="width:<?= max(5, min(100, round(($ev['seat_available']/$ev['seat_total'])*100))); ?>%"></i></div>
                    <p class="meta mt-2"><?= (int)$ev['seat_available']; ?> seats available</p>
                    <div class="card-actions">
                        <a class="primary-btn small" href="<?= url('event_detail.php?id='.(int)$ev['id_event']); ?>">Details</a>
                        <?php if (is_logged_in()): ?>
                        <form action="<?= url('api/wishlist.php'); ?>" method="POST" data-wishlist-form>
                            <input type="hidden" name="id_event" value="<?= (int)$ev['id_event']; ?>">
                            <button class="wishlist-btn" type="submit">♡ Wishlist</button>
                        </form>
                        <?php else: ?>
                            <a class="wishlist-btn" href="<?= url('auth/login.php'); ?>">♡ Wishlist</a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div>
            <p class="eyebrow">Popular Events</p>
            <h2>Trending now.</h2>
        </div>
        <a class="outline-btn" href="<?= url('events.php?status=popular'); ?>">View all</a>
    </div>
    <div class="grid-3">
        <?php foreach ($popular as $ev): ?>
            <article class="event-card">
                <img class="event-img" src="<?= e(filter_var($ev['image_url'], FILTER_VALIDATE_URL) ? $ev['image_url'] : url($ev['image_url'])); ?>" alt="<?= e($ev['title']); ?>">
                <div class="event-body">
                    <span class="badge"><?= e($ev['status']); ?></span>
                    <h3><?= e($ev['title']); ?></h3>
                    <p class="meta"><?= e($ev['description']); ?></p>
                    <div class="card-actions"><a class="primary-btn small" href="<?= url('booking.php?event='.(int)$ev['id_event']); ?>">Buy Ticket</a></div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div>
            <p class="eyebrow">Featured Artists</p>
            <h2>Hologram-ready lineup.</h2>
        </div>
        <a class="outline-btn" href="<?= url('artists.php'); ?>">All Artists</a>
    </div>
    <div class="grid-4">
        <?php foreach ($artists as $a): ?>
            <article class="artist-card">
                <img src="<?= e(filter_var($a['image_url'], FILTER_VALIDATE_URL) ? $a['image_url'] : url($a['image_url'])); ?>" alt="<?= e($a['artist_name']); ?>">
                <div class="content">
                    <span class="badge"><?= e($a['genre']); ?></span>
                    <h3><?= e($a['artist_name']); ?></h3>
                    <p class="meta"><?= e($a['bio']); ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
