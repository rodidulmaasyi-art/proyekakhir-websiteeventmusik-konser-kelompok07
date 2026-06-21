<?php
include '../config/database.php';
require_admin();
$page_title = 'Laporan Penjualan';
$rows = db_all("SELECT p.*,u.name,e.title,t.ticket_name FROM purchases p JOIN users u ON p.id_user=u.id_user JOIN events e ON p.id_event=e.id_event JOIN ticket_types t ON p.id_ticket=t.id_ticket ORDER BY p.created_at DESC");
include '../includes/admin_header.php';
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1 class="admin-title" style="margin: 0;">Laporan Penjualan</h1>
    <a href="<?= url('admin/purchases_report.php'); ?>" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background-color: #333; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#555'" onmouseout="this.style.backgroundColor='#333'">
        📊 Cetak PDF
    </a>
</div>;
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>User</th>
                <th>Event</th>
                <th>Ticket</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
                <th>QR</th>
            </tr>
        </thead>
        <tbody><?php foreach ($rows as $r): ?><tr>
                    <td><?= date('d M Y H:i', strtotime($r['created_at'])); ?></td>
                    <td><?= e($r['name']); ?></td>
                    <td><?= e($r['title']); ?></td>
                    <td><?= e($r['ticket_name']); ?></td>
                    <td><?= (int)$r['quantity']; ?></td>
                    <td><?= rupiah($r['total_price']); ?></td>
                    <td><?= e($r['status']); ?></td>
                    <td><?= e($r['qr_code']); ?></td>
                </tr><?php endforeach; ?></tbody>
    </table>
</div>
<?php include '../includes/admin_footer.php'; ?>