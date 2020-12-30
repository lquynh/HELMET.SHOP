<?php
include_once 'controller/HomeController.php';
$c = new HomeController;
return $c->getHomePage();
?>