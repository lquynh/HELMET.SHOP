<?php
include_once 'DBConnect.php';

class DetailModel extends DBConnect{

    function getDetailProduct($id,$date){
        $sql = "SELECT * 
                FROM promotion s 
                INNER JOIN promotion_detail sd 
                ON s.promotion_code = sd.promotion_code
                RIGHT JOIN products p 
                ON sd.product_code = p.product_code
                AND '$date' >= s.date_start AND '$date' <= s.date_end 
                WHERE p.product_code = '$id'";
        return $this->loadOneRow($sql);
    }

    function selectProductByType($idType,$id,$date){
        $sql = "SELECT * 
                FROM promotion s 
                INNER JOIN promotion_detail sd 
                ON s.promotion_code = sd.promotion_code 
                RIGHT JOIN products p 
                ON sd.product_code = p.product_code
                AND '$date' >= s.date_start AND '$date' <= s.date_end 
                WHERE p.cate_code = '$idType'
                AND p.product_code != '$id' AND p.status = '0'";
        return $this->loadMoreRows($sql);
    }

    function selectProductById($id,$date){
        $sql = "SELECT *
                FROM promotion s 
                INNER JOIN promotion_detail sd 
                ON s.promotion_code = sd.promotion_code 
                RIGHT JOIN products p 
                ON sd.product_code = p.product_code
                AND '$date' >= s.date_start AND '$date' <= s.date_end
                WHERE p.product_code = '$id'";
        return $this->loadOneRow($sql);
    }
	
	function getDistricts() {
		$sql = "SELECT * FROM district";
		return $this->loadMoreRows($sql);
	}

}

?>