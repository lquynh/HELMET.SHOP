<?php
    include_once('..\model\BaseModel.php');
    class DeleteProductModel extends BaseModel{
		function isTypeOkayToDelete($cate_code) {
			$sql="SELECT COUNT(name) AS PRODUCT_COUNT FROM products WHERE cate_code='$cate_code' AND status='0'";
			$count=$this->loadOneRow($sql);
			return $count->PRODUCT_COUNT==0?true:false;
		}
		function isProductOkayToDelete($product_code) {
			$sql = "SELECT * FROM products
			WHERE product_code='$product_code' AND quantity_exist='0'";
		   return  $this->loadOneRow($sql);
		}
        function deleteProduct($product_code){
            $sql = "UPDATE products
            SET status='1'
            WHERE product_code='$product_code'";
           return  $this->executeQuery($sql);
        }

        function deleteType($id){
            // $sql = "UPDATE categories
            //         SET status='1'
            //         WHERE cate_code='$id'";
            $sql = "DELETE FROM categories WHERE cate_code='$id'";
            return  $this->executeQuery($sql);
        }

        function updateProductAuto($id){
            $sql = "UPDATE products
                    SET cate_code='5'
                    WHERE cate_code='$id'";
            return  $this->executeQuery($sql);
        }
    }
?>