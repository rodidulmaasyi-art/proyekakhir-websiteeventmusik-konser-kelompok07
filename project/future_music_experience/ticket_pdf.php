<?php
include 'config/database.php';
require_login();
$id=(int)($_GET['id'] ?? 0);
$uid=current_user_id();
$where = is_admin() ? "p.id_purchase=$id" : "p.id_purchase=$id AND p.id_user=$uid";
$ticket = db_one("SELECT p.*, e.title, e.event_date, e.start_time, v.venue_name, v.city, t.ticket_name
FROM purchases p
JOIN events e ON p.id_event=e.id_event
JOIN venues v ON e.id_venue=v.id_venue
JOIN ticket_types t ON p.id_ticket=t.id_ticket
WHERE $where");
if (!$ticket) { die('Ticket tidak ditemukan.'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>E-Ticket <?= e($ticket['qr_code']); ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="<?= url('assets/css/style.css'); ?>">
<style>@media print{.no-print{display:none}.qr-ticket{box-shadow:none;margin:0}}</style>
</head>
<body>
<div class="qr-ticket">
    <div class="qr-head">
        <p class="eyebrow" style="color:#fff">E-Ticket PDF</p>
        <h1 class="text-4xl font-black"><?= e($ticket['title']); ?></h1>
        <p><?= date('d M Y', strtotime($ticket['event_date'])); ?> · <?= e($ticket['venue_name']); ?>, <?= e($ticket['city']); ?></p>
    </div>
    <div class="qr-body">
        <div>
            <p><strong>Buyer:</strong> <?= e($ticket['buyer_name']); ?></p>
            <p><strong>Email:</strong> <?= e($ticket['buyer_email']); ?></p>
            <p><strong>Ticket:</strong> <?= e($ticket['ticket_name']); ?> × <?= (int)$ticket['quantity']; ?></p>
            <p><strong>Status:</strong> <?= e($ticket['status']); ?></p>
            <p><strong>QR Code:</strong> <?= e($ticket['qr_code']); ?></p>
            <button class="primary-btn no-print mt-6" onclick="window.print()">Download / Print PDF</button>
        </div>
        <div class="qr-box">
            QR<br><?= e($ticket['qr_code']); ?>
        </div>
    </div>
</div>
</body>
</html>
