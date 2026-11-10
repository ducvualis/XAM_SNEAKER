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

$id = intval($_GET['id'] ?? 0);

// Lấy thông tin user
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "<div class='err'>Không tìm thấy người dùng.</div>";
    include __DIR__ . '/includes/admin_footer.php';
    exit;
}
?>

<div class="page-header"><h2>Sửa người dùng #<?= $user['id'] ?></h2></div>

<form method="post" action="user_update.php" class="form">
  <input type="hidden" name="id" value="<?= $user['id'] ?>">
  
  <label>Tên đăng nhập</label>
  <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

  <label>SĐT</label>
  <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">

  <label>Email</label>
  <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">

  <label>Quyền</label>
  <select name="role">
    <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
    <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
  </select>

  <div class="actions">
    <button class="btn primary" type="submit">Cập nhật</button>
    <a class="btn secondary" href="users.php">Quay lại</a>
  </div>
</form>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
