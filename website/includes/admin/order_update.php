<?php
require_once __DIR__ . '/../includes/db.php';
if (!is_admin()) { header('Location: /login.php'); exit; }
?>
<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/admin_header.php'; // đảm bảo quyền

$id = intval($_POST['id'] ?? 0);
$status = trim($_POST['status'] ?? '');

if ($id > 0 && $status) {
  $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
  $stmt->bind_param("si", $status, $id);
  $stmt->execute();
  $stmt->close();
}

header('Location: order_detail.php?id=' . $id);
exit;
