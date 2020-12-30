<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\BaseModel.php');
    class TypeModel extends BaseModel{
        function getProductsByType($id){
            $sql = "SELECT * FROM products WHERE cate_code='$id'";
           return  $this->loadMoreRows($sql);
        }
    }
   

?>