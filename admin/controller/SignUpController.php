<?php
if(session_status() !== PHP_SESSION_ACTIVE) 
	session_start();
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\SignUpModel.php');
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\FunctionModel.php');
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\controller\CheckController.php');
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
class SignUpController {
	function dangkiTK($fullname, $address, $gender, $phone, $password, $email) {
		$userModel = new SignUpModel();
		$checkController = new CheckController();
		$checkName = $checkController->ktraMin($fullname, 'tên', 5);
		if ($checkName) {
			$checkAddress = $checkController->ktraMin($address, 'địa chỉ', 5);
			if ($checkAddress) {
				$checkPhone = $checkController->ktraPhone($phone);
				if ($checkPhone) {
					$checkPhoneMin = $checkController->kiemTraMinPhone($phone);
					if ($checkPhoneMin) {
						$checkPass = $checkController->ktraMin($password, 'mật khẩu', 6);
						if ($checkPass) {
							$checkMailHopLe = maill('aa', $email, 'aa', 'aa');
							if ($checkMailHopLe) {
								$check = $userModel->selectUser($email);
								if ($check == false) {
									try {
										$result = $userModel->insertUser($fullname, $address, $gender, $phone, $password, $email);
										$_SESSION['login_email'] = $email;
										header('location:manage-bill.php?status=0');
									}
									catch(Excetion $e) {
										echo $e;
										return;
									}
								}
								else {
									$_SESSION['errormail'] = 'Email đã tồn tại';
								}
							}
							else {
								$_SESSION['errormail'] = 'Email không có thực';
							}
						}
					}
				}
			}
		}
	}

	function dangnhapTk($email, $password) {
		$userModel = new SignUpModel();

		if (strpos($email, '@'))
			$check = $userModel->selectLoginByEmail($email, $password);
		else
			$check = $userModel->selectLoginByUsername($email, $password);

		if (!$check) {
            $_SESSION['error'] = 'Sai username hoặc password';
            return false;
        }

        $_SESSION['login_id'] = $check->id;
        $_SESSION['login_email'] = $check->email;
        $_SESSION['login_username'] = $check->username;
        $_SESSION['login_name'] = $check->name;

        if (isset($_SESSION['error'])) {
            unset($_SESSION['error']);
        }

        $_SESSION['role']=$check->id_role;
        $functionModel=new FunctionModel;
        $functions=$functionModel->getFunctions($check->id_role);

        switch($check->id_role) {
            case ID_ROLE_ADMIN:
                header('location:views/employees.php');
                break;
            case ID_ROLE_MANAGER:
                header('location:views/statistical-interest.php');
                break;
            case ID_ROLE_APPROVER:
                header('location:views/manage-bill.php?status='.ORDER_PENDING);
                break;
            case ID_ROLE_SHIPPER:
                header('location:views/manage-bill.php?status='.ORDER_MYSHIPORDERS);
                break;
		}
	}

	function selectUse($email) {
		$userModel = new SignUpModel();
		$check = $userModel->selectUser($email);
		return $check;
	}

	function dangXuatTk() {
		unset($_SESSION['login_email']);
		unset($_SESSION['login_username']);
		unset($_SESSION['message']);
		header('location:login.php');
	}

}
?>
