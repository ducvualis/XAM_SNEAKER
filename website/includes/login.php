<?php include __DIR__ . '/includes/header.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['login'] ?? '');
  $password = trim($_POST['password'] ?? '');
  if ($login && $password) {
    // Tìm user theo phone hoặc email
    $stmt = $conn->prepare("SELECT * FROM users WHERE phone=? OR email=? LIMIT 1");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($u = $res->fetch_assoc()) {
      // Kiểm tra mật khẩu
      if (password_verify($password, $u['password'])) {
        session_regenerate_id(true);
        // Lưu session với đầy đủ thông tin, bao gồm role
        $_SESSION['user'] = [
          'id'       => $u['id'],
          'username' => $u['username'],
          'phone'    => $u['phone'],
          'email'    => $u['email'],
          'role'     => $u['role']
        ];

        // Nếu là admin thì chuyển thẳng vào trang quản trị
        if ($u['role'] === 'admin') {
          header('Location: /admin/index.php');
          exit;
        } else {
          header('Location: /index.php');
          exit;
        }
      } else {
        $err = 'Sai mật khẩu.';
      }
    } else {
      $err = 'Tài khoản không tồn tại.';
    }
  } else {
    $err = 'Vui lòng nhập thông tin đăng nhập và mật khẩu.';
  }
}
?>
<h2>Đăng nhập</h2>
<form method="post" class="form">
  <?php if ($err) echo "<div style='color:#d00'>$err</div>"; ?>
  <input type="text" name="login" placeholder="Số điện thoại hoặc Email" required>
  <input type="password" name="password" placeholder="Mật khẩu" required>
  <button class="btn primary" type="submit">Đăng nhập</button>
</form>
<div class="center" style="margin-top:10px">
  Chưa có tài khoản? <a class="btn" href="/register.php">Đăng ký ngay</a>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
