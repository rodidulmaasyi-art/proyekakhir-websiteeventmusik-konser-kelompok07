<?php
include '../config/database.php';
require_admin();
$page_title='CRUD User';
if(isset($_GET['delete'])){mysqli_query($conn,"DELETE FROM users WHERE id_user=".(int)$_GET['delete']." AND role!='admin'");set_flash('success','User dihapus.');header('Location: '.url('admin/users.php'));exit;}
if($_SERVER['REQUEST_METHOD']==='POST'){
    $id=(int)$_POST['id_user'];$role=$_POST['role'];$membership=$_POST['membership'];
    if(in_array($role,['admin','user'],true)&&in_array($membership,['Free','Neon','Galaxy','Legend'],true)){
        mysqli_query($conn,"UPDATE users SET role='".mysqli_real_escape_string($conn,$role)."', membership='".mysqli_real_escape_string($conn,$membership)."' WHERE id_user=$id");
        set_flash('success','User berhasil diupdate.');
    }
}
$rows=db_all("SELECT * FROM users ORDER BY id_user DESC");
include '../includes/admin_header.php';show_flash();
?>
<h1 class="admin-title">CRUD User</h1>
<div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Membership</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= e($r['name']); ?></td><td><?= e($r['email']); ?></td><td colspan="2"><form method="POST" style="display:flex;gap:8px"><input type="hidden" name="id_user" value="<?= (int)$r['id_user']; ?>"><select class="select" name="role"><option <?= $r['role']==='user'?'selected':''; ?>>user</option><option <?= $r['role']==='admin'?'selected':''; ?>>admin</option></select><select class="select" name="membership"><?php foreach(['Free','Neon','Galaxy','Legend'] as $m): ?><option <?= $r['membership']===$m?'selected':''; ?>><?= e($m); ?></option><?php endforeach; ?></select><button class="action-link">Update</button></form></td><td><a class="action-link" data-confirm="Hapus user?" href="?delete=<?= (int)$r['id_user']; ?>">Hapus</a></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php include '../includes/admin_footer.php'; ?>
