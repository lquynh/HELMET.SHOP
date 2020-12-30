<?php
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\BaseModel.php');
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');

class StatisticalModel extends BaseModel{
	function getStatis($dateStart,$dateEnd){
		$start = explode("-", $dateStart);
		$end = explode("-", $dateEnd);
		// $sql="SELECT od.product_code, sum(od.quantity_out)sum,p.name 
		// FROM orders_detail od, orders o, products p 
		// WHERE od.id_order=o.id AND p.product_code = od.product_code AND o.status = '3' AND DATE(o.created_at) >= '$start[2]-$start[1]-$start[0]' AND DATE(o.created_at)<= '$end[2]-$end[1]-$end[0]' 
		// GROUP BY od.product_code 
        // ORDER BY od.product_code";
        $sql="CALL SP_SAN_PHAM_BAN('$start[2]-$start[1]-$start[0]','$end[2]-$end[1]-$end[0]')";
		return $this->loadMoreRows($sql);
	}

	function getStatisRevenueByMonth($dateStart,$dateEnd) {
		$start = explode("-",$dateStart);
		$end = explode("-",$dateEnd);
		// $sql="SELECT MONTH(created_at) AS THANG, YEAR(created_at) AS NAM, SUM(total) AS DOANHTHU
		// FROM orders as O
		// WHERE DATE(O.created_at)>='$start[2]-$start[1]-$start[0]' AND DATE(O.created_at)<='$end[2]-$end[1]-$end[0]' AND status='".ORDER_FINISHED."'
        // GROUP BY MONTH(created_at)";
        $sql="CALL SP_DOANH_THU('$start[2]-$start[1]-$start[0]','$end[2]-$end[1]-$end[0]')";
		return $this->loadMoreRows($sql);
	}
	
	function getStatisInventory($dateStart){
		$start = explode("-", $dateStart);
		$sql = "CALL SP_TON_KHO('$start[2]-$start[1]-$start[0]')";
		return $this->loadMoreRows($sql);
	}
	
	function getStatisInterest($dateStart){
		$start=explode("-",$dateStart);
		$sql="CALL SP_LOI_NHUAN('$start[2]-$start[1]-$start[0]')";
		return $this->loadMoreRows($sql);
	}
}


?>