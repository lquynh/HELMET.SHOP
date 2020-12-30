<link href="../favicon.ico" rel="icon" />
<title>Hóa đơn</title>
<?php
	session_start();
    include_once('..\model\BillModel.php');
	if (!isset($_SESSION['login_email'])) header("location:views/manage-bill.php?status=1");

	$model=new BillModel;
	$idOrder=$_GET['id'];
	$info=$model->getBillInfo($idOrder);
	$btotal=$model->getBillTotal($idOrder);
    $detail=$model->getBillDetail($idOrder);
    $bill=$model->selectBill($idOrder);
	$stt=1;
?>
<link href="../libraries/css/style.css" rel="stylesheet" />
<script src="/helmet_shop/admin/libraries/js/jquery.js"></script>
<style>
table{border-collapse: collapse;}
td{vertical-align:top;}
@media print {
  #btnSaveAndPrint {
    display: none !important;
  }
}
</style>
<center>
<table style="width:90%;">
	<tr>
		<td width='30%'>
			<b><?=SHOP_NAME?></b><br/>
			Địa chỉ: <?=SHOP_ADDRESS?><br />
			SĐT: <?=SHOP_PHONE?><br />
            Email: <?=SHOP_EMAIL?>
		</td>
		<td style='text-align:right;' width='50%'>
			Mã hóa đơn: HD-<?php echo $idOrder; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><center><br /><h1>HÓA ĐƠN BÁN HÀNG</h1></center></td>
	</tr>
	<tr>
		<td>
			Tên NV: <?=$detail->TENNV?><br />
			Ngày:
            <?php if (!$bill) { ?>
                <span id="ngayxuathoadon"></span><script>var d=new Date();$("#ngayxuathoadon").text(d.getDate()+"-"+(d.getMonth()+1)+"-"+d.getFullYear());</script>
            <?php } else {
                echo "<span id='ngayxuathoadon'>".date_format(date_create($bill->created_at),"d-m-Y")."</span>";
            } ?>
		</td>
		<td style='text-align:right;'>
			Tên KH: <?=$detail->TENKH?><br />
			SĐT: <?=$detail->phone?><br />
			Đ/c: <?=$detail->address?>, <?=$detail->district_name?></br >
		</td>
	</tr>
	<tr>
		<td colspan="2"><br /></td>
	</tr>
	<tr>
		<td colspan="2">
			<table width="100%" border="1">
				<tr>
					<th width='5%'>STT</th>
					<th width='40%'>Tên mặt hàng</th>
					<th width='10%'>Số lượng</th>
					<th width='10%'>Đơn giá</th>
					<th>Thành tiền</th>
				</tr>
				<?php foreach($info as $i): ?>
				<tr>
					<td style='text-align:center;'><?=$stt++?></td>
					<td style='text-align:center;'><?=$i->name?></td>
					<td style='text-align:center;'><?=$i->quantity_out?></td>
					<td style='text-align:center;'><span class='price'><?=number_format($i->price,PRICE_DECIMALS,'.',',')?></span></td>
					<td style='text-align:center;'><span class='price'><?=number_format($i->price*$i->quantity_out,PRICE_DECIMALS,'.',',')?></span></td>
				</tr>
				<?php endforeach ?>
				<tr>
					<td colspan="4"></td>
					<td style='text-align:center;'>Tổng tiền: <span class='price'><?=number_format($btotal->total,PRICE_DECIMALS,'.',',')?></span></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2"><br /></td>
	</tr>
	<tr>
		<td width="50%">
			<center>
			Người mua hàng<br />
			<br />
			<br />
			<br />
			<br />
			<i><?=$detail->TENKH?></i>
			</center>
		</td>
		<td>
			<center>
			Người bán hàng<br />
			<br />
			<br />
			<br />
			<br />
			<i><?=SHOP_NAME?></i>
			</center>
		</td>
	</tr>
	<tr>
		<td colspan="2"><center><button idOrder='<?=$idOrder?>' id='btnSaveAndPrint'>Lưu & In</button></center></td>
	</tr>
</table>
</center>

<script>
$(document).ready(function() {
	$("#btnSaveAndPrint").click(function() {
		let idOrder = $(this).attr("idOrder");
		let createdAt = $("#ngayxuathoadon").text();
		$.ajax({
			url:"/helmet_shop/admin/views/print.php",
			type: "POST",
			data: {
				action: "saveAndPrint",
				idOrder:idOrder,
				createdAt:createdAt
			},
			success: function(e) {
				alert(e);
				window.print();
			},
			error: function(e) {
				alert(e);
			}
		});
	});
});
</script>

