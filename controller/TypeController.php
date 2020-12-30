<?php
include_once "Controller.php";
include_once "model/TypeModel.php";

class TypeController extends Controller{

    function loadPageType(){
        $date=date("Y-m-d");
        $alias = isset($_GET['type']) ? $_GET['type'] : '';

        
        if($alias == ''){
            header('Location:404.apsx'); // apsx xem .htaccess
            return;
        }
        $model = new TypeModel;
        $type = $model->getNameType($alias);
        $products = $model->selectProductByCategories($alias,$date);       
        $allType = $model->selectAllType();
        $count = $model->countProduct($alias);
        $result = [
            'allType'=>$allType,
            'products'=>$products,
            'nametype'=>$type->name,
            'count'=>$count
        ];

        return $this->loadView('type',$result, $type->name );
    }

    // function AjaxCategories(){
    //     $alias = $_GET['alias'];
    //     $model = new TypeModel;
    //     $products = $model->selectProductLevel2($alias,-1,-1);
    //     $data = [
    //         'products'=>$products,
    //         'alias'=>$alias
    //     ];
    //     return $this->loadViewAjax('category',$data);
    // }
}



?>