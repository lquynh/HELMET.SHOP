<?php
include_once 'controller/MyOrdersController.php';
$c = new MyOrdersController;
if (isset($_POST['action']) && $_POST['action']=='cancelOrder') {
	$order_id=$_POST['order_id'];
	return $c->cancelOrder($order_id);
} else
	return $c->getMyOrdersPage();
?>