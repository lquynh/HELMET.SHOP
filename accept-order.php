<?php
require_once 'controller/LoginController.php';
$c = new LoginController;
$token = $_GET['token'];
$c->accept($token);

?>