<?php
$page_title = $page_title ?? 'Future Music Experience';
$active = $active ?? '';
?>
<!DOCTYPE html>
<html lang="id" data-lang="id" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($page_title); ?> | Future Music Experience</title>
<meta name="description" content="Future Music Experience adalah platform festival musik futuristik dengan AI recommendation, digital ticketing, QR pass, membership, dan event internasional.">
<meta name="keywords" content="festival musik, tiket konser, future music, AI event, digital ticketing">
<meta property="og:title" content="<?= e($page_title); ?> | Future Music Experience">
<meta property="og:description" content="Cyber-modern music festival platform with AI, ticketing digital, and interactive experience.">
<meta name="theme-color" content="#080b2a">
<link rel="manifest" href="<?= url('manifest.json'); ?>">
<script>
(function () {
    var savedTheme = localStorage.getItem('fmx_theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
})();
</script>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="<?= url('assets/css/style.css'); ?>">
<script>window.FMX_BASE_URL = "<?= e(url('')); ?>";</script>
</head>
<body>
<div class="bg-orb orb-1"></div>
<div class="bg-orb orb-2"></div>
<nav class="site-nav">
    <a class="brand" href="<?= url('index.php'); ?>">
        <span class="brand-mark">FM</span>
        <span>
            <strong>FutureMusic</strong>
            <small>Experience</small>
        </span>
    </a>

    <button class="mobile-toggle" data-mobile-toggle>☰</button>

    <div class="nav-links" data-nav-menu>
        <a class="<?= $active==='home'?'active':''; ?>" href="<?= url('index.php'); ?>" data-i18n="nav_home">Home</a>
        <a class="<?= $active==='events'?'active':''; ?>" href="<?= url('events.php'); ?>" data-i18n="nav_events">Events</a>
        <a class="<?= $active==='artists'?'active':''; ?>" href="<?= url('artists.php'); ?>" data-i18n="nav_artists">Artists</a>
        <a class="<?= $active==='schedule'?'active':''; ?>" href="<?= url('schedule.php'); ?>" data-i18n="nav_schedule">Schedule</a>
        <a class="<?= $active==='gallery'?'active':''; ?>" href="<?= url('gallery.php'); ?>" data-i18n="nav_gallery">Gallery</a>
        <a class="<?= $active==='membership'?'active':''; ?>" href="<?= url('membership.php'); ?>" data-i18n="nav_membership">Membership</a>
        <a class="<?= $active==='contact'?'active':''; ?>" href="<?= url('contact.php'); ?>" data-i18n="nav_contact">Contact</a>
    </div>

    <div class="nav-actions">
        <button class="theme-toggle" type="button" data-theme-toggle title="Ganti mode gelap/terang"><span data-theme-icon>☾</span><span data-theme-label>Dark</span></button>
        <button class="icon-btn" data-lang-toggle title="Switch language">ID/EN</button>
        <?php if (is_logged_in()): ?>
            <?php if (is_admin()): ?>
                <a class="ghost-btn" href="<?= url('admin/dashboard.php'); ?>">Admin</a>
            <?php else: ?>
                <a class="ghost-btn" href="<?= url('user/dashboard.php'); ?>">Dashboard</a>
            <?php endif; ?>
            <a class="primary-btn small" href="<?= url('logout.php'); ?>">Logout</a>
        <?php else: ?>
            <a class="ghost-btn" href="<?= url('auth/login.php'); ?>">Login</a>
            <a class="primary-btn small" href="<?= url('auth/register.php'); ?>">Register ↗</a>
        <?php endif; ?>
    </div>
</nav>
<main>
