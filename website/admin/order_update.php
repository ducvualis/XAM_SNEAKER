<?php
require_once __DIR__.'/../includes/db.php';if(!is_admin()){header('Location: /login.php');exit;}
require_csrf();$id=(int)($_POST['id']??0);$status=(string)($_POST['status']??'');
if($id>0 && in_array($status,['Đang xử lý','Hoàn tất','Đã hủy'],true)) {
  $stmt=$conn->prepare('UPDATE orders SET status=? WHERE id=?');$stmt->bind_param('si',$status,$id);$stmt->execute();flash('Đã cập nhật trạng thái đơn hàng.');
} else flash('Trạng thái không hợp lệ.','error');
header('Location: /admin/order_detail.php?id='.$id);exit;
