<?php

include_once 'controller/CartController.php';
$c = new CartController;
if (isset($_POST['action'])) {
    if ($_POST['action']=='add')
        return $c->addToCart();
    if ($_POST['action']=='remove')
        return $c->removeFromCart();
    if ($_POST['action']=='removeAll')
        return $c->removeAllFromCart();
}
?>


