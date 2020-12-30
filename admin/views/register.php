<?php
include_once($_SERVER["DOCUMENT_ROOT"].'\admin_helmet\controller\SignUpController.php');
    if(isset($_POST['register'])){
        $userController = new SignUpController();
        $fullname=$_POST['inputFullname'];
        $address=$_POST['inputAddress'];
        $gender=$_POST['gender'];
        $phone=$_POST['inputPhone'];
        $password=$_POST['inputPassword'];
        $email=$_POST['inputEmail'];
     //   print_r($email);
        $userController->dangkiTK($fullname,$address,$gender,$phone,$password,$email);	
    }
    
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.html">

    <title>Đăng ký</title>
    <base href="http://localhost:7999/admin_helmet/">

    <!-- Bootstrap core CSS -->
    <link href="admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="admin/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="admin/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="css/owl.carousel.css" type="text/css">

    <link href="admin/css/style-responsive.css" rel="stylesheet" />
    <link href="admin/css/login-form.css" rel="stylesheet">

</head>
<style>
input,button{
    margin-top:10px;
}
</style>
  <body>
    <div class="container">
        <div class="card card-container" style="margin-top:1%">
            <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
            <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin" method="post" action="">
                <span id="reauth-email" class="reauth-email"></span>
                <input type="text" name="inputFullname" class="form-control" id="fullname" placeholder="Fullname" required autofocus>
                <input type="text" name="inputAddress" class="form-control" id="address" placeholder="Address" required autofocus>
                <input type="phone" name="inputPhone" class="form-control" id="phone" placeholder="Phone" required>
                <input type="password" name="inputPassword" class="form-control" id="password" placeholder="Password" required>
                <input type="email" name="inputEmail" class="form-control" id="email" placeholder="Email" required>
                <input type="radio" name="gender" value="Male" checked style="width:20px;">Male
                <input type="radio" name="gender" value="Female" style="width:20px;margin-left:30px;">Female	<br>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="register">Sign Up</button>
            </form><!-- /form -->
            <a href="views/login.php" class="forgot-password">
                Sign In?
            </a>
            <?php if(isset($_SESSION['errormail'])){
                echo "<div class='text-danger' size='3'>".$_SESSION['errormail']."</div>";
            }
            unset($_SESSION['errormail']);
	        ?>	
        </div><!-- /card-container -->
    </div><!-- /container -->
    <!-- js placed at the end of the document so the pages load faster -->
    <script src="admin/js/register.js"></script>
    <script src="admin/js/jquery.js"></script>
    <script src="admin/js/bootstrap.min.js"></script>
    
  </body>

<!-- Mirrored from thevectorlab.net/flatlab/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 14 Aug 2015 03:43:32 GMT -->
</html>
