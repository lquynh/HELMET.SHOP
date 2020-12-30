<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
	include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\UserModel.php');
	if (!isset($_SESSION['login_email'])) 
		header("location:../login.php");
	$model=new UserModel;
	$customers=$model->getCustomers();
	$districts=$model->getDistricts();
	// print_r($customers);
?>
<head>
    <title>Danh sách khách hàng</title>
    <?php include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\views\common.php'); ?>
<style>
#editKhachHang td:nth-child(1){font-weight:bold;width:150px;}
#editKhachHang input,#editKhachHang select{width:100%;}
#btnAddCustomer{margin-bottom:7px;}
</style>
</head>
<body>
	<div id='editKhachHang' class='absolute_center'>
		<div class="panel-heading">
			<b id='editKhachHangHeader'></b>
		</div><br />
		<table class='table table-bordered table-hover'>
			<tr>
				<td>Username</td>
				<td><input id='editUsername' type='text'/></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input id='editPassword' type='password'/></td>
			</tr>
			<tr>
				<td>Email</td>
				<td><input id='editEmail' type='text'/></td>
			</tr>
			<tr>
				<td>Họ tên</td>
				<td><input id='editName' type='text'/></td>
			</tr>
			<tr>
				<td>Giới tính</td>
				<td>
					<select id='editGender'>
						<option value='Nam'>Nam</option>
						<option value='Nữ'>Nữ</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Địa chỉ</td>
				<td><input id='editAddress' type='text'/></td>
			</tr>
			<tr>
				<td>Quận/huyện</td>
				<td>
					<select id='editDistrict'>
					<?php
					foreach($districts as $d)
						echo "<option value='$d->district_code'>$d->name</option>";
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Số điện thoại</td>
				<td><input id='editPhone' type='text' maxlength="10"/></td>
			</tr>
			<tr>
				<td>Status</td>
				<td>
					<select id='editStatus'>
						<option value='0'>Ngưng hoạt động</option>
						<option value='1'>Hoạt động</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><button id='btnEdit'>Cập nhật</button></td>
			</tr>
		</table>
		<button id='btnCloseEdit' class='btnClose'><i class="fa fa-times"></i> Đóng</button>
	</div>
    <section id="container">
	<?php include_once('header.php')?>
	<?php if(isset($_SESSION['login_email'])) include_once('menu.php'); ?>
	<section id="main-content">
	<section class="wrapper">
    <div class="panel panel-body">
        <section class="content">
            <div class="panel panel-default">
				<div class="panel-heading">
					<b>Danh sách khách hàng</b>
				</div>
				<div class="panel-body">
				<button id='btnAddCustomer'><i class="fa fa-plus"></i> Thêm khách hàng</button><br/>
				<?php 
					echo "<table class='table table-bordered table-hover'>";
					echo "<thead>";
					echo "<tr>";
					echo "<th style='text-align:center;'>Username</th>";
					echo "<th style='text-align:center;'>Email</th>";
					echo "<th style='text-align:center;'>Họ tên</th>";
					echo "<th style='text-align:center;'>Giới tính</th>";
					echo "<th style='text-align:center;'>Địa chỉ</th>";
					echo "<th style='text-align:center;'>Quận</th>";
					echo "<th style='text-align:center;'>SĐT</th>";
					echo "<th style='text-align:center;'>Status</th>";
					echo "<th style='text-align:center;'>Tùy chọn</th>";
					echo "</tr>";
					echo "</thead>";
					foreach ($customers as $c) {
						echo "<tr>";
						echo "<td><b>".$c->username."</b></td>";
						echo "<td>".$c->email."</td>";
						echo "<td>".$c->name."</td>";
						echo "<td>".$c->gender."</td>";
						echo "<td>".$c->address."</td>";
						echo "<td>".$c->district_name."</td>";
						echo "<td>".$c->phone."</td>";
						$status=($c->status==1)?"Hoạt động":"Ngừng hoạt động";
						echo "<td>".$status."</td>";
						echo "<td><span class='optionButtons'><button class='btn btn-sm btn-success btnEdit' username='$c->username' password='$c->password' email='$c->email' name='$c->name' gender='$c->gender' address='$c->address' district_code='$c->district_code' phone='$c->phone' status='$c->status'>Sửa</button>";
						if ($c->status==1)
							echo "<button username='$c->username' class='btn btn-sm btn-danger btnLock'>Khóa</button>";
						echo "<button id_customer='$c->id' username='$c->username' class='btn btn-sm btn-danger btnDelete'>Xóa</button>";
						echo "</span></td>";
						echo "</tr>";
					}
					echo "</table>";
				?>
				</div>
            </div>
        </section>
    </div>
</section>
</section>
<?php include_once('footer.php'); ?>
</section>

<script>
	let original_info="";
	let new_info="";
	
	const ADD="add";
	const EDIT="edit";
	let editMode="";//'add' or 'edit'
	
	$("#btnAddCustomer").click(function(e) {
		editMode=ADD;
		$("#editUsername").val("");
		$("#editPassword").val("");
		$("#editEmail").val("");
		$("#editName").val("");
		$("#editGender").val("Nam");
		$("#editAddress").val("");
		$("#editDistrict option:first").val();
		$("#editPhone").val("");
		$("#editStatus").val("0");
		
		$("#editKhachHang").css("display", "block");
		$("#editUsername").prop("disabled", "");
		$("#editUsername").prop("readonly", "");
		$("#editKhachHangHeader").text("Thêm khách hàng mới");
		$("#btnEdit").text("Thêm");
	});
	
	$(".btnEdit").click(function(e) {
		editMode=EDIT;
		let username=$(this).attr("username");
		let password=$(this).attr("password");
		let email=$(this).attr("email");
		let name=$(this).attr("name");
		let gender=$(this).attr("gender");
		let address=$(this).attr("address");
		let district_code=$(this).attr("district_code");
		let phone=$(this).attr("phone");
		let status=$(this).attr("status");
		
		original_info=username+password+email+name+gender+address+district_code+phone+status;
		
		$("#editUsername").val(username);
		$("#editPassword").val(password);
		$("#editEmail").val(email);
		$("#editName").val(name);
		$("#editGender").val(gender);
		$("#editAddress").val(address);
		$("#editDistrict").val(district_code);
		$("#editPhone").val(phone);
		$("#editStatus").val(status);
		
		$("#editKhachHang").css("display", "block");
		$("#editUsername").prop("disabled", "disabled");
		$("#editUsername").prop("readonly", "readonly");
		$("#editKhachHangHeader").text("Chỉnh sửa thông tin khách hàng");
		$("#btnEdit").text("Cập nhật");
	});
	
	$("#btnEdit").click(function(e) {
		let username=$("#editUsername").val();
		let password=$("#editPassword").val();
		let email=$("#editEmail").val();
		let name=$("#editName").val();
		let gender=$("#editGender").val();
		let address=$("#editAddress").val();
		let district_code=$("#editDistrict").val();
		let phone=$("#editPhone").val();
		let status=$("#editStatus").val();
		
		new_info=username+password+email+name+gender+address+district_code+phone+status;
		if (original_info==new_info) {
			alert("Không có gì thay đổi!");
			return false;
		}
		
		let error="";
		error+=validateUsername(username);
		error+=validateName(name);
		error+=validateAddress(address);
		error+=validatePhone(phone);
		error+=validatePassword(password);
		error+=validateEmail(email);
		
		if (error != "") {
			alert(error);
			return false;
		}

		let action = (editMode == EDIT) ? "editKhachHang" : "addKhachHang";
		$.ajax({
			url: "controller/UserController.php",
			type: "POST",
			data: {
				action: action,
				username: username,
				password: password,
				email: email,
				name: name,
				gender: gender,
				address: address,
				district_code: district_code,
				phone: phone,
				status: status
			},
			success: function(res) {
				if (res!="ok") {
					alert(res);
					return;
				}
				let msg=(editMode==EDIT)?"Cập nhật thành công!":"Thêm thành công!";
				alert(msg);
				location.reload();
			}
		});
	});
	
	$(".btnLock").click(function(e) {
		let username=$(this).attr("username");
		let ans=confirm("Bạn có thật sự muốn khóa '"+username+"'?");
		if (!ans) return false;
		$.ajax({
			url: "controller/UserController.php",
			type: "POST",
			data: {
				action: "lockKhachHang",
				username: username
			},
			success: function(res) {
				if (res!="ok") {
					alert(res);
					return;
				}
				alert("Khóa '"+username+"' thành công!");
				location.reload();
			}
		});
	});

	$(".btnDelete").click(function(e) {
		let username=$(this).attr("username");
		let id_customer=$(this).attr("id_customer");
		let ans=confirm("Bạn có thật sự muốn xóa '"+username+"'?");
		if (!ans) return false;
		$.ajax({
			url: "controller/UserController.php",
			type: "POST",
			data: {
				action: "deleteKhachHang",
				id_customer: id_customer
			},
			success: function(res) {
				if (res!="ok") {
					alert(res);
					return;
				}
				alert("Xóa '"+username+"' thành công!");
				location.reload();
			}
		});
	});
	
	$("#btnCloseEdit").click(function(e) {
		let editKhachHang=$("#editKhachHang");
		if ($(editKhachHang).css("display")=="block") {
			$(editKhachHang).css("display","none");
			editMode="";
		}
	});
</script>
</body>
</html>