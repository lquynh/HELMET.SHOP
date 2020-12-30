<?php
	if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\controller\SignUpController.php');
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
    $userController = new SignUpController();
    if(isset($_POST['error']))
		unset($_SESSION['error']);    
    if(isset($_POST['login'])){
        $email=$_POST['inputEmail'];
        $password=$_POST['inputPassword'];
        $check=$userController->dangnhapTk($email,$password);
    }
  ?>
<style>
body{background-image:linear-gradient(#337ab7, #999)!important;}
</style>
<head>
    <title>Đăng nhập</title>
    <?php include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\views\common.php'); ?>
</head>

<body>
<br />
<center><h1 style='color:#fff;'>TRANG QUẢN TRỊ <?=SHOP_NAME?></h1></center>
<div class="container">
	<div class="card card-container">
		<form class="form-signin" method="post" action="">
			<!--span id="reauth-email" class="reauth-email"></span-->
			<input type="text" name="inputEmail" class="form-control" placeholder="Email/Username" required autofocus>
			<input type="password" name="inputPassword" class="form-control" placeholder="Mật khẩu" required>
			<button class="btn-login" type="submit" name="login">Đăng nhập</button>
		</form>
		<?php if(isset($_SESSION['error'])){
		echo "<center><b style='color:#fff;margin-top:10px;'>".$_SESSION['error']."</b></center>";
		}
		unset($_SESSION['error']);
		?>	
	</div>
</div>
</body>
</html>
