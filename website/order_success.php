<?php
require_once __DIR__.'/includes/db.php';
$id=(int)($_GET['id']??0);
if (!$id || (int)($_SESSION['last_order_id']??0)!==$id) { header('Location: /index.php');exit; }
$pageTitle='Đặt hàng thành công | XÁM SNEAKER';include __DIR__.'/includes/header.php';
?>
<div class="success-state"><div class="success-icon">✓</div><h1>Đặt hàng thành công!</h1><p>Đơn hàng <strong>#<?= $id ?></strong> đã được lưu vào hệ thống. Cửa hàng sẽ liên hệ xác nhận thông tin giao hàng.</p><p>Bạn có thể theo dõi đơn hàng sau khi đăng nhập (nếu đã đặt bằng tài khoản).</p><div class="actions center-actions"><a class="btn btn-primary" href="/index.php">Về trang chủ</a><a class="btn btn-outline" href="/my_orders.php">Đơn hàng của tôi</a></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
