<?php
include '../config/database.php';
require_admin();
$page_title='CRUD Artist';
$errors=[];
$edit_id=(int)($_GET['edit'] ?? 0);
$edit=$edit_id?db_one("SELECT * FROM artists WHERE id_artist=$edit_id"):null;

$upload_dir = '../assets/uploads/artists/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    $id=(int)($_POST['id_artist'] ?? 0);
    $name=trim($_POST['artist_name']??'');$genre=trim($_POST['genre']??'');$bio=trim($_POST['bio']??'');$ig=trim($_POST['instagram']??'');$yt=trim($_POST['youtube']??'');$sp=trim($_POST['spotify']??'');
    $image=trim($_POST['old_image']??'');
    
    if(strlen($name)<2)$errors[]='Nama artist minimal 2 karakter.';
    if(strlen($genre)<2)$errors[]='Genre wajib diisi.';
    if(strlen($bio)<10)$errors[]='Bio minimal 10 karakter.';
    
    $file_uploaded = isset($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE;
    if ($file_uploaded) {
        $file_error = $_FILES['image_file']['error'];
        if ($file_error !== UPLOAD_ERR_OK) {
            $errors[] = 'Gagal mengunggah file gambar (error code: ' . $file_error . ').';
        } else {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $_FILES['image_file']['tmp_name']);
            finfo_close($finfo);
            
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (!in_array($mime_type, $allowed_types) || !in_array($ext, $allowed_exts)) {
                $errors[] = 'Format gambar tidak valid. Hanya JPG, JPEG, PNG, dan WEBP yang diperbolehkan.';
            }
            
            $max_size = 2 * 1024 * 1024; // 2MB
            if ($_FILES['image_file']['size'] > $max_size) {
                $errors[] = 'Ukuran gambar maksimal 2MB.';
            }
            
            if (!$errors) {
                $new_filename = 'artist_' . uniqid() . '.' . $ext;
                $dest_path = $upload_dir . $new_filename;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $dest_path)) {
                    $image = 'assets/uploads/artists/' . $new_filename;
                    // Hapus gambar lama jika ada dan file lokal
                    if ($id && !empty($_POST['old_image'])) {
                        $old_image_path = '../' . $_POST['old_image'];
                        if (file_exists($old_image_path) && is_file($old_image_path)) {
                            @unlink($old_image_path);
                        }
                    }
                } else {
                    $errors[] = 'Gagal menyimpan gambar di server.';
                }
            }
        }
    } else {
        if (!$id) {
            $errors[] = 'Gambar artist wajib diunggah.';
        }
    }
    
    if(!$errors){
        if($id){$stmt=mysqli_prepare($conn,"UPDATE artists SET artist_name=?,genre=?,bio=?,image_url=?,instagram=?,youtube=?,spotify=? WHERE id_artist=?");mysqli_stmt_bind_param($stmt,'sssssssi',$name,$genre,$bio,$image,$ig,$yt,$sp,$id);}
        else{$stmt=mysqli_prepare($conn,"INSERT INTO artists (artist_name,genre,bio,image_url,instagram,youtube,spotify) VALUES (?,?,?,?,?,?,?)");mysqli_stmt_bind_param($stmt,'sssssss',$name,$genre,$bio,$image,$ig,$yt,$sp);}
        mysqli_stmt_execute($stmt);set_flash('success','Artist berhasil disimpan.');header('Location: '.url('admin/artists.php'));exit;
    }
}
if(isset($_GET['delete'])){
    $del_id=(int)$_GET['delete'];
    $artist=db_one("SELECT image_url FROM artists WHERE id_artist=$del_id");
    if($artist && !empty($artist['image_url'])){
        $old_image_path='../'.$artist['image_url'];
        if(file_exists($old_image_path) && is_file($old_image_path)){
            @unlink($old_image_path);
        }
    }
    mysqli_query($conn,"DELETE FROM artists WHERE id_artist=$del_id");
    set_flash('success','Artist dihapus.');
    header('Location: '.url('admin/artists.php'));
    exit;
}
$rows=db_all("SELECT * FROM artists ORDER BY artist_name");
include '../includes/admin_header.php';show_flash();
?>
<h1 class="admin-title">CRUD Artist</h1>
<div class="form-card mb-6"><form method="POST" enctype="multipart/form-data" class="crud-form" data-validate-form novalidate>
<?php validation_box($errors); ?>
<input type="hidden" name="id_artist" value="<?= (int)($edit['id_artist']??0); ?>">
<input type="hidden" name="old_image" value="<?= e($edit['image_url']??''); ?>">
<div class="form-grid"><div><label>Artist Name</label><input class="input" name="artist_name" value="<?= e($edit['artist_name']??''); ?>" data-validate data-required="true" data-min="2" data-label="Nama artist"></div><div><label>Genre</label><input class="input" name="genre" value="<?= e($edit['genre']??''); ?>" data-validate data-required="true" data-min="2" data-label="Genre"></div></div>
<div class="mb-4">
    <label>Upload Image</label>
    <input class="input" type="file" name="image_file" accept="image/jpeg, image/png, image/webp" <?= !$edit ? 'data-validate data-required="true" data-label="Gambar"' : ''; ?>>
    <p class="form-hint" style="font-size: 0.85rem; color: #a0aec0; margin-top: 4px;">Hanya diperbolehkan JPG, JPEG, PNG, atau WEBP (Maksimal 2MB).</p>
    <?php if ($edit && !empty($edit['image_url'])): ?>
        <div class="image-preview mt-3" style="margin-top: 12px;">
            <p style="font-size: 0.9rem; margin-bottom: 6px; font-weight: bold;">Current Image:</p>
            <img src="<?= e(filter_var($edit['image_url'], FILTER_VALIDATE_URL) ? $edit['image_url'] : url($edit['image_url'])); ?>" alt="Current Image" style="max-width: 150px; border-radius: 8px; border: 1px solid #4a5568;">
        </div>
    <?php endif; ?>
</div>
<label>Bio</label><textarea name="bio" rows="4" data-validate data-required="true" data-min="10" data-label="Bio artist"><?= e($edit['bio']??''); ?></textarea>
<div class="form-grid"><div><label>Instagram</label><input class="input" name="instagram" value="<?= e($edit['instagram']??''); ?>"></div><div><label>YouTube</label><input class="input" name="youtube" value="<?= e($edit['youtube']??''); ?>"></div><div><label>Spotify</label><input class="input" name="spotify" value="<?= e($edit['spotify']??''); ?>"></div></div>
<button class="primary-btn">Simpan Artist</button>
</form></div>
<div class="table-wrap"><table><thead><tr><th>Artist</th><th>Genre</th><th>Social</th><th>Aksi</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?= e($r['artist_name']); ?></td><td><?= e($r['genre']); ?></td><td><?= e($r['instagram']); ?></td><td><a class="action-link" href="?edit=<?= (int)$r['id_artist']; ?>">Edit</a> <a class="action-link" data-confirm="Hapus artist?" href="?delete=<?= (int)$r['id_artist']; ?>">Hapus</a></td></tr><?php endforeach; ?></tbody></table></div>
<?php include '../includes/admin_footer.php'; ?>

