<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\MenuModel.php');
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\controller\Controller.php');
    class MenuController extends Controller{
        function getType(){
            $model = new MenuModel;
            $data=$model->getAllType();
            return $data;
        }
    }
?>