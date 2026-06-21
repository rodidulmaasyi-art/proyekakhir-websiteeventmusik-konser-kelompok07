<?php
include 'config/database.php';
session_destroy();
session_start();
set_flash('success','Logout berhasil.');
header('Location: '.url('index.php'));
exit;
?>
