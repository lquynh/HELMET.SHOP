<?php
require_once 'controller/SignUpController.php';
!isset($_SESSION) ? session_start(): '';
$c = new SignUpController;
return $c->getSingin();
?>