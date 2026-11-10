<?php
require_once __DIR__.'/includes/db.php';
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    require_csrf();$login=trim((string)($_POST['login']??''));$pass=(string)($_POST['password']??'');
    if($login!=='' && $pass!=='') {
        $stmt=$conn->prepare('SELECT id,username,phone,email,password,role FROM users WHERE phone=? OR email=? LIMIT 1');
        $stmt->bind_param('ss',$login,$login);$stmt->execute();$u=$stmt->get_result()->fetch_assoc();
        if($u && password_verify($pass,$u['password'])) {
            session_regenerate_id(true);unset($u['password']);$_SESSION['user']=$u;
            header('Location: '.($u['role']==='admin'?'/admin/index.php':'/index.php'));exit;
        }
    }
    $err='Email/số điện thoại hoặc mật khẩu chưa chính xác.';
}
$pageTitle='Đăng nhập | XÁM SNEAKER';include __DIR__.'/includes/header.php';
?>
<div class="auth-page"><div class="auth-panel"><div class="eyebrow">CHÀO MỪNG TRỞ LẠI</div><h1>Đăng nhập</h1><p>Đăng nhập để mua sắm và theo dõi đơn hàng.</p><?php if($err):?><div class="notice error"><?=e($err)?></div><?php endif;?><form method="post" class="form"><?=csrf_field()?><label>Email hoặc số điện thoại</label><input name="login" autocomplete="username" value="<?=e($_POST['login']??'')?>" required><label>Mật khẩu</label><input type="password" name="password" autocomplete="current-password" required><button class="btn btn-primary" type="submit">Đăng nhập →</button></form><p class="auth-alternate">Chưa có tài khoản? <a href="/register.php">Đăng ký ngay</a></p></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
