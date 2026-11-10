<?php
require_once __DIR__.'/includes/db.php';if(!isset($_SESSION['user'])){header('Location: /login.php');exit;}
$uid=(int)$_SESSION['user']['id'];$stmt=$conn->prepare('SELECT username,phone,email,created_at FROM users WHERE id=?');$stmt->bind_param('i',$uid);$stmt->execute();$user=$stmt->get_result()->fetch_assoc();
$pageTitle='Tài khoản | XÁM SNEAKER';include __DIR__.'/includes/header.php';
?>
<div class="page-heading"><div><div class="eyebrow">CÁ NHÂN</div><h1>Hồ sơ của tôi</h1><p>Thông tin tài khoản bạn đang sử dụng.</p></div></div><div class="profile-card"><div class="avatar-icon">☻</div><h2><?=e($user['username']??'Người dùng')?></h2><div class="profile-rows"><p><span>Email</span><strong><?=e($user['email']??'Chưa cập nhật')?></strong></p><p><span>Số điện thoại</span><strong><?=e($user['phone']??'Chưa cập nhật')?></strong></p><p><span>Ngày tham gia</span><strong><?=e($user['created_at']??'')?></strong></p></div><div class="actions center-actions"><a class="btn btn-primary" href="/my_orders.php">Đơn hàng của tôi</a><a class="btn btn-outline" href="/logout.php">Đăng xuất</a></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
