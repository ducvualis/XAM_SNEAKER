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
?>

<div class="page-header">
  <h2>📦 Quản lý đơn hàng</h2>
</div>

<div class="table-wrap">
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Khách</th>
        <th>SĐT</th>
        <th>Tổng</th>
        <th>Trạng thái</th>
        <th>Ngày</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $res = $conn->query("SELECT * FROM orders ORDER BY id DESC");
      if ($res && $res->num_rows > 0):
        while($o = $res->fetch_assoc()):
      ?>
      <tr>
        <td><?= $o['id'] ?></td>
        <td><?= htmlspecialchars($o['customer_name']) ?></td>
        <td><?= htmlspecialchars($o['customer_phone'] ?: '-') ?></td>
        <td><?= number_format($o['total_price']) ?>₫</td>
        <td>
          <?php if ($o['status'] === 'Hoàn tất'): ?>
            <span class="status success">Hoàn tất</span>
          <?php elseif ($o['status'] === 'Đang xử lý'): ?>
            <span class="status pending">Đang xử lý</span>
          <?php else: ?>
            <?= htmlspecialchars($o['status']) ?>
          <?php endif; ?>
        </td>
        <td><?= $o['created_at'] ?></td>
        <td><a class="btn" href="order_detail.php?id=<?= $o['id'] ?>">Chi tiết</a></td>
      </tr>
      <?php
        endwhile;
      else:
      ?>
      <tr>
        <td colspan="7">Chưa có đơn hàng nào.</td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
