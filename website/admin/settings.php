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
?>

<div class="page-header">
  <h2>⚙️ Cài đặt hệ thống</h2>
</div>

<div class="card">
  <h3>Thông tin tài khoản</h3>
  <p><strong>Tên đăng nhập:</strong> <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
  <p><strong>Quyền:</strong> <?= htmlspecialchars($_SESSION['user']['role'] ?? 'user') ?></p>
</div>

<h3>Thay đổi mật khẩu</h3>
<form method="post" action="settings_update.php" class="form">
  <label>Mật khẩu hiện tại</label>
  <input type="password" name="current_password" required>

  <label>Mật khẩu mới</label>
  <input type="password" name="new_password" required>

  <label>Xác nhận mật khẩu mới</label>
  <input type="password" name="confirm_password" required>

  <div class="actions">
    <button class="btn primary" type="submit">Cập nhật</button>
    <a class="btn secondary" href="index.php">Quay lại</a>
  </div>
</form>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
