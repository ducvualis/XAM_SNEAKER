<?php
if (!ob_get_level()) ob_start();
require_once __DIR__ . '/functions.php';
$config = [
    'host'=>getenv('DB_HOST') ?: 'localhost',
    'port'=>(int)(getenv('DB_PORT') ?: 3306),
    'user'=>getenv('DB_USER') ?: 'root',
    'password'=>getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '',
    'name'=>getenv('DB_NAME') ?: 'xamsneaker',
];
$local = __DIR__ . '/config.local.php';
if (is_file($local)) {
    $override = require $local;
    if (is_array($override)) $config = array_merge($config, $override);
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($config['host'],$config['user'],$config['password'],$config['name'],(int)$config['port']);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $ex) {
    error_log('XAM SNEAKER connection error: '.$ex->getMessage());
    http_response_code(503);
    exit('Website chưa kết nối được CSDL. Vui lòng kiểm tra cấu hình hosting.');
}
unset($config);
