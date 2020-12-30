<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\BaseModel.php');
    class MenuModel extends BaseModel{
        function getAllType(){
            $sql = "SELECT *
            FROM categories c
            WHERE c.status = '0'
            AND c.cate_code IN( SELECT p.cate_code FROM products p)";
           return  $this->loadMoreRows($sql);
        }
    }
?>