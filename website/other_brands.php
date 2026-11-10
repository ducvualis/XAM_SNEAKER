<?php include __DIR__ . '/includes/header.php'; ?>
<h2>Thương hiệu khác</h2>
<p>ASICS, PUMA, Onitsuka Tiger… những mẫu giày thể thao độc đáo và chất lượng cao.</p>
<?php
$res = $conn->query("SELECT * FROM products WHERE brand='OTHER' ORDER BY created_at DESC");
?>
<div class="grid">
  <?php while($p = $res->fetch_assoc()): ?>
    <div class="card">
      <img src="/assets/images/<?= htmlspecialchars($p['image']) ?>" alt="">
      <h3><?= htmlspecialchars($p['name']) ?></h3>
      <div class="price">
        <?php if ($p['discount']): ?>
          <span class="old"><?= number_format($p['price']) ?>đ</span>
          <span class="badge">-<?= $p['discount'] ?>%</span>
        <?php endif; ?>
        <span><b><?= number_format(final_price($p['price'], $p['discount'])) ?>đ</b></span>
      </div>
      <div class="actions">
<a class="btn primary" href="/product_detail.php?id=<?= (int)$p['id'] ?>">
    Xem chi tiết
  </a>        <a class="btn primary" href="/product_detail.php?id=<?= $p['id'] ?>">Thêm vào giỏ</a>

      </div>
    </div>
  <?php endwhile; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
