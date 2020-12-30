<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once 'model/MyOrdersModel.php';
$model=new MyOrdersModel;
// print_r($_SESSION['customer']);
// print_r($data);
?>
<style>
#tblMyOrders{width:100%;}
#tblMyOrders td, #tblMyOrders thead th {border:1px solid #ccc;padding:7px;}
#tblMyOrders thead th{font-weight:bold;font-size:14px;}
</style>
<section class="main-container col2-right-layout">
	<div class="main container">
	<div class="row">
		<div class="col-main col-sm-12 col-xs-12">
		<div class="page-content checkout-page">
			<div class="page-title">
			  <h2>Đơn hàng của tôi</h2>
			</div>
			<?php
			if (empty($data)) {
				echo "Chưa có đơn hàng nào!";
				return;
			}
			echo "<table id='tblMyOrders'>";
			echo "<thead>";
			echo "<tr>";
			echo "<th style='text-align:center;'>Mã</th>";
			echo "<th style='text-align:center;'>Ngày đặt</th>";
			echo "<th style='text-align:center;'>Người nhận</th>";
			echo "<th style='text-align:center;'>Số nhà</th>";
			echo "<th style='text-align:center;'>Quận/huyện</th>";
			echo "<th style='text-align:center;'>Điện thoại</th>";
			echo "<th style='text-align:center;'>Sản phẩm</th>";
			echo "<th style='text-align:center;'>Trạng thái</th>";
			echo "<th style='text-align:center;'>Tùy chọn</th>";
			echo "</tr>";
			echo "</thead>";
			foreach($data as $o) {
				echo "<tr>";
				echo "<td style='text-align:center;'>".$o->id."</td>";
				echo "<td>".$o->created_at."</td>";
				echo "<td>".$o->name."</td>";
				echo "<td>".$o->address."</td>";
				echo "<td style='text-align:center;'>".$o->district_name."</td>";
				echo "<td style='text-align:center;'>".$o->phone."</td>";
				$detail=$model->getOrderDetails($o->id);
				// print_r($detail);
				echo "<td><table style='width:100%;'>";
				echo "<tr>";
				echo "<td width='70%' style='text-align:center;'><b>Tên SP</b></td>";
				echo "<td style='text-align:center;'><b>SL</b></td>";
				echo "<td style='text-align:center;'><b>Đơn giá</b></td>";
				echo "<td style='text-align:center;'><b>Trị giá</b></td>";
				echo "</tr>";
				foreach($detail as $d) {
					echo "<tr>";
					echo "<td>".$d->product_name."</td>";
					echo "<td style='text-align:center;'>".$d->quantity_out."</td>";
					echo "<td><span class='price'>".number_format($d->price,PRICE_DECIMALS,'.',',')."</span></td>";
					echo "<td><span class='price'>".number_format($d->price*$d->quantity_out,PRICE_DECIMALS,'.',',')."</span></td>";
					echo "</tr>";
				}
				echo "<tr><td></td><td></td><td>Tổng:</td><td><span class='price'>".number_format($o->total,PRICE_DECIMALS,'.','')."</span></td></tr>";
				echo "</table></td>";
				echo "<td><i>";
				switch ($o->status) {
					case ORDER_PENDING:
					case ORDER_WAITFORSHIPPER:
						echo "Chờ xác nhận";
						break;
					case ORDER_INDELIVERY:
						echo "Đang giao";
						break;
					case ORDER_FINISHED:
						echo "Đã hoàn tất";
						break;
					case ORDER_CANCELLED:
						echo "Đã hủy";
						break;
				}
				echo "</i></td>";
				echo ($o->status==0||$o->status==1)?"<td><button class='btn btn-danger btnHuy' id_order='$o->id' id_status='".ORDER_CANCELLED."'>Hủy</button></td>":"<td></td>";
				echo "</tr>";
			}
			echo "</table>";
			?>
		</div>
		</div>

	</div>
	</div>
	</section>

<div id='loading-screen' style='z-index:9999;display:none;background:#000;opacity:0.7;color:#fff;position:fixed;top:0;left:0;width:100%;height:100%;text-align:center;margin:0 auto;'><span style='position:absolute;top:50%;'>Đang xử lý...</span></div>

<script>
$(document).ready(function() {
	let loading_on=()=>$("#loading-screen").css("display","block");
	let loading_off=()=>$("#loading-screen").css("display","none");

	$(".btnHuy").click(function(e) {
		loading_on();
		var idOrder = $(this).attr('id_order');
		var idStatus= $(this).attr('id_status');
		var idCus=<?=$_SESSION['customer']->id?>;
		var idName="<?=$_SESSION['customer']->name?>";
		$.ajax({
			url:"/helmet_shop/admin/editbill.php",
			type:'POST',
			data:{
				idOrder:idOrder,
				idStatus:idStatus,
				idName:idName,
				idCus:idCus
			},
			success:function(res){
				loading_off();
				console.log(res)
				if($.trim(res)=='Cập nhật thất bại'){
					alert('Cập nhật thất bại');
					return false;
				}
				if($.trim(res)=='Gửi email thất bại'){
					alert('Gửi email thất bại');
					return false;
				}
				alert('Đơn hàng HD-'+idOrder+' đã được chuyển qua trạng thái hủy!');
				location.reload();
			},
			error:function(err){
				loading_off();
				console.log(err);
			}
		});
	});
});
</script>