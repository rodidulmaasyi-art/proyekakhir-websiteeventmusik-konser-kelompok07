<?php
include '../config/database.php';
require_admin();
$page_title='CRUD Event';
$errors=[];
$edit_id=(int)($_GET['edit'] ?? 0);
$edit=$edit_id?db_one("SELECT * FROM events WHERE id_event=$edit_id"):null;
$venues=db_all("SELECT * FROM venues ORDER BY venue_name");

$upload_dir = '../assets/uploads/events/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $id=(int)($_POST['id_event'] ?? 0);
    $id_venue=(int)$_POST['id_venue'];
    $title=trim($_POST['title'] ?? '');
    $description=trim($_POST['description'] ?? '');
    $event_date=$_POST['event_date'] ?? '';
    $start_time=$_POST['start_time'] ?? '';
    $category=trim($_POST['category'] ?? '');
    $status=$_POST['status'] ?? 'upcoming';
    $seat_total=(int)$_POST['seat_total'];
    $seat_available=(int)$_POST['seat_available'];
    $image_url=trim($_POST['old_image'] ?? '');
    
    if(strlen($title)<3)$errors[]='Judul event minimal 3 karakter.';
    if(strlen($description)<10)$errors[]='Deskripsi minimal 10 karakter.';
    if(!$event_date)$errors[]='Tanggal wajib diisi.';
    if(!$start_time)$errors[]='Jam wajib diisi.';
    if($seat_total<1 || $seat_available<0)$errors[]='Seat tidak valid.';
    
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
                $new_filename = 'event_' . uniqid() . '.' . $ext;
                $dest_path = $upload_dir . $new_filename;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $dest_path)) {
                    $image_url = 'assets/uploads/events/' . $new_filename;
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
            $errors[] = 'Gambar event wajib diunggah.';
        }
    }
    
    if(!$errors){
        $slug=make_slug($title).'-'.substr(md5($title.$event_date),0,5);
        if($id){
            $stmt=mysqli_prepare($conn,"UPDATE events SET id_venue=?,title=?,description=?,event_date=?,start_time=?,category=?,status=?,image_url=?,seat_total=?,seat_available=? WHERE id_event=?");
            mysqli_stmt_bind_param($stmt,'isssssssiii',$id_venue,$title,$description,$event_date,$start_time,$category,$status,$image_url,$seat_total,$seat_available,$id);
        }else{
            $stmt=mysqli_prepare($conn,"INSERT INTO events (id_venue,title,slug,description,event_date,start_time,category,status,image_url,seat_total,seat_available) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            mysqli_stmt_bind_param($stmt,'issssssssii',$id_venue,$title,$slug,$description,$event_date,$start_time,$category,$status,$image_url,$seat_total,$seat_available);
        }
        mysqli_stmt_execute($stmt);
        set_flash('success','Data event berhasil disimpan.');
        header('Location: '.url('admin/events.php'));exit;
    }
}
if(isset($_GET['delete'])){
    $id=(int)$_GET['delete'];
    $event=db_one("SELECT image_url FROM events WHERE id_event=$id");
    if($event && !empty($event['image_url'])){
        $old_image_path='../'.$event['image_url'];
        if(file_exists($old_image_path) && is_file($old_image_path)){
            @unlink($old_image_path);
        }
    }
    mysqli_query($conn,"DELETE FROM events WHERE id_event=$id");
    set_flash('success','Event berhasil dihapus.');
    header('Location: '.url('admin/events.php'));exit;
}
$rows=db_all("SELECT e.*,v.venue_name FROM events e JOIN venues v ON e.id_venue=v.id_venue ORDER BY e.event_date DESC");
include '../includes/admin_header.php';
show_flash();
?>
<h1 class="admin-title">CRUD Event</h1>
<div class="form-card mb-6">
<form method="POST" enctype="multipart/form-data" class="crud-form" data-validate-form novalidate>
    <?php validation_box($errors); ?>
    <input type="hidden" name="id_event" value="<?= (int)($edit['id_event'] ?? 0); ?>">
    <input type="hidden" name="old_image" value="<?= e($edit['image_url'] ?? ''); ?>">
    <div class="form-grid">
        <div><label>Venue</label><select class="select" name="id_venue"><?php foreach($venues as $v): ?><option value="<?= (int)$v['id_venue']; ?>" <?= (($edit['id_venue']??'')==$v['id_venue'])?'selected':''; ?>><?= e($v['venue_name']); ?></option><?php endforeach; ?></select></div>
        <div><label>Title</label><input class="input" name="title" value="<?= e($edit['title'] ?? ''); ?>" data-validate data-required="true" data-min="3" data-label="Judul event"></div>
        <div><label>Date</label><input class="input" type="date" name="event_date" value="<?= e($edit['event_date'] ?? ''); ?>" data-validate data-required="true" data-label="Tanggal event"></div>
        <div><label>Start Time</label><input class="input" type="time" name="start_time" value="<?= e($edit['start_time'] ?? ''); ?>" data-validate data-required="true" data-label="Jam mulai"></div>
        <div><label>Category</label><input class="input" name="category" value="<?= e($edit['category'] ?? ''); ?>" data-validate data-required="true" data-min="2" data-label="Kategori"></div>
        <div><label>Status</label><select class="select" name="status"><?php foreach(['upcoming','popular','featured','completed'] as $s): ?><option <?= (($edit['status']??'')===$s)?'selected':''; ?>><?= e($s); ?></option><?php endforeach; ?></select></div>
        <div><label>Seat Total</label><input class="input" type="number" name="seat_total" value="<?= e($edit['seat_total'] ?? 1000); ?>" data-validate data-required="true" data-min-number="1" data-label="Total seat"></div>
        <div><label>Seat Available</label><input class="input" type="number" name="seat_available" value="<?= e($edit['seat_available'] ?? 1000); ?>" data-validate data-required="true" data-min-number="0" data-label="Seat tersedia"></div>
    </div>
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
    <label>Description</label><textarea name="description" rows="4" data-validate data-required="true" data-min="10" data-label="Deskripsi event"><?= e($edit['description'] ?? ''); ?></textarea>
    <button class="primary-btn">Simpan Event</button>
</form>
</div>
<div class="table-wrap"><table><thead><tr><th>Title</th><th>Venue</th><th>Date</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= e($r['title']); ?></td><td><?= e($r['venue_name']); ?></td><td><?= e($r['event_date']); ?></td><td><?= e($r['status']); ?></td><td><a class="action-link" href="?edit=<?= (int)$r['id_event']; ?>">Edit</a> <a class="action-link" data-confirm="Hapus event?" href="?delete=<?= (int)$r['id_event']; ?>">Hapus</a></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php include '../includes/admin_footer.php'; ?>

