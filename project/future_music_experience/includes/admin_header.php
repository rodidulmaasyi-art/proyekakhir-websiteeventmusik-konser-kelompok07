<?php
$page_title = $page_title ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($page_title); ?> | Admin FutureMusic</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="<?= url('assets/css/style.css'); ?>">
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a class="brand" href="<?= url('admin/dashboard.php'); ?>"><span class="brand-mark">FM</span><strong>Admin</strong></a>
        <nav>
            <a href="<?= url('admin/dashboard.php'); ?>">Dashboard</a>
            <a href="<?= url('admin/events.php'); ?>">CRUD Event</a>
            <a href="<?= url('admin/artists.php'); ?>">CRUD Artist</a>
            <a href="<?= url('admin/users.php'); ?>">CRUD User</a>
            <a href="<?= url('admin/tickets.php'); ?>">CRUD Ticket</a>
            <a href="<?= url('admin/venues.php'); ?>">CRUD Venue</a>
            <a href="<?= url('admin/purchases.php'); ?>">Laporan Penjualan</a>
            <a href="<?= url('admin/scanner.php'); ?>">QR Scanner</a>
            <a href="<?= url('index.php'); ?>">Lihat Website</a>
            <a href="<?= url('logout.php'); ?>">Logout</a>
        </nav>
    </aside>
    <main class="admin-main">
