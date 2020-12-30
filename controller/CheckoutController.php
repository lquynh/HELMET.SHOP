<?php
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;

require 'vendor/autoload.php';
include_once "Controller.php";
include_once 'model/CheckoutModel.php';
include_once 'helper/Cart.php';
include_once 'helper/functions.php';
include_once 'helper/phpmailer/mailer.php';
include_once 'LoginController.php';
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');

class CheckoutController extends Controller{

    function loadCheckoutPage(){
        return $this->loadView('checkout',[],"Đặt hàng");
    }

    function checkOut(){
        $paypal = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AR6LBVpSDkNLKEDfzeTf1TwaT0EcBBQtdmQ-li9BI0k5zOh3K3Ogz27cx0QXeOrEa5ODr1Bh6vd3vw_G',
                'EDKnFIlgHueKzqTyh-WM3bbVwTromvXW5VtZyHXtvZBm0zrxgnn1jqccyxFjqhiBTFZ4oTeg9ft7UOFg'
            )
          );
        $address = trim($_POST['address']);
		$district_code = trim($_POST['district_code']);
        $name=trim($_POST['name']);
		$phone=trim($_POST['phone']);
		$date_receive=trim($_POST['date_receive']);
		
        $names=explode(" ",$name);
        // print_r($name);
        // die;
        if(isset($name) === true && $name === ''){
            // print_r('aab');
            // die;
            $_SESSION['addresserror']='Tên không hợp lệ';
            header('location:checkout.php');
            return;
        }
        elseif(isset($address) === true && $address === ''){
            // print_r('aaa');
            // die;
            $_SESSION['addresserror']='Địa chỉ không hợp lệ';
            header('location:checkout.php');
            return;
        }
       
        $_SESSION['nameCustomer']=$name;
        $_SESSION['addressCustomer']=$address;
		$_SESSION['districtCodeCustomer']=$district_code;
		$_SESSION['phoneCustomer']=$phone;
        $_SESSION['dateReceiveCustomer']=$date_receive;
		
        $model = new CheckoutModel();
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;
		if($cart==null){
			header('location:index.php'); 
			return;
		}
		
        // construct paypal payment API       
		$payer=new Payer();
		$itemList=new ItemList();
		$details=new Details();
		$amount=new Amount();
		$transaction=new Transaction();
		$redirectUrls=new RedirectUrls();
		$payment=new Payment();
		
		$idCustomer=$model->selectCustomer($_SESSION['name']);
		$array = get_object_vars($idCustomer);
		$dateOrder = date('Y-m-d',time());
		$promtPrice = $cart->promtPrice;
		$total = $promtPrice;
		// $idOrder=$model->saveOrder($dateOrder,$array['id'],$address,$name,$total); //lưu order

		// if($idOrder){
		//lưu order detail  
		$itemss=array();
		$i=0;
		foreach($cart->items as $id=>$sp){
			//  print_r($cart);
			$idProduct = $id;
			$qty = $sp['totalQtity'];
			// $price = $sp['discountPrice'];
			$price = $sp['unitPrice'];
			$name=$sp['item']->name;
			$subTotal=$price*$qty;              
			// $check = $model->saveOrderDetail($idOrder,$idProduct, $qty, $price);
			// if(!$check){                        
			//     $model->deleteOrder($idOrder);                
			//     $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại";
			//     header('location:checkout.php');
			//     return;
			// }else{  
					$payer->setPaymentMethod('paypal');
					$itemss[$i]=new Item();
					$itemss[$i]->setName($name)
						 ->setCurrency('USD')
						 ->setQuantity($qty)
						 ->setPrice($price);   
					$i++;
			// }
		}
		$itemList->setItems($itemss); 

		// print_r($cart);
		// print_r($itemList);
		// print_r(round($promtPrice, 2));die;
		$details->setShipping(0)
		->setTax(0)
		->setSubtotal($promtPrice);  
										 
		$amount->setCurrency('USD')
		->setTotal($promtPrice)
		->setDetails($details);
	   
		$transaction->setAmount($amount)
		->setItemList($itemList)
		->setDescription('Pay s/t')
		->setInvoiceNumber(uniqid());
	   
		$redirectUrls->setReturnUrl(APP_URL . '/pay.php?success=true')
		->setCancelUrl(APP_URL . '/pay.php?success=false');

		$payment->setIntent('sale')
		->setPayer($payer)
		->setRedirectUrls($redirectUrls)
		->setTransactions(array($transaction));
		try {
			$payment->create($paypal);
		} catch (PayPal\Exception\PayPalConnectionException $ex) {
			echo $ex->getCode(); // Prints the Error Code
			echo "<br>";
			echo $ex->getData(); // Prints the detailed error message 
			echo "<br>";
			die($ex);
		} catch (Exception $ex) {
			die($ex);
		}
		$approvalUrl=$payment->getApprovalLink();
		header("Location: {$approvalUrl}");
		
		// }
		// else{
		//     $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại";
		//     header('location:checkout.php');
		//     return;
		// }
    }
	
    function updateQuantity(){
        $cart=$_SESSION['cart'];
        $modelUpdate=new CheckoutModel();
        foreach($cart->items as $id=>$sp){
            $idProduct = $id;
            $qty = $sp['totalQtity'];
            $countProduct=$modelUpdate->countProduct($idProduct);
            $arrayCount=get_object_vars($countProduct);
            $quantityProducNow=$arrayCount['quantity_exist'];
            $qualityExist=$quantityProducNow-$qty;                              
            $checkUpdateQuality=$modelUpdate->updateQualityProduct($idProduct,$qualityExist);  
            if(!$checkUpdateQuality){
                $modelUpdate->deleteOrder($idOrder);
                $modelUpdate->deleteOrderDetail($idOrder);
                $_SESSION['error'] = "Cập Nhật Số Lượng Tồn Thất Bại";
                header('location:checkout.php');
                return;
            }
        }

        unset($_SESSION['cart']);
		print_r("<center><br /><br /><h2>THANH TOÁN THÀNH CÔNG!</h2><br /><a href='index.php'><button style='background-color:#e65d55;cursor:pointer;padding:13px;border-radius:8px;text-transform:uppercase;border:0px;'><b style='color:#fff;'>Về trang chủ</b></button></a></center>");die;
    }

    function saveOrder(){
		$model = new CheckoutModel();
        $cart = $_SESSION['cart'];
        $idCustomer=$_SESSION['customer']->id;
        $dateOrder = date('Y-m-d',time());
        $total = $cart->promtPrice;

        $address=$_SESSION['addressCustomer'];
		$district_code=$_SESSION['districtCodeCustomer'];
		$name=$_SESSION['nameCustomer'];
		$phone=$_SESSION['phoneCustomer'];
        $date_receive=$_SESSION['dateReceiveCustomer'];
		$email=$_SESSION['customer']->email;
        $idOrder=$model->saveOrder($dateOrder,$idCustomer,$address,$district_code,$name,$phone,$date_receive,$total);

        if($idOrder){
            foreach($cart->items as $id=>$sp){
                $idProduct=$id;
                $qty=$sp['totalQtity'];
                $price=$sp['unitPrice'];
                $check=$model->saveOrderDetail($idOrder,$idProduct,$qty,$price);
                    if(!$check){
                        $model->deleteOrder($idOrder);
                        $_SESSION['error'] = "SaveOrder: Có lỗi xảy ra, vui lòng thử lại";
                        header('location:checkout.php');
                        return false;
                    }
            }
            $subject = "Trạng thái đơn hàng HD-".$idOrder;
            $content = "
                           Chào bạn ".$_SESSION['customer']->name.",<br/>
                           Cảm ơn bạn đã đặt hàng tại website của chúng tôi.<br/>
                           Đơn hàng của bạn đã được xác nhận.<br/>
                           Vui lòng thường xuyên cập nhật email để biết thêm trạng thái đơn hàng <br/>
                           Thanks and Best Regard.
                       ";
           $check=maill($name,$email,$subject,$content);
        }else{
			$_SESSION['error'] = "SaveOrder2: Có lỗi xảy ra, vui lòng thử lại";
			header('location:checkout.php');
			return false;
		}
		return true;
    }
}
?>