<?php
include '../config/database.php';
require_admin();
$page_title='Laporan Penjualan';
$rows=db_all("SELECT p.*,u.name,e.title,t.ticket_name FROM purchases p JOIN users u ON p.id_user=u.id_user JOIN events e ON p.id_event=e.id_event JOIN ticket_types t ON p.id_ticket=t.id_ticket ORDER BY p.created_at DESC");
include '../includes/admin_header.php';
?>
<h1 class="admin-title">Laporan Penjualan</h1>
<div class="table-wrap"><table><thead><tr><th>User</th><th>Event</th><th>Ticket</th><th>Qty</th><th>Total</th><th>Status</th><th>QR</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['name']); ?></td><td><?= e($r['title']); ?></td><td><?= e($r['ticket_name']); ?></td><td><?= (int)$r['quantity']; ?></td><td><?= rupiah($r['total_price']); ?></td><td><?= e($r['status']); ?></td><td><?= e($r['qr_code']); ?></td></tr><?php endforeach; ?></tbody></table></div>
<?php include '../includes/admin_footer.php'; ?>
