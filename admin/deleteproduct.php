<?php
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\controller\DeleteProductController.php');

$c = new DeleteProductController;
$id=$_GET['id'];
$c->deleteProduct($id);
?>