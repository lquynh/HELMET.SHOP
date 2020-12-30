<?php
include_once 'DBConnect.php';
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\helper\constants.php');
class MyOrdersModel extends DBConnect {
	function getOrders($id_customer) {
		$sql="SELECT o.id,o.id_customer,o.created_at,o.name,o.address,d.name as district_name,o.phone,o.status,o.total FROM orders o
		INNER JOIN district d ON o.district_code=d.district_code
		WHERE o.id_customer='$id_customer' order by o.id DESC";
		// print_r($sql);
		return $this->loadMoreRows($sql);
	}
	
	function getOrderDetails($id_order) {
		$sql="SELECT od.price,od.quantity_out,p.name as product_name
		FROM orders_detail od
		INNER JOIN products p ON od.product_code=p.product_code
		WHERE od.id_order='$id_order'";
		// print_r($sql);
		return $this->loadMoreRows($sql);
	}
	
	function cancelOrders() {
		$sql="SELECT * FROM district";
		return $this->loadMoreRows($sql);
	}
}
?>