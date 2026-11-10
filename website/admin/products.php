<?php
require_once __DIR__.'/../includes/db.php';if(!is_admin()){header('Location: /login.php');exit;}
$q=trim((string)($_GET['q']??''));$brand=(string)($_GET['brand']??'');
$sql='SELECT * FROM products WHERE name LIKE ?';$types='s';$values=['%'.$q.'%'];
if(in_array($brand,['NIKE','ADIDAS','MLB','LUXURY','OTHER'],true)){$sql.=' AND brand=?';$types.='s';$values[]=$brand;}
$sql.=' ORDER BY id DESC LIMIT 250';$stmt=$conn->prepare($sql);$stmt->bind_param($types,...$values);$stmt->execute();$products=$stmt->get_result();
$adminTitle='Quản lý sản phẩm';include __DIR__.'/includes/admin_header.php';
?>
<div class="panel-heading"><div><h2>Danh sách sản phẩm</h2><p>Thêm, sửa hoặc xóa sản phẩm đang bán.</p></div><a class="btn" href="/admin/product_add.php">+ Thêm sản phẩm</a></div>
<form class="admin-filter" method="get"><input name="q" placeholder="Tìm theo tên sản phẩm" value="<?=e($q)?>"><select name="brand"><option value="">Tất cả hãng</option><?php foreach(['NIKE','ADIDAS','MLB','LUXURY','OTHER'] as $item): ?><option value="<?=$item?>" <?=$brand===$item?'selected':''?>><?=$item?></option><?php endforeach; ?></select><button class="btn" type="submit">Tìm kiếm</button><a class="btn secondary" href="/admin/products.php">Xóa lọc</a></form>
<div class="table-wrap"><table class="table"><thead><tr><th>Sản phẩm</th><th>Hãng</th><th>Giá bán</th><th>Giảm</th><th>Thao tác</th></tr></thead><tbody><?php while($p=$products->fetch_assoc()): ?><tr><td><div class="product-cell"><img src="<?=e(product_image($p['image']))?>" alt=""><div><strong><?=e($p['name'])?></strong><small>Mã #<?=(int)$p['id']?></small></div></div></td><td><?=e($p['brand'])?></td><td><?=money(final_price($p['price'],$p['discount']))?></td><td><?=(int)$p['discount']?>%</td><td><div class="row-actions"><a class="btn secondary" href="/admin/product_edit.php?id=<?=(int)$p['id']?>">Sửa</a><form method="post" action="/admin/product_delete.php" data-confirm="Bạn chắc chắn muốn xóa sản phẩm này? Nếu đã có trong đơn hàng, hệ thống sẽ từ chối xóa."><?=csrf_field()?><input type="hidden" name="id" value="<?=(int)$p['id']?>"><button class="btn danger" type="submit">Xóa</button></form></div></td></tr><?php endwhile; ?></tbody></table></div>
<?php include __DIR__.'/includes/admin_footer.php'; ?>
