<?php
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\controller\DeleteTypeController.php');

$c = new DeleteTypeController;
$id=$_GET['id'];
$name=$_GET['name'];
$c->deleteType($id,$name);
?>