<?php
require_once __DIR__ . '/../includes/db.php';
if (!is_admin()) {
    header('Location: /login.php');
    exit;
}

$tables = [
    'products' => 'Sản phẩm',
    'users' => 'Tài khoản',
    'orders' => 'Đơn hàng',
    'order_items' => 'Chi tiết đơn hàng',
    'contacts' => 'Liên hệ',
];

$counts = [];
try {
    foreach ($tables as $table => $label) {
        // Table identifiers come from the fixed allowlist above, never user input.
        $res = $conn->query('SELECT COUNT(*) AS total FROM `' . $table . '`');
        $counts[$table] = (int)$res->fetch_assoc()['total'];
    }
} catch (mysqli_sql_exception $e) {
    error_log('MVP DB verification error: ' . $e->getMessage());
    http_response_code(500);
    exit('Không truy vấn được CSDL. Kiểm tra lại cấu trúc bảng.');
}

include __DIR__ . '/includes/admin_header.php';
?>
<div class="page-header">
  <h2>Kiểm tra kết nối cơ sở dữ liệu</h2>
</div>
<div class="ok">
  <strong>Đã kết nối MySQL và truy vấn dữ liệu thành công.</strong>
  <p>Trang chỉ hiển thị với tài khoản quản trị.</p>
</div>
<div class="table-wrap">
  <table class="table">
    <thead><tr><th>Bảng dữ liệu</th><th>Số bản ghi hiện tại</th></tr></thead>
    <tbody>
      <?php foreach ($tables as $table => $label): ?>
      <tr>
        <td><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= $counts[$table] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<p><a class="btn" href="/admin/index.php">Quay lại quản trị</a></p>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>
