<?php 
session_start();
if (!isset($_SESSION['login_email'])) header("location:../login.php");
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\UserModel.php');
require_once ($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
$model=new UserModel;

if (isset($_POST['action']) && $_POST['action']=='checkOldPassword') {
    $old_password=$_POST['old_password'];
    $id=$_POST['id'];
    if ($old_password!=$model->getEmployeePassword($id)) {
        echo "Mật khẩu cũ không đúng!";
        return false;
    }
    echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='updateMyAccount') {
    $id=$_POST['id'];
    $username=$_POST['username'];
	$password=$_POST['password'];
	$email=$_POST['email'];
	$name=$_POST['name'];
	$gender=$_POST['gender'];
	$address=$_POST['address'];
    $phone=$_POST['phone'];
    $status=1;

    $model->updateMyAccount($id,$username,$password,$email,$name,$gender,$address,$phone,$status);
    echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='editNhanVien') {
	$username=$_POST['username'];
	$password=$_POST['password'];
	$email=$_POST['email'];
	$name=$_POST['name'];
	$gender=$_POST['gender'];
	$id_role=$_POST['id_role'];
	$address=$_POST['address'];
	$phone=$_POST['phone'];
	$status=$_POST['status'];
	if ($model->checkEmailEdit($email,$username,"employee")) {
		echo "Email đã tồn tại!";
		return false;
	}
	if ($model->checkPhoneEdit($phone,$username,"employee")) {
		echo "Số điện thoại đã tồn tại!";
		return false;
	}
	$model->updateEmployee($username,$password,$email,$name,$gender,$id_role,$address,$phone,$status);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='addNhanVien') {
	$username=$_POST['username'];
	$password=$_POST['password'];
	$email=$_POST['email'];
	$name=$_POST['name'];
	$gender=$_POST['gender'];
	$id_role=$_POST['id_role'];
	$address=$_POST['address'];
	$phone=$_POST['phone'];
	$status=$_POST['status'];
	if ($model->checkUsername($username,"employee")) {
		echo "Username đã tồn tại!";
		return false;
	}
	if ($model->checkEmailAdd($email,"employee")) {
		echo "Email đã tồn tại!";
		return false;
	}
	if ($model->checkPhoneAdd($phone,"employee")) {
		echo "Số điện thoại đã tồn tại!";
		return false;
	}
	$model->addEmployee($username,$password,$email,$name,$gender,$id_role,$address,$phone,$status);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='lockNhanVien') {
	$username=$_POST['username'];
	$model->deactivateEmployee($username);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='deleteNhanVien') {
	$id_employee=$_POST['id_employee'];
	if (!$model->canDeleteEmployee($id_employee)) {
		echo "Không thể xóa nhân viên vì vẫn còn bảng tham chiếu tới nhân viên này!";
		return false;
	}
	$model->deleteEmployee($id_employee);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='editKhachHang') {
	$username=$_POST['username'];
	$password=$_POST['password'];
	$email=$_POST['email'];
	$name=$_POST['name'];
	$gender=$_POST['gender'];
	$address=$_POST['address'];
	$district_code=$_POST['district_code'];
	$phone=$_POST['phone'];
	$status=$_POST['status'];
	if ($model->checkEmailEdit($email,$username,"customers")) {
		echo "Email đã tồn tại!";
		return false;
	}
	if ($model->checkPhoneEdit($phone,$username,"customers")) {
		echo "Số điện thoại đã tồn tại!";
		return false;
	}
	$model->updateCustomer($username,$password,$email,$name,$gender,$address,$district_code,$phone,$status);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='addKhachHang') {
	$username=$_POST['username'];
	$password=$_POST['password'];
	$email=$_POST['email'];
	$name=$_POST['name'];
	$gender=$_POST['gender'];
	$address=$_POST['address'];
	$district_code=$_POST['district_code'];
	$phone=$_POST['phone'];
	$status=$_POST['status'];
	if ($model->checkUsername($username,"customers")) {
		echo "Username đã tồn tại!";
		return false;
	}
	if ($model->checkEmailAdd($email,"customers")) {
		echo "Email đã tồn tại!";
		return false;
	}
	if ($model->checkPhoneAdd($phone,"customers")) {
		echo "Số điện thoại đã tồn tại!";
		return false;
	}
	$model->addCustomer($username,$password,$email,$name,$gender,$address,$district_code,$phone,$status);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='lockKhachHang') {
	$username=$_POST['username'];
	$model->deactivateCustomer($username);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='deleteKhachHang') {
	$id_customer=$_POST['id_customer'];
	if(!$model->canDeleteCustomer($id_customer)) {
		echo "Không thể xóa khách hàng vì vẫn còn bảng tham chiếu tới khách hàng này!";
		return false;
	}
	$model->deleteCustomer($id_customer);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='editNCC') {
	$supp_code=$_POST['supp_code'];
	$name=$_POST['name'];
	$address=$_POST['address'];
	$email=$_POST['email'];
    $phone=$_POST['phone'];
    if ($model->checkTenNCC($name)) {
        echo "Tên NCC đã tồn tại!";
        return false;
    }

	if ($model->checkEmailNCCEdit($email,$supp_code)) {
		echo "Email NCC đã tồn tại!";
		return false;
	}
	if ($model->checkSdtNCCEdit($phone,$supp_code)) {
		echo "Số điện thoại NCC đã tồn tại!";
		return false;
	}
	$model->updateSupplier($supp_code,$name,$address,$email,$phone);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='addNCC') {
	$supp_code=$_POST['supp_code'];
	$name=$_POST['name'];
	$address=$_POST['address'];
	$email=$_POST['email'];
    $phone=$_POST['phone'];
    if ($model->checkTenNCC($name)) {
        echo "Tên NCC đã tồn tại!";
        return false;
    }

	if ($model->checkMaNCC($supp_code)) {
		echo "Mã NCC đã tồn tại!";
		return false;
	}
	if ($model->checkEmailNCC($email)) {
		echo "Email NCC đã tồn tại!";
		return false;
	}
	if ($model->checkSdtNCC($phone)) {
		echo "Số điện thoại NCC đã tồn tại!";
		return false;
	}
	$model->addSupplier($supp_code,$name,$address,$email,$phone);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='deleteNCC') {
	$supp_code=$_POST['supp_code'];
	if(!$model->canDeleteSupplier($supp_code)) {
		echo "Không thể xóa NCC vì vẫn còn bảng tham chiếu tới NCC này!";
		return false;
	}
	$model->deleteSupplier($supp_code);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='editPhanCong') {
	$district_code=$_POST['district_code'];
	$id_employee=$_POST['id_employee'];
	$original_district_code=$_POST['original_district_code'];
	$original_id_employee=$_POST['original_id_employee'];

	if ($model->checkDivisionEdit($district_code,$id_employee)) {
		echo "Phân công đã tồn tại!";
		return false;
	}
	$model->updateDivision($district_code,$id_employee,$original_district_code,$original_id_employee);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='addPhanCong') {
	$district_code=$_POST['district_code'];
	$id_employee=$_POST['id_employee'];

	if ($model->checkDivisionEdit($district_code,$id_employee)) {
		echo "Phân công đã tồn tại!";
		return false;
	}
	$model->addDivision($district_code,$id_employee);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='deletePhanCong') {
	$district_code=$_POST['district_code'];
	$id_employee=$_POST['id_employee'];

	$model->deleteDivision($district_code,$id_employee);
	echo "ok";
}
?>