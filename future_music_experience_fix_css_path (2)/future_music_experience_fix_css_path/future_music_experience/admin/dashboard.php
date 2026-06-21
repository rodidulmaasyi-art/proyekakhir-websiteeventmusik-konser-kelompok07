<?php
include '../config/database.php';
require_admin();
$page_title='Dashboard Analytics';
$stats = [
    'Users' => db_one("SELECT COUNT(*) total FROM users")['total'] ?? 0,
    'Events' => db_one("SELECT COUNT(*) total FROM events")['total'] ?? 0,
    'Tickets Sold' => db_one("SELECT COALESCE(SUM(quantity),0) total FROM purchases WHERE status='paid'")['total'] ?? 0,
    'Revenue' => rupiah(db_one("SELECT COALESCE(SUM(total_price),0) total FROM purchases WHERE status='paid'")['total'] ?? 0),
];
$sales = db_all("SELECT e.title, COALESCE(SUM(p.total_price),0) revenue, COALESCE(SUM(p.quantity),0) qty FROM events e LEFT JOIN purchases p ON e.id_event=p.id_event AND p.status='paid' GROUP BY e.id_event ORDER BY revenue DESC LIMIT 6");
include '../includes/admin_header.php';
?>
<h1 class="admin-title">Dashboard Analytics</h1>
<div class="admin-cards">
    <?php foreach($stats as $k=>$v): ?><div class="admin-card"><span><?= e($k); ?></span><strong><?= e($v); ?></strong></div><?php endforeach; ?>
</div>
<div class="dashboard-panel">
    <div class="section-head"><div><p class="eyebrow">Sales Report</p><h2>Laporan Penjualan</h2></div><div class="kpi-ring">72%</div></div>
    <div class="table-wrap"><table><thead><tr><th>Event</th><th>Ticket Sold</th><th>Revenue</th></tr></thead><tbody>
    <?php foreach($sales as $s): ?><tr><td><?= e($s['title']); ?></td><td><?= (int)$s['qty']; ?></td><td><?= rupiah($s['revenue']); ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
</div>
<?php include '../includes/admin_footer.php'; ?>
