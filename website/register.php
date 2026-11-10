<?php
require_once __DIR__.'/includes/db.php';
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    require_csrf();
    $name=trim((string)($_POST['username']??''));$phone=trim((string)($_POST['phone']??''));$email=trim((string)($_POST['email']??''));
    $pass=(string)($_POST['password']??'');$confirm=(string)($_POST['confirm']??'');
    if(app_length($name)<2||app_length($name)>100||($phone===''&&$email==='')||($email!==''&&!filter_var($email,FILTER_VALIDATE_EMAIL))||($phone!==''&&!preg_match('/^[0-9+().\-\s]{9,20}$/',$phone)))$err='Vui lòng nhập tên và email/số điện thoại hợp lệ.';
    elseif(strlen($pass)<8)$err='Mật khẩu cần có ít nhất 8 ký tự.';
    elseif($pass!==$confirm)$err='Mật khẩu xác nhận không khớp.';
    else {
        $hash=password_hash($pass,PASSWORD_DEFAULT);$phone=$phone?:null;$email=$email?:null;
        try{$stmt=$conn->prepare('INSERT INTO users (username,phone,email,password) VALUES (?,?,?,?)');$stmt->bind_param('ssss',$name,$phone,$email,$hash);$stmt->execute();flash('Đăng ký thành công! Hãy đăng nhập để mua sắm.');header('Location: /login.php');exit;}
        catch(mysqli_sql_exception $ex){if($ex->getCode()==1062)$err='Email hoặc số điện thoại đã được đăng ký.';else {error_log('Registration error: '.$ex->getMessage());$err='Không thể đăng ký lúc này. Vui lòng thử lại.';}}
    }
}
$pageTitle='Đăng ký | XÁM SNEAKER';include __DIR__.'/includes/header.php';
?>
<div class="auth-page"><div class="auth-panel"><div class="eyebrow">THAM GIA XÁM SNEAKER</div><h1>Tạo tài khoản</h1><p>Đăng ký để lưu lịch sử đặt hàng của bạn.</p><?php if($err):?><div class="notice error"><?=e($err)?></div><?php endif;?><form method="post" class="form"><?=csrf_field()?><label>Tên hiển thị *</label><input name="username" maxlength="100" autocomplete="name" value="<?=e($_POST['username']??'')?>" required><label>Số điện thoại</label><input name="phone" maxlength="20" autocomplete="tel" value="<?=e($_POST['phone']??'')?>"><label>Email</label><input type="email" name="email" maxlength="120" autocomplete="email" value="<?=e($_POST['email']??'')?>"><small>Nhập ít nhất một trong hai: số điện thoại hoặc email.</small><label>Mật khẩu *</label><input type="password" name="password" minlength="8" autocomplete="new-password" required><label>Xác nhận mật khẩu *</label><input type="password" name="confirm" minlength="8" autocomplete="new-password" required><button class="btn btn-primary" type="submit">Tạo tài khoản →</button></form><p class="auth-alternate">Đã có tài khoản? <a href="/login.php">Đăng nhập</a></p></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
