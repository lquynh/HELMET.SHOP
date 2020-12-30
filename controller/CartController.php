<?php
include_once 'Controller.php';
include_once 'model/DetailModel.php';
include_once 'helper/Cart.php';
include_once 'helper/constants.php';
session_start();

class CartController extends Controller{

    function loadShoppingCart(){
        $oldCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;
        $cart = new Cart($oldCart);
        return $this->loadView('shopping-cart',$cart,"Giỏ hàng của bạn");
    }

    function addToCart(){
        $id=$_POST['id'];
        $date=date("Y-m-d");
        $model=new DetailModel;
        $product=$model->selectProductById($id,$date);
        $oldCart=isset($_SESSION['cart']) ? $_SESSION['cart'] : null;
        $cart = new Cart($oldCart);
        $cart->add($product,1);
        $_SESSION['cart'] = $cart;
        echo json_encode([
            'item'=>$cart->items[$id],
            'totalQtity'=>$cart->totalQtity,
            'totalPrice'=>$cart->totalPrice,
            'promtPrice'=>$cart->promtPrice
        ]);
    }

    function removeFromCart(){
        $id=$_POST['id'];
        $date=date("Y-m-d");
        $model=new DetailModel;
        $product=$model->selectProductById($id,$date);
        $oldCart=isset($_SESSION['cart']) ? $_SESSION['cart'] : null;
        $cart = new Cart($oldCart);        
        $cart->add($product,-1);
        $_SESSION['cart'] = $cart;  
        echo json_encode([
            'item'=>$cart->items[$id],
            'totalQtity'=>$cart->totalQtity,
            'totalPrice'=>$cart->totalPrice,
            'promtPrice'=>$cart->promtPrice
        ]);
    }

    function removeAllFromCart(){
        $id = $_POST['id'];
        $oldCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        $_SESSION['cart'] = $cart;
        echo json_encode([
			'totalQtity'=>$cart->totalQtity,
            'totalPrice'=>$cart->totalPrice,
            'promtPrice'=>$cart->promtPrice
        ]);
    }
}
?>