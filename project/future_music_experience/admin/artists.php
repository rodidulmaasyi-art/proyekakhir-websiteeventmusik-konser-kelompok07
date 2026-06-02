<?php
include '../config/database.php';
require_admin();
$page_title='CRUD Artist';
$errors=[];
$edit_id=(int)($_GET['edit'] ?? 0);
$edit=$edit_id?db_one("SELECT * FROM artists WHERE id_artist=$edit_id"):null;
if($_SERVER['REQUEST_METHOD']==='POST'){
    $id=(int)($_POST['id_artist'] ?? 0);
    $name=trim($_POST['artist_name']??'');$genre=trim($_POST['genre']??'');$bio=trim($_POST['bio']??'');$image=trim($_POST['image_url']??'');$ig=trim($_POST['instagram']??'');$yt=trim($_POST['youtube']??'');$sp=trim($_POST['spotify']??'');
    if(strlen($name)<2)$errors[]='Nama artist minimal 2 karakter.';
    if(strlen($genre)<2)$errors[]='Genre wajib diisi.';
    if(strlen($bio)<10)$errors[]='Bio minimal 10 karakter.';
    if(!filter_var($image,FILTER_VALIDATE_URL))$errors[]='Image URL valid wajib diisi.';
    if(!$errors){
        if($id){$stmt=mysqli_prepare($conn,"UPDATE artists SET artist_name=?,genre=?,bio=?,image_url=?,instagram=?,youtube=?,spotify=? WHERE id_artist=?");mysqli_stmt_bind_param($stmt,'sssssssi',$name,$genre,$bio,$image,$ig,$yt,$sp,$id);}
        else{$stmt=mysqli_prepare($conn,"INSERT INTO artists (artist_name,genre,bio,image_url,instagram,youtube,spotify) VALUES (?,?,?,?,?,?,?)");mysqli_stmt_bind_param($stmt,'sssssss',$name,$genre,$bio,$image,$ig,$yt,$sp);}
        mysqli_stmt_execute($stmt);set_flash('success','Artist berhasil disimpan.');header('Location: '.url('admin/artists.php'));exit;
    }
}
if(isset($_GET['delete'])){mysqli_query($conn,"DELETE FROM artists WHERE id_artist=".(int)$_GET['delete']);set_flash('success','Artist dihapus.');header('Location: '.url('admin/artists.php'));exit;}
$rows=db_all("SELECT * FROM artists ORDER BY artist_name");
include '../includes/admin_header.php';show_flash();
?>
<h1 class="admin-title">CRUD Artist</h1>
<div class="form-card mb-6"><form method="POST" class="crud-form" data-validate-form novalidate>
<?php validation_box($errors); ?>
<input type="hidden" name="id_artist" value="<?= (int)($edit['id_artist']??0); ?>">
<div class="form-grid"><div><label>Artist Name</label><input class="input" name="artist_name" value="<?= e($edit['artist_name']??''); ?>" data-validate data-required="true" data-min="2" data-label="Nama artist"></div><div><label>Genre</label><input class="input" name="genre" value="<?= e($edit['genre']??''); ?>" data-validate data-required="true" data-min="2" data-label="Genre"></div></div>
<label>Image URL / Cloudinary URL</label><input class="input" name="image_url" value="<?= e($edit['image_url']??'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=900&q=80'); ?>" data-validate data-required="true" data-label="Image URL">
<label>Bio</label><textarea name="bio" rows="4" data-validate data-required="true" data-min="10" data-label="Bio artist"><?= e($edit['bio']??''); ?></textarea>
<div class="form-grid"><div><label>Instagram</label><input class="input" name="instagram" value="<?= e($edit['instagram']??''); ?>"></div><div><label>YouTube</label><input class="input" name="youtube" value="<?= e($edit['youtube']??''); ?>"></div><div><label>Spotify</label><input class="input" name="spotify" value="<?= e($edit['spotify']??''); ?>"></div></div>
<button class="primary-btn">Simpan Artist</button>
</form></div>
<div class="table-wrap"><table><thead><tr><th>Artist</th><th>Genre</th><th>Social</th><th>Aksi</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['artist_name']); ?></td><td><?= e($r['genre']); ?></td><td><?= e($r['instagram']); ?></td><td><a class="action-link" href="?edit=<?= (int)$r['id_artist']; ?>">Edit</a> <a class="action-link" data-confirm="Hapus artist?" href="?delete=<?= (int)$r['id_artist']; ?>">Hapus</a></td></tr><?php endforeach; ?></tbody></table></div>
<?php include '../includes/admin_footer.php'; ?>
