<?php
	require_once 'controller/LoginController.php';
	$c = new LoginController;

	if(isset($_POST['action']) && $_POST['action']=='login') {
		$email=$_POST['email'];
		$pass=$_POST['pass'];
        $res = $c->dangnhapTk($email,$pass);
        echo $res;
    }
    if (isset($_POST['action']) && $_POST['action']=='register') {
		$user_name_regis=$_POST['user_name_regis'];
		$fullname_regis=$_POST['fullname_regis'];
		$gender_regis=$_POST['gender'];
		$email_regis=$_POST['email_regis'];
		$address_regis=$_POST['address_regis'];
		$district_regis=$_POST['district_regis'];
		$phone_regis=$_POST['phone_regis'];
		$pass_regis=$_POST['pass_regis'];
        $res = $c->dangkyTk($user_name_regis,$fullname_regis,$gender_regis,$email_regis,$address_regis,$district_regis,$phone_regis,$pass_regis);
        echo $res;
	}
?>