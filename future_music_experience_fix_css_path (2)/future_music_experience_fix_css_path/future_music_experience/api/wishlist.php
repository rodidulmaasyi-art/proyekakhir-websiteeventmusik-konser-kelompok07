<?php
include '../config/database.php';
header('Content-Type: application/json');
if (!is_logged_in()) {
    echo json_encode(['ok'=>false,'message'=>'Login required']);
    exit;
}
$id_event = (int)($_POST['id_event'] ?? 0);
$uid = current_user_id();
if (!$id_event) { echo json_encode(['ok'=>false,'message'=>'Invalid event']); exit; }
$exists = db_one("SELECT id_wishlist FROM wishlist WHERE id_user=$uid AND id_event=$id_event");
if ($exists) {
    mysqli_query($conn, "DELETE FROM wishlist WHERE id_user=$uid AND id_event=$id_event");
    echo json_encode(['ok'=>true,'status'=>'removed']);
} else {
    mysqli_query($conn, "INSERT IGNORE INTO wishlist (id_user,id_event) VALUES ($uid,$id_event)");
    echo json_encode(['ok'=>true,'status'=>'added']);
}
?>
