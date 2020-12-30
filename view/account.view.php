<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once 'model/AccountModel.php';
$user=$_SESSION['customer'];
$model=new AccountModel;
$districts=$model->getDistricts();
?>
<style>
#tblAccount{width:70%;}
#tblAccount select,#tblAccount input{width:100%;height:30px;}
button{padding:7px;}
</style>
<section class="main-container col2-right-layout">
  <div class="main container">
    <div class="row">
      <div class="col-main col-sm-12 col-xs-12">
        <div class="page-content checkout-page">
			<div class="page-title">
			  <h2>Thông tin khách hàng</h2>
			</div>
			<table id='tblAccount'>
				<tr>
					<td width='30%'>Tên đăng nhập</td>
					<td><input id='username' type='text' readonly value='<?=$user->username?>' disabled /></td>
				</tr>
				<tr>
					<td>Họ tên</td>
					<td><input id='name' type='text' value='<?=$user->name?>'/></td>
				</tr>
				<tr>
					<td>Giới tính</td>
					<td>
						<select id='gender'>
							<option value="Nam" <?php if ($user->gender=='Nam') echo "selected"; ?>>Nam</option>
							<option value="Nữ" <?php if ($user->gender=='Nữ') echo "selected"; ?>>Nữ</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Mật khẩu</td>
					<td><input id='password' type='password' value='<?=$user->password?>'/></td>
				</tr>
				<tr>
					<td>Nhập lại mật khẩu</td>
					<td><input id='password2' type='password' value='<?=$user->password?>'/></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><input id='email' type='text' value='<?=$user->email?>' /></td>
				</tr>
				<tr>
					<td>Số nhà + tên đường</td>
					<td><input id='address' type='text' value='<?=$user->address?>' /></td>
				</tr>
				<tr>
					<td>Quận/huyện</td>
					<td>
					<select id='district_code' >
					<?php
						foreach($districts as $district) {
							if ($district->district_code==$user->district_code)
								echo "<option value='".$district->district_code."' selected>".$district->name."</option>";
							else
								echo "<option value='".$district->district_code."'>".$district->name."</option>";
						}
					?>
					</select>
					</td>
				</tr>
				<tr>
					<td>Số điện thoại</td>
					<td><input id='phone' type='text' value='<?=$user->phone?>' /></td>
				</tr>
				<tr>
					<td></td>
					<td colspan=><button id='btnUpdate'>Cập nhật</button></td>
				</tr>
			</table>
        </div>
      </div>

    </div>
  </div>
  </section>
<script src="../helmet_shop/admin/libraries/js/my-utilities.js"></script>
<script>
$(document).ready(function() {
	let username=$("#username").val();
	let fullname=$("#name").val();
	let gender=$("#gender").val();
	let password=$("#password").val();
	let password2=$("#password2").val();
	let email=$("#email").val();
	let address=$("#address").val();
	let district_code=$("#district_code").val();
	let phone=$("#phone").val();
	let original_info=username+fullname+gender+password+password2+email+address+district_code+phone;

	$("#btnUpdate").click(function(e) {
		let username=$("#username").val();
		let fullname=$("#name").val();
		let gender=$("#gender").val();
		let password=$("#password").val();
		let password2=$("#password2").val();
		let email=$("#email").val();
		let address=$("#address").val();
		let district_code=$("#district_code").val();
		let phone=$("#phone").val();
		let new_info=username+fullname+gender+password+password2+email+address+district_code+phone;
		if (new_info==original_info) {
			alert("Không có gì thay đổi!");
			return;
		}
		let error="";

		error+=validateUsername(username);
		error+=validateName(fullname);
		error+=validateAddress(address);
		error+=validatePhone(phone);
		error+=validatePassword(password);
		error+=(password2!=password)?"Mật khẩu nhập lại không khớp!\n":"";
		error+=validateEmail(email);

		if (error!="") {
			alert(error);
			return;
		}
		$.ajax({
			url: "account.php",
			type: "POST",
			data: {
				action: "updateAccount",
				username:username,
				name:fullname,
				gender:gender,
				password:password,
				email:email,
				address:address,
				district_code:district_code,
				phone:phone
			},
			success: function(res) {
				if (res!="ok") {
					alert(res);
					return;
				}
				alert("Cập nhật thành công!");
				location.reload();
			}
		});
	});
});
</script>