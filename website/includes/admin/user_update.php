<?php
require_once __DIR__ . '/../includes/db.php';
if (!is_admin()) { header('Location: /login.php'); exit; }
?>
<?php

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Kết nối CSDL
require_once __DIR__ . '/includes/db.php';

// Nhận dữ liệu từ form
$id       = intval($_POST['id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$email    = trim($_POST['email'] ?? '');
$role     = trim($_POST['role'] ?? 'user');

// Nếu thiếu ID hoặc username thì dừng
if ($id <= 0 || $username === '') {
    die("Thiếu thông tin cần thiết.");
}

// Cập nhật vào DB
$stmt = $conn->prepare("UPDATE users SET username=?, phone=?, email=?, role=? WHERE id=?");
$stmt->bind_param("ssssi", $username, $phone, $email, $role, $id);

if ($stmt->execute()) {
    // Thành công → quay lại danh sách
    header("Location: users.php");
    exit;
} else {
    echo "Có lỗi khi cập nhật: " . $conn->error;
}
