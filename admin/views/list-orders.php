<?php
session_start();
if (!isset($_SESSION['login_email'])) header("location:../login.php");
include_once('..\model\ImportModel.php');
$model=new ImportModel;
$status=$_GET['status'];
$orders=$model->getOrders($status);
// print_r($orders);
?>
<head>
    <title>Đặt hàng</title>
    <?php include_once('..\views\common.php'); ?>
<style>
.inprogress{font-weight:bold;color:orange;font-style:italic;}
.finished{font-weight:bold;color:green;}
.cancelled{font-weight:bold;color:red;}
</style>
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
					<b>Danh sách đơn đặt hàng <?php switch($status) { case 0: echo "đang xử lý";break; case 1: echo "đã hoàn tất";break; case 2: echo "đã hủy";break; } ?></b>
				</div>
				<div class="panel-body">
					<?php if($status==0){?>
						<a href='views/add-order.php'><button><i class='fa fa-plus'></i> Tạo đơn đặt hàng</button></a><br />
					<?php } ?>
					<br />
					<?php if(empty($orders)) {
							echo "<i>Chưa có đặt hàng nào :)</i>";
						} else {
						echo "<table class='table table-bordered table-hover'>";
						echo "<thead>";
						echo "<tr>";
						echo "<th style='text-align:center;'>Mã đặt hàng</th>";
						echo "<th style='text-align:center;'>Mã nhập</th>";
						echo "<th style='text-align:center;'>Ngày tạo</th>";
						echo "<th style='text-align:center;'>Nhân viên</th>";
						echo "<th style='text-align:center;'>NCC</th>";
						echo "<th style='text-align:center;'>Chi tiết</th>";
						echo "<th style='text-align:center;'>Tùy chọn</th>";
						echo "</tr>";
						echo "</thead>";
						foreach($orders as $o) {
							echo "<tr>";
							echo "<td style='width:100px'>".$o->place_order_code."</td>";
							echo "<td style='width:100px'>".$o->import_code."</td>";
							echo "<td>".date_format(date_create($o->created_at),"d-m-Y")."</td>";
							echo "<td><b>".$model->getEmployeeName($o->id_employee)."</b></td>";
							echo "<td>".$model->getSupplierName($o->supp_code)."</td>";
							echo "<td>";
							$details=$model->getOrderDetails($o->place_order_code);
							$total=0;
							$total_qty=0;

							echo "<table class='table table-bordered table-hover'>";
							echo "<thead><tr>";
							echo "<th width='55%' style='text-align:center;'>Tên sản phẩm</th>";
							echo "<th width='15%' style='text-align:center;'>Số lượng</th>";
							echo "<th width='15%' style='text-align:center;'>Đơn giá</th>";
							echo "<th style='text-align:center;'>Trị giá</th>";
							echo "</tr></thead>";
							foreach($details as $d) {
								$total_qty+=$d->quantity_ord;
								$total+=$d->quantity_ord*$d->price_ord;
								echo "<tr class='$o->place_order_code-products2export'>";
								echo "<td><span class='name'>".$model->getProductName($d->product_code)."</span> (<span class='product_code'>$d->product_code</span>)</td>";
								echo "<td 'width:60px' style='text-align:center;'><span class='qty'/>".$d->quantity_ord."</span></td>";
								echo "<td style='text-align:right;'><span class='price pricePick'>".number_format($d->price_ord,PRICE_DECIMALS,'.',',')."</span></td>";
								echo "<td style='text-align:right;'><span class='price'>".number_format($d->price_ord*$d->quantity_ord,PRICE_DECIMALS,'.',',')."</span></td>";
								echo "</tr>";
							}
							echo "</table>";
							echo "Tổng số lượng: <b>".$total_qty."</b><br/>";
							echo "Tổng tiền: <span class='price'>".number_format($total,PRICE_DECIMALS,'.',',')."</span>";
							echo "</td>";
							if ($o->status==0) echo "<td><span class='optionButtons'><button class='btn btn-sm btn-success btnXuatFile' supp_code='".$o->supp_code."' supp_name='".$model->getSupplierName($o->supp_code)."' employee_name='".$model->getEmployeeName($o->id_employee)."' created_at='".date_format(date_create($o->created_at), "d-m-Y")."' place_order_code='".$o->place_order_code."'>Xuất file</button><button place_order_code='".$o->place_order_code."' class='btn btn-sm btn-danger btnHuy'>Hủy</button></span></td>";
							if ($o->status==1) echo "<td class='finished'>Đã hoàn tất</td>";
							if ($o->status==2) echo "<td class='cancelled'>Đã hủy</td>";
							echo "</tr>";
						}
						echo "</table>";
					} ?>
				</div>
            </div>
        </section>
    </div>
</section>
</section>
<?php include_once('footer.php'); ?>
</section>

<script>
	$(".btnHuy").click(function(e) {
		let place_order_code = $(this).attr("place_order_code");
		//alert(place_order_code);
		$.ajax({
			url: "controller/PlaceOrderController.php",
			type: "POST",
			data: {
				action:'huyDonNhapHang',
				place_order_code:place_order_code
			},
			success: function(res) {
				if (res=="ok") {
					alert("Hủy thành công đơn nhập hàng "+place_order_code+"!");
					location.reload();
				}
			}
		});
	});
	
	$(".btnXuatFile").click(function(e){
		let p="",total=0;
		let place_order_code=$(this).attr("place_order_code");
		let supp_code=$(this).attr("supp_code");
		let supp_name=$(this).attr("supp_name");
		let created_at=$(this).attr("created_at");
		let name_employee=$(this).attr("employee_name");

		$("."+place_order_code+"-products2export").each(function(e) {
			let product_code=$($(this).find("span[class='product_code']")).text();
			let name=$($(this).find("span[class='name']")).text();
			let qty=$($(this).find("span[class='qty']")).text();
			let price=$($(this).find("span[class='price pricePick']")).text();
			let delimiter="<?=DELIMITER?>";
			p+=product_code+delimiter+name+delimiter+qty+delimiter+price+"|";
			total+=parseInt(qty)*parseFloat(price);
		});
		p=p.slice(0, -1);//remove the last character
		console.log(p+": "+created_at);
		loading_on();
		$.ajax({
			url: "controller/ExportFileController.php",
			type: "POST",
			data: {
				action: "exportFile",
				products: p,
				total: total,
				place_order_code: place_order_code,
				supp_code: supp_code,
				supp_name: supp_name,
				created_at: created_at,
				name_employee: name_employee
			},
			success: function(res) {
				console.log(res);
				loading_off();
				location.href=res;
			}
		});
	});
</script>
</body>
</html>