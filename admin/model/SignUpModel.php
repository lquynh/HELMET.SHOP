<?php
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\BaseModel.php');
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');

class SignUpModel extends BaseModel{
    function selectUser($email){
        $sql = "SELECT email FROM employee WHERE email='$email'";
       return  $this->loadOneRow($sql);
    }

	//TODO:
    // function insertUser($fullname,$address,$gender,$phone,$password,$email){
        // $sql = "INSERT INTO employee( username,fullname,address,gender,deleted,role,phone,password,email) VALUES ( '','$fullname', '$address', '$gender','0','1','$phone','$password','$email')";
        // return $this->executeQuery($sql);
    // }

    function selectStatus($username){
        $sql = "SELECT status FROM employ WHERE username='$username'";
        return  $this->loadOneRow($sql);
    }

    function selectLoginByEmail($email,$password){
		$sql="SELECT * FROM employee
		WHERE email='$email'
		AND password='$password'
		AND status=1";
		return  $this->loadOneRow($sql);
    }
	
	function selectLoginByUsername($username,$password){
		$sql="SELECT * FROM employee
		WHERE username='$username'
		AND password='$password'
		AND status=1";
		return  $this->loadOneRow($sql);
    }
}
?>