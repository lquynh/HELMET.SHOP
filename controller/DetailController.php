<?php
require_once 'Controller.php';
require_once 'model/DetailModel.php';

class DetailController extends Controller {
    
    function getDetailPage(){
        $id = $_GET['alias'];
        $date=date("Y-m-d");
        $model = new DetailModel();
        $product = $model->getDetailProduct($id,$date);
        $type = $product->cate_code;
        $relatedProducts = $model->selectProductByType($type,$id,$date);
        if($product == ''){
            header('location:404.php');
        }
        $data = [
            'product'=>$product,
            'relatedProducts'=>$relatedProducts
        ];
        $this->loadView('detail',$data, $product->name);
    }

}







?>