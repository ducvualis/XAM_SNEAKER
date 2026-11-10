<?php
require_once __DIR__.'/db.php';
$brand = $brand ?? 'NIKE';
$brandLabels = ['NIKE'=>'Nike','ADIDAS'=>'Adidas','MLB'=>'MLB','LUXURY'=>'Luxury','OTHER'=>'Thương hiệu khác'];
$heading = $brandLabels[$brand] ?? 'Bộ sưu tập';
$pageTitle='Giày '.$heading.' | XÁM SNEAKER';
include __DIR__.'/header.php';
$stmt=$conn->prepare('SELECT * FROM products WHERE brand=? ORDER BY id DESC');
$stmt->bind_param('s',$brand);$stmt->execute();$products=$stmt->get_result();
?>
<div class="page-heading"><div><div class="eyebrow">XÁM SNEAKER / BỘ SƯU TẬP</div><h1><?=e($heading)?></h1><p>Tìm mẫu giày phù hợp phong cách của bạn · <?= $products->num_rows ?> sản phẩm</p></div><a href="/search.php?brand=<?=urlencode($brand)?>" class="btn btn-outline">Lọc theo giá →</a></div>
<?php if ($products->num_rows): ?><div class="product-grid"><?php while($p=$products->fetch_assoc()) product_tile($p); ?></div><?php else: ?><div class="empty-state">Chưa có sản phẩm trong danh mục này.</div><?php endif; ?>
<?php include __DIR__.'/footer.php'; ?>
