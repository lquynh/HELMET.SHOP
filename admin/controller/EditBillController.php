<?php
include_once ($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\BillModel.php');
include_once ($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\controller\EditBillController.php');
include_once ($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\mail.php');
class EditBillController {
    function updateBillRevoke($idOrder) {
		$model = new BillModel;
        $rs = $model->updateRevokeOrder($idOrder);
        if (!$rs) {
            echo "Chuyển trạng thái 'thu hồi' thất bại!";
			return false;
		}
		echo "ok";
	}

    function updateBillWaitForShipper($idOrder,$idEmploy,$idShipper) {
		$model = new BillModel;
        $rs = $model->updateWaitForShipperOrder($idOrder,$idEmploy,$idShipper);
        if (!$rs) {
            echo "Chuyển trạng thái 'chờ shipper xác nhận' thất bại!";
			return false;
		}
		echo "ok";
	}

	function updateBillInDelivery($idOrder,$idCus,$idEmploy,$idShipper) {
		$model = new BillModel;
		$result = $model->getEmailCus($idCus);
		$array = get_object_vars($result);
		$email = $array["email"];
		$nameCus = $array["name"];
		$subject = "Đơn hàng HD-".$idOrder." đang được giao đến bạn!";
		$content = "
            Chào bạn $nameCus,<br/>
            Cảm ơn bạn đã đặt hàng tại ".SHOP_NAME.".<br/>
            Đơn hàng của bạn đang được đóng gói và vận chuyển.<br/>
            Vui lòng thường xuyên cập nhật để biết trạng thái đơn hàng.<br/>
            Thanks and Best Regard.
        ";
		$check = maill($nameCus, $email, $subject, $content);
		if ($check) {
			$status = 1;
			$rs = $model->updateInDeliveryOrder($idOrder,$idEmploy,$idShipper);
			if (!$rs) echo "Cập nhật thất bại";
		} else {
			echo "Gửi Email thất bại";
		}
		echo "ok";
	}

	function updateBillFininsh($idOrder,$idCus) {
		$model = new BillModel;
		$result = $model->getEmailCus($idCus);
		$array = get_object_vars($result);
		$email = $array["email"];
		$nameCus = $array["name"];

		$subject = "Đơn hàng HD-".$idOrder." đã được giao thành công!";
		$content = "
			Chào bạn $nameCus,<br/>
			Đơn hàng của bạn đã được giao thành công.<br/>
			Cảm ơn bạn đã đặt hàng tại ".SHOP_NAME.".<br/>
			Thanks and Best Regard.";
		$check1 = maill($nameCus,$email,$subject,$content);
		if ($check1) {
			$rs1 = $model->updateFinishOrder($idOrder);
			if (!$rs1) echo "Cập nhật thất bại";
		}
		else {
			echo "Gửi Email thất bại";
		}
		echo "ok";
	}

	function updateBillCancel($idOrder,$idCus) {
		$model = new BillModel;
		$result = $model->getEmailCus($idCus);
		$array = get_object_vars($result);
		$email = $array["email"];
		$nameCus = $array["name"];
		$productOrder = $model->getOrderDetail($idOrder);
		foreach ($productOrder as $p) {
			$qualityOut = $p->quantity_out;
			$idProduct = $p->product_code;
			$product = $model->getProduct($idProduct);
			$quantityExist = $product->quantity_exist + $qualityOut;
			$model->updateQuantiProduct($quantityExist, $idProduct);
		}
		$subject = "Đơn hàng HD-".$idOrder." đã bị hủy!";
		$content = "
			Chào bạn $nameCus,<br/>
			Đơn hàng của bạn đã bị hủy.<br/>
			Thanks and Best Regard.";
		$check1 = maill($nameCus, $email, $subject, $content);
		if (!$check1) {
			echo "Gửi email thất bại";
		} else {
			$model->updateCancelOrder($idOrder);
			$model->deleteBill($idOrder);
		}
		echo "ok";
	}
}
?>
