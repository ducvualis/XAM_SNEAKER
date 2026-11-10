<?php
require_once __DIR__.'/../includes/db.php';if(!is_admin()){header('Location: /login.php');exit;}
require_csrf();$id=(int)($_POST['id']??0);
if($id===(int)$_SESSION['user']['id']||$id<=0){flash('Không thể xóa tài khoản đang đăng nhập.','error');}
else {try{$stmt=$conn->prepare('DELETE FROM users WHERE id=?');$stmt->bind_param('i',$id);$stmt->execute();flash('Đã xóa tài khoản.');}catch(mysqli_sql_exception $ex){flash('Không thể xóa tài khoản này.','error');}}
header('Location: /admin/users.php');exit;
