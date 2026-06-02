<?php
include '../config/database.php';

$errors = [];
$fieldErrors = [];
$name = $email = $phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (strlen($name) < 3) {
        $fieldErrors['name'] = 'Nama minimal 3 karakter.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['email'] = 'Email tidak valid.';
    }
    if (strlen($password) < 6) {
        $fieldErrors['password'] = 'Password minimal 6 karakter.';
    }
    if ($password !== $confirm) {
        $fieldErrors['confirm'] = 'Konfirmasi password tidak sama.';
    }

    $errors = array_values($fieldErrors);

    if (!$errors) {
        $safeEmail = mysqli_real_escape_string($conn, $email);
        if (db_one("SELECT id_user FROM users WHERE email='$safeEmail'")) {
            $fieldErrors['email'] = 'Email sudah terdaftar.';
            $errors[] = 'Email sudah terdaftar.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (name,email,password,phone,role,membership) VALUES (?,?,?,?, 'user','Free')");
            mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $hash, $phone);
            if (mysqli_stmt_execute($stmt)) {
                set_flash('success','Registrasi berhasil. Silakan login.');
                header('Location: '.url('auth/login.php'));
                exit;
            } else {
                $errors[] = 'Registrasi gagal.';
            }
        }
    }
}

$page_title = 'Register';
include '../includes/header.php';
?>
<div class="auth-wrap">
    <form method="POST" class="auth-card" data-validate-form novalidate>
        <p class="eyebrow">Create Account</p>
        <h1>Register</h1>
        <p>Buat akun untuk membeli tiket dan menikmati membership.</p>

        <?php validation_box($errors); ?>

        <div data-field-wrap class="mb-4">
            <label>Name</label>
            <input class="<?= e(control_class('input', $fieldErrors, 'name')); ?>" name="name" value="<?= e($name); ?>" data-validate data-required="true" data-min="3" data-label="Nama">
            <?php field_error($fieldErrors, 'name'); ?>
        </div>

        <div data-field-wrap class="mb-4">
            <label>Email</label>
            <input class="<?= e(control_class('input', $fieldErrors, 'email')); ?>" type="email" name="email" value="<?= e($email); ?>" data-validate data-required="true" data-email="true" data-label="Email">
            <?php field_error($fieldErrors, 'email'); ?>
        </div>

        <div data-field-wrap class="mb-4">
            <label>Phone</label>
            <input class="input" name="phone" value="<?= e($phone); ?>" placeholder="08xxxxxxxxxx">
            <p class="form-hint">Opsional, digunakan untuk informasi ticketing.</p>
        </div>

        <div data-field-wrap class="mb-4">
            <label>Password</label>
            <input id="register_password" class="<?= e(control_class('input', $fieldErrors, 'password')); ?>" type="password" name="password" data-validate data-required="true" data-min="6" data-label="Password">
            <?php field_error($fieldErrors, 'password'); ?>
        </div>

        <div data-field-wrap class="mb-5">
            <label>Confirm Password</label>
            <input class="<?= e(control_class('input', $fieldErrors, 'confirm')); ?>" type="password" name="confirm" data-validate data-required="true" data-match="#register_password" data-label="Konfirmasi password">
            <?php field_error($fieldErrors, 'confirm'); ?>
        </div>

        <button class="primary-btn w-full">Register</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
