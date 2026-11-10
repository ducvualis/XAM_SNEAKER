<?php
require_once __DIR__.'/../includes/db.php';if(!is_admin()){header('Location: /login.php');exit;}
require_csrf();$id=(int)($_POST['id']??0);$name=trim((string)($_POST['username']??''));$phone=trim((string)($_POST['phone']??''));$email=trim((string)($_POST['email']??''));$role=(string)($_POST['role']??'user');
if($id<=0||app_length($name)<2||app_length($name)>100||!in_array($role,['user','admin'],true)||($email!==''&&!filter_var($email,FILTER_VALIDATE_EMAIL))){flash('Dữ liệu tài khoản không hợp lệ.','error');header('Location: /admin/users.php');exit;}
if($id===(int)$_SESSION['user']['id'] && $role!=='admin'){flash('Không thể tự gỡ quyền quản trị của mình.','error');header('Location: /admin/users.php');exit;}
$phone=$phone?:null;$email=$email?:null;
try{$stmt=$conn->prepare('UPDATE users SET username=?,phone=?,email=?,role=? WHERE id=?');$stmt->bind_param('ssssi',$name,$phone,$email,$role,$id);$stmt->execute();flash('Đã cập nhật tài khoản.');}
catch(mysqli_sql_exception $ex){flash('Không thể lưu: số điện thoại hoặc email đã tồn tại.','error');}
header('Location: /admin/users.php');exit;
