<?php include __DIR__ . '/includes/header.php';

if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}

$user = $_SESSION['user'];
?>
<div class="profile-page">
  <h2>Thông tin tài khoản</h2>

  <div class="profile-card">
    <div class="profile-avatar">
      <span class="avatar-icon">👤</span>
    </div>
    <div class="profile-info">
      <p><strong>Tên hiển thị:</strong> <?= htmlspecialchars($user['username']) ?></p>
      <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($user['phone'] ?? '-') ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '-') ?></p>
    </div>
    <div class="profile-actions">
      <a class="btn" href="/my_orders.php">📦 Đơn hàng của tôi</a>
      <a class="btn secondary" href="/logout.php">🚪 Đăng xuất</a>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
