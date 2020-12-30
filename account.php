<?php
include_once 'controller/AccountController.php';
$c = new AccountController;
if (isset($_POST['action']) && $_POST['action']=='updateAccount') {
	$username=$_POST['username'];
	$password=$_POST['password'];
	$gender=$_POST['gender'];
	$name=$_POST['name'];
	$email=$_POST['email'];
	$address=$_POST['address'];
	$district_code=$_POST['district_code'];
	$phone=$_POST['phone'];
	return $c->updateAccount($username,$name,$gender,$password,$email,$address,$district_code,$phone);
} else
	return $c->getAccountPage();
?>