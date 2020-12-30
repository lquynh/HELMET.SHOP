<?php
if (!isset($_SESSION)) session_start();
include_once "Controller.php";
include_once "model/LoginModel.php";
include_once "helper/phpmailer/mailer.php";
include_once "helper/functions.php";
include_once "helper/constants.php";
class LoginController extends Controller
{
    public function dangkyTk($user_name,$name,$gender,$email,$address,$district,$phone,$password) {
        $model = new LoginModel();
        if ($model->selectCustomers($email)) return "Email đã tồn tại!";
        if ($model->selectUsername($user_name)) return "Username đã tồn tại!";
        if ($model->selectPhone($phone)) return "Số điện thoại đã tồn tại!";

        try {
            $result = $model->insertCustomers($user_name,$password,$name,$gender,$address,$district,$email,$phone);
        } catch (Exception $e) {
            echo $e;
            return;
        }

        $tokens = $email;
        $tokenDate = date("Y-m-d");
        $link = APP_URL."accept-order.php?token=$tokens&time=$tokenDate";
        $content="
        Xin chào $name,<br/>
        Cám ơn bạn đã đăng ký tài khoản tại ".SHOP_NAME.".<br/>
        Vui lòng nhấn vào link sau để xác nhận tài khoản:$link<br/>
        Thanks and Best Regard.";
        maill($name,$email,'XÁC NHẬN TÀI KHOẢN',$content);
        return "ok";
    }

    public function dangnhapTk($user, $password) {
        $model = new LoginModel();
		$customer = NULL;

        $customer = strpos($user,'@')?$model->selectLoginEmail($user,$password):$model->selectLoginUsername($user,$password);
        if (!$customer)
            return "Sai tên đăng nhập/mật khẩu hoặc tài khoản chưa được kích hoạt!";
        $_SESSION['name']=$user;
        $_SESSION['customer']=$customer;
        return "ok";
    }

    public function selectUse($email) {
        $model=new LoginModel();
        $check=$model->selectCustomers($email);
        return $check;
    }

    public function dangXuatTk() {
        unset($_SESSION['name']);
        unset($_SESSION['customer']);
        unset($_SESSION['cart']);
        unset($cart);
        header('location:index.php');
    }

    public function accept($token) {
        $model = new LoginModel();
        $model->updateActive($token);
        header('location:signup.php');
        return;
    }
}
