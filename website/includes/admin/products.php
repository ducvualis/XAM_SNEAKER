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
  <h2>👟 Quản lý sản phẩm</h2>
  <p><a class="btn primary" href="product_add.php">+ Thêm sản phẩm</a></p>
</div>

<div class="table-wrap">
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Ảnh</th>
        <th>Tên</th>
        <th>Giá</th>
        <th>Giảm</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
      if ($res && $res->num_rows > 0):
        while($p = $res->fetch_assoc()):
      ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td>
          <?php if (!empty($p['image'])): ?>
            <img src="/assets/images/<?= htmlspecialchars($p['image']) ?>" alt="" style="height:50px">
          <?php else: ?>
            -
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= number_format($p['price']) ?>₫</td>
        <td><?= intval($p['discount']) ?>%</td>
        <td>
          <a class="btn secondary" href="product_edit.php?id=<?= $p['id'] ?>">Sửa</a>
          <a class="btn danger" href="product_delete.php?id=<?= $p['id'] ?>" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
        </td>
      </tr>
      <?php
        endwhile;
      else:
      ?>
      <tr>
        <td colspan="6">Chưa có sản phẩm nào.</td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
