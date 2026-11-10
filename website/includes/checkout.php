<?php
require_once __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('final_price')) {
  function final_price($price, $discount) {
    return $discount > 0 ? $price * (100 - $discount) / 100 : $price;
  }
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
  echo '<div class="center"><p>Giỏ hàng trống, không thể thanh toán.</p><a class="btn primary" href="/index.php">Về trang chủ</a></div>';
  include __DIR__ . '/includes/footer.php'; exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name    = trim($_POST['name'] ?? '');
  $phone   = trim($_POST['phone'] ?? '');
  $email   = trim($_POST['email'] ?? '');
  $address = trim($_POST['address'] ?? '');

  if ($name && $phone && $address) {
    // Tính tổng
    $total = 0;
    foreach ($cart as $item) {
      $fp = final_price($item['product']['price'], $item['product']['discount']);
      $total += $fp * $item['qty'];
    }

    // Lưu đơn hàng
    $uid = $_SESSION['user']['id'] ?? null;
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, customer_name, customer_phone, customer_email, customer_address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $uid, $total, $name, $phone, $email, $address);

    if ($stmt->execute()) {
      $order_id = $stmt->insert_id;

      // Lưu item
      $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
      foreach ($cart as $item) {
        $p    = $item['product'];
        $fp   = final_price($p['price'], $p['discount']);
        $pid  = $p['id']; 
        $qty  = $item['qty']; 
        $price= $fp;
        $stmtItem->bind_param("iiii", $order_id, $pid, $qty, $price);
        $stmtItem->execute();
      }

      // Clear giỏ
      $_SESSION['cart'] = [];
      echo '<div class="center"><h3>Đặt hàng thành công!</h3><p>Chúng tôi sẽ liên hệ xác nhận.</p><a class="btn" href="/index.php">Về trang chủ</a></div>';
      include __DIR__ . '/includes/footer.php'; exit;
    } else {
      $err = "Có lỗi khi lưu đơn hàng: " . $conn->error;
    }
  } else {
    $err = "Vui lòng nhập đầy đủ tên, điện thoại, địa chỉ.";
  }
}
?>
<h2>Thanh toán</h2>
<form method="post" class="form">
  <?php if (!empty($err)) echo "<div style='color:#d00'>".htmlspecialchars($err)."</div>"; ?>
  <input type="text" name="name" placeholder="Họ tên" required>
  <input type="text" name="phone" placeholder="Số điện thoại" required>
  <input type="email" name="email" placeholder="Email (không bắt buộc)">
  <input type="text" name="address" placeholder="Địa chỉ nhận hàng" required>
  <button class="btn primary" type="submit">Xác nhận đặt hàng</button>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>
