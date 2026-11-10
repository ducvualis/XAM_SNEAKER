<?php
require_once __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
  header('Location: /login.php');
  exit;
}

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add') {
  $id   = intval($_POST['id'] ?? $_GET['id'] ?? 0);
  $size = $_POST['size'] ?? '';
  $qty  = intval($_POST['qty'] ?? 1);

  if ($id > 0) {
    $stmt = $conn->prepare("SELECT id, name, price, image, discount FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
      $key = $id . '_' . $size;
      if (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['qty'] += $qty;
      } else {
        $_SESSION['cart'][$key] = [
          'product' => $product,
          'size'    => $size,
          'qty'     => $qty,
        ];
      }
    }
  }
  header("Location: /cart.php");
  exit;
}

if ($action === 'update') {
  if (!empty($_POST['qty'])) {
    foreach ($_POST['qty'] as $key => $qty) {
      $qty = max(1, intval($qty));
      if (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['qty'] = $qty;
      }
    }
  }
  header("Location: /cart.php?updated=1");
  exit;
}

if ($action === 'remove') {
  $key = $_GET['id'] ?? '';
  unset($_SESSION['cart'][$key]);
  header("Location: /cart.php");
  exit;
}

$cart = $_SESSION['cart'];
if (empty($cart)) {
  echo '<div class="center"><p>Giỏ hàng chưa có sản phẩm.</p><a class="btn primary" href="/index.php">Quay lại trang chủ</a></div>';
  include __DIR__ . '/includes/footer.php'; exit;
}

function final_price($price, $discount) {
  return $discount > 0 ? $price * (100 - $discount) / 100 : $price;
}
?>

<h2>🛒 Giỏ hàng</h2>
<?php if (isset($_GET['updated'])): ?>
  <div style="color:green; margin:10px 0">✅ Giỏ hàng đã được cập nhật</div>
<?php endif; ?>

<form method="post" action="/cart.php">
  <input type="hidden" name="action" value="update">
  <table class="table cart-table">
    <tr>
      <th>Ảnh</th><th>Sản phẩm</th><th>Size</th><th>Giá</th><th>Giảm</th><th>Giá sau giảm</th><th>Số lượng</th><th>Thành tiền</th><th>Xóa</th>
    </tr>
    <?php $total = 0; foreach ($cart as $key => $item):
      if (!isset($item['product'])) continue;
      $p        = $item['product'];
      $price    = $p['price'] ?? 0;
      $discount = $p['discount'] ?? 0;
      $fp       = final_price($price, $discount);
      $qty      = $item['qty'] ?? 1;
      $line     = $fp * $qty;
      $total   += $line;
    ?>
    <tr>
      <td><?php if (!empty($p['image'])): ?><img src="/assets/images/<?= htmlspecialchars($p['image']) ?>" style="height:60px"><?php endif; ?></td>
      <td><?= htmlspecialchars($p['name'] ?? '') ?></td>
      <td><?= htmlspecialchars($item['size'] ?? '') ?></td>
      <td><?= number_format($price) ?>₫</td>
      <td><?= $discount ?>%</td>
      <td><?= number_format($fp) ?>₫</td>
      <td><input type="number" name="qty[<?= $key ?>]" value="<?= $qty ?>" min="1" style="width:60px"></td>
      <td><?= number_format($line) ?>₫</td>
      <td><a class="btn danger" href="/cart.php?action=remove&id=<?= urlencode($key) ?>" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a></td>
    </tr>
    <?php endforeach; ?>
  </table>
  <div class="actions">
    <button class="btn" type="submit">Cập nhật giỏ</button>
    <a class="btn primary" href="/checkout.php">Thanh toán</a>
  </div>
  <div class="total">Tổng: <?= number_format($total) ?>₫</div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
