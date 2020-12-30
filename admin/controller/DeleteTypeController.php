<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\DeleteProductModel.php');
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\controller\Controller.php');
    class DeleteTypeController extends Controller{
        function deleteType($id,$name){
            $model = new DeleteProductModel;

			if (!$model->isTypeOkayToDelete($id)) {
				echo "Loại sản phẩm '$name' không thể xóa vì có sản phẩm thuộc loại này!";
				return;
			}

            $check=$model->deleteType($id);
            if($check){
                $model->updateProductAuto($id);
                echo "success";
            }else{
                echo "error";
            }
        }
    }
?>