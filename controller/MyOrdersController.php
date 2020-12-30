<?php 
include_once 'Controller.php';
include_once 'model/MyOrdersModel.php';
session_start();

class MyOrdersController extends Controller {
	function getMyOrdersPage() {
		$model=new MyOrdersModel;
		// print_r($_SESSION['customer']);
		$orders=$model->getOrders($_SESSION['customer']->id);
		return $this->loadView('my-orders',$orders,'Đơn hàng của tôi');
	}
	
	// hủy đơn hàng
	function cancelOrder($id_order) {
		$model=new MyOrdersModel;
		$model->cancelOrder($id_order);
		echo "ok";
	}
}
?>