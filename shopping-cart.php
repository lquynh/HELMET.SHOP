<?php
include_once "controller/CartController.php";

$c = new CartController;
return $c->loadShoppingCart();

?>