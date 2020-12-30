<?php
	include_once('..\model\BaseModel.php');
	include_once('..\helper\constants.php');

	class PromotionModel extends BaseModel {
		function getPromotions(){
			$sql = "SELECT p.promotion_code,p.date_start,p.date_end,p.description,p.id_employee,e.name AS employee_name
            FROM promotion p
            INNER JOIN employee e ON e.id=p.id_employee
            ORDER BY date_start DESC";
			return $this->loadMoreRows($sql);
        }

        function getPromotionDetails($promotion_code) {
            $sql = "SELECT p.name AS product_name,pd.percent,pd.product_code
            FROM promotion_detail pd
            INNER JOIN products p ON p.product_code=pd.product_code
            WHERE promotion_code='$promotion_code'";
            return $this->loadMoreRows($sql);
        }

        function isPromotionCodeExisting($promotion_code) {
            $sql="SELECT * FROM promotion WHERE promotion_code='$promotion_code'";
            return $this->loadOneRow($sql);
        }

        function dateRangeOverlaps($date_start,$date_end) {
            $start=explode("-",$date_start);
            $end=explode("-",$date_end);
            $sql="SELECT * FROM promotion WHERE (date_start<='$end[2]-$end[1]-$end[0]' AND date_end>='$start[2]-$start[1]-$start[0]')";
            return $this->loadOneRow($sql);
        }

        function dateRangeOverlapsEdit($promotion_code,$date_start,$date_end) {
            $start=explode("-",$date_start);
            $end=explode("-",$date_end);
            $sql="SELECT * FROM promotion
            WHERE (date_start<='$end[2]-$end[1]-$end[0]'
            AND date_end>='$start[2]-$start[1]-$start[0]')
            AND (promotion_code !='$promotion_code')";
            return $this->loadOneRow($sql);
        }

        function getProducts() {
            $sql="SELECT * FROM products";
            return $this->loadMoreRows($sql);
        }

        function insertPromotion($promotion_code,$date_start,$date_end,$description,$id_employee) {
            $start=explode("-",$date_start);
            $end=explode("-",$date_end);
            $sql="INSERT INTO promotion(promotion_code,date_start,date_end,description,id_employee) VALUES('$promotion_code','$start[2]-$start[1]-$start[0]','$end[2]-$end[1]-$end[0]','$description','$id_employee')";
            return $this->executeQuery($sql);
        }

        function insertPromotionDetail($promotion_code,$product_code,$percent) {
            $sql="INSERT INTO promotion_detail(promotion_code,product_code,percent) VALUES('$promotion_code','$product_code','$percent')";
            return $this->executeQuery($sql);
        }

        function updatePromotion($promotion_code,$date_start,$date_end,$description) {
            $start=explode("-",$date_start);
            $end=explode("-",$date_end);
            $sql="UPDATE promotion SET date_start='$start[2]-$start[1]-$start[0]',date_end='$end[2]-$end[1]-$end[0]',description='$description' WHERE promotion_code='$promotion_code'";
            return $this->executeQuery($sql);
        }

        function deletePromotion($promotion_code) {
            $sql="DELETE FROM promotion WHERE promotion_code='$promotion_code'";
            return $this->executeQuery($sql);
        }

        function deletePromotionDetails($promotion_code) {
            $sql="DELETE FROM promotion_detail WHERE promotion_code='$promotion_code'";
            return $this->executeQuery($sql);
        }

	}
?>