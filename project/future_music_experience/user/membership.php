<?php
include '../config/database.php';
require_login();
$plan = $_GET['plan'] ?? '';
$allowed = ['Free','Neon','Galaxy','Legend'];
if (in_array($plan,$allowed,true)) {
    $uid=current_user_id();
    $safe=mysqli_real_escape_string($conn,$plan);
    mysqli_query($conn, "UPDATE users SET membership='$safe' WHERE id_user=$uid");
    $_SESSION['membership']=$plan;
    set_flash('success','Membership berhasil diubah menjadi '.$plan.'.');
    header('Location: '.url('user/dashboard.php')); exit;
}
header('Location: '.url('membership.php'));
exit;
?>
