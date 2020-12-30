<?php
include_once "DBConnect.php";
class HomeModel extends DBConnect{
	
	// SAN PHAM NOI BAT
    function selectFeaturedProduct($date){
        $sql = "SELECT *
        FROM promotion s
        INNER JOIN promotion_detail sd
            ON s.promotion_code= sd.promotion_code
        INNER JOIN products p
            ON sd.product_code = p.product_code
        AND '$date' >= s.date_start AND '$date' <=s.date_end
        WHERE p.new = 1 AND sd.percent <> 'null' AND p.status ='0'";
		
		/*
		$sql = "SELECT *
        FROM promotion s
        INNER JOIN promotion_detail sd
            ON s.promotion_code= sd.promotion_code
        INNER JOIN products p
            ON sd.product_code = p.product_code";*/
		
        return $this->loadMoreRows($sql);   
    }
	
	// SAN PHAM KHUYEN MAI
    function selectBestSeller($date){
        $sql = "SELECT *
        FROM promotion s
        INNER JOIN promotion_detail sd
            ON s.promotion_code = sd.promotion_code
        INNER JOIN products p
            ON sd.product_code = p.product_code
        AND '$date' >= s.date_start AND '$date' <= s.date_end
        WHERE  p.new <> 1 AND p.status = '0'";
		
		// $sql = "SELECT *
        // FROM promotion s
        // INNER JOIN promotion_detail sd
            // ON s.promotion_code = sd.promotion_code
        // INNER JOIN products p
            // ON sd.product_code = p.product_code
		// WHERE p.status='0'";
        
        return $this->loadMoreRows($sql);   
            
    }
    function selectNewProduct(){
        $sql = "SELECT * FROM products 
        WHERE new = 1 AND status = '0' AND product_code NOT IN(
        SELECT p.product_code
		FROM products p
		INNER JOIN promotion_detail sd
		ON sd.product_code = p.product_code
		WHERE p.new = 1)";
        return $this->loadMoreRows($sql);   
            
    }
}


?>