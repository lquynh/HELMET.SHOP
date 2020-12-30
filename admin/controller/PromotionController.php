<?php
session_start();
if (!isset($_SESSION['login_email'])) header("location:../login.php");
include_once('..\model\PromotionModel.php');
require_once ('..\helper\constants.php');
$model=new PromotionModel;

if (isset($_POST['action']) && $_POST['action']=='addPromotion') {
    $promotion_code=$_POST['promotion_code'];
    $date_start=$_POST['date_start'];
    $date_end=$_POST['date_end'];
    $description=$_POST['description'];
    $id_employee=$_POST['id_employee'];
    $products_infos=$_POST['products_infos'];

    if ($model->isPromotionCodeExisting($promotion_code)) {
        echo "Mã khuyến mãi đã tồn tại!";
        return false;
    }
    if ($model->dateRangeOverlaps($date_start,$date_end)) {
        echo "Đã có khuyến mãi trong khoảng thời gian $date_start - $date_end!";
        return false;
    }

    $model->insertPromotion($promotion_code,$date_start,$date_end,$description,$id_employee);
    foreach(explode("|",$products_infos) as $promotion_detail) {
        $details=explode(DELIMITER,$promotion_detail);
        $product_code=$details[0];
        $percent=$details[1];
        $model->insertPromotionDetail($promotion_code,$product_code,$percent);
    }
    echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='updatePromotion') {
    $promotion_code=$_POST['promotion_code'];
    $date_start=$_POST['date_start'];
    $date_end=$_POST['date_end'];
    $description=$_POST['description'];
    $products_infos=$_POST['products_infos'];

    if ($model->dateRangeOverlapsEdit($promotion_code,$date_start,$date_end)) {
        echo "Đã có khuyến mãi trong khoảng thời gian $date_start - $date_end!";
        return false;
    }

    $model->updatePromotion($promotion_code,$date_start,$date_end,$description);
    $model->deletePromotionDetails($promotion_code);
    foreach(explode("|",$products_infos) as $promotion_detail) {
        $details=explode(DELIMITER,$promotion_detail);
        $product_code=$details[0];
        $percent=$details[1];
        $model->insertPromotionDetail($promotion_code,$product_code,$percent);
    }
    echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='deletePromotion') {
    $promotion_code=$_POST['promotion_code'];
    $model->deletePromotionDetails($promotion_code);
    $model->deletePromotion($promotion_code);
    echo "ok";
}
?>