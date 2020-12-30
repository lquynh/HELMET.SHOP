<?php 
include_once 'Controller.php';
include_once 'model/AccountModel.php';
session_start();

class AccountController extends Controller {
	function getAccountPage() {
		return $this->loadView('account',[],'Tài khoản');
	}

	function updateAccount($username,$name,$gender,$password,$email,$address,$district_code,$phone) {
		$model=new AccountModel;
		if ($model->checkPhone($phone)) {
			echo "Số điện thoại đã tồn tại!";
			return;
		}
		$model->updateAccount($username,$name,$gender,$password,$email,$address,$district_code,$phone);
		$_SESSION['customer']=$model->getUserDetails($username);
		echo "ok";
	}
}
?>