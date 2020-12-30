<?php
	include_once('..\model\BaseModel.php');
	include_once('..\helper\constants.php');

	class UserModel extends BaseModel {
		function checkMaNCC($supp_code){
			$sql = "SELECT * FROM suppliers WHERE supp_code='$supp_code'";
			return $this->loadOneRow($sql);
        }

        function checkTenNCC($name){
			$sql = "SELECT * FROM suppliers WHERE name='$name'";
			return $this->loadOneRow($sql);
		}

		function checkEmailNCC($email){
			$sql = "SELECT * FROM suppliers WHERE email='$email'";
			return $this->loadOneRow($sql);
		}

		function checkEmailNCCEdit($email,$supp_code){
			$sql = "SELECT * FROM suppliers WHERE (email='$email' AND supp_code!='$supp_code')";
			return $this->loadOneRow($sql);
		}

		function checkSdtNCC($phone){
			$sql = "SELECT * FROM suppliers WHERE phone='$phone'";
			return $this->loadOneRow($sql);
		}

		function checkSdtNCCEdit($phone,$supp_code){
			$sql = "SELECT * FROM suppliers WHERE (phone='$phone' AND supp_code!='$supp_code')";
			return $this->loadOneRow($sql);
		}

		function checkEmailEdit($email,$username,$table){
			$sql = "SELECT * FROM $table WHERE (email='$email' AND username!='$username')";
		   return $this->loadOneRow($sql);
		}

		function checkPhoneEdit($phone,$username,$table){
			$sql = "SELECT * FROM $table WHERE (phone='$phone' AND username!='$username')";
		   return $this->loadOneRow($sql);
		}

		function checkEmailAdd($email,$table){
			$sql = "SELECT * FROM $table WHERE email='$email'";
		   return $this->loadOneRow($sql);
		}

		function checkPhoneAdd($phone,$table){
			$sql = "SELECT * FROM $table WHERE phone='$phone'";
		   return $this->loadOneRow($sql);
		}

		function checkUsername($username,$table){
			$sql = "SELECT * FROM $table WHERE username='$username'";
			return $this->loadOneRow($sql);
        }

        function getEmployee($id) {
            $sql="SELECT e.id,e.username,e.email,e.password,e.name,e.gender,e.address,e.phone,r.name as role_name
            FROM employee e
            INNER JOIN role r ON e.id_role=r.id_role
            WHERE e.id='$id'";
            return $this->loadOneRow($sql);
        }

        function getEmployeePassword($id) {
            $sql="SELECT password FROM employee WHERE id='$id'";
            return ($this->loadOneRow($sql))->password;
        }

		function getEmployees() {
			$sql="SELECT e.id,e.username,e.password,e.name,e.email,e.address,e.gender,e.phone,e.status,e.id_role,r.name AS role_name
			FROM employee e
			INNER JOIN role r ON e.id_ROLE=r.id_role
			ORDER BY e.id_role";
			return  $this->loadMoreRows($sql);
		}

		function getCustomers() {
			$sql="SELECT c.id,c.username,c.password,c.name,c.email,c.address,c.district_code,c.gender,c.phone,c.status,d.name AS district_name
			FROM customers c
			INNER JOIN district d ON c.district_code=d.district_code
			ORDER BY c.username";
			return  $this->loadMoreRows($sql);
		}

		function getRoles() {
			$sql="SELECT * FROM role WHERE name!='Customer'";
			return $this->loadMoreRows($sql);
		}

		function addEmployee($username,$password,$email,$name,$gender,$id_role,$address,$phone,$status) {
			$sql="INSERT INTO employee(username,password,email,name,gender,id_role,address,phone,status) VALUES('$username','$password','$email','$name','$gender','$id_role','$address','$phone','$status')";
			return $this->executeQuery($sql);
		}

		function updateEmployee($username,$password,$email,$name,$gender,$id_role,$address,$phone,$status) {
			$sql="UPDATE employee SET password='$password',email='$email',name='$name',gender='$gender',id_role='$id_role',address='$address',phone='$phone',status='$status' WHERE username='$username'";
			return $this->executeQuery($sql);
        }

        function updateMyAccount($id,$username,$password,$email,$name,$gender,$address,$phone,$status) {
            $sql="UPDATE employee SET username='$username',email='$email',name='$name',gender='$gender',address='$address',phone='$phone',status='$status'";
            $sql.=($password=='')?"":",password='$password'";
            $sql.=" WHERE id='$id'";
			return $this->executeQuery($sql);
		}

		function deactivateEmployee($username) {
			$sql="UPDATE employee SET status='0' WHERE username='$username'";
			return $this->executeQuery($sql);
		}

		function deleteEmployee($id_employee) {
			$sql="DELETE FROM employee WHERE id='$id_employee'";
			return $this->executeQuery($sql);
		}

		function addCustomer($username,$password,$email,$name,$gender,$address,$district_code,$phone,$status) {
			$sql="INSERT INTO customers(username,password,email,name,gender,id_role,address,district_code,phone,status) VALUES('$username','$password','$email','$name','$gender','5','$address','$district_code','$phone','$status')";
			// print_r($sql);
			return $this->executeQuery($sql);
		}

		function updateCustomer($username,$password,$email,$name,$gender,$address,$district_code,$phone,$status) {
			$sql="UPDATE customers SET password='$password',email='$email',name='$name',gender='$gender',address='$address',district_code='$district_code',phone='$phone',status='$status' WHERE username='$username'";
			return $this->executeQuery($sql);
		}

		function deactivateCustomer($username) {
			$sql="UPDATE customers SET status='0' WHERE username='$username'";
			return $this->executeQuery($sql);
		}

		function deleteCustomer($id_customer) {
			$sql="DELETE FROM customers WHERE id='$id_customer'";
			return $this->executeQuery($sql);
		}

		function getDistricts() {
			$sql="SELECT * FROM district";
			return  $this->loadMoreRows($sql);
		}

		function getSuppliers() {
			$sql="SELECT * FROM suppliers";
			return  $this->loadMoreRows($sql);
        }

        function getSupplier($supp_code) {
			$sql="SELECT * FROM suppliers WHERE supp_code='$supp_code'";
			return  $this->loadOneRow($sql);
		}

		function addSupplier($supp_code,$name,$address,$email,$phone) {
			$sql="INSERT INTO suppliers(supp_code,name,address,email,phone,status) VALUES('$supp_code','$name','$address','$email','$phone','1')";
			return $this->executeQuery($sql);
		}

		function updateSupplier($supp_code,$name,$address,$email,$phone) {
			$sql="UPDATE suppliers SET email='$email',name='$name',address='$address',phone='$phone' WHERE supp_code='$supp_code'";
			return $this->executeQuery($sql);
		}

		function deleteSupplier($supp_code) {
			$sql="DELETE FROM suppliers WHERE supp_code='$supp_code'";
			return $this->executeQuery($sql);
		}

		function canDeleteEmployee($id_employee) {
			// comment, comment_employee, division_detail, import, orders, place_order, promotion, returns
			if ($this->loadOneRow("SELECT * FROM comment WHERE id_employee='$id_employee'")) return false;
			if ($this->loadOneRow("SELECT * FROM comment_employee WHERE id_employee='$id_employee'")) return false;
			if ($this->loadOneRow("SELECT * FROM division_detail WHERE id_employee='$id_employee'")) return false;
			if ($this->loadOneRow("SELECT * FROM import WHERE id_employee='$id_employee'")) return false;
			if ($this->loadOneRow("SELECT * FROM orders WHERE id_employee='$id_employee'")) return false;
			if ($this->loadOneRow("SELECT * FROM place_order WHERE id_employee='$id_employee'")) return false;
			if ($this->loadOneRow("SELECT * FROM promotion WHERE id_employee='$id_employee'")) return false;
			if ($this->loadOneRow("SELECT * FROM returning WHERE id_employee='$id_employee'")) return false;
			return true;
		}

		function canDeleteCustomer($id_customer) {
			// comment, comment_customer, orders
			if ($this->loadOneRow("SELECT * FROM comment WHERE id_customer='$id_customer'")) return false;
			if ($this->loadOneRow("SELECT * FROM comment_customer WHERE id_customer='$id_customer'")) return false;
			if ($this->loadOneRow("SELECT * FROM orders WHERE id_customer='$id_customer'")) return false;
			return true;
		}

		function canDeleteSupplier($supp_code) {
			// place_order, products
			if ($this->loadOneRow("SELECT * FROM place_order WHERE supp_code='$supp_code'")) return false;
			if ($this->loadOneRow("SELECT * FROM products WHERE supp_code='$supp_code'")) return false;
			return true;
		}

		function getDivision() {
			$sql="SELECT d.district_code,d.name AS district_name,e.id,e.username,e.name
			FROM division_detail dd
			INNER JOIN district d ON dd.district_code=d.district_code
			INNER JOIN employee e ON dd.id_employee=e.id
			ORDER BY d.district_code";
			return $this->loadMoreRows($sql);
		}

		function getShippers() {
			$sql="SELECT * FROM employee WHERE id_role='".ID_ROLE_SHIPPER."'";
			return $this->loadMoreRows($sql);
		}

		function checkDivisionEdit($district_code,$id_employee) {
			$sql="SELECT * FROM division_detail WHERE
			district_code='$district_code'
			AND id_employee='$id_employee'";
			return $this->loadOneRow($sql);
		}

		function updateDivision($district_code,$id_employee,$original_district_code,$original_id_employee) {
			$sql="UPDATE division_detail
			SET district_code='$district_code',id_employee='$id_employee'
			WHERE (district_code='$original_district_code' AND id_employee='$original_id_employee')";
			return $this->executeQuery($sql);
		}

		function addDivision($district_code,$id_employee) {
			$sql="INSERT INTO division_detail(district_code,id_employee)
			VALUES('$district_code','$id_employee')";
			return $this->executeQuery($sql);
		}

		function deleteDivision($district_code,$id_employee) {
			$sql="DELETE FROM division_detail WHERE district_code='$district_code' AND id_employee='$id_employee'";
			return $this->executeQuery($sql);
		}
	}
?>