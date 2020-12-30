<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\BaseModel.php');
	include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
    class BillModel extends BaseModel{
		
        function getOrder($status){
			$sql="SELECT o.id,o.created_at,o.id_customer,o.name,o.address,o.district_code,o.phone,o.date_receive,o.id_employee,o.status,o.id_shipper,o.total,o.district_code,d.name AS district_name
				FROM orders o
				INNER JOIN district d ON o.district_code=d.district_code";
				
			if($status==ORDER_MYSHIPORDERS)
				$sql .= " INNER JOIN employee e ON o.id_shipper=e.id
				WHERE (o.status='".ORDER_WAITFORSHIPPER."'
				OR o.status='".ORDER_INDELIVERY."'
				OR o.status='".ORDER_FINISHED."')
				AND e.username='".$_SESSION['login_username']."'";
			else
				$sql .= " WHERE o.status='$status'";
			//print_r($sql);
			return  $this->loadMoreRows($sql);
        }

        function getProductDetail($status,$idOrder){
			if ($status==ORDER_MYSHIPORDERS)
				$sql="SELECT p.name,c.quantity_out
				FROM products p
				INNER JOIN
				(SELECT product_code,od.quantity_out FROM orders_detail od
				INNER JOIN orders o ON o.id=od.id_order
				INNER JOIN employee e ON o.id_shipper=e.id
				WHERE (o.status='".ORDER_WAITFORSHIPPER."'
				OR o.status='".ORDER_INDELIVERY."'
				OR o.status='".ORDER_FINISHED."')
				AND od.id_order='$idOrder'
				AND e.username='".$_SESSION['login_username']."') c 
				ON p.product_code=c.product_code";
			else
				$sql="SELECT p.name,c.quantity_out
				FROM products p
				INNER JOIN
				(SELECT product_code,od.quantity_out FROM orders_detail od
				INNER JOIN orders o
				ON o.id=od.id_order
				WHERE o.status='$status' AND od.id_order='$idOrder') c 
				ON p.product_code=c.product_code";
			// print_r($sql."<br />");
            return $this->loadMoreRows($sql);
        }

		function updateRevokeOrder($idOrder){
            $sql="UPDATE orders
            SET status='".ORDER_PENDING."',id_shipper='1'
            WHERE id='$idOrder'";
			//print_r($sql);
            return $this->executeQuery($sql);
        }

		function updateWaitForShipperOrder($idOrder,$idEmploy,$idShipper){
            $sql="UPDATE orders
            SET status='".ORDER_WAITFORSHIPPER."',id_employee='$idEmploy',id_shipper='$idShipper'
            WHERE id='$idOrder'";
            return $this->executeQuery($sql);
        }

		function updateInDeliveryOrder($idOrder,$idEmploy,$idShipper){
            $sql="UPDATE orders
            SET status='".ORDER_INDELIVERY."',id_employee='$idEmploy',id_shipper='$idShipper'
            WHERE id='$idOrder'";
            // print_r($sql);
            return $this->executeQuery($sql);
        }

        function updateFinishOrder($idOrder){
            $sql="UPDATE orders
            SET status='".ORDER_FINISHED."'
            WHERE id='$idOrder'";
            return $this->executeQuery($sql);
        }

		function updateCancelOrder($idOrder){
            $sql="UPDATE orders
            SET status='".ORDER_CANCELLED."'
            WHERE id='$idOrder'";
            return $this->executeQuery($sql);
        }

        function getShipper($district_code){
			// $sql="SELECT e.id,e.name,e.address,e.phone
			// FROM employee e
			// INNER JOIN division_detail d ON e.id=d.id_employee
			// WHERE e.id_role='".ID_ROLE_SHIPPER."'
			// AND d.district_code='$district_code'";
			$sql="CALL SP_GIAO_HANG('$district_code')";
            return $this->loadMoreRows($sql);
        }

        function getIdEmploy($email){
            $sql="SELECT *
            FROM employee
            WHERE email='$email'";
            return $this->loadOneRow($sql);
        }

        function getEmailCus($idCus){
            $sql="SELECT *
            FROM customers 
            WHERE id='$idCus'";
            return $this->loadOneRow($sql);
        }

        function getOrderDetail($idOrder){
            $sql="SELECT *  
            FROM orders_detail
            WHERE id_order='$idOrder'";
            return $this->loadMoreRows($sql);
        }
        function updateQuantiProduct($quanti,$idProduct){
            $sql = "UPDATE products SET quantity_exist='$quanti' WHERE product_code='$idProduct'";
            return $this->executeQuery($sql);
        }
        function getProduct($idProduct){
            $sql="SELECT *  
            FROM products
            WHERE product_code='$idProduct'";
            return $this->loadOneRow($sql);
        }

		function getBillInfo($idOrder) {
			$sql="SELECT O.id, P.name, D.price, D.quantity_out FROM orders O
			INNER JOIN orders_detail D
			INNER JOIN products P
			ON O.id = D.id_order AND D.product_code = P.product_code
			WHERE O.id='$idOrder'";
            return $this->loadMoreRows($sql);
		}

		function getBillDetail($idOrder) {
			$sql="SELECT O.id, e.name AS TENNV, O.name AS TENKH, O.phone, O.address, d.name AS district_name
			FROM orders O
			INNER JOIN employee e ON O.id_employee=e.id
            INNER JOIN district d ON O.district_code=d.district_code
			WHERE O.id='$idOrder'";
			return $this->loadOneRow($sql);
		}
		
		function getBillTotal($idOrder) {
			$sql="SELECT total FROM orders O WHERE O.id='$idOrder'";
            return $this->loadOneRow($sql);
		}
		
		function selectBill($idOrder) {
			$sql="SELECT * FROM bills WHERE bill_code='HD-$idOrder'";
            return $this->loadOneRow($sql);
		}
		
		function deleteBill($idOrder) {
			$sql="DELETE FROM bills WHERE bill_code='HD-$idOrder'";
			// print_r($sql);die;
            return $this->executeQuery($sql);
		}
		
		function insertBillInfo($idOrder,$created_at) {
			$receive=explode("-",$created_at);
			$sql="INSERT INTO bills(bill_code,created_at,id_order) VALUES('HD-$idOrder','$receive[2]-$receive[1]-$receive[0]','$idOrder')";
			// print_r($sql);
            return $this->executeQuery($sql);
		}
		
		function getEmployeeName($id) {
			$sql="SELECT name FROM employee WHERE id='$id'";
			return $this->loadOneRow($sql);
			
		}
    }
?>