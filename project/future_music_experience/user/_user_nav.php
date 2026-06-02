<aside class="dashboard-sidebar">
    <a class="<?= ($user_active ?? '')==='dashboard'?'active':''; ?>" href="<?= url('user/dashboard.php'); ?>">Dashboard User</a>
    <a class="<?= ($user_active ?? '')==='booking'?'active':''; ?>" href="<?= url('events.php'); ?>">Booking Tiket</a>
    <a class="<?= ($user_active ?? '')==='purchases'?'active':''; ?>" href="<?= url('user/purchases.php'); ?>">Riwayat Pembelian</a>
    <a class="<?= ($user_active ?? '')==='wishlist'?'active':''; ?>" href="<?= url('user/wishlist.php'); ?>">Wishlist Event</a>
    <a class="<?= ($user_active ?? '')==='membership'?'active':''; ?>" href="<?= url('user/membership.php'); ?>">Membership</a>
    <a href="<?= url('index.php'); ?>">Home</a>
</aside>
