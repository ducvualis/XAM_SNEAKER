<?php
require_once __DIR__.'/includes/db.php';if(!isset($_SESSION['user'])){header('Location: /login.php');exit;}
$userId=(int)$_SESSION['user']['id'];$stmt=$conn->prepare('SELECT id,total_price,status,created_at FROM orders WHERE user_id=? ORDER BY id DESC');$stmt->bind_param('i',$userId);$stmt->execute();$orders=$stmt->get_result();
$pageTitle='Đơn hàng của tôi | XÁM SNEAKER';include __DIR__.'/includes/header.php';
?>
<div class="page-heading"><div><div class="eyebrow">TÀI KHOẢN</div><h1>Đơn hàng của tôi</h1><p>Theo dõi các đơn bạn đã đặt bằng tài khoản này.</p></div></div>
<?php if(!$orders->num_rows): ?><div class="empty-state"><h2>Bạn chưa có đơn hàng nào</h2><a class="btn btn-primary" href="/search.php">Mua sắm ngay</a></div><?php else: ?><div class="table-wrap"><table class="table"><thead><tr><th>Mã đơn</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày đặt</th><th>Thao tác</th></tr></thead><tbody><?php while($o=$orders->fetch_assoc()): ?><tr><td><strong>#<?= (int)$o['id']?></strong></td><td><?=money($o['total_price'])?></td><td><span class="status-badge"><?=e($o['status'])?></span></td><td><?=e($o['created_at'])?></td><td><a class="btn btn-outline btn-sm" href="/order_detail.php?id=<?=(int)$o['id']?>">Xem chi tiết</a></td></tr><?php endwhile; ?></tbody></table></div><?php endif; ?>
<?php include __DIR__.'/includes/footer.php'; ?>
