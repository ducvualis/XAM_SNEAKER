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

// Nhận dữ liệu từ form
$id              = $_SESSION['user']['id']; // lấy ID từ session
$currentPassword = $_POST['current_password'] ?? '';
$newPassword     = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Kiểm tra mật khẩu mới và xác nhận
if ($newPassword !== $confirmPassword) {
    die("Mật khẩu mới và xác nhận không khớp.");
}

// Lấy mật khẩu hiện tại từ DB
$stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();

if (!$user) {
    die("Không tìm thấy người dùng.");
}

// Kiểm tra mật khẩu hiện tại
if (!password_verify($currentPassword, $user['password'])) {
    die("Mật khẩu hiện tại không đúng.");
}

// Hash mật khẩu mới
$newHash = password_hash($newPassword, PASSWORD_DEFAULT);

// Cập nhật mật khẩu
$stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->bind_param("si", $newHash, $id);

if ($stmt->execute()) {
    echo "Đổi mật khẩu thành công. <a href='settings.php'>Quay lại</a>";
} else {
    echo "Có lỗi khi cập nhật: " . $conn->error;
}
