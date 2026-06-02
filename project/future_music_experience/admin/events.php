<?php
include '../config/database.php';
require_admin();
$page_title='CRUD Event';
$errors=[];
$edit_id=(int)($_GET['edit'] ?? 0);
$edit=$edit_id?db_one("SELECT * FROM events WHERE id_event=$edit_id"):null;
$venues=db_all("SELECT * FROM venues ORDER BY venue_name");

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $id=(int)($_POST['id_event'] ?? 0);
    $id_venue=(int)$_POST['id_venue'];
    $title=trim($_POST['title'] ?? '');
    $description=trim($_POST['description'] ?? '');
    $event_date=$_POST['event_date'] ?? '';
    $start_time=$_POST['start_time'] ?? '';
    $category=trim($_POST['category'] ?? '');
    $status=$_POST['status'] ?? 'upcoming';
    $image_url=trim($_POST['image_url'] ?? '');
    $seat_total=(int)$_POST['seat_total'];
    $seat_available=(int)$_POST['seat_available'];
    if(strlen($title)<3)$errors[]='Judul event minimal 3 karakter.';
    if(strlen($description)<10)$errors[]='Deskripsi minimal 10 karakter.';
    if(!$event_date)$errors[]='Tanggal wajib diisi.';
    if(!$start_time)$errors[]='Jam wajib diisi.';
    if(!filter_var($image_url,FILTER_VALIDATE_URL))$errors[]='Image URL harus valid.';
    if($seat_total<1 || $seat_available<0)$errors[]='Seat tidak valid.';
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
<form method="POST" class="crud-form" data-validate-form novalidate>
    <?php validation_box($errors); ?>
    <input type="hidden" name="id_event" value="<?= (int)($edit['id_event'] ?? 0); ?>">
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
    <label>Image URL / Cloudinary URL</label><input class="input" name="image_url" value="<?= e($edit['image_url'] ?? 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1400&q=80'); ?>" data-validate data-required="true" data-label="Image URL">
    <label>Description</label><textarea name="description" rows="4" data-validate data-required="true" data-min="10" data-label="Deskripsi event"><?= e($edit['description'] ?? ''); ?></textarea>
    <button class="primary-btn">Simpan Event</button>
</form>
</div>
<div class="table-wrap"><table><thead><tr><th>Title</th><th>Venue</th><th>Date</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= e($r['title']); ?></td><td><?= e($r['venue_name']); ?></td><td><?= e($r['event_date']); ?></td><td><?= e($r['status']); ?></td><td><a class="action-link" href="?edit=<?= (int)$r['id_event']; ?>">Edit</a> <a class="action-link" data-confirm="Hapus event?" href="?delete=<?= (int)$r['id_event']; ?>">Hapus</a></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php include '../includes/admin_footer.php'; ?>
