<?php
require_once __DIR__ . '/../includes/db.php';
if (!is_admin()) { header('Location: /login.php'); exit; }
?>
<?php

// Nếu chưa đăng nhập thì chuyển hướng về trang login
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['username'])) {
    header("Location: login.php");
    exit;
}

include __DIR__ . '/includes/admin_header.php';
?>

<div class="page-header">
  <h2>📊 Dashboard</h2>
  <div class="welcome">
    Xin chào, 
    <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong>
  </div>
</div>

<!-- Cards quản lý -->
<div class="card-grid">
  <div class="card">
    <h3>👟 Sản phẩm</h3>
    <p>Xem và quản lý tất cả sản phẩm</p>
    <a class="btn primary" href="products.php">Đi tới sản phẩm</a>
  </div>
  <div class="card">
    <h3>📦 Đơn hàng</h3>
    <p>Theo dõi và cập nhật trạng thái đơn</p>
    <a class="btn primary" href="orders.php">Đi tới đơn hàng</a>
  </div>
  <div class="card">
    <h3>👤 Người dùng</h3>
    <p>Quản lý tài khoản người dùng</p>
    <a class="btn primary" href="users.php">Đi tới người dùng</a>
  </div>
</div>


<?php include __DIR__ . '/includes/admin_footer.php'; ?>
