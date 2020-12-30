<?php
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
include_once "controller/CheckoutController.php";
require 'vendor/autoload.php';
class PayController extends Controller{
    function pay(){
        $checkOutController=new CheckoutController();
        $paypal = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AR6LBVpSDkNLKEDfzeTf1TwaT0EcBBQtdmQ-li9BI0k5zOh3K3Ogz27cx0QXeOrEa5ODr1Bh6vd3vw_G',
                'EDKnFIlgHueKzqTyh-WM3bbVwTromvXW5VtZyHXtvZBm0zrxgnn1jqccyxFjqhiBTFZ4oTeg9ft7UOFg'
            )
          );
        if(!isset($_GET['success'],$_GET['paymentId'],$_GET['PayerID'])){
            die();
        }
        if((bool)$_GET['success']===false){
            echo "Thanh Toán Không Thành Công";
            die();
        }
        $paymentId=$_GET['paymentId'];
        $payerId=$_GET['PayerID'];
        $payment=Payment::get($paymentId,$paypal);
        $excute=new PaymentExecution();
        $excute->setPayerId($payerId);
        try{
            $result=$payment->execute($excute,$paypal);
        }catch(Exception $e){
            echo $e;
            die();
        }
        $result=$checkOutController->saveOrder();
        if($result){
			$checkOutController->updateQuantity();
        }
    }
    
}

?>