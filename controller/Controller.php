<?php
require_once "model/BaseModel.php";
require_once "helper/Cart.php";

class Controller{
    
    function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    function loadView($view, $data = [], $title = 'Trang chủ'){
        $model = new BaseModel;
        $menu = $model->selectMenu();
        include_once 'view/layout.view.php';
    }   
    function loadViewAjax($view, $data=[]){
        include_once "view/ajax/$view.view.php";
    }
}

?>