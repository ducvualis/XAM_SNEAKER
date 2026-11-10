<?php
session_start();
session_unset();
session_destroy();

// Chuyển hướng về trang login chung ở trang chủ
header("Location: /login.php");
exit;
