<?php
session_start();
if (!isset($_SESSION['login_email'])) header("location:../login.php");
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\ImportModel.php');
require_once ($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
$model=new ImportModel;

// load corresponding place orders when selecting supplier code
if (isset($_POST['action']) && $_POST['action']=='loadPlaceOrders') {
	$supp_code=$_POST['supp_code'];
	$orders=$model->getOrdersBySupp($supp_code);
	$res="";
	foreach($orders as $o) {
		$res.="<option value='".$o->place_order_code."'>".$o->place_order_code."</option>";
	}
	echo $res=="" ? "<option value=''>Không có đơn đặt hàng</option>" : $res;
	return;
}

// load corresponding products when user select supplier code
if (isset($_POST['action']) && $_POST['action']=='loadProducts') {
	$place_order_code=$_POST['place_order_code'];
	$details=$model->getOrderDetails($place_order_code);
	$total=0;
	$i=0;
	$res="";
	
	$res="<table class='table table-bordered table-hover'>";
	$res.="<thead><tr>";
	$res.="<th style='text-align:center;'>Tên sản phẩm</th>";
	$res.="<th style='text-align:center;'>Số lượng</th>";
	$res.="<th style='text-align:center;'>Đơn giá</th>";
	$res.="<th style='text-align:center;'>Tùy chọn</th>";
	$res.="</tr></thead>";
	foreach($details as $d) {
		$res.="<tr class='products2import' created_at='".date_format(date_create($d->created_at),"d-m-Y")."'>";
		$res.="<td><input type='hidden' class='order_qty' value='".$d->quantity_ord."'/><input type='hidden' class='product_code' value='".$d->product_code."' /><b>".$d->product_code."</b>: <b class='name'>".$model->getProductName($d->product_code)."</b></td>";
		$res.="<td><input type='number' class='qty' value='".$d->quantity_ord."' /></td>";
		$res.="<td>$<input type='number' step='0.01' class='price' value='".number_format($d->price_ord,PRICE_DECIMALS,'.','')."' /></td>";
		$res.="<td style='text-align:center;'><button class='btnXoa' onclick='$(this).parent().parent().remove();'><i class='fa fa-times'></i> Xóa</button></td>";
		$res.="</tr>";
	}
	$res.="</table>";
	echo $res;
	return;
}

// nhap hang tu UI
if (isset($_POST['action']) && $_POST['action']=='nhaphang') {
	$place_order_code=$_POST['place_order_code'];
	$import_code=$_POST['import_code'];
	$id_employee=$_POST['id_employee'];
	$created_at=$_POST['created_at'];
	$products=explode("|",$_POST['products']);
	$total=$_POST['total'];
	
	if ($model->checkImportCode($import_code)) {
		echo "Mã nhập hàng đã tồn tại!";
		return;
	}
	
	$model->insertImport($import_code,$created_at,$id_employee,$total,$place_order_code);
	foreach ($products as $product) {
		$p=explode(DELIMITER,$product);
		$product_code=$p[0];
		$quantity_in=$p[1];
		$price_in=round($p[2], PRICE_DECIMALS);
		$model->insertImportDetail($import_code,$product_code,$price_in,$quantity_in);

		$old_quantity=$model->getProductQuantity($product_code);
		$old_price=$model->getProductPrice($product_code);
        $new_price=$price_in+$price_in*IMPORT_INTEREST;
		$new_price=round($new_price, PRICE_DECIMALS);

		$model->updateOrderProducts($product_code,$old_quantity+$quantity_in,$new_price);
	}
	$model->updateOrderFinish($place_order_code,$import_code);
	
	echo "ok";
}
?>