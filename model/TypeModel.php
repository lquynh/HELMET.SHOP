<?php
require_once 'DBConnect.php';

class TypeModel extends DBConnect{

    function selectProductByCategories($id,$date){
        $sql = "SELECT * 
                FROM promotion s 
                INNER JOIN promotion_detail sd 
                ON s.promotion_code = sd.promotion_code
                RIGHT JOIN products p 
                ON sd.product_code = p.product_code
                AND '$date' >= s.date_start AND '$date' <= s.date_end 
                WHERE p.cate_code ='$id' AND p.status='0'";
        return $this->loadMoreRows($sql);
    }

    function countProduct($id){
        $sql = "SELECT COUNT(p.product_code)
        FROM products p 
         WHERE p.cate_code ='$id'";
        return $this->loadOneRow($sql);
    }

    function getNameType($id){
        $sql = "SELECT c.name
                FROM categories c 
                WHERE c.cate_code = '$id'";
        return $this->loadOneRow($sql);
    }

    function selectAllType(){
        $sql = "SELECT count(p.product_code) as soluong , c.name
                FROM products p
                INNER JOIN categories c 
                ON p.cate_code = c.cate_code 
                GROUP BY c.cate_code";
        return $this->loadMoreRows($sql);
    }
}



?>