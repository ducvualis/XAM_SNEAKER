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

$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "<div class='err'>Không tìm thấy sản phẩm.</div>";
    include __DIR__ . '/includes/admin_footer.php';
    exit;
}

// Nếu form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $brand = $_POST['brand'] ?? 'OTHER';
    if (!in_array($brand, ['NIKE','ADIDAS','MLB','LUXURY','OTHER'], true)) { $brand = 'OTHER'; }
    $price    = intval($_POST['price'] ?? 0);
    $discount = intval($_POST['discount'] ?? 0);
    $image    = $product['image'];

    if (!empty($_FILES['image']['name'])) {
        $image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . "/../assets/images/" . $image);
    }

    $stmt = $conn->prepare("UPDATE products SET name=?, brand=?, price=?, discount=?, image=? WHERE id=?");
    $stmt->bind_param("ssiisi", $name, $brand, $price, $discount, $image, $id);
    $stmt->execute();

    header("Location: products.php");
    exit;
}
?>

<div class="page-header"><h2>✏️ Sửa sản phẩm #<?= $product['id'] ?></h2></div>
<form method="post" enctype="multipart/form-data" class="form">
  <label>Tên sản phẩm</label>
  <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

  <label>Thương hiệu</label>
  <select name="brand" required>
    <?php foreach (['NIKE'=>'Nike','ADIDAS'=>'Adidas','MLB'=>'MLB','LUXURY'=>'Luxury','OTHER'=>'Khác'] as $v=>$label): ?>
    <option value="<?= $v ?>" <?= ($product['brand'] === $v ? 'selected' : '') ?>><?= $label ?></option>
    <?php endforeach; ?>
  </select>

  <label>Giá</label>
  <input type="number" name="price" value="<?= $product['price'] ?>" required>

  <label>Giảm (%)</label>
  <input type="number" name="discount" value="<?= $product['discount'] ?>">

  <label>Ảnh hiện tại</label>
  <?php if ($product['image']): ?>
    <img src="/assets/images/<?= htmlspecialchars($product['image']) ?>" style="height:50px">
  <?php endif; ?>
  <input type="file" name="image">

  <div class="actions">
    <button class="btn primary" type="submit">Cập nhật</button>
    <a class="btn secondary" href="products.php">Quay lại</a>
  </div>
</form>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
