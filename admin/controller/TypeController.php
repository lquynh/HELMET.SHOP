<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\TypeModel.php');
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\controller\Controller.php');
    class TypeController extends Controller{
        function getProducts(){
            $model = new TypeModel;
            $id=$_GET['type'];
            $data=$model->getProductsByType($id);
            return $data;
        }
    }
?>