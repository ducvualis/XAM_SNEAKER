<?php
require_once __DIR__.'/includes/db.php';
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) $_SESSION['cart']=[];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    require_csrf();
    $action=(string)($_POST['action']??'');
    if ($action==='add') {
        $id=(int)($_POST['id']??0);$size=(string)($_POST['size']??'');$qty=(int)($_POST['quantity']??1);
        if ($id>0 && in_array($size,['38','39','40','41','42'],true) && $qty>=1 && $qty<=20) {
            $stmt=$conn->prepare('SELECT id,name,brand,price,discount,image FROM products WHERE id=?');
            $stmt->bind_param('i',$id);$stmt->execute();$p=$stmt->get_result()->fetch_assoc();
            if ($p) {
                $key=$id.'_'.$size;
                if(isset($_SESSION['cart'][$key])) $_SESSION['cart'][$key]['qty']=min(20,$_SESSION['cart'][$key]['qty']+$qty);
                else $_SESSION['cart'][$key]=['product'=>$p,'size'=>$size,'qty'=>$qty];
                flash('Đã thêm sản phẩm vào giỏ hàng.');
            }
        } else flash('Vui lòng chọn size và số lượng hợp lệ.','error');
    } elseif ($action==='update') {
        foreach(($_POST['qty']??[]) as $key=>$qty) {
            if(isset($_SESSION['cart'][$key])) $_SESSION['cart'][$key]['qty']=max(1,min(20,(int)$qty));
        }
        flash('Đã cập nhật giỏ hàng.');
    } elseif ($action==='remove') {
        $key=(string)($_POST['key']??'');unset($_SESSION['cart'][$key]);flash('Đã xóa sản phẩm khỏi giỏ.');
    }
    header('Location: /cart.php');exit;
}
$pageTitle='Giỏ hàng | XÁM SNEAKER'; include __DIR__.'/includes/header.php';
$cart=$_SESSION['cart'];
?>
<div class="page-heading"><div><div class="eyebrow">ĐẶT HÀNG</div><h1>Giỏ hàng của bạn</h1><p>Kiểm tra sản phẩm và số lượng trước khi đặt hàng.</p></div></div>
<?php if(!$cart): ?><div class="empty-state"><div class="empty-emoji">🛒</div><h2>Giỏ hàng đang trống</h2><p>Khám phá các mẫu giày phù hợp và thêm vào giỏ nhé.</p><a class="btn btn-primary" href="/search.php">Mua sắm ngay →</a></div>
<?php else: ?>
<div class="cart-layout"><div class="table-wrap"><form id="cart-update" method="post" action="/cart.php"><?=csrf_field()?><input type="hidden" name="action" value="update"><table class="table cart-table"><thead><tr><th>Sản phẩm</th><th>Size</th><th>Giá</th><th>Số lượng</th><th>Tổng</th><th></th></tr></thead><tbody>
<?php $total=0; foreach($cart as $key=>$item): $p=$item['product'];$qty=(int)$item['qty'];$price=final_price($p['price'],$p['discount']);$line=$price*$qty;$total+=$line; ?>
<tr><td><div class="cart-product"><img src="<?=e(product_image($p['image']))?>" alt=""><span><?=e($p['name'])?></span></div></td><td><?=e($item['size'])?></td><td><?=money($price)?></td><td><input aria-label="Số lượng" type="number" name="qty[<?=e($key)?>]" min="1" max="20" value="<?=$qty?>"></td><td><strong><?=money($line)?></strong></td><td><button class="text-danger" type="submit" form="remove-<?=e(md5($key))?>" aria-label="Xóa sản phẩm">Xóa</button></td></tr>
<?php endforeach; ?></tbody></table><div class="cart-table-actions"><a class="btn btn-outline" href="/search.php">← Tiếp tục mua sắm</a><button class="btn btn-outline" type="submit">Cập nhật số lượng</button></div></form>
<?php foreach($cart as $key=>$item): ?><form id="remove-<?=e(md5($key))?>" method="post" action="/cart.php"><?=csrf_field()?><input type="hidden" name="action" value="remove"><input type="hidden" name="key" value="<?=e($key)?>"></form><?php endforeach; ?></div>
<aside class="order-summary"><h2>Tóm tắt đơn hàng</h2><div class="summary-row"><span>Tạm tính</span><strong><?=money($total)?></strong></div><div class="summary-row"><span>Phí vận chuyển</span><span>Xác nhận khi giao hàng</span></div><div class="summary-total"><span>Tổng tiền hàng</span><strong><?=money($total)?></strong></div><a class="btn btn-primary" href="/checkout.php">Tiến hành đặt hàng →</a><p>Thanh toán khi nhận hàng (MVP).</p></aside></div>
<?php endif; ?>
<?php include __DIR__.'/includes/footer.php'; ?>
