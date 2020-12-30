<?php
session_start();
if (!isset($_SESSION['login_email'])) header("location:../login.php");
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\ImportModel.php');
require_once ($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
$model=new ImportModel;

if (isset($_POST['action']) && $_POST['action']=='datHang') {
	$place_order_code=$_POST['place_order_code'];
	$supp_code=$_POST['supp_code'];
	$id_employee=$_POST['id_employee'];
    // $created_at=$_POST['created_at'];
	$products=explode("|",$_POST['products']);
	// print_r($created_at);

	if ($model->checkPlaceOrderCode($place_order_code)) {
		echo "Mã đặt hàng đã tồn tại!";
		return;
	}

	$model->insertPlaceOrder($place_order_code,$id_employee,$supp_code,0);
	foreach ($products as $product) {
		$p=explode(DELIMITER,$product);
		$product_code=$p[0];
		$quantity_ord=$p[1];
		$price_ord=$p[2];
		$model->insertPlaceOrderDetail($place_order_code,$product_code,$quantity_ord,$price_ord);
	}

	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='huyDonNhapHang') {
	$place_order_code=$_POST['place_order_code'];
	$model->cancelPlaceOrder($place_order_code);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='loadProductsBySuppCode') {
	$supp_code=$_POST['supp_code'];
	$products=$model->getProducts($supp_code);
	echo json_encode($products);
}
?>