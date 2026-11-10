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

$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i",$id); $stmt->execute(); $res = $stmt->get_result();
if (!$product = $res->fetch_assoc()) { echo "Không tìm thấy"; include __DIR__ . '/includes/admin_footer.php'; exit; }
$err=''; $ok='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name = trim($_POST['name'] ?? '');
  $desc = trim($_POST['description'] ?? '');
  $price = intval($_POST['price'] ?? 0);
  $discount = intval($_POST['discount'] ?? 0);
  $slug = slugify($name);
  $img = upload_image($_FILES['image'] ?? null);
  if ($name && $price>0) {
    if ($img) {
      $stmt = $conn->prepare("UPDATE products SET name=?, slug=?, description=?, price=?, discount=?, image=? WHERE id=?");
      $stmt->bind_param("sssissi",$name,$slug,$desc,$price,$discount,$img,$id);
    } else {
      $stmt = $conn->prepare("UPDATE products SET name=?, slug=?, description=?, price=?, discount=? WHERE id=?");
      $stmt->bind_param("sssiii",$name,$slug,$desc,$price,$discount,$id);
    }
    if ($stmt->execute()) $ok = 'Cập nhật thành công.';
    else $err = 'Lỗi khi cập nhật.';
  } else $err = 'Vui lòng nhập tên và giá.';
  // reload product
  $stmt = $conn->prepare("SELECT * FROM products WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute(); $product = $stmt->get_result()->fetch_assoc();
}
?>
<h2>Sửa sản phẩm</h2>
<?php if($err) echo "<div class='err'>$err</div>"; if($ok) echo "<div class='ok'>$ok</div>"; ?>
<form method="post" enctype="multipart/form-data" class="form">
  <label>Tên</label><input name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
  <label>Mô tả</label><textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
  <label>Giá</label><input name="price" type="number" value="<?= $product['price'] ?>" required>
  <label>Giảm (%)</label><input name="discount" type="number" value="<?= $product['discount'] ?>">
  <?php if ($product['image']): ?><p>Ảnh hiện tại:<br><img src="/uploads/<?= htmlspecialchars($product['image']) ?>" style="height:80px"></p><?php endif; ?>
  <label>Thay ảnh</label><input name="image" type="file" accept="image/*">
  <button class="btn" type="submit">Lưu</button>
  <a class="btn" href="products.php">Hủy</a>
</form>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>
