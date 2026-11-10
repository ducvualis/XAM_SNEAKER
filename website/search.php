<?php
require_once __DIR__.'/includes/db.php';
$q=trim((string)($_GET['q']??''));
$brand=(string)($_GET['brand']??'');
$allowed=['','NIKE','ADIDAS','MLB','LUXURY','OTHER'];
if (!in_array($brand,$allowed,true)) $brand='';
$min=max(0,(int)($_GET['min']??0)); $max=max(0,(int)($_GET['max']??0));
if ($max>0 && $max<$min) [$min,$max]=[$max,$min];
$sort=(string)($_GET['sort']??'new');
$order=['new'=>'id DESC','price_asc'=>'price_after ASC','price_desc'=>'price_after DESC','discount'=>'discount DESC, id DESC'][$sort]??'id DESC';
// Filter on the actual discounted price; no fake size availability filter.
$sql='SELECT *, ROUND(price * (100-LEAST(100,GREATEST(0,discount))) / 100) AS price_after FROM products WHERE 1=1';
$types='';$values=[];
if ($q!=='') {$sql.=' AND name LIKE ?';$types.='s';$values[]='%'.$q.'%';}
if ($brand!=='') {$sql.=' AND brand=?';$types.='s';$values[]=$brand;}
if ($min>0) {$sql.=' AND ROUND(price * (100-LEAST(100,GREATEST(0,discount))) / 100) >= ?';$types.='i';$values[]=$min;}
if ($max>0) {$sql.=' AND ROUND(price * (100-LEAST(100,GREATEST(0,discount))) / 100) <= ?';$types.='i';$values[]=$max;}
$sql.=' ORDER BY '.$order.' LIMIT 120';
$stmt=$conn->prepare($sql); if($types) $stmt->bind_param($types,...$values);$stmt->execute();$products=$stmt->get_result();
$pageTitle='Tìm sản phẩm | XÁM SNEAKER';include __DIR__.'/includes/header.php';
?>
<div class="page-heading"><div><div class="eyebrow">TÌM KIẾM & BỘ LỌC</div><h1>Khám phá sneaker</h1><p>Chọn hãng, khoảng giá hoặc nhập tên sản phẩm bạn yêu thích.</p></div></div>
<div class="catalog-layout"><aside class="filter-panel"><h2>Bộ lọc sản phẩm</h2><form method="get" action="/search.php"><label for="catalog-q">Tên sản phẩm</label><input id="catalog-q" type="search" name="q" value="<?=e($q)?>" placeholder="Nike Air Force 1..."><label for="brand">Thương hiệu</label><select id="brand" name="brand"><option value="">Tất cả thương hiệu</option><?php foreach(['NIKE'=>'Nike','ADIDAS'=>'Adidas','MLB'=>'MLB','LUXURY'=>'Luxury','OTHER'=>'Khác'] as $key=>$label): ?><option value="<?=e($key)?>" <?=$brand===$key?'selected':''?>><?=e($label)?></option><?php endforeach;?></select><label for="min">Giá sau giảm từ (VNĐ)</label><input id="min" name="min" type="number" min="0" step="10000" value="<?=$min?:''?>" placeholder="Ví dụ: 500000"><label for="max">Đến (VNĐ)</label><input id="max" name="max" type="number" min="0" step="10000" value="<?=$max?:''?>" placeholder="Ví dụ: 2000000"><label for="sort">Sắp xếp</label><select id="sort" name="sort"><option value="new" <?=$sort==='new'?'selected':''?>>Mới nhất</option><option value="price_asc" <?=$sort==='price_asc'?'selected':''?>>Giá thấp đến cao</option><option value="price_desc" <?=$sort==='price_desc'?'selected':''?>>Giá cao đến thấp</option><option value="discount" <?=$sort==='discount'?'selected':''?>>Giảm giá nhiều</option></select><button class="btn btn-primary" type="submit">Áp dụng bộ lọc</button><a class="btn btn-outline" href="/search.php">Xóa bộ lọc</a></form></aside><section><div class="results-heading"><strong><?=$products->num_rows?> kết quả</strong><span>Giá hiển thị đã tính giảm giá</span></div><?php if($products->num_rows): ?><div class="product-grid"><?php while($p=$products->fetch_assoc()) product_tile($p); ?></div><?php else: ?><div class="empty-state"><h3>Chưa tìm thấy sản phẩm phù hợp</h3><p>Thử bỏ bớt điều kiện lọc hoặc tìm theo thương hiệu khác.</p><a href="/search.php" class="btn btn-outline">Xem tất cả sản phẩm</a></div><?php endif; ?></section></div>
<?php include __DIR__.'/includes/footer.php'; ?>
