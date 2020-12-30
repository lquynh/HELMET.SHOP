<?php
require_once 'DBConnect.php';

class CheckoutModel extends DBConnect{
    function selectCustomer($name){
		$sql = strpos($name, '@') ? "SELECT id FROM customers WHERE email='$name'" : "SELECT id FROM customers WHERE username='$name'";
		return $this->loadOneRow($sql);
    }

	function selectCustomerInfo($email){
        $sql = "SELECT * FROM customers WHERE email='$email'";
        return $this->loadOneRow($sql);
    }

    function saveOrder($created_at, $id_customer, $address, $district_code, $name, $phone, $date_receive, $total){
        $receive=explode("-", $date_receive);
        $t=time();
        $current_time=date("H:i:s",$t);
        $sql = "INSERT INTO orders(created_at,id_customer,name,address,district_code,phone,date_receive,status,total) VALUES ('$created_at $current_time','$id_customer','$name','$address','$district_code','$phone','$receive[2]-$receive[1]-$receive[0]','0','$total')";
		// print_r($sql);die;
        $result = $this->executeQuery($sql);
        return $result ? $this->getLastId() : false;
    }

    function saveOrderDetail($idOrder,$idProduct, $qty, $price){
        $sql = "INSERT INTO orders_detail(id_order,product_code,price,quantity_out) VALUES('$idOrder', '$idProduct','$price','$qty')";
        return $this->executeQuery($sql); 
    }

    function deleteCustomer($id){
        $sql = "DELETE FROM customers WHERE id_customer='$id'";
        return $this->executeQuery($sql);
    }
    function deleteOrder($id){
        $sql = "DELETE FROM orders WHERE id='$id'";
        return $this->executeQuery($sql);
    }
	
    function deleteOrderDetail($idOrder){
        $sql = "DELETE FROM orders_detail WHERE id_order='$idOrder'";
        return $this->executeQuery($sql);
    }

    function updateQualityProduct($idProduct,$quantityProductOut){
        $sql = "UPDATE products SET quantity_exist='$quantityProductOut' WHERE product_code='$idProduct'";
        return $this->executeQuery($sql);
    }

    function countProduct($idProduct){
        $sql = "SELECT quantity_exist FROM products WHERE product_code='$idProduct'";
        return $this->loadOneRow($sql);
    }
}
?>