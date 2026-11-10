<?php
require_once __DIR__.'/../includes/db.php';if(!is_admin()){header('Location: /login.php');exit;}
$id=(int)($_GET['id']??0);$stmt=$conn->prepare('SELECT id,username,phone,email,role FROM users WHERE id=?');$stmt->bind_param('i',$id);$stmt->execute();$user=$stmt->get_result()->fetch_assoc();if(!$user){http_response_code(404);exit('Không tìm thấy tài khoản.');}
$adminTitle='Sửa người dùng';include __DIR__.'/includes/admin_header.php';
?>
<div class="panel-heading"><div><h2>Chỉnh sửa tài khoản #<?=$id?></h2><p>Cập nhật thông tin và vai trò người dùng.</p></div></div><form class="form" method="post" action="/admin/user_update.php"><?=csrf_field()?><input type="hidden" name="id" value="<?=$id?>"><label>Tên hiển thị</label><input name="username" maxlength="100" value="<?=e($user['username'])?>" required><label>Số điện thoại</label><input name="phone" value="<?=e($user['phone'])?>"><label>Email</label><input type="email" name="email" value="<?=e($user['email'])?>"><label>Quyền</label><select name="role"><option value="user" <?=$user['role']==='user'?'selected':''?>>Khách hàng</option><option value="admin" <?=$user['role']==='admin'?'selected':''?>>Quản trị</option></select><div class="actions"><button class="btn" type="submit">Lưu thay đổi</button><a class="btn secondary" href="/admin/users.php">Hủy</a></div></form>
<?php include __DIR__.'/includes/admin_footer.php'; ?>
