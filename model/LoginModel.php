<?php
include_once 'DBConnect.php';
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\helper\constants.php');

class LoginModel extends DBConnect{
    function selectCustomers($email){
        $sql = "SELECT * FROM customers 
		WHERE email='$email'";
       return $this->loadOneRow($sql);
	}

	function selectPhone($phone){
        $sql = "SELECT * FROM customers 
		WHERE phone='$phone'";
       return $this->loadOneRow($sql);
    }

	function selectUsername($username){
        $sql = "SELECT * FROM customers 
		WHERE username='$username'";
       return $this->loadOneRow($sql);
    }

    function insertCustomers($user_name,$password,$name,$gender,$address,$district,$email,$phone){
        $sql = "INSERT INTO customers(username,password,email,name,gender,address,district_code,phone,status,id_role) VALUES ('$user_name','$password','$email','$name','$gender','$address','$district','$phone','".DEACTIVATED."','".ID_ROLE_CUSTOMER."')";
		// print_r($sql);die;
        return $this->executeQuery($sql);
    }
	
	// Login by Username
	function selectLoginUsername($username, $password) {
		$sql = "SELECT * FROM customers c
		WHERE c.username='$username'
		AND c.password='$password'
		AND c.status='".ACTIVATED."'";
		return $this->loadOneRow($sql);
	}

	// Login by Email
    function selectLoginEmail($email,$password){
		$sql = "SELECT * FROM customers c
		WHERE c.email='$email' 
		AND c.password='$password' 
		AND c.status='".ACTIVATED."'";
		return $this->loadOneRow($sql);
    }
	
	function selectCustomerInfoById($id_customer) {
		$sql = "SELECT * FROM customers WHERE id_customer='$id_customer'";
		return $this->loadOneRow($sql);
	}
	
	function selectCustomerInfoByEmail($email) {
		$sql = "SELECT * FROM customers WHERE email='$email'";
		return $this->loadOneRow($sql);
	}

    function updateActive($token){
        $sql = "UPDATE customers SET status='".ACTIVATED."' WHERE email='$token'";
        return $this->executeQuery($sql);
    }
}

?>