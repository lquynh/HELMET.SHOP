<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
	include_once('..\model\UserModel.php');
	if (!isset($_SESSION['login_email'])) header("location:../login.php");
    $model=new UserModel;
    $info=$model->getEmployee($_SESSION['login_id']);
    //print_r($info);
?>
<head>
    <title>Tài khoản của tôi</title>
    <?php include_once('..\views\common.php'); ?>
<style>
#old_password,#new_password_1{margin-bottom:5px;}
#tblMyAccount td:nth-child(1){font-weight:bold;}
#tblMyAccount input,#tblMyAccount select{width:50%;}
</style>
</head>
<body>
    <section id="container">
	<?php include_once('header.php')?>
	<?php if(isset($_SESSION['login_email'])) include_once('menu.php'); ?>
	<section id="main-content">
	<section class="wrapper">
    <div class="panel panel-body">
        <section class="content">
            <div class="panel panel-default">
				<div class="panel-heading">
					<b>Thông tin tài khoản</b>
				</div>
				<div class="panel-body">
                <table id='tblMyAccount' class='table table-bordered table-hover'>
                    <tr>
                        <td width='15%'>Tên đăng nhập</td>
                        <td><input disabled id='username' type='text' value='<?=$info->username?>'/></td>
                    </tr>
                    <tr>
                        <td>Đổi mật khẩu</td>
                        <td>
                            <input id='old_password' type='password' placeholder='Mật khẩu cũ' value=''/><br/>
                            <input id='new_password_1' type='password' placeholder='Mật khẩu mới' value=''/><br/>
                            <input id='new_password_2' type='password' placeholder='Nhập lại mật khẩu mới' value=''/>
                        </td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><input disabled id='email' type='text' value='<?=$info->email?>'/></td>
                    </tr>
                    <tr>
                        <td>Họ tên</td>
                        <td><input disabled id='name' type='text' value='<?=$info->name?>'/></td>
                    </tr>
                    <tr>
                        <td>Giới tính</td>
                        <td>
                            <select id='gender' disabled>
                                <?php
                                    echo "<option value='Nam' ";
                                    echo $info->gender=='Nam'?"selected":"";
                                    echo ">Nam</option>";

                                    echo "<option value='Nữ'";
                                    echo $info->gender=='Nữ'?"selected":"";
                                    echo ">Nữ</option>";
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Địa chỉ</td>
                        <td><input disabled id='address' type='text' value='<?=$info->address?>'/></td>
                    </tr>
                    <tr>
                        <td>Số điện thoại</td>
                        <td><input disabled id='phone' type='text' value='<?=$info->phone?>'/></td>
                    </tr>
                    <tr>
                        <td>Quyền</td>
                        <td><span id='role'><?=$info->role_name?></span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><button id='btnUpdateInfo'><i class="fa fa-floppy-og"></i> Cập nhật</button></td>
                    </tr>
                </table>
				</div>
            </div>
        </section>
    </div>
</section>
</section>
<?php include_once('footer.php'); ?>
</section>

<script>
$(document).ready(function(e) {
    let username=$("#username").val();
    let old_password=$("#old_password").val();
    let new_password_1=$("#new_password_1").val();
    let new_password_2=$("#new_password_2").val();
    let email=$("#email").val();
    let name=$("#name").val();
    let gender=$("#gender").val();
    let address=$("#address").val();
    let phone=$("#phone").val();

    let original_info=username+old_password+new_password_1+new_password_2+email+name+gender+address+phone;

    async function checkOldPassword(old_password) {
        let result = await $.ajax({
            url: "controller/UserController.php",
            type: "POST",
            data: {
                action: "checkOldPassword",
                old_password: old_password,
                id: <?=$_SESSION['login_id']?>
            }
        });
        return result;
    }

    async function updateAccountInfo(username,password,email,name,gender,address,phone) {
        let result = await $.ajax({
            url: "controller/UserController.php",
            type: "POST",
            data: {
                action: "updateMyAccount",
                id: <?=$_SESSION['login_id']?>,
                username: username,
                password: password,
                email: email,
                name: name,
                gender: gender,
                address: address,
                phone: phone
            }
        });
        return result;
    }

    $("#btnUpdateInfo").click(async function(e) {
        username=$("#username").val().trim();
        old_password=$("#old_password").val().trim();
        new_password_1=$("#new_password_1").val().trim();
        new_password_2=$("#new_password_2").val().trim();
        email=$("#email").val().trim();
        name=$("#name").val().trim();
        gender=$("#gender").val();
        address=$("#address").val().trim();
        phone=$("#phone").val().trim();

        let new_info=username+old_password+new_password_1+new_password_2+email+name+gender+address+phone;
        if (new_info===original_info) {
            alert("Không có gì thay đổi!");
            return false;
        }

        let error="";
        error+=validateUsername(username);
        error+=validateEmail(email);
        error+=validateName(name);
        error+=validateAddress(address);
        error+=validatePhone(phone);

        if (old_password !== "" || new_password_1 !== "" || new_password_2 !== "") {
            if (new_password_2 != new_password_1) {
                alert("Mật khẩu mới và mật khẩu nhập lại không giống nhau!");
                return false;
            }
            if (old_password==="") {
                alert("Bạn chưa nhập mật khẩu cũ!");
                return false;
            }
            let res = await checkOldPassword(old_password);
            if (res != "ok") {
                alert(res);
                return false;
            }
            error+=validatePassword(new_password_1);
        }

        if (error !== "") {
            alert(error);
            return false;
        }

        let res = await updateAccountInfo(username,new_password_1,email,name,gender,address,phone);
        if (res != "ok") {
            alert(res);
            return false;
        }
        alert("Cập nhật thành công!");
        location.reload();
    });

});
</script>
</body>
</html>