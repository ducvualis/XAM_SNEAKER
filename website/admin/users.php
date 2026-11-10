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
  <h2>👤 Quản lý người dùng</h2>
</div>

<div class="table-wrap">
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>SĐT</th>
        <th>Email</th>
        <th>Quyền</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $res = $conn->query("SELECT id, username, phone, email, role FROM users ORDER BY id DESC");
      if ($res && $res->num_rows > 0):
        while($u = $res->fetch_assoc()):
      ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
        <td><?= htmlspecialchars($u['email'] ?? '-') ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
        <td>
          <a class="btn secondary" href="user_edit.php?id=<?= $u['id'] ?>">Sửa</a>
          <a class="btn danger" href="user_delete.php?id=<?= $u['id'] ?>" onclick="return confirm('Xóa người dùng này?')">Xóa</a>
        </td>
      </tr>
      <?php
        endwhile;
      else:
      ?>
      <tr>
        <td colspan="6">Chưa có người dùng nào.</td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
