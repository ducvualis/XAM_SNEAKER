<?php include __DIR__ . '/includes/header.php';

$err = ''; $ok = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $confirm = trim($_POST['confirm'] ?? '');

  if ($username && $password && $confirm && ($phone || $email)) {
    if ($password !== $confirm) {
      $err = 'Mật khẩu xác nhận không khớp.';
    } else {
      $hash = password_hash($password, PASSWORD_BCRYPT);
      if ($email === '') $email = null;
      if ($phone === '') $phone = null;

      $stmt = $conn->prepare("INSERT INTO users (username, phone, email, password) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $username, $phone, $email, $hash);

      if ($stmt->execute()) {
        $ok = 'Đăng ký thành công. Mời bạn đăng nhập.';
      } else {
        $err = 'Số điện thoại hoặc email đã tồn tại, hoặc lỗi hệ thống.';
      }
    }
  } else {
    $err = 'Vui lòng nhập tên, mật khẩu và số điện thoại hoặc email.';
  }
}
?>
<h2>Đăng ký</h2>
<form method="post" class="form">
  <?php if ($err) echo "<div style='color:#d00'>$err</div>"; ?>
  <?php if ($ok) echo "<div style='color:#090'>$ok</div>"; ?>
  <input type="text" name="username" placeholder="Tên hiển thị" required>
  <input type="text" name="phone" placeholder="Số điện thoại (hoặc bỏ trống)">
  <input type="email" name="email" placeholder="Email (hoặc bỏ trống)">
  <input type="password" name="password" placeholder="Mật khẩu" required>
  <input type="password" name="confirm" placeholder="Xác nhận mật khẩu" required>
  <button class="btn primary" type="submit">Đăng ký</button>
</form>
<div class="center" style="margin-top:10px">
  <a class="btn" href="/login.php">Quay lại đăng nhập</a>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
