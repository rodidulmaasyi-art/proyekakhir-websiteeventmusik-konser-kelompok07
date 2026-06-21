<?php
include '../config/database.php';

// Cek jika user sudah login, arahkan ke dashboard
if (is_logged_in()) {
    header('Location: '.url(is_admin() ? 'admin/dashboard.php' : 'user/dashboard.php'));
    exit;
}

$errors = [];
$fieldErrors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['email'] = 'Email tidak valid.';
    }
    if ($password === '') {
        $fieldErrors['password'] = 'Password wajib diisi.';
    }

    $errors = array_values($fieldErrors);

    if (!$errors) {
        $safe = mysqli_real_escape_string($conn, $email);
        $user = db_one("SELECT * FROM users WHERE email='$safe' LIMIT 1");
        
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Mencegah session fixation attack
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['membership'] = $user['membership'];
            
            set_flash('success', 'Login berhasil. Selamat datang, '.$user['name'].'.');
            header('Location: '.url($user['role']==='admin' ? 'admin/dashboard.php' : 'user/dashboard.php'));
            exit;
        } else {
            $errors[] = 'Email atau password salah.';
        }
    }
}

$page_title = 'Login';
include '../includes/header.php';
show_flash();
?>
<div class="auth-wrap">
    <form method="POST" class="auth-card" data-validate-form novalidate>
        <p class="eyebrow">Member Access</p>
        <h1>Login</h1>
        <p>Masuk untuk booking tiket, wishlist event, download e-ticket PDF, dan melihat riwayat pembelian.</p>

        <?php validation_box($errors); ?>

        <div data-field-wrap class="mb-4">
            <label>Email</label>
            <input class="<?= e(control_class('input', $fieldErrors, 'email')); ?>" type="email" name="email" value="<?= e($email); ?>" placeholder="user@futuremusic.test" data-validate data-required="true" data-email="true" data-label="Email">
            <?php field_error($fieldErrors, 'email'); ?>
        </div>

        <div data-field-wrap class="mb-4">
            <label>Password</label>
            <input class="<?= e(control_class('input', $fieldErrors, 'password')); ?>" type="password" name="password" placeholder="123456" data-validate data-required="true" data-label="Password">
            <?php field_error($fieldErrors, 'password'); ?>
        </div>



        <button class="primary-btn w-full mb-4">Login</button>

        <p style="text-align: center; margin-top: 15px; font-size: 0.95rem;">
            Belum punya akun? <a href="<?= url('auth/register.php'); ?>" style="color: var(--primary-color, #007bff); font-weight: bold; text-decoration: none;">Register dulu</a>
        </p>
    </form>
</div>
<?php include '../includes/footer.php'; ?>