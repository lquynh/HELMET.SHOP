<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\BaseModel.php');
    class AddProductModel extends BaseModel{
        function insertProduct($id_type,$product_code,$name,$detail,$value,$img,$id_distributor,$quantity_exist,$date){
			if (trim($detail)=="") return false;
            $sql="INSERT INTO products (cate_code,name,description,price,image,new,quantity_exist,supp_code,product_code)
            VALUES ('$id_type','$name','$detail','$value','$img','1','$quantity_exist','$id_distributor','$product_code')";
            return $this->executeQuery($sql);
        }

        function checkProduct($product_code,$name) {
            $sql="SELECT * FROM products WHERE (product_code='$product_code' OR name='$name')";
            return $this->loadOneRow($sql);
        }

        function insertType($cate_code, $name){
            $sql= "INSERT INTO categories (cate_code,name)
                   VALUES ('$cate_code','$name')";
                   return $this->executeQuery($sql);
        }

        function checkType($cate_code,$name) {
            $sql="SELECT * FROM categories WHERE (cate_code='$cate_code' OR name='$name')";
            return $this->loadOneRow($sql);
        }

        function getDistributor(){
            $sql="SELECT * FROM suppliers";
            return $this->loadMoreRows($sql);
        }
    }
?>