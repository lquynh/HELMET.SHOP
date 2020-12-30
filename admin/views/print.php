<?php
	session_start();
	include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\BillModel.php');
	if (!isset($_SESSION['login_email'])) header("location:views/manage-bill.php?status=1");
	
	if (isset($_POST['action'])) {
		if ($_POST['action']=='saveAndPrint') {
			$model=new BillModel;
			$idOrder=$_POST['idOrder'];
			$createdAt=$_POST['createdAt'];
			
			if ($model->selectBill($idOrder)) {
				echo "Hóa đơn đã được lưu vào DB trước đó!";
			} else {
				$model->insertBillInfo($idOrder,$createdAt);
				echo "Hóa đơn đã được lưu vào DB thành công!";
			}
		}
	}
?>