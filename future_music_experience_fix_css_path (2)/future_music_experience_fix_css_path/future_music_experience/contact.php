<?php
include 'config/database.php';

$page_title = 'Contact';
$active = 'contact';
$errors = [];
$fieldErrors = [];
$name = $email = $message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (strlen($name) < 3) {
        $fieldErrors['name'] = 'Nama minimal 3 karakter.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fieldErrors['email'] = 'Email tidak valid.';
    }
    if (strlen($message) < 10) {
        $fieldErrors['message'] = 'Pesan minimal 10 karakter.';
    }

    $errors = array_values($fieldErrors);

    if (!$errors) {
        set_flash('success','Pesan berhasil dikirim. Tim FutureMusic akan menghubungi kamu.');
        header('Location: '.url('contact.php'));
        exit;
    }
}

include 'includes/header.php';
show_flash();
?>
<section class="section">
    <div class="section-head">
        <div>
            <p class="eyebrow">Contact</p>
            <h2>Talk with our crew.</h2>
        </div>
        <p>Form ini sudah memakai validasi server-side PHP dan validasi interaktif JavaScript dengan style Tailwind/cyber.</p>
    </div>

    <form method="POST" class="form-card" data-validate-form novalidate>
        <?php validation_box($errors); ?>

        <div class="form-grid">
            <div data-field-wrap>
                <label>Name</label>
                <input class="<?= e(control_class('input', $fieldErrors, 'name')); ?>" name="name" value="<?= e($name); ?>" placeholder="Nama lengkap" data-validate data-required="true" data-min="3" data-label="Nama">
                <?php field_error($fieldErrors, 'name'); ?>
            </div>

            <div data-field-wrap>
                <label>Email</label>
                <input class="<?= e(control_class('input', $fieldErrors, 'email')); ?>" type="email" name="email" value="<?= e($email); ?>" placeholder="nama@email.com" data-validate data-required="true" data-email="true" data-label="Email">
                <?php field_error($fieldErrors, 'email'); ?>
            </div>
        </div>

        <div data-field-wrap class="mt-4">
            <label>Message</label>
            <textarea class="<?= e(control_class('', $fieldErrors, 'message')); ?>" name="message" rows="6" placeholder="Tulis pesan minimal 10 karakter..." data-validate data-required="true" data-min="10" data-label="Pesan"><?= e($message); ?></textarea>
            <?php field_error($fieldErrors, 'message'); ?>
        </div>

        <button class="primary-btn mt-5">Send Message</button>
    </form>
</section>
<?php include 'includes/footer.php'; ?>
