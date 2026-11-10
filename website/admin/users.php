<?php
require_once __DIR__.'/../includes/db.php';if(!is_admin()){header('Location: /login.php');exit;}
$users=$conn->query('SELECT id,username,phone,email,role,created_at FROM users ORDER BY id DESC LIMIT 250');
$adminTitle='Quản lý người dùng';include __DIR__.'/includes/admin_header.php';
?>
<div class="panel-heading"><div><h2>Danh sách tài khoản</h2><p>Chỉ người quản trị mới có quyền sửa phân quyền người dùng.</p></div></div>
<div class="table-wrap"><table class="table"><thead><tr><th>Người dùng</th><th>Số điện thoại</th><th>Email</th><th>Quyền</th><th>Hành động</th></tr></thead><tbody><?php while($u=$users->fetch_assoc()): ?><tr><td><strong><?=e($u['username'])?></strong><small class="block">#<?=(int)$u['id']?></small></td><td><?=e($u['phone']??'—')?></td><td><?=e($u['email']??'—')?></td><td><span class="status <?= $u['role']==='admin'?'success':'pending'?>"><?=e($u['role'])?></span></td><td><div class="row-actions"><a class="btn secondary" href="/admin/user_edit.php?id=<?=(int)$u['id']?>">Sửa</a><?php if((int)$u['id']!==(int)$_SESSION['user']['id']):?><form method="post" action="/admin/user_delete.php" data-confirm="Xóa người dùng này? Thao tác không thể hoàn tác."><?=csrf_field()?><input type="hidden" name="id" value="<?=(int)$u['id']?>"><button class="btn danger">Xóa</button></form><?php endif;?></div></td></tr><?php endwhile; ?></tbody></table></div>
<?php include __DIR__.'/includes/admin_footer.php'; ?>
