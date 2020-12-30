<?php
include_once "controller/CheckoutController.php";

$c = new CheckoutController;
return isset($_POST['btnCheckout']) ? $c->checkOut() : $c->loadCheckoutPage();
?>