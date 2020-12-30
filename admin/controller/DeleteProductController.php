<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\DeleteProductModel.php');
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\controller\Controller.php');
    class DeleteProductController extends Controller{
        function deleteProduct($id){
            $model = new DeleteProductModel;
			
			if (!$model->isProductOkayToDelete($id)) {
				echo "Sản phẩm $id không thể xóa vì số lượng tồn > 0";
				return;
			}
			
            $check=$model->deleteProduct($id);
            if($check){
                echo "success";
            }else{
                echo "error";
            }
        }
    }
?>