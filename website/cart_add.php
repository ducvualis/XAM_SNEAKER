<?php
require_once __DIR__.'/includes/db.php';
$id=max(0,(int)($_GET['id']??0));
header('Location: /product_detail.php?id='.$id);exit;
