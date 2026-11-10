<?php
require __DIR__ . '/includes/db.php';

// Xoá toàn bộ session
$_SESSION = [];
session_unset();
session_destroy();

// Chuyển về trang chủ
header('Location: /index.php');
exit;
