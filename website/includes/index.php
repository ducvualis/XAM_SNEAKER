<?php include __DIR__ . '/includes/header.php'; ?>

<!-- Banner hình ảnh to phủ hết -->
<section class="banner-full">
  <img src="/assets/images/banner_flashsale.jpg" alt="Flash Sale">
</section>

<!-- SẢN PHẨM BÁN CHẠY -->
<h2>SẢN PHẨM BÁN CHẠY</h2>
<?php
$resBest = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 4");
?>
<div class="grid">
  <?php while($p = $resBest->fetch_assoc()): ?>
    <div class="card">
      <img src="/assets/images/<?= $p['image'] ?>" alt="">
      <h3><?= $p['name'] ?></h3>
      <div class="price">
        <span class="old"><?= number_format($p['price']) ?>đ</span>
        <span class="badge circle">-<?= $p['discount'] ?>%</span>
        <span><b><?= number_format(final_price($p['price'], $p['discount'])) ?>đ</b></span>
      </div>
      <div class="actions">
        <a class="btn" href="/product_detail.php?id=<?= $p['id'] ?>">Chi tiết</a>
         <a class="btn primary" href="/product_detail.php?id=<?= $p['id'] ?>">Thêm vào giỏ</a>

      </div>
    </div>
  <?php endwhile; ?>
</div>

<!-- THƯƠNG HIỆU NỔI BẬT - NIKE -->
<h2>THƯƠNG HIỆU NỔI BẬT - NIKE</h2>
<?php
$resNike = $conn->query("SELECT * FROM products WHERE brand='NIKE' ORDER BY created_at DESC LIMIT 4");
?>
<div class="grid">
  <?php while($p = $resNike->fetch_assoc()): ?>
    <div class="card">
      <img src="/assets/images/<?= $p['image'] ?>" alt="">
      <h3><?= $p['name'] ?></h3>
      <div class="price">
        <span class="old"><?= number_format($p['price']) ?>đ</span>
        <span class="badge circle">-<?= $p['discount'] ?>%</span>
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

<!-- THƯƠNG HIỆU NỔI BẬT - ADIDAS -->
<h2>THƯƠNG HIỆU NỔI BẬT - ADIDAS</h2>
<?php
$resAdidas = $conn->query("SELECT * FROM products WHERE brand='ADIDAS' ORDER BY created_at DESC LIMIT 4");
?>
<div class="grid">
  <?php while($p = $resAdidas->fetch_assoc()): ?>
    <div class="card">
      <img src="/assets/images/<?= $p['image'] ?>" alt="">
      <h3><?= $p['name'] ?></h3>
      <div class="price">
        <span class="old"><?= number_format($p['price']) ?>đ</span>
        <span class="badge circle">-<?= $p['discount'] ?>%</span>
        <span><b><?= number_format(final_price($p['price'], $p['discount'])) ?>đ</b></span>
      </div>
      <div class="actions">
        <a class="btn" href="/product_detail.php?id=<?= $p['id'] ?>">Chi tiết</a>
         <a class="btn primary" href="/product_detail.php?id=<?= $p['id'] ?>">Thêm vào giỏ</a>

      </div>
    </div>
  <?php endwhile; ?>
</div>

<!-- THƯƠNG HIỆU NỔI BẬT - MLB -->
<h2>THƯƠNG HIỆU NỔI BẬT - MLB</h2>
<?php
$resMlb = $conn->query("SELECT * FROM products WHERE brand='MLB' ORDER BY created_at DESC LIMIT 4");
?>
<div class="grid">
  <?php while($p = $resMlb->fetch_assoc()): ?>
    <div class="card">
      <img src="/assets/images/<?= $p['image'] ?>" alt="">
      <h3><?= $p['name'] ?></h3>
      <div class="price">
        <span class="old"><?= number_format($p['price']) ?>đ</span>
        <span class="badge circle">-<?= $p['discount'] ?>%</span>
        <span><b><?= number_format(final_price($p['price'], $p['discount'])) ?>đ</b></span>
      </div>
      <div class="actions">
        <a class="btn" href="/product_detail.php?id=<?= $p['id'] ?>">Chi tiết</a>
        <a class="btn primary" href="/product_detail.php?id=<?= $p['id'] ?>">Thêm vào giỏ</a>

      </div>
    </div>
  <?php endwhile; ?>
</div>

<!-- GỢI Ý DÀNH RIÊNG CHO BẠN -->
<h2>GỢI Ý DÀNH RIÊNG CHO BẠN</h2>
<?php
$resRec = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 4");
?>
<div class="grid">
  <?php while($p = $resRec->fetch_assoc()): ?>
    <div class="card">
      <img src="/assets/images/<?= $p['image'] ?>" alt="">
      <h3><?= $p['name'] ?></h3>
      <div class="price">
        <span class="old"><?= number_format($p['price']) ?>đ</span>
        <span class="badge circle">-<?= $p['discount'] ?>%</span>
        <span><b><?= number_format(final_price($p['price'], $p['discount'])) ?>đ</b></span>
      </div>
      <div class="actions">
        <a class="btn" href="/product_detail.php?id=<?= $p['id'] ?>">Chi tiết</a>
         <a class="btn primary" href="/product_detail.php?id=<?= $p['id'] ?>">Thêm vào giỏ</a>

      </div>
    </div>
  <?php endwhile; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
