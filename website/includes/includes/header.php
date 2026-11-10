<?php
require_once __DIR__ . '/db.php';

// đảm bảo session đã khởi tạo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Xám Sneaker</title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="container header-bar">
      <!-- Logo -->
      <div class="logo">
        <a href="/index.php">XÁM SNEAKER</a>
      </div>

      <!-- Search -->
      <div class="search">
        <form method="get" action="/search.php">
          <input type="text" name="q" placeholder="Tìm kiếm sản phẩm...">
          <button type="submit">🔍</button>
        </form>
      </div>

      <!-- Hotline -->
      <div class="hotline">📞 <strong>0799353295</strong></div>

      <!-- User -->
      <div class="user">
        <?php if (isset($_SESSION['user'])): ?>
          <div class="user-menu">
            <div class="user-avatar">👤</div>
            <span class="user-name"><?= htmlspecialchars($_SESSION['user']['username']) ?></span>
            <div class="user-dropdown">
              <a href="/profile.php">Thông tin tài khoản</a>
              <a href="/my_orders.php">Đơn hàng của tôi</a>
              <a href="/logout.php">Đăng xuất</a>
            </div>
          </div>
        <?php else: ?>
          <a href="/login.php">Đăng nhập</a>
          <a href="/register.php">Đăng ký</a>
        <?php endif; ?>
      </div>

      <!-- Hamburger button -->
      <button class="menu-toggle">☰</button>
    </div>

    <!-- Navigation -->
    <nav class="nav container">
      <a href="/index.php">Trang chủ</a>
      <a href="/nike.php">Nike</a>
      <a href="/adidas.php">Adidas</a>
      <a href="/mlb.php">MLB</a>
      <a href="/luxury.php">Luxury</a>
      <a href="/other_brands.php">Thương hiệu khác</a>
      <a href="/cart.php">Giỏ hàng</a>
    </nav>
  </header>

  <main class="container">

  <script>
    // Toggle menu on mobile
    document.querySelector('.menu-toggle').addEventListener('click', function() {
      document.querySelector('.nav').classList.toggle('show');
    });
  </script>
