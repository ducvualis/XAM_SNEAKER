<?php
// functions.php - các hàm dùng chung

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tính giá sau giảm giá
if (!function_exists('final_price')) {
    function final_price($price, $discount) {
        return $discount ? intval($price * (100 - $discount) / 100) : intval($price);
    }
}

// Kiểm tra quyền admin
if (!function_exists('is_admin')) {
    function is_admin() {
        return isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin';
    }
}

// Tạo slug từ text (URL-friendly)
if (!function_exists('slugify')) {
    function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return $text ?: 'n-a';
    }
}

// Upload ảnh
function upload_image($file, $targetDir = __DIR__ . '/../uploads/') {
    if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) return null;
    $allowed = ['image/jpeg','image/png','image/webp'];
    if (!in_array($file['type'], $allowed)) return null;
    if ($file['size'] > 5*1024*1024) return null; // giới hạn 5MB
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $name = uniqid('img_') . '.' . $ext;
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    $dest = $targetDir . $name;
    if (move_uploaded_file($file['tmp_name'], $dest)) return $name;
    return null;
}

