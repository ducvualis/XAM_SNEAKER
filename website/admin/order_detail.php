<?php
require_once __DIR__ . '/../includes/db.php';
if (!is_admin()) { header('Location: /login.php'); exit; }
?>
<?php

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['username'])) {
    header("Location: login.php");
    exit;
}

// Kết nối CSDL
require_once __DIR__ . '/includes/db.php';

include __DIR__ . '/includes/admin_header.php';

$id = intval($_GET['id'] ?? 0);

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("SELECT * FROM orders WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
  echo "<div class='err'>Không tìm thấy đơn hàng.</div>";
  include __DIR__ . '/includes/admin_footer.php';
  exit;
}

// Lấy chi tiết sản phẩm trong đơn
$stmtItems = $conn->prepare("SELECT oi.*, p.name, p.image 
                             FROM order_items oi 
                             JOIN products p ON oi.product_id = p.id 
                             WHERE oi.order_id=?");
$stmtItems->bind_param("i", $id);
$stmtItems->execute();
$items = $stmtItems->get_result();
?>

<div class="page-header"><h2>Chi tiết đơn hàng #<?= $order['id'] ?></h2></div>

<div class="card">
  <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
  <p><strong>SĐT:</strong> <?= htmlspecialchars($order['customer_phone'] ?: '-') ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email'] ?: '-') ?></p>
  <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['customer_address']) ?></p>
  <p><strong>Tổng tiền:</strong> <?= number_format($order['total_price']) ?>₫</p>
  <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
  <p><strong>Ngày tạo:</strong> <?= $order['created_at'] ?></p>
</div>

<h3>Sản phẩm trong đơn</h3>
<div class="table-wrap">
  <table class="table">
    <thead>
      <tr>
        <th>Ảnh</th>
        <th>Tên sản phẩm</th>
        <th>Số lượng</th>
        <th>Giá</th>
        <th>Thành tiền</th>
      </tr>
    </thead>
    <tbody>
      <?php while($item = $items->fetch_assoc()):
        $line = $item['price'] * $item['quantity'];
      ?>
      <tr>
        <td>
          <?php if (!empty($item['image'])): ?>
            <img src="/uploads/<?= htmlspecialchars($item['image']) ?>" style="height:50px" alt="">
          <?php else: ?>-<?php endif; ?>
        </td>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= $item['quantity'] ?></td>
        <td><?= number_format($item['price']) ?>₫</td>
        <td><?= number_format($line) ?>₫</td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<h3>Cập nhật trạng thái đơn hàng</h3>
<form method="post" action="order_update.php" class="form">
  <input type="hidden" name="id" value="<?= $order['id'] ?>">
  <label>Trạng thái</label>
  <select name="status">
    <option value="Đang xử lý" <?= $order['status']=='Đang xử lý'?'selected':'' ?>>Đang xử lý</option>
    <option value="Hoàn tất" <?= $order['status']=='Hoàn tất'?'selected':'' ?>>Hoàn tất</option>
    <option value="Đã hủy" <?= $order['status']=='Đã hủy'?'selected':'' ?>>Đã hủy</option>
  </select>
  <div class="actions">
    <button class="btn" type="submit">Cập nhật</button>
    <a class="btn secondary" href="orders.php">Quay lại</a>
  </div>
</form>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
