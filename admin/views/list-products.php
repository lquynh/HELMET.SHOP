<?php
    session_start();
    include_once('..\controller\TypeController.php');
    include_once('..\model\TypeModel.php');
	if (!isset($_SESSION['login_email'])) header("location:../login.php");
    $model = new TypeModel;
    $id=$_GET['type'];
    $data=$model->getProductsByType($id);
?>
<head>
    <title>Danh sách sản phẩm</title>
    <?php include_once('..\views\common.php'); ?>
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
				<b>Danh sách sản phẩm theo loại</b>
			</div>
			<div class="panel-body">
				<a href="views/add-product.php?type=<?=$id?>"><button><i class='fa fa-plus'></i> Thêm sản phẩm</button></a>
				<br /><br />
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th style='text-align:center;'>Mã</th>
							<th style='text-align:center;'>Tên</th>
							<th style='text-align:center;'>Mô tả</th>
							<th style='text-align:center;'>Giá</th>
							<th style='text-align:center;'>Số lượng</th>
							<th style='text-align:center;'>Trạng thái</th>
							<th style='text-align:center;' width="11%">Tuỳ chọn</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($data as $t):
						?>
						<tr id="sanpham-<?= $t->product_code?>">
							<td style='text-align:center;'><?= $t->product_code?></td>
							<td class="name-<?= $t->product_code?>">
							<?php
								if ($t->status==0)
									echo "<b>".$t->name."</b>";
								else {
									echo "<b>".$t->name."</b> <i style='color:red;font-weight:bold;'>(Ngừng kinh doanh)</i>";
								}
							?>
							</td>
							<td><?=$t->description?></td>
							<td style='text-align:center;'><span class='price'><?=number_format($t->price,PRICE_DECIMALS,'.','')?></span></td>
							<td style='color:green;font-weight:bold;text-align:center;'><?=$t->quantity_exist?></td>
							<td style='text-align:center;'>
							<input type="checkbox" name="" value="" disabled="disabled" <?php if($t->new ==1){?>checked<?php }?>>
							</td>
							<td>
								<a style=" padding-bottom:10px" href="views/edit-product.php?id=<?= $t->product_code?>"><button class="btn btn-sm btn-success">Sửa</button></a>
                                <?php if ($t->status==0) { ?>
								    <button class="btn btn-sm btn-danger" data-id="<?= $t->product_code?>">Xóa</button>
                                <?php } ?>
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
</section>
<?php include_once('footer.php'); ?>
</section>

<script>
	var id =''
	$('.btn-danger').click(function(){
		id = $(this).attr('data-id')
		var ans=confirm("Bạn có muốn xóa sản phẩm "+id+"?");
		if (ans) {
			$.ajax({
				url:"deleteproduct.php",
				data:{
					id:id
				},
				type:"GET",
				success:function(data){
					var mess = "";
					if($.trim(data)=='error'){
						mess = "Không thể xoá"
					} else if($.trim(data)=='success'){
						mess = "Xoá thành công";
						location.reload();
					}
					else mess = data;
					alert(mess);
				}
			});
		}
	});
</script>

</body>
</html>