<?php
include __DIR__ . '/includes/header.php';

if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}

$userId  = $_SESSION['user']['id'];
$orderId = intval($_GET['id'] ?? 0);

// Lấy đơn hàng của chính user
$stmt = $conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
  echo "<div class='err'>Không tìm thấy đơn hàng hoặc bạn không có quyền xem.</div>";
  include __DIR__ . '/includes/footer.php';
  exit;
}

// Lấy sản phẩm trong đơn
$stmtItems = $conn->prepare("SELECT oi.*, p.name, p.image 
                             FROM order_items oi 
                             JOIN products p ON oi.product_id = p.id 
                             WHERE oi.order_id=?");
$stmtItems->bind_param("i", $orderId);
$stmtItems->execute();
$items = $stmtItems->get_result();
?>

<h2>Chi tiết đơn hàng #<?= $order['id'] ?></h2>
<div class="card">
  <p><strong>Ngày đặt:</strong> <?= $order['created_at'] ?></p>
  <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
  <p><strong>Tổng tiền:</strong> <?= number_format($order['total_price']) ?>₫</p>
</div>

<h3>Sản phẩm đã mua</h3>
<div class="table-wrap">
  <table class="table">
    <thead>
      <tr>
        <th>Ảnh</th>
        <th>Tên</th>
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
          <?php if ($item['image']): ?>
            <img src="/assets/images/<?= htmlspecialchars($item['image']) ?>" style="height:50px">

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

<p><a class="btn" href="my_orders.php">Quay lại danh sách đơn hàng</a></p>
<?php include __DIR__ . '/includes/footer.php'; ?>
