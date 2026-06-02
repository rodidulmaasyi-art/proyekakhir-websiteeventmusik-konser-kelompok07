<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'future_music_db';

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function url($path='') {
    /*
     * Membuat URL selalu mengarah ke root project.
     * Sebelumnya halaman di folder auth/admin/user mencari asset ke:
     * auth/assets/css/style.css
     * sehingga CSS tidak terbaca dan tampilan jadi polos.
     */
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $currentFolder = basename($scriptDir);

    if (in_array($currentFolder, ['auth', 'admin', 'user', 'api'], true)) {
        $base = dirname($scriptDir);
    } else {
        $base = $scriptDir;
    }

    $base = rtrim($base, '/');
    if ($base === '/' || $base === '.' || $base === '\\') {
        $base = '';
    }

    return $base . '/' . ltrim($path, '/');
}

function set_flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function show_flash() {
    if (!isset($_SESSION['flash'])) return;
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $class = $flash['type'] === 'success' ? 'flash-success' : 'flash-error';
    echo '<div class="flash '.$class.'"><span>'.($flash['type']==='success'?'✓':'!').'</span><p>'.e($flash['message']).'</p></div>';
}

function is_logged_in() {
    return isset($_SESSION['id_user']);
}

function current_user_id() {
    return (int)($_SESSION['id_user'] ?? 0);
}

function is_admin() {
    return ($_SESSION['role'] ?? '') === 'admin';
}

function require_login() {
    if (!is_logged_in()) {
        set_flash('error', 'Silakan login terlebih dahulu.');
        header('Location: ' . url('auth/login.php'));
        exit;
    }
}

function require_admin() {
    require_login();
    if (!is_admin()) {
        set_flash('error', 'Akses ditolak. Halaman ini hanya untuk admin.');
        header('Location: ' . url('index.php'));
        exit;
    }
}

function rupiah($number) {
    return 'Rp ' . number_format((float)$number, 0, ',', '.');
}


function validation_box($errors) {
    if (empty($errors)) return;
    echo '<div class="validation-summary mb-6 rounded-3xl border border-pink-400/30 bg-pink-500/10 p-4 text-pink-100 shadow-[0_18px_50px_rgba(255,47,208,.12)] backdrop-blur-xl">';
    echo '<div class="flex items-start gap-3">';
    echo '<span class="grid h-8 w-8 shrink-0 place-items-center rounded-2xl bg-pink-500 text-sm font-black text-white">!</span>';
    echo '<div>';
    echo '<p class="font-black">Form belum lengkap</p>';
    echo '<ul class="mt-2 list-disc space-y-1 pl-5 text-sm font-semibold">';
    foreach ($errors as $error) {
        echo '<li>'.e($error).'</li>';
    }
    echo '</ul>';
    echo '</div></div></div>';
}

function field_error($fieldErrors, $field) {
    if (!isset($fieldErrors[$field])) return;
    echo '<p class="field-error mt-2 rounded-xl border border-pink-400/30 bg-pink-500/10 px-3 py-2 text-sm font-bold text-pink-200">'.e($fieldErrors[$field]).'</p>';
}

function control_class($baseClass, $fieldErrors, $field) {
    return $baseClass . (isset($fieldErrors[$field]) ? ' validation-invalid ring-2 ring-pink-400/50 border-pink-400/60' : '');
}

function db_one($sql) {
    global $conn;
    $q = mysqli_query($conn, $sql);
    return $q ? mysqli_fetch_assoc($q) : null;
}

function db_all($sql) {
    global $conn;
    $q = mysqli_query($conn, $sql);
    $rows = [];
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) $rows[] = $row;
    }
    return $rows;
}

function make_slug($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-') ?: 'event';
}

function generate_qr_code() {
    return 'FMX-' . strtoupper(substr(md5(uniqid('', true)), 0, 12));
}
?>
