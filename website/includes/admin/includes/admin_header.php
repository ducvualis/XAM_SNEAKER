<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Admin - Xám Sneaker</title>
  <!-- Liên kết CSS admin -->
  <link rel="stylesheet" href="/admin/admin.css">
  <!-- Có thể thêm font hoặc icon nếu cần -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Header đồng bộ với web -->
  <header class="site-header">
    <div class="container header-bar">
      <div class="logo">
        <a href="/index.php">XÁM <span>SNEAKER</span></a>
      </div>
      <nav class="nav">
        <a href="/index.php">🏠 Trang web</a>
        <a href="/admin/index.php" class="active">📊 Admin</a>
      </nav>
    </div>
  </header>

  <!-- Layout admin -->
  <div class="admin-layout">
    <aside class="admin-sidebar">
      <div class="brand">
        <h2>Xám Sneaker Admin</h2>
        <p>Quản trị hệ thống</p>
      </div>
      <nav>
        <a href="index.php" class="active">📊 Dashboard</a>
        <a href="products.php">👟 Sản phẩm</a>
        <a href="orders.php">📦 Đơn hàng</a>
        <a href="users.php">👤 Người dùng</a>
        <a href="db_check.php">🗄️ Kiểm tra CSDL</a>\n        <a href="settings.php">⚙️ Cài đặt</a>
        <a href="logout.php">🚪 Đăng xuất</a>
      </nav>
    </aside>
    <main class="admin-content">
