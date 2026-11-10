<?php
require_once __DIR__.'/../includes/db.php';if(!is_admin()){header('Location: /login.php');exit;}
require_csrf();$id=(int)($_POST['id']??0);
try{$stmt=$conn->prepare('DELETE FROM products WHERE id=?');$stmt->bind_param('i',$id);$stmt->execute();flash($stmt->affected_rows?'Đã xóa sản phẩm.':'Không tìm thấy sản phẩm.');}
catch(mysqli_sql_exception $ex){flash('Không thể xóa sản phẩm đã xuất hiện trong đơn hàng.','error');}
header('Location: /admin/products.php');exit;
