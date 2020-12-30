<?php
include_once 'DBConnect.php';
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\helper\constants.php');
class AccountModel extends DBConnect {
	function getUserDetails($username) {
		$sql="SELECT * FROM customers WHERE username='$username'";
		return $this->loadOneRow($sql);
	}

	function getDistricts() {
		$sql="SELECT * FROM district";
		return $this->loadMoreRows($sql);
	}

	function checkPhone($phone) {
		$sql="SELECT * FROM customers WHERE phone='$phone'";
		return $this->loadOneRow($sql);
	}

	function updateAccount($username,$name,$gender,$password,$email,$address,$district_code,$phone) {
		$sql="UPDATE customers SET email='$email',password='$password',name='$name',gender='$gender',address='$address',district_code='$district_code',phone='$phone' WHERE username='$username'";
		// print_r($sql);die;
		return $this->executeQuery($sql);
	}
}
?>