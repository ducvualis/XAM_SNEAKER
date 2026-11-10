<?php
// Buffer early HTML from legacy pages so login/cart redirects still send HTTP headers.
if (!ob_get_level()) { ob_start(); }

$config = [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'port' => (int)(getenv('DB_PORT') ?: 3306),
    'user' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '',
    'name' => getenv('DB_NAME') ?: 'xamsneaker',
];
// Create config.local.php from config.example.php on the host; never commit credentials.
$localConfig = __DIR__ . '/config.local.php';
if (is_file($localConfig)) {
    $override = require $localConfig;
    if (is_array($override)) { $config = array_merge($config, $override); }
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($config['host'], $config['user'], $config['password'], $config['name'], (int)$config['port']);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log('XamSneaker MySQL connection failed: ' . $e->getMessage());
    http_response_code(503);
    exit('Website chưa kết nối được CSDL. Vui lòng kiểm tra cấu hình hosting.');
}

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!function_exists('final_price')) {
    function final_price($price, $discount) {
        $price = (float)$price;
        $discount = (float)$discount;
        return $discount ? (int)($price * (100 - $discount) / 100) : $price;
    }
}
if (!function_exists('is_admin')) {
    function is_admin() {
        return isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin';
    }
}
