<?php include __DIR__ . '/includes/header.php';

if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}

$userId = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
?>
<h2>Đơn hàng của tôi</h2>
<div class="table-wrap">
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Tổng tiền</th>
        <th>Trạng thái</th>
        <th>Ngày</th>
        <th>Chi tiết</th>
      </tr>
    </thead>
    <tbody>
      <?php while($o = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $o['id'] ?></td>
        <td><?= number_format($o['total_price']) ?>₫</td>
        <td><?= htmlspecialchars($o['status']) ?></td>
        <td><?= $o['created_at'] ?></td>
       <a class="btn" href="order_detail.php?id=<?= $o['id'] ?>">Xem</a>

      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
