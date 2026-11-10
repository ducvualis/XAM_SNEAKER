<?php
require_once __DIR__.'/../includes/db.php';if(!is_admin()){header('Location: /login.php');exit;}
$status=(string)($_GET['status']??'');$allowed=['Đang xử lý','Hoàn tất','Đã hủy'];
if(in_array($status,$allowed,true)){$stmt=$conn->prepare('SELECT * FROM orders WHERE status=? ORDER BY id DESC LIMIT 250');$stmt->bind_param('s',$status);$stmt->execute();$orders=$stmt->get_result();}
else $orders=$conn->query('SELECT * FROM orders ORDER BY id DESC LIMIT 250');
$adminTitle='Quản lý đơn hàng';include __DIR__.'/includes/admin_header.php';
?>
<div class="panel-heading"><div><h2>Danh sách đơn hàng</h2><p>Kiểm tra thông tin và cập nhật tình trạng xử lý đơn.</p></div></div>
<form class="admin-filter" method="get"><label for="status">Lọc trạng thái</label><select id="status" name="status"><option value="">Tất cả</option><?php foreach($allowed as $item):?><option value="<?=e($item)?>" <?=$status===$item?'selected':''?>><?=e($item)?></option><?php endforeach;?></select><button class="btn" type="submit">Lọc</button></form>
<div class="table-wrap"><table class="table"><thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Liên hệ</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày đặt</th><th></th></tr></thead><tbody><?php while($o=$orders->fetch_assoc()):?><tr><td><strong>#<?=(int)$o['id']?></strong></td><td><?=e($o['customer_name'])?></td><td><?=e($o['customer_phone'])?></td><td><?=money($o['total_price'])?></td><td><span class="status <?= $o['status']==='Hoàn tất'?'success':'pending'?>"><?=e($o['status'])?></span></td><td><?=e($o['created_at'])?></td><td><a class="btn secondary" href="/admin/order_detail.php?id=<?=(int)$o['id']?>">Chi tiết</a></td></tr><?php endwhile;?></tbody></table></div>
<?php include __DIR__.'/includes/admin_footer.php'; ?>
