<?php
require_once __DIR__ . '/../includes/db.php';
if (!is_admin()) { header('Location: /login.php'); exit; }
?>
<?php include __DIR__ . '/includes/admin_header.php';
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: /login.php');
  exit;
}

$err=''; $ok='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name = trim($_POST['name'] ?? '');
  $desc = trim($_POST['description'] ?? '');
  $price = intval($_POST['price'] ?? 0);
  $discount = intval($_POST['discount'] ?? 0);
  $slug = slugify($name);
  $img = upload_image($_FILES['image'] ?? null);
  if ($name && $price>0) {
    $stmt = $conn->prepare("INSERT INTO products (name, slug, description, price, discount, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $name, $slug, $desc, $price, $discount, $img);
    if ($stmt->execute()) $ok = 'Thêm sản phẩm thành công.';
    else $err = 'Lỗi khi thêm sản phẩm.';
  } else $err = 'Vui lòng nhập tên và giá.';
}
?>
<h2>Thêm sản phẩm</h2>
<?php if($err) echo "<div class='err'>$err</div>"; if($ok) echo "<div class='ok'>$ok</div>"; ?>
<form method="post" enctype="multipart/form-data" class="form">
  <label>Tên</label><input name="name" required>
  <label>Mô tả</label><textarea name="description"></textarea>
  <label>Giá</label><input name="price" type="number" required>
  <label>Giảm (%)</label><input name="discount" type="number" value="0">
  <label>Ảnh</label><input name="image" type="file" accept="image/*">
  <button class="btn" type="submit">Lưu</button>
  <a class="btn" href="products.php">Hủy</a>
</form>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>
