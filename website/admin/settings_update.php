<?php
require_once __DIR__.'/../includes/db.php';if(!is_admin()){header('Location: /login.php');exit;}
require_csrf();$old=(string)($_POST['current_password']??'');$new=(string)($_POST['new_password']??'');$again=(string)($_POST['confirm_password']??'');
$id=(int)$_SESSION['user']['id'];$stmt=$conn->prepare('SELECT password FROM users WHERE id=?');$stmt->bind_param('i',$id);$stmt->execute();$u=$stmt->get_result()->fetch_assoc();
if(!$u||!password_verify($old,$u['password']))flash('Mật khẩu hiện tại không đúng.','error');
elseif(strlen($new)<8||$new!==$again)flash('Mật khẩu mới cần ít nhất 8 ký tự và phải trùng với xác nhận.','error');
else {$hash=password_hash($new,PASSWORD_DEFAULT);$stmt=$conn->prepare('UPDATE users SET password=? WHERE id=?');$stmt->bind_param('si',$hash,$id);$stmt->execute();flash('Đã đổi mật khẩu thành công.');}
header('Location: /admin/settings.php');exit;
