<?php
require_once __DIR__ . '/includes/header.php';

$q = trim($_GET['q'] ?? '');
?>
<h2>Kết quả tìm kiếm cho: "<?= htmlspecialchars($q) ?>"</h2>

<?php
if ($q !== '') {
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE CONCAT('%', ?, '%') ORDER BY id DESC");
    $stmt->bind_param("s", $q);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        echo '<div class="product-grid">';
        while ($p = $res->fetch_assoc()) {
            echo '<div class="product-card">';
            
            // Ảnh sản phẩm
            if (!empty($p['image'])) {
                echo '<img src="/assets/images/' . htmlspecialchars($p['image']) . '" alt="' . htmlspecialchars($p['name']) . '">';
            } else {
                echo '<img src="/assets/images/no-image.png" alt="No image">';
            }

            // Tên + giá
            echo '<h3>' . htmlspecialchars($p['name']) . '</h3>';
            echo '<p class="price">' . number_format($p['price']) . '₫</p>';

            // Nút thao tác
            echo '<div class="card-actions">';
            echo '<a class="btn detail" href="/product_detail.php?id=' . $p['id'] . '">Xem chi tiết</a>';
            echo '<a class="btn cart" href="/cart_add.php?id=' . $p['id'] . '">Thêm vào giỏ</a>';
            echo '</div>';

            echo '</div>';
        }
        echo '</div>';
    } else {
        echo "<p>Không tìm thấy sản phẩm nào.</p>";
    }
} else {
    echo "<p>Vui lòng nhập từ khóa để tìm kiếm.</p>";
}

require_once __DIR__ . '/includes/footer.php';
?>
