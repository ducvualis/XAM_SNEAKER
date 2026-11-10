<?php
require_once __DIR__.'/includes/db.php';
$cart=$_SESSION['cart']??[];
if (!$cart) { header('Location: /cart.php');exit; }
// Re-query prices and product existence from MySQL before checkout (never trust session prices).
$lines=[];$total=0;
foreach ($cart as $key=>$item) {
    $id=(int)($item['product']['id']??0);$qty=max(1,min(20,(int)($item['qty']??1)));
    $stmt=$conn->prepare('SELECT id,name,image,price,discount FROM products WHERE id=?');
    $stmt->bind_param('i',$id);$stmt->execute();$p=$stmt->get_result()->fetch_assoc();
    if (!$p) { flash('Một sản phẩm không còn tồn tại, vui lòng kiểm tra giỏ hàng.','error');header('Location: /cart.php');exit; }
    $price=final_price($p['price'],$p['discount']);$lines[]=['id'=>$id,'name'=>$p['name'],'size'=>$item['size'],'qty'=>$qty,'price'=>$price];$total+=$price*$qty;
}
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    require_csrf();
    $name=trim((string)($_POST['name']??''));$phone=trim((string)($_POST['phone']??''));
    $email=trim((string)($_POST['email']??''));$address=trim((string)($_POST['address']??''));
    if (app_length($name)<2 || app_length($name)>100 || !preg_match('/^[0-9+().\-\s]{9,20}$/',$phone) || app_length($address)<8 || app_length($address)>255 || ($email!==''&&!filter_var($email,FILTER_VALIDATE_EMAIL))) {
        $err='Vui lòng nhập đầy đủ thông tin hợp lệ: họ tên, số điện thoại và địa chỉ nhận hàng.';
    } else {
        try {
            $conn->begin_transaction();
            $uid=$_SESSION['user']['id']??null;
            $stmt=$conn->prepare('INSERT INTO orders (user_id,total_price,customer_name,customer_phone,customer_email,customer_address) VALUES (?,?,?,?,?,?)');
            $stmt->bind_param('iissss',$uid,$total,$name,$phone,$email,$address);$stmt->execute();$orderId=$conn->insert_id;
            $sizeColumn = order_has_size($conn);
            $stmtItem=$conn->prepare($sizeColumn
                ? 'INSERT INTO order_items (order_id,product_id,quantity,price,shoe_size) VALUES (?,?,?,?,?)'
                : 'INSERT INTO order_items (order_id,product_id,quantity,price) VALUES (?,?,?,?)');
            foreach($lines as $line){
                $pid=$line['id'];$qty=$line['qty'];$price=$line['price'];$size=(string)$line['size'];
                if($sizeColumn) $stmtItem->bind_param('iiiis',$orderId,$pid,$qty,$price,$size);
                else $stmtItem->bind_param('iiii',$orderId,$pid,$qty,$price);
                $stmtItem->execute();
            }
            $conn->commit();$_SESSION['cart']=[];
            $_SESSION['last_order_id']=$orderId;
            header('Location: /order_success.php?id='.$orderId);exit;
        } catch(Throwable $ex){$conn->rollback();error_log('Checkout failed: '.$ex->getMessage());$err='Chưa thể lưu đơn hàng. Vui lòng thử lại, không bấm đặt hàng nhiều lần.';}
    }
}
$pageTitle='Đặt hàng | XÁM SNEAKER';include __DIR__.'/includes/header.php';
?>
<div class="page-heading"><div><div class="eyebrow">THANH TOÁN</div><h1>Thông tin đặt hàng</h1><p>Vui lòng điền chính xác thông tin người nhận hàng.</p></div></div>
<?php if($err): ?><div class="notice error"><?=e($err)?></div><?php endif; ?>
<div class="checkout-layout"><form method="post" class="form form-panel"><?=csrf_field()?><h2>Thông tin giao hàng</h2><label>Họ và tên *</label><input name="name" maxlength="100" value="<?=e($_POST['name']??($_SESSION['user']['username']??''))?>" required><label>Số điện thoại *</label><input name="phone" maxlength="20" value="<?=e($_POST['phone']??($_SESSION['user']['phone']??''))?>" required><label>Email (không bắt buộc)</label><input type="email" name="email" maxlength="120" value="<?=e($_POST['email']??($_SESSION['user']['email']??''))?>"><label>Địa chỉ nhận hàng *</label><textarea name="address" rows="3" maxlength="255" required><?=e($_POST['address']??'')?></textarea><p class="form-hint">Đơn hàng được ghi nhận để cửa hàng xác nhận. Không có thanh toán online trong bản MVP.</p><button class="btn btn-primary" type="submit">Xác nhận đặt hàng →</button></form><aside class="order-summary"><h2>Đơn hàng (<?=count($lines)?> sản phẩm)</h2><?php foreach($lines as $line): ?><div class="summary-row"><span><?=e($line['name'])?> × <?=$line['qty']?> <small>(size <?=e($line['size'])?>)</small></span><strong><?=money($line['price']*$line['qty'])?></strong></div><?php endforeach; ?><div class="summary-total"><span>Tổng tiền hàng</span><strong><?=money($total)?></strong></div><p>Phí vận chuyển được trao đổi khi xác nhận.</p></aside></div>
<?php include __DIR__.'/includes/footer.php'; ?>
