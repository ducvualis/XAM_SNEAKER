<?php
require_once __DIR__ . '/db.php';
$pageTitle = $pageTitle ?? 'XÁM SNEAKER | Giày sneaker phong cách';
$activePath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$cartCount = array_sum(array_map(static function($item){return (int)($item['qty']??0);}, $_SESSION['cart']??[]));
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="description" content="XÁM SNEAKER - Khám phá giày sneaker, tìm kiếm mẫu yêu thích và đặt hàng trực tuyến.">
  <title><?= e($pageTitle) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css?v=2">
</head>
<body>
<div class="utility-bar"><div class="container utility-inner"><span>Giao diện thử nghiệm MVP · Thanh toán khi nhận hàng</span><span>Hỗ trợ: 0799 353 295</span></div></div>
<header class="site-header">
  <div class="container header-main">
    <a class="logo" href="/index.php" aria-label="XÁM SNEAKER Trang chủ">XÁM<span>SNEAKER</span><small>EST. 2026</small></a>
    <form class="search-form" method="get" action="/search.php" role="search">
      <input type="search" name="q" aria-label="Tìm kiếm sản phẩm" placeholder="Tìm mẫu giày bạn yêu thích..." value="<?= e($_GET['q']??'') ?>">
      <button type="submit" aria-label="Tìm kiếm">⌕ <span>Tìm kiếm</span></button>
    </form>
    <div class="header-actions">
      <?php if(isset($_SESSION['user'])): ?>
        <details class="account-menu"><summary>☻ <span><?= e($_SESSION['user']['username']) ?></span></summary>
          <div class="account-dropdown"><a href="/profile.php">Tài khoản</a><a href="/my_orders.php">Đơn hàng của tôi</a><?php if(is_admin()): ?><a href="/admin/index.php">Trang quản trị</a><?php endif; ?><a href="/logout.php">Đăng xuất</a></div>
        </details>
      <?php else: ?>
        <a class="login-link" href="/login.php">Đăng nhập</a><a class="login-link secondary" href="/register.php">Đăng ký</a>
      <?php endif; ?>
      <a class="cart-link" href="/cart.php" aria-label="Giỏ hàng">🛒 <span class="cart-count"><?= $cartCount ?></span></a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-label="Mở danh mục">☰</button>
    </div>
  </div>
  <nav class="nav" id="site-nav" aria-label="Danh mục sản phẩm">
    <div class="container nav-inner">
      <?php foreach(['/index.php'=>'Trang chủ','/nike.php'=>'Nike','/adidas.php'=>'Adidas','/mlb.php'=>'MLB','/luxury.php'=>'Luxury','/other_brands.php'=>'Thương hiệu khác','/search.php'=>'Khám phá sản phẩm'] as $href=>$label): ?>
        <a class="<?= $activePath===$href?'active':'' ?>" href="<?= e($href) ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </div>
  </nav>
</header>
<main class="container page-content">
<?php show_flash(); ?>
