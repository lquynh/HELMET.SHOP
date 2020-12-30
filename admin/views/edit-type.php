<?php
    session_start();
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\EditProductModel.php');
	if (!isset($_SESSION['login_email'])) header("location:../login.php");
    $model= new EditProductModel;
    if (!isset($_GET['id'])) header("location:manage-bill.php?status=0");
	$id=$_GET['id'];
    
	$productType=$model->getTypeById($id);
    if(isset($_POST['submit'])){
        $name=$_POST['name'];
        $check=$model->updateType($id,$name);
        if(!$check){
			echo "<script>alert('Cập nhật sản phẩm thất bại!');</script>";
        }else{
            echo "<script>alert('Cập nhật sản phẩm thành công!');</script>";
            header("Refresh:0");
        }
    }
?>
<head>
    <title>Sửa loại sản phẩm</title>
    <?php include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\views\common.php'); ?>
</head>
<body>
<section id="container">
<?php include_once('header.php')?>
<?php if(isset($_SESSION['login_email'])) include_once('menu.php'); ?>
<section id="main-content">
<section class="wrapper">
<div class="panel panel-body">
<section class="content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <b>Sửa thông tin loại sản phẩm <span style="color:blue"><?=$productType->name?></span></b>
        </div>
        <div class="panel-body">
           <?php if(isset($_SESSION['message'])):?>
            <div class="alert alert-danger"><?php echo $_SESSION['message']?></div>
            <?php endif?>
            <?php unset($_SESSION['message'])?>
            <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-sm-2">Tên:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" placeholder="Nhập tên loại" required value="<?= $productType->name?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button type="submit" class="btn btn-primary" name="submit">Cập nhật</button>
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
<script>
    //owl carousel
    $(document).ready(function () {
        $("#owl-demo").owlCarousel({
            navigation: true,
            slideSpeed: 300,
            paginationSpeed: 400,
            singleItem: true,
            autoPlay: true
        });
    });

    //custom select box
    $(function () {
        $('select.styled').customSelect();
    });
</script>
</body>
</html>