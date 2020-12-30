<?php
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\BaseModel.php');
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
class ImportModel extends BaseModel{

	function getOrders($status) {
		$sql="SELECT * FROM place_order WHERE status='$status' ORDER BY created_at DESC";
		return $this->loadMoreRows($sql);
	}

	function getImports() {
		$sql="SELECT ip.import_code,ip.created_at,ip.id_employee,ip.total,ip.place_order_code,po.supp_code
		FROM import AS ip
		INNER JOIN place_order AS po
		ON ip.import_code=po.import_code
		ORDER BY ip.created_at DESC";
		// print_r($sql);
		return $this->loadMoreRows($sql);
	}

	function getImportDetails($import_code) {
		$sql="SELECT * FROM import_detail WHERE import_code='$import_code'";
		return $this->loadMoreRows($sql);
	}

	function getOrdersBySupp($supp_code) {
		$sql="SELECT * FROM place_order WHERE supp_code='$supp_code' AND status='0'";
		// print_r($sql);
		return  $this->loadMoreRows($sql);
	}

	function updateOrderProducts($product_code,$quantity_exist,$price) {
		$sql="UPDATE products SET quantity_exist='$quantity_exist',price='$price' WHERE product_code='$product_code'";
		return  $this->executeQuery($sql);
	}

	function updateOrderFinish($place_order_code,$import_code) {
		$sql="UPDATE place_order SET status='1',import_code='$import_code' WHERE place_order_code='$place_order_code'";
		return  $this->executeQuery($sql);
	}

	function getSuppliers() {
		$sql="SELECT DISTINCT p.supp_code,s.name
		FROM products p
		INNER JOIN suppliers s ON p.supp_code=s.supp_code";
		return $this->loadMoreRows($sql);
	}

	function getProducts($supp_code) {
		$sql="SELECT * FROM products WHERE status=0 AND supp_code='$supp_code'";	// only display active products
		return $this->loadMoreRows($sql);
	}

	function getOrderDetails($place_order_code) {
		$sql="SELECT pod.place_order_code,pod.product_code,pod.quantity_ord,pod.price_ord,po.created_at
		FROM place_order_detail pod
		INNER JOIN place_order po
		ON pod.place_order_code=po.place_order_code
		WHERE pod.place_order_code='$place_order_code'";
		return $this->loadMoreRows($sql);
	}

	function checkPlaceOrderCode($place_order_code) {
		$sql="SELECT * FROM place_order WHERE place_order_code='$place_order_code'";
		return $this->loadOneRow($sql);
	}

	function insertPlaceOrder($place_order_code,$id_employee,$supp_code,$status){
        $current_date=date('Y-m-d H:i:s');
		$sql="INSERT INTO place_order(place_order_code,created_at,id_employee,supp_code,status) VALUES('$place_order_code','$current_date','$id_employee','$supp_code','$status')";
		return $this->executeQuery($sql);
	}

	function insertPlaceOrderDetail($place_order_code,$product_code,$quantity_ord,$price_ord){
		$sql="INSERT INTO place_order_detail(place_order_code,product_code,quantity_ord,price_ord) VALUES('$place_order_code','$product_code','$quantity_ord','$price_ord')";
		// print_r($sql);
		return $this->executeQuery($sql);
	}

	function checkImportCode($import_code) {
		$sql="SELECT * FROM import WHERE import_code='$import_code'";
		return $this->loadOneRow($sql);
	}

	function insertImport($import_code,$created_at,$id_employee,$total,$place_order_code){
        $receive=explode("-",$created_at);
        $t=time();
        $current_time=date("H:i:s",$t);
		$sql="INSERT INTO import(import_code,created_at,id_employee,total,place_order_code) VALUES('$import_code','$receive[2]-$receive[1]-$receive[0] $current_time','$id_employee','$total','$place_order_code')";
		// print_r($sql);die;
		return $this->executeQuery($sql);
	}

	function insertImportDetail($import_code,$product_code,$price,$quantity_in){
		$sql="INSERT INTO import_detail(import_code,product_code,price,quantity_in) VALUES('$import_code','$product_code','$price','$quantity_in')";
		//print_r($sql);die;
		return $this->executeQuery($sql);
	}

	function getEmployeeName($id) {
		$sql="SELECT name FROM employee WHERE id='$id'";
		return get_object_vars($this->loadOneRow($sql))['name'];
	}

	function getSupplierCode($place_order_code){
		$sql="SELECT supp_code FROM place_order WHERE place_order_code='$place_order_code'";
		return get_object_vars($this->loadOneRow($sql))['supp_code'];
	}

	function getSupplierName($supp_code){
		$sql="SELECT name FROM suppliers WHERE supp_code='$supp_code'";
		return get_object_vars($this->loadOneRow($sql))['name'];
	}

	function getProductName($product_code) {
		$sql="SELECT name FROM products WHERE product_code='$product_code'";
		return get_object_vars($this->loadOneRow($sql))['name'];
	}

	function getProductQuantity($product_code) {
		$sql="SELECT quantity_exist FROM products WHERE product_code='$product_code'";
		return get_object_vars($this->loadOneRow($sql))['quantity_exist'];
	}

	function getProductPrice($product_code) {
		$sql="SELECT price FROM products WHERE product_code='$product_code'";
		return get_object_vars($this->loadOneRow($sql))['price'];
	}

	function cancelPlaceOrder($place_order_code) {
		$sql="UPDATE place_order SET status='2' WHERE place_order_code='$place_order_code'";
		// print_r($sql);
		return  $this->executeQuery($sql);
    }

    function checkProductCode($product_code) {
        $sql="SELECT * FROM products WHERE product_code='$product_code'";
        return $this->loadOneRow($sql);
    }

    function checkProductCodeBelongToSupp($product_code,$supp_code) {
        $sql="SELECT * FROM products WHERE (product_code='$product_code' AND supp_code='$supp_code')";
        return $this->loadOneRow($sql);
    }

    function checkProductCodeBelongToOrder($product_code,$place_order_code) {
        $sql="SELECT * FROM place_order_detail WHERE (product_code='$product_code' AND place_order_code='$place_order_code')";
        return $this->loadOneRow($sql);
    }
}
?>