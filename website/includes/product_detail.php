<?php include __DIR__ . '/includes/header.php'; 
?>

<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$p = null;
if ($id) {
  $res = $conn->query("SELECT * FROM products WHERE id=$id");
  $p = $res->fetch_assoc();
}
if (!$p): ?>
  <div class="center">Sản phẩm không tồn tại. <a class="btn" href="/index.php">Về trang chủ</a></div>
<?php else: ?>
<div class="flex">
  <div style="flex:1">
    <img src="/assets/images/<?= htmlspecialchars($p['image'] ?? 'noimg.jpg') ?>" alt="" style="width:100%;max-width:500px">
  </div>
  <div style="flex:1">
    <h2><?= htmlspecialchars($p['name']) ?></h2>
    <p>Thương hiệu: <b><?= htmlspecialchars($p['brand']) ?></b></p>
    <div class="price">
      <?php if ($p['discount']): ?>
        <span class="old"><?= number_format($p['price']) ?>₫</span>
        <span class="badge">-<?= $p['discount'] ?>%</span>
      <?php endif; ?>
      <span><b><?= number_format(final_price($p['price'], $p['discount'])) ?>₫</b></span>
    </div>
    <p><?= nl2br(htmlspecialchars($p['description'] ?? '')) ?></p>

    <form method="post" action="/cart.php" class="actions">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="id" value="<?= $p['id'] ?>">

      <label for="size">Chọn size:</label>
      <select name="size" id="size" required>
        <option value="38">38</option>
        <option value="39">39</option>
        <option value="40">40</option>
        <option value="41">41</option>
        <option value="42">42</option>
      </select>

      <label for="quantity">Số lượng:</label>
      <input type="number" name="quantity" id="quantity" value="1" min="1" style="width:60px;">

      <button class="btn primary" type="submit">Thêm vào giỏ hàng</button>
    </form>
  </div>
</div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
