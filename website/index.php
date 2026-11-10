<?php
$pageTitle='XÁM SNEAKER | Sneaker nổi bật';
require_once __DIR__.'/includes/header.php';
$res = $conn->query('SELECT * FROM products ORDER BY created_at DESC, id DESC LIMIT 8');
$best = $conn->query('SELECT * FROM products WHERE discount > 0 ORDER BY discount DESC, id DESC LIMIT 4');
?>
<section class="hero"><div class="hero-content"><div class="hero-kicker">XÁM SNEAKER / NEW SEASON</div><h1>Bước ra ngoài.<br><em>Chất riêng của bạn.</em></h1><p>Khám phá các mẫu sneaker nổi bật từ Nike, Adidas, MLB và nhiều thương hiệu khác.</p><a class="btn btn-light" href="/search.php">Khám phá bộ sưu tập →</a></div><div class="hero-image" role="img" aria-label="Sneaker Flash Sale"></div></section>
<section class="benefit-strip"><div>✓ Mẫu giày đa dạng</div><div>✓ Đặt hàng trực tuyến</div><div>✓ Theo dõi đơn dễ dàng</div></section>
<section class="section-block"><div class="section-head"><div><span class="eyebrow">BỘ SƯU TẬP</span><h2>Mua sắm theo thương hiệu</h2></div></div><div class="brand-grid"><a href="/nike.php">NIKE <span>Khám phá →</span></a><a href="/adidas.php">ADIDAS <span>Khám phá →</span></a><a href="/mlb.php">MLB <span>Khám phá →</span></a><a href="/luxury.php">LUXURY <span>Khám phá →</span></a></div></section>
<section class="section-block"><div class="section-head"><div><span class="eyebrow">MỚI CẬP NHẬT</span><h2>Sản phẩm nổi bật</h2></div><a href="/search.php" class="text-link">Xem tất cả →</a></div><div class="product-grid"><?php while($p=$res->fetch_assoc()) product_tile($p); ?></div></section>
<section class="promo-band"><div><span class="eyebrow">ƯU ĐÃI</span><h2>Tìm đôi sneaker phù hợp với bạn</h2><p>Lọc theo thương hiệu, khoảng giá và chọn size tại trang sản phẩm.</p></div><a class="btn btn-light" href="/search.php">Tìm giày ngay →</a></section>
<section class="section-block"><div class="section-head"><div><span class="eyebrow">ƯU ĐÃI TỐT</span><h2>Đang giảm giá</h2></div><a href="/search.php?sort=discount" class="text-link">Xem thêm →</a></div><div class="product-grid"><?php while($p=$best->fetch_assoc()) product_tile($p); ?></div></section>
<?php include __DIR__.'/includes/footer.php'; ?>
