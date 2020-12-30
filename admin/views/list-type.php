<?php
    session_start();
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\EditProductModel.php');
	if (!isset($_SESSION['login_email'])) 
		header("location:../login.php");
    $model=new EditProductModel;
    $types=$model->getType();
?>

<head>
    <title>Danh sách loại sản phẩm</title>
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
                    <b>Danh sách loại sản phẩm</b>
                </div>
                <div class="panel-body">
                    <a href="views/add-type.php"><button><i class='fa fa-plus'></i> Thêm loại sản phẩm</button></a><br /><br />
                    <table class="table table-bordered table-hover" style="width:50%">
                        <thead>
                            <tr>
                                <th style='text-align:center;' width="10%" >STT</th>
                                <th style='text-align:center;'>Tên loại</th>
                                <th style='text-align:center;' width="120px">Tuỳ chọn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $stt = 1;?>
                           <?php foreach($types as $t):?>
                            <tr id="sanpham-<?= $t->id?>">
                                <td style='text-align:center;'><?=$stt++?></td>
                                <td class="name-<?=$t->id?>"><b><?=$t->name?></b> <i>(<?=$t->cate_code?>)</i></td>
                                <td style='text-align:center;'>
                                    <a style=" padding-bottom:10px;" href="views/edit-type.php?id=<?= $t->cate_code?>"><button class="btn btn-sm btn-success">Sửa</button></a>
                                    <button class="btn btn-sm btn-danger" data-id="<?=$t->cate_code?>" data-name="<?=$t->name?>">Xóa</button>
                                </td>
                            </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</section>

<script>
    $(document).ready(function(){
        var id =''
        $('.btn-danger').click(function(){
            id = $(this).attr('data-id');
			name = $(this).attr('data-name');
			var ans=confirm("Bạn có muốn xóa loại sản phẩm '"+name+"'?");
			if (ans) {
				$.ajax({
                    url:"deletetype.php",
                    data:{
                        id:id,
						name:name
                    },
                    type:"GET",
                    success:function(data){
                        var mess = "";
                        if($.trim(data)=='error'){
                            mess = "Không thể xoá";
                        }
                        else if($.trim(data)=='success'){
                            mess = "Xoá thành công";
                            // $('#sanpham-'+id).hide()
                        }
                        else mess = data;
						alert(mess);
						location.reload();
                    }
                });
			}
        })
    })
</script>
</section>
<?php include_once('footer.php'); ?>
</section>
</body>
</html>