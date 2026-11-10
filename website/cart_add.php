<?php
require_once __DIR__ . '/includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id       = intval($_POST['id'] ?? $_GET['id'] ?? 0);
$size     = $_POST['size'] ?? '';
$quantity = max(1, intval($_POST['quantity'] ?? 1));

if ($id > 0) {
    $stmt = $conn->prepare("SELECT id, name, price, image, discount FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $key = $id . '_' . $size;

        if (!isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key] = [
                'product' => [
                    'id'       => $product['id'],
                    'name'     => $product['name'],
                    'price'    => $product['price'],
                    'image'    => $product['image'],
                    'discount' => $product['discount'],
                ],
                'size' => $size,
                'qty'  => $quantity
            ];
        } else {
            $_SESSION['cart'][$key]['qty'] += $quantity;
        }
    }
}

// Quay lại giỏ hàng
header("Location: /cart.php");
exit;
