<?php
include 'config/database.php';
$page_title = 'Schedule';
$active = 'schedule';
$schedules = db_all("SELECT s.*, e.title, a.artist_name, a.genre, v.city
FROM schedules s 
JOIN events e ON s.id_event=e.id_event
JOIN artists a ON s.id_artist=a.id_artist
JOIN venues v ON e.id_venue=v.id_venue
ORDER BY s.perform_date, s.start_time");
include 'includes/header.php';
?>
<section class="section">
    <div class="section-head"><div><p class="eyebrow">Schedule</p><h2>Performance timeline.</h2></div></div>
    <div class="schedule-list">
        <?php foreach($schedules as $s): ?>
        <div class="schedule-item">
            <strong><?= date('d M Y', strtotime($s['perform_date'])); ?><br><?= date('H:i', strtotime($s['start_time'])); ?> - <?= date('H:i', strtotime($s['end_time'])); ?></strong>
            <div><h3 class="text-xl font-black"><?= e($s['artist_name']); ?></h3><p class="meta"><?= e($s['title']); ?> · <?= e($s['genre']); ?> · <?= e($s['city']); ?></p></div>
            <span class="badge"><?= e($s['stage_name']); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
