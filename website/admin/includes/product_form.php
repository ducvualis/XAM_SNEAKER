<?php
require_once __DIR__.'/../../includes/db.php';
if(!is_admin()){header('Location: /login.php');exit;}
$editing=isset($editId);
if ($editing && $editId <= 0) { http_response_code(404); exit('ID sản phẩm không hợp lệ.'); }
$product=null;
if($editing){$stmt=$conn->prepare('SELECT * FROM products WHERE id=?');$stmt->bind_param('i',$editId);$stmt->execute();$product=$stmt->get_result()->fetch_assoc();if(!$product){http_response_code(404);exit('Sản phẩm không tồn tại.');}}
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    require_csrf();$name=trim((string)($_POST['name']??''));$brand=(string)($_POST['brand']??'OTHER');$description=trim((string)($_POST['description']??''));$price=(int)($_POST['price']??0);$discount=(int)($_POST['discount']??0);
    if(app_length($name)<3||app_length($name)>200||!in_array($brand,['NIKE','ADIDAS','MLB','LUXURY','OTHER'],true)||$price<=0||$discount<0||$discount>95) $err='Vui lòng nhập tên (3-200 ký tự), thương hiệu, giá dương và giảm giá từ 0-95%.';
    else {
        try {
            $image=upload_product_image($_FILES['image']??null)??($product['image']??'');
            if($editing){$stmt=$conn->prepare('UPDATE products SET name=?,brand=?,description=?,price=?,discount=?,image=? WHERE id=?');$stmt->bind_param('sssiisi',$name,$brand,$description,$price,$discount,$image,$editId);}
            else {$stmt=$conn->prepare('INSERT INTO products (name,brand,description,price,discount,image) VALUES (?,?,?,?,?,?)');$stmt->bind_param('sssiis',$name,$brand,$description,$price,$discount,$image);}
            $stmt->execute();flash($editing?'Đã cập nhật sản phẩm.':'Đã thêm sản phẩm mới.');header('Location: /admin/products.php');exit;
        } catch(Throwable $ex){error_log('Product save: '.$ex->getMessage());$err=$ex instanceof RuntimeException?$ex->getMessage():'Không thể lưu sản phẩm, vui lòng thử lại.';}
    }
}
$adminTitle=$editing?'Sửa sản phẩm':'Thêm sản phẩm'; include __DIR__.'/admin_header.php';
?>
<div class="panel-heading"><div><h2><?=e($adminTitle)?></h2><p>Cập nhật thông tin giày bán trên cửa hàng.</p></div><a class="btn secondary" href="/admin/products.php">← Quay lại</a></div>
<?php if($err):?><div class="err"><?=e($err)?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="form"><?=csrf_field()?><label for="pname">Tên sản phẩm *</label><input id="pname" name="name" maxlength="200" value="<?=e($_POST['name']??($product['name']??''))?>" required><label for="pbrand">Thương hiệu *</label><select id="pbrand" name="brand"><?php foreach(['NIKE'=>'Nike','ADIDAS'=>'Adidas','MLB'=>'MLB','LUXURY'=>'Luxury','OTHER'=>'Thương hiệu khác'] as $key=>$label):?><option value="<?=$key?>" <?=($_POST['brand']??($product['brand']??'NIKE'))===$key?'selected':''?>><?=$label?></option><?php endforeach; ?></select><div class="form-columns"><div><label for="pprice">Giá gốc (VNĐ) *</label><input id="pprice" name="price" type="number" min="1" value="<?=e($_POST['price']??($product['price']??''))?>" required></div><div><label for="pdiscount">Giảm giá (%)</label><input id="pdiscount" name="discount" type="number" min="0" max="95" value="<?=e($_POST['discount']??($product['discount']??0))?>"></div></div><label for="pdesc">Mô tả</label><textarea id="pdesc" name="description" rows="5"><?=e($_POST['description']??($product['description']??''))?></textarea><label for="pimage">Hình ảnh (JPG, PNG, WebP; tối đa 4MB)</label><?php if($editing && $product['image']): ?><img class="product-preview" src="<?=e(product_image($product['image']))?>" alt="Ảnh sản phẩm hiện tại"><?php endif; ?><input id="pimage" name="image" type="file" accept="image/jpeg,image/png,image/webp"><div class="actions"><button class="btn" type="submit"><?=$editing?'Lưu thay đổi':'Thêm sản phẩm'?></button><a class="btn secondary" href="/admin/products.php">Hủy</a></div></form>
<?php include __DIR__.'/admin_footer.php'; ?>
