<?php
require_once __DIR__.'/../../includes/db.php';
if(!is_admin()){header('Location: /login.php');exit;}
$adminTitle=$adminTitle??'Tổng quan';
$currentPage=basename(parse_url($_SERVER['REQUEST_URI']??'/',PHP_URL_PATH));
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($adminTitle)?> | XÁM SNEAKER Admin</title><link rel="stylesheet" href="/admin/admin.css?v=2"></head><body>
<div class="admin-shell"><aside class="admin-sidebar"><a class="admin-logo" href="/admin/index.php">XÁM <span>SNEAKER</span><small>ADMIN PANEL</small></a><div class="sidebar-label">QUẢN LÝ CỬA HÀNG</div><nav aria-label="Quản trị">
<?php foreach(['/admin/index.php'=>['▦','Tổng quan'], '/admin/products.php'=>['◈','Sản phẩm'], '/admin/orders.php'=>['▣','Đơn hàng'], '/admin/users.php'=>['♙','Người dùng'], '/admin/db_check.php'=>['▤','Kiểm tra CSDL'], '/admin/settings.php'=>['⚙','Cài đặt']] as $href=>$data):?><a href="<?=$href?>" class="<?=basename($href)===$currentPage?'active':''?>"><span class="menu-icon"><?=$data[0]?></span><?=$data[1]?></a><?php endforeach; ?></nav><div class="sidebar-footer"><a href="/index.php">↗ Xem website khách hàng</a><a href="/logout.php">⇥ Đăng xuất</a></div></aside><div class="admin-main"><header class="admin-topbar"><div><span class="breadcrumb-label">XÁM SNEAKER / QUẢN TRỊ</span><h1><?=e($adminTitle)?></h1></div><div class="admin-user">☻ <span><?=e($_SESSION['user']['username']??'Admin')?></span></div></header><main class="admin-content"><?php show_flash(); ?>
