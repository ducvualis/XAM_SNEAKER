<?php require_once __DIR__ . '/../includes/db.php'; if(!is_admin()){header('Location: /login.php');exit;} header('Location: /admin/product_add.php');exit; ?>
