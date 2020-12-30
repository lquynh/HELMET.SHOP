<?php

include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\controller\EditBillController.php');
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\mail.php');
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
$c= new EditBillController;
$idOrder=$_POST['idOrder'];
$idStatus=$_POST['idStatus'];
$idCus=$_POST['idCus'];

if ($idStatus==ORDER_INDELIVERY) {
    $idEmploy=$_POST['idEmploy'];
    $idShipper=$_POST['idShipper'];
    $c->updateBillInDelivery($idOrder,$idCus,$idEmploy,$idShipper);
}

if ($idStatus==ORDER_FINISHED) $c->updateBillFininsh($idOrder,$idCus);
if ($idStatus==ORDER_CANCELLED) $c->updateBillCancel($idOrder,$idCus);
if($idStatus==ORDER_REVOKE) $c->updateBillRevoke($idOrder);
?>