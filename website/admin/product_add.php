<?php
require_once __DIR__ . '/../includes/db.php';
if (!is_admin()) { header('Location: /login.php'); exit; }
?>
<?php

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/admin_header.php';

// Nếu form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $brand = $_POST['brand'] ?? 'OTHER';
    if (!in_array($brand, ['NIKE','ADIDAS','MLB','LUXURY','OTHER'], true)) { $brand = 'OTHER'; }
    $price    = intval($_POST['price'] ?? 0);
    $discount = intval($_POST['discount'] ?? 0);
    $image    = '';

    // Upload ảnh nếu có
    if (!empty($_FILES['image']['name'])) {
        $image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . "/../assets/images/" . $image);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, brand, price, discount, image) VALUES (?,?,?,?,?)");
    $stmt->bind_param("ssiis", $name, $brand, $price, $discount, $image);
    $stmt->execute();

    header("Location: products.php");
    exit;
}
?>

<div class="page-header"><h2>➕ Thêm sản phẩm</h2></div>
<form method="post" enctype="multipart/form-data" class="form">
  <label>Tên sản phẩm</label>
  <input type="text" name="name" required>

  <label>Thương hiệu</label>
  <select name="brand" required><option value="NIKE">Nike</option><option value="ADIDAS">Adidas</option><option value="MLB">MLB</option><option value="LUXURY">Luxury</option><option value="OTHER">Khác</option></select>

  <label>Giá</label>
  <input type="number" name="price" required>

  <label>Giảm (%)</label>
  <input type="number" name="discount" value="0">

  <label>Ảnh</label>
  <input type="file" name="image">

  <div class="actions">
    <button class="btn primary" type="submit">Thêm</button>
    <a class="btn secondary" href="products.php">Quay lại</a>
  </div>
</form>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
