<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\BaseModel.php');
    class EditProductModel extends BaseModel{
        function getProductsById($id){
            $sql = "SELECT * FROM products WHERE product_code='$id'";
           return  $this->loadOneRow($sql);
        }

        function getType(){
            $sql="SELECT * FROM categories WHERE status='0'";
            return $this->loadMoreRows($sql);
        }

        function checkProductNameEdit($product_code,$name) {
            $sql="SELECT * FROM products WHERE (name='$name' AND product_code!='$product_code')";
            return $this->loadOneRow($sql);
        }

        function updateProduct($product_code,$id_categories,$name,$detail,$price,$image,$new,$quantityExist,$date){
            $sql="UPDATE products
            SET cate_code='$id_categories',name='$name',description='$detail',price='$price',image='$image',new='$new',quantity_exist='$quantityExist'";

            if ($quantityExist>0)
                $sql.=",status='0'";

            $sql.=" WHERE product_code='$product_code'";
            return $this->executeQuery($sql);
        }

        function getTypeById($id){
            $sql="SELECT * FROM categories WHERE cate_code='$id'";
            return $this->loadOneRow($sql);
        }

        function updateType($id,$name){
            $sql="UPDATE categories
            SET name='$name'
            WHERE cate_code='$id'";
            return $this->executeQuery($sql);
        }
    }
   

?>