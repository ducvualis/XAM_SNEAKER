<?php
require_once __DIR__ . '/includes/db.php';
$success = false;
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name === '' || $message === '' || ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))) {
        $err = 'Vui lòng nhập tên, nội dung và email hợp lệ (nếu có).';
    } else {
        try {
            $stmt = $conn->prepare('INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $name, $email, $message);
            $stmt->execute();
            $success = true;
        } catch (mysqli_sql_exception $e) {
            error_log('Contact insert failed: ' . $e->getMessage());
            $err = 'Không lưu được liên hệ. Vui lòng thử lại.';
        }
    }
}
include __DIR__ . '/includes/header.php';
?>
<h2>Liên hệ</h2>
<p>Nhận tư vấn size, mẫu, đơn hàng. Điền thông tin bên dưới:</p>
<?php if ($err): ?><div style="color:#b00"><?= htmlspecialchars($err) ?></div><?php endif; ?>
<?php if ($success): ?><div style="color:green">Đã lưu liên hệ thành công.</div><?php endif; ?>
<form method="post" class="form">
  <input type="text" name="name" placeholder="Tên của bạn" required>
  <input type="email" name="email" placeholder="Email của bạn">
  <textarea name="message" rows="5" placeholder="Nội dung" required></textarea>
  <button class="btn primary" type="submit">Gửi liên hệ</button>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>
