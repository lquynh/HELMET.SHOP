<?php
	session_start();
	if (isset($_SESSION['login_email']))
		header("location:manage-bill.php?status=0");
	else
		header("location:login.php");
?>
<head>
    <meta charset="utf-8">
    <link href="views/favicon.ico" rel="icon" />

    <title>Trang Chủ</title>
    <base href="http://localhost:7999/admin_helmet/">

    <!-- Bootstrap core CSS -->
    <link href="admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="admin/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="admin/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/css/owl.carousel.css" type="text/css">

    <!--right slidebar-->
    <link href="admin/css/slidebars.css" rel="stylesheet">

    <!-- Custom styles for this template -->

    <link href="admin/css/style.css" rel="stylesheet">
    <link href="admin/css/style-responsive.css" rel="stylesheet" />
    <script src="admin/ckeditor/ckeditor.js"></script>
    <script src="admin/ckfinder/ckfinder.js"></script>
    <!--script src="admin/js/jquery.js"></script-->
	
	<!-- jquery -->
	<link href="admin/css/jquery-ui-1.10.4.css" rel="stylesheet">
	<script src="admin/js/jquery-1.10.2.js"></script>
	<script src="admin/js/jquery-ui-1.10.4.js"></script>
	
</head>
<body>
    <section id="container">
        
        <?php include_once('header.php')?>

        <!--sidebar start-->
        <?php 
        if(isset($_SESSION['login_email'])):
        include_once('menu.php');
        endif?>
        <!--sidebar end-->

        <!--main content start-->
        <section id="main-content">
        <div>
        <h2 style="text-align: center;margin-top: 52px;">TRANG QUẢN TRỊ ADMIN</h2>
        </div>
      

        </section>
        <!--main content end-->
        <!--footer start-->
        <?php include_once('footer.php'); ?>
        <!--footer end-->
    </section>

</body>

</html>