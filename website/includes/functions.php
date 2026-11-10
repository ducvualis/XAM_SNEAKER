<?php
// Shared helpers; no external dependencies.
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!function_exists('e')) {
    function e($value) { return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
}
if (!function_exists('final_price')) {
    function final_price($price, $discount) {
        return (int)round(max(0, (float)$price) * (100 - max(0, min(100, (float)$discount))) / 100);
    }
}
function app_length($text) { return function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text); }
function order_has_size($conn) {
    static $hasSize = null;
    if ($hasSize === null) { $columns = $conn->query("SHOW COLUMNS FROM order_items LIKE 'shoe_size'"); $hasSize = $columns->num_rows > 0; }
    return $hasSize;
}
function money($amount) { return number_format((float)$amount, 0, ',', '.') . '₫'; }
function is_admin() { return isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin'; }
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function csrf_field() { return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">'; }
function require_csrf() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals(csrf_token(), (string)($_POST['csrf_token'] ?? ''))) {
        http_response_code(403); exit('Yêu cầu không hợp lệ. Vui lòng tải lại trang và thử lại.');
    }
}
function flash($message, $type='ok') { $_SESSION['flash_message'] = ['text'=>$message, 'type'=>$type]; }
function show_flash() {
    if (empty($_SESSION['flash_message'])) return;
    $f=$_SESSION['flash_message']; unset($_SESSION['flash_message']);
    echo '<div class="notice ' . ($f['type']==='error' ? 'error' : 'success') . '">' . e($f['text']) . '</div>';
}
function product_image($image) {
    $image = basename((string)$image);
    if ($image === '') return '/assets/images/logo.jpg';
    // New uploaded images live in uploads; imported catalog lives in assets/images.
    if (is_file(__DIR__ . '/../uploads/' . $image)) return '/uploads/' . rawurlencode($image);
    return '/assets/images/' . rawurlencode($image);
}
function product_tile($p) {
    $id=(int)$p['id']; $price=final_price($p['price'],$p['discount']);
    $discount=(int)$p['discount'];
    echo '<article class="product-card">';
    echo '<a class="product-media" href="/product_detail.php?id='.$id.'">';
    if ($discount>0) echo '<span class="product-off">-'.$discount.'%</span>';
    echo '<img loading="lazy" src="'.e(product_image($p['image'])).'" alt="'.e($p['name']).'"></a>';
    echo '<div class="product-info"><div class="product-brand">'.e($p['brand']).'</div>';
    echo '<a class="product-title" href="/product_detail.php?id='.$id.'">'.e($p['name']).'</a>';
    echo '<div class="product-prices"><strong>'.money($price).'</strong>';
    if ($discount>0) echo '<del>'.money($p['price']).'</del>';
    echo '</div><a class="btn btn-outline" href="/product_detail.php?id='.$id.'">Xem & chọn size <span aria-hidden="true">→</span></a></div></article>';
}
function upload_product_image($file) {
    if (empty($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Không thể tải ảnh lên.');
    if ($file['size'] > 4 * 1024 * 1024) throw new RuntimeException('Ảnh phải nhỏ hơn 4MB.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $types = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if (!isset($types[$mime])) throw new RuntimeException('Chỉ chấp nhận ảnh JPG, PNG hoặc WebP.');
    $filename = bin2hex(random_bytes(12)) . '.' . $types[$mime];
    $dir=__DIR__.'/../uploads/';
    if (!is_dir($dir) && !mkdir($dir,0755,true)) throw new RuntimeException('Không tạo được thư mục ảnh.');
    if (!move_uploaded_file($file['tmp_name'],$dir.$filename)) throw new RuntimeException('Lưu ảnh thất bại.');
    return $filename;
}
