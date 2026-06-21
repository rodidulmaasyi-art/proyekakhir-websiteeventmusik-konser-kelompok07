<?php
include '../config/database.php';
require_admin();
$page_title='QR Ticket Scanner';
$result=null;
if($_SERVER['REQUEST_METHOD']==='POST'){
    $code=mysqli_real_escape_string($conn,trim($_POST['qr_code']??''));
    $result=db_one("SELECT p.*, e.title, u.name FROM purchases p JOIN events e ON p.id_event=e.id_event JOIN users u ON p.id_user=u.id_user WHERE p.qr_code='$code' LIMIT 1");
    if($result){
        mysqli_query($conn,"INSERT INTO ticket_scans (id_purchase,scan_status) VALUES (".(int)$result['id_purchase'].",'valid')");
    }
}
include '../includes/admin_header.php';
?>
<h1 class="admin-title">QR Ticket Scanner</h1>
<div class="form-card mb-6">
    <form method="POST"><label>Masukkan QR Code E-Ticket</label><input class="input" name="qr_code" placeholder="FMX-USER-0001"><button class="primary-btn mt-4">Scan QR</button></form>
</div>
<?php if($result): ?><div class="flash flash-success"><span>✓</span><p>Ticket valid: <?= e($result['title']); ?> - <?= e($result['name']); ?> - <?= e($result['status']); ?></p></div><?php elseif($_SERVER['REQUEST_METHOD']==='POST'): ?><div class="flash flash-error"><span>!</span><p>Ticket tidak ditemukan.</p></div><?php endif; ?>
<?php include '../includes/admin_footer.php'; ?>
