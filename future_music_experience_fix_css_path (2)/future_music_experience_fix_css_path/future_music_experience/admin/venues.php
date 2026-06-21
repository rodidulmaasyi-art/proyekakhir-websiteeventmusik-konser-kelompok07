<?php
include '../config/database.php';
require_admin();
$page_title='CRUD Venue';
$edit_id=(int)($_GET['edit']??0);$edit=$edit_id?db_one("SELECT * FROM venues WHERE id_venue=$edit_id"):null;$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
$id=(int)($_POST['id_venue']??0);$name=trim($_POST['venue_name']);$city=trim($_POST['city']);$address=trim($_POST['address']);$capacity=(int)$_POST['capacity'];
if(strlen($name)<3)$errors[]='Nama venue minimal 3 karakter.'; if(strlen($city)<2)$errors[]='Kota wajib diisi.'; if($capacity<1)$errors[]='Capacity tidak valid.';
if(!$errors){if($id){$stmt=mysqli_prepare($conn,"UPDATE venues SET venue_name=?,city=?,address=?,capacity=? WHERE id_venue=?");mysqli_stmt_bind_param($stmt,'sssii',$name,$city,$address,$capacity,$id);}else{$stmt=mysqli_prepare($conn,"INSERT INTO venues (venue_name,city,address,capacity) VALUES (?,?,?,?)");mysqli_stmt_bind_param($stmt,'sssi',$name,$city,$address,$capacity);}mysqli_stmt_execute($stmt);set_flash('success','Venue disimpan.');header('Location: '.url('admin/venues.php'));exit;}}
if(isset($_GET['delete'])){mysqli_query($conn,"DELETE FROM venues WHERE id_venue=".(int)$_GET['delete']);set_flash('success','Venue dihapus.');header('Location: '.url('admin/venues.php'));exit;}
$rows=db_all("SELECT * FROM venues ORDER BY venue_name");include '../includes/admin_header.php';show_flash();
?>
<h1 class="admin-title">CRUD Venue</h1>
<div class="form-card mb-6"><form method="POST" class="crud-form" data-validate-form novalidate><?php validation_box($errors); ?><input type="hidden" name="id_venue" value="<?= (int)($edit['id_venue']??0); ?>"><div class="form-grid"><div><label>Venue</label><input class="input" name="venue_name" value="<?= e($edit['venue_name']??''); ?>" data-validate data-required="true" data-min="3" data-label="Nama venue"></div><div><label>City</label><input class="input" name="city" value="<?= e($edit['city']??''); ?>" data-validate data-required="true" data-min="2" data-label="Kota"></div><div><label>Capacity</label><input class="input" type="number" name="capacity" value="<?= e($edit['capacity']??1000); ?>" data-validate data-required="true" data-min-number="1" data-label="Kapasitas"></div></div><label>Address</label><textarea name="address" rows="3" data-validate data-required="true" data-min="5" data-label="Alamat"><?= e($edit['address']??''); ?></textarea><button class="primary-btn">Simpan Venue</button></form></div>
<div class="table-wrap"><table><thead><tr><th>Venue</th><th>City</th><th>Capacity</th><th>Aksi</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['venue_name']); ?></td><td><?= e($r['city']); ?></td><td><?= (int)$r['capacity']; ?></td><td><a class="action-link" href="?edit=<?= (int)$r['id_venue']; ?>">Edit</a> <a class="action-link" data-confirm="Hapus venue?" href="?delete=<?= (int)$r['id_venue']; ?>">Hapus</a></td></tr><?php endforeach; ?></tbody></table></div>
<?php include '../includes/admin_footer.php'; ?>
