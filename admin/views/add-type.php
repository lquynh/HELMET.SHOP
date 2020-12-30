<?php
    session_start();
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\AddProductModel.php');
	if (!isset($_SESSION['login_email'])) 
		header("location:login.php");
	$model = new AddProductModel;
	if(isset($_POST['submit'])){
		$cate_code = $_POST['cate_code'];
		$name = $_POST['name'];
        
        if ($model->checkType($cate_code,$name)) {
            echo "<script>alert('Tên loại hoặc Mã loại đã tồn tại!');</script>";
            header("Refresh:0");
            return false;
        }
        
        $check = $model->insertType($cate_code, $name);
		if(!$check){
            echo "<script>alert('Thêm thất bại!');</script>";
            header("Refresh:0");
            return false;
		}
        echo "<script>alert('Thêm thành công!');</script>";
        header("Refresh:0");
	}
?>
<head>
    <title>Thêm loại sản phẩm</title>
    <?php include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\views\common.php'); ?>
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
        <section class="wrapper">
    <div class="panel panel-body">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b>Thêm loại mới</b>
                </div>
                <div class="panel-body">
                   <?php if(isset($_SESSION['message'])):?>
                    <div class="alert alert-danger"><?php echo $_SESSION['message']?></div>
                    <?php endif?>
                    <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
						<div class="form-group">
                            <label class="col-sm-2">Mã:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="cate_code" placeholder="Nhập mã" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2">Tên:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" placeholder="Nhập tên loại" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <button type="submit" class="btn btn-primary" name="submit">Thêm</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </section>
    </div>
</section>
</section>
<?php include_once('footer.php'); ?>
</section>
</body>
</html>