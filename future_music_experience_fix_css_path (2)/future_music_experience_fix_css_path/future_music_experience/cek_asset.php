<?php
include 'config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cek Asset</title>
<link rel="stylesheet" href="<?= url('assets/css/style.css'); ?>">
</head>
<body>
<section class="section">
    <div class="form-card">
        <p class="eyebrow">Asset Check</p>
        <h1 class="text-4xl font-black">CSS berhasil terbaca</h1>
        <p class="meta mt-3">Jika halaman ini tampil gelap/cyber, maka path CSS sudah benar.</p>
        <p class="mt-4">Path CSS: <code><?= e(url('assets/css/style.css')); ?></code></p>
        <a class="primary-btn mt-5" href="<?= url('auth/login.php'); ?>">Cek Login</a>
    </div>
</section>
</body>
</html>
