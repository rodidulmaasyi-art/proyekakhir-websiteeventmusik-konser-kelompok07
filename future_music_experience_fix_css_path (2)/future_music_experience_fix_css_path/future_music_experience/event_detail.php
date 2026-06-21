<?php
include 'config/database.php';
$id = (int)($_GET['id'] ?? 0);
$event = db_one("SELECT e.*, v.venue_name, v.city, v.address FROM events e JOIN venues v ON e.id_venue=v.id_venue WHERE e.id_event=$id");
if (!$event) {
    set_flash('error','Event tidak ditemukan.');
    header('Location: '.url('events.php'));
    exit;
}
$tickets = db_all("SELECT * FROM ticket_types WHERE id_event=$id ORDER BY FIELD(ticket_name,'Regular','VIP','VVIP','Backstage Pass')");
$schedules = db_all("SELECT s.*, a.artist_name, a.genre FROM schedules s JOIN artists a ON s.id_artist=a.id_artist WHERE s.id_event=$id ORDER BY s.perform_date,s.start_time");
$page_title = $event['title'];
$active = 'events';
include 'includes/header.php';
?>
<section class="hero" style="min-height:520px">
    <div class="video-fallback" style="background-image:linear-gradient(rgba(3,6,26,.35),rgba(3,6,26,.82)),url('<?= e(filter_var($event['image_url'], FILTER_VALIDATE_URL) ? $event['image_url'] : url($event['image_url'])); ?>')"></div>
    <div class="hero-content">
        <p class="eyebrow"><?= e($event['category']); ?> · <?= e($event['status']); ?></p>
        <h1><?= e($event['title']); ?></h1>
        <p><?= e($event['description']); ?></p>
        <div class="hero-actions">
            <a class="primary-btn" href="<?= url('booking.php?event='.(int)$event['id_event']); ?>">Buy Ticket</a>
            <a class="outline-btn" href="#schedule">View Schedule</a>
        </div>
    </div>
    <div class="hero-glass">
        <h3 class="text-2xl font-black">Event Countdown</h3>
        <p class="meta"><?= date('d M Y', strtotime($event['event_date'])); ?> · <?= e($event['venue_name']); ?></p>
        <div class="countdown" data-countdown="<?= e($event['event_date'].' '.$event['start_time']); ?>">
            <div><strong>00</strong><span>Days</span></div>
            <div><strong>00</strong><span>Hours</span></div>
            <div><strong>00</strong><span>Min</span></div>
            <div><strong>00</strong><span>Sec</span></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div><p class="eyebrow">Ticket System</p><h2>Choose your access.</h2></div>
        <p><?= (int)$event['seat_available']; ?> seats available live from database.</p>
    </div>
    <div class="grid-4">
        <?php foreach($tickets as $t): ?>
        <article class="ticket-card">
            <span class="badge"><?= e($t['ticket_name']); ?></span>
            <div class="price"><?= rupiah($t['price']); ?></div>
            <ul>
                <?php foreach(explode(',', $t['benefits']) as $b): ?>
                    <li><?= e(trim($b)); ?></li>
                <?php endforeach; ?>
            </ul>
            <a class="primary-btn mt-6 w-full" href="<?= url('booking.php?event='.(int)$event['id_event'].'&ticket='.(int)$t['id_ticket']); ?>">Select</a>
        </article>
        <?php endforeach; ?>
    </div>
</section>

<section id="schedule" class="section">
    <div class="section-head"><div><p class="eyebrow">Schedule</p><h2>Stage timeline.</h2></div></div>
    <div class="schedule-list">
        <?php foreach($schedules as $s): ?>
        <div class="schedule-item">
            <strong><?= date('H:i', strtotime($s['start_time'])); ?> - <?= date('H:i', strtotime($s['end_time'])); ?></strong>
            <div><h3 class="text-xl font-black"><?= e($s['artist_name']); ?></h3><p class="meta"><?= e($s['genre']); ?></p></div>
            <span class="badge"><?= e($s['stage_name']); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
