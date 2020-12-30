<?php
include_once 'Controller.php';
include_once 'model/HomeModel.php';

class HomeController extends Controller{

    function getHomePage(){
        $model = new HomeModel;
		$date=date("Y-m-d");
		$featuredProduct = $model->selectFeaturedProduct($date);
		$bestSellers = $model->selectBestSeller($date);
		$newProducts = $model->selectNewProduct();
        $data = [
            'featuredProduct'=>$featuredProduct,
            'bestSellers' => $bestSellers,
            'newProducts' => $newProducts
        ];
        return $this->loadView('home',$data);
    }
}

?>