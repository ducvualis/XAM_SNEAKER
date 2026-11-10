<?php
require_once __DIR__.'/includes/db.php';if(!isset($_SESSION['user'])){header('Location: /login.php');exit;}
$id=max(0,(int)($_GET['id']??0));$uid=(int)$_SESSION['user']['id'];
$stmt=$conn->prepare('SELECT * FROM orders WHERE id=? AND user_id=?');$stmt->bind_param('ii',$id,$uid);$stmt->execute();$order=$stmt->get_result()->fetch_assoc();
$pageTitle='Chi tiết đơn hàng | XÁM SNEAKER';include __DIR__.'/includes/header.php';
if(!$order){echo '<div class="empty-state">Không tìm thấy đơn hàng hoặc bạn không có quyền xem.</div>';include __DIR__.'/includes/footer.php';exit;}
$stmt=$conn->prepare('SELECT oi.quantity,oi.price,p.name,p.image, '.(order_has_size($conn)?'oi.shoe_size':"'' AS shoe_size").' FROM order_items oi LEFT JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?');$stmt->bind_param('i',$id);$stmt->execute();$items=$stmt->get_result();
?>
<div class="page-heading"><div><div class="eyebrow">CHI TIẾT ĐƠN HÀNG</div><h1>Đơn hàng #<?=$id?></h1><p>Đặt lúc <?=e($order['created_at'])?> · <?=e($order['status'])?></p></div><a class="btn btn-outline" href="/my_orders.php">← Danh sách đơn</a></div>
<div class="order-info-grid"><div class="info-card"><h3>Thông tin người nhận</h3><p><?=e($order['customer_name'])?></p><p><?=e($order['customer_phone'])?></p><p><?=e($order['customer_address'])?></p></div><div class="info-card"><h3>Thanh toán</h3><p>Tổng tiền hàng</p><strong><?=money($order['total_price'])?></strong><p>Trạng thái: <?=e($order['status'])?></p></div></div>
<div class="table-wrap"><table class="table"><thead><tr><th>Sản phẩm</th><th>Size</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr></thead><tbody><?php while($item=$items->fetch_assoc()): ?><tr><td><div class="cart-product"><img src="<?=e(product_image($item['image']))?>" alt=""><span><?=e($item['name']??'Sản phẩm đã xóa')?></span></div></td><td><?=e($item['shoe_size']?:'—')?></td><td><?= (int)$item['quantity']?></td><td><?=money($item['price'])?></td><td><?=money($item['price']*$item['quantity'])?></td></tr><?php endwhile; ?></tbody></table></div>
<?php include __DIR__.'/includes/footer.php'; ?>
