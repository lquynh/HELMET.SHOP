<?php

include_once "HomeController.php";
include_once "model/SearchModel.php";
include_once "model/TypeModel.php";
include_once "model/DetailModel.php";

class SearchController extends Controller{
    function getSearch(){
        if(!isset($_GET['keyword']) || $_GET['keyword'] == ''){
            header('Location:index.php');
        }
        else if(isset($_GET['keyword'])){
            $model = new SearchModel;
            $date=date("Y-m-d");
            // $model1 = new TypeModel;
            // $allType = $model1->countProductByType();
            $keyword = trim($_GET['keyword']);
            $products = $model->findProducts($keyword,$date);
            // print_r($products);die;
            
            // $specialProduct = $model1->selectSpecialProduct();
            $data = [
                // 'allType'=>$allType,
                'products'=>$products
                // 'specialProduct' => $specialProduct,
            ];
            
        return $this->loadView('search',$data,'Search');
        }
    }
}

?>