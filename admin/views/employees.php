<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
	include_once('..\model\UserModel.php');
	if (!isset($_SESSION['login_email'])) 
		header("location:../login.php");
	$model=new UserModel;
	$employees=$model->getEmployees();
	$roles=$model->getRoles();
?>
<head>
    <title>Danh sách nhân viên</title>
    <?php include_once('..\views\common.php'); ?>
<style>
#editNhanVien td:nth-child(1){font-weight:bold;width:150px;}
#editNhanVien input,#editNhanVien select{width:100%;}
#btnAddEmployee{margin-bottom:7px;}
</style>
</head>
<body>
	<div id='editNhanVien' class='absolute_center'>
		<div class="panel-heading">
			<b id='editNhanVienHeader'></b>
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
				<td>Số điện thoại</td>
				<td><input id='editPhone' type='text'/></td>
			</tr>
			<tr>
				<td>Quyền</td>
				<td>
					<select id='editRole'>
					<?php
						foreach($roles as $role)
							echo "<option value='$role->id_role'>".$role->name."</option>";
					?>
					</select>
				</td>
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
					<b>Danh sách nhân viên</b>
				</div>
				<div class="panel-body">
				<button id='btnAddEmployee'><i class="fa fa-plus"></i> Thêm nhân viên</button><br/>
				<?php
					echo "<table class='table table-bordered table-hover'>";
					echo "<thead>";
					echo "<tr>";
					echo "<th style='text-align:center;'>Username</th>";
					echo "<th style='text-align:center;'>Email</th>";
					echo "<th style='text-align:center;'>Họ tên</th>";
					echo "<th style='text-align:center;'>Giới tính</th>";
					echo "<th style='text-align:center;'>Địa chỉ</th>";
					echo "<th style='text-align:center;'>SĐT</th>";
					echo "<th style='text-align:center;'>Quyền</th>";
					echo "<th style='text-align:center;'>Status</th>";
					echo "<th style='text-align:center;'>Tùy chọn</th>";
					echo "</tr>";
					echo "</thead>";
					foreach ($employees as $e) {
						echo "<tr>";
						echo "<td><b>".$e->username."</b></td>";
						echo "<td>".$e->email."</td>";
						echo "<td>".$e->name."</td>";
						echo "<td>".$e->gender."</td>";
						echo "<td>".$e->address."</td>";
						echo "<td>".$e->phone."</td>";
						echo "<td>".$e->role_name."</td>";
						$status=($e->status==1)?"Hoạt động":"Ngừng hoạt động";
						echo "<td>".$status."</td>";
                        echo "<td><span class='optionButtons'>";
                        echo "<button class='btn btn-sm btn-success btnEdit' username='$e->username' password='$e->password' email='$e->email' name='$e->name' gender='$e->gender' address='$e->address' phone='$e->phone' id_role='$e->id_role' status='$e->status'>Sửa</button>";
                        if ($e->username!='admin') {
                            if ($e->status==1)
                                echo "<button username='$e->username' class='btn btn-sm btn-danger btnLock'>Khóa</button>";
                            echo "<button id_employee='$e->id' username='$e->username' class='btn btn-sm btn-danger btnDelete'>Xóa</button>";
                        }
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
	
	$("#btnAddEmployee").click(function(e) {
		editMode=ADD;
		$("#editUsername").val("");
		$("#editPassword").val("");
		$("#editEmail").val("");
		$("#editName").val("");
		$("#editGender").val("Nam");
		$("#editAddress").val("");
		$("#editPhone").val("");
		$("#editRole").val(3);
		$("#editStatus").val("0");
		
		$("#editNhanVien").css("display", "block");
		$("#editUsername").prop("disabled", "");
		$("#editUsername").prop("readonly", "");
		$("#editNhanVienHeader").text("Thêm nhân viên mới");
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
		let phone=$(this).attr("phone");
        let id_role=$(this).attr("id_role");
		let status=$(this).attr("status");

		original_info=username+password+email+name+gender+address+phone+id_role+status;

		$("#editUsername").val(username);
		$("#editPassword").val(password);
		$("#editEmail").val(email);
		$("#editName").val(name);
		$("#editGender").val(gender);
		$("#editAddress").val(address);
		$("#editPhone").val(phone);
		$("#editRole").val(id_role);
        $("#editStatus").val(status);

        if (username=="admin")
            $("#editStatus").attr("disabled", "disabled");
        else {
            if ($("#editStatus")[0].hasAttribute("disabled"))
                $("#editStatus").removeAttr("disabled");
        }

		$("#editNhanVien").css("display", "block");
		$("#editUsername").prop("disabled", "disabled");
		$("#editUsername").prop("readonly", "readonly");
		$("#editNhanVienHeader").text("Chỉnh sửa thông tin nhân viên");
		$("#btnEdit").text("Cập nhật");
	});

	$("#btnEdit").click(function(e) {
		let username=$("#editUsername").val();
		let password=$("#editPassword").val();
		let email=$("#editEmail").val();
		let name=$("#editName").val();
		let gender=$("#editGender").val();
		let address=$("#editAddress").val();
		let phone=$("#editPhone").val();
		let id_role=$("#editRole").val();
		let status=$("#editStatus").val();

		new_info=username+password+email+name+gender+address+phone+id_role+status;
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
		
		let action = (editMode == EDIT) ? "editNhanVien" : "addNhanVien";
		
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
				phone: phone,
				id_role: id_role,
				status: status
			},
			success: function(res) {
				if (res=="ok") {
					let msg=(editMode==EDIT)?"Cập nhật thành công!":"Thêm thành công!";
					alert(msg);
					location.reload();
				} else {
					alert(res);
				}
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
				action: "lockNhanVien",
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
		let id_employee=$(this).attr("id_employee");
		let ans=confirm("Bạn có thật sự muốn xóa '"+username+"'?");
		if (!ans) return false;
		$.ajax({
			url: "controller/UserController.php",
			type: "POST",
			data: {
				action: "deleteNhanVien",
				id_employee: id_employee
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
		let editNhanVien=$("#editNhanVien");
		if ($(editNhanVien).css("display")=="none") return;
		$(editNhanVien).css("display","none");
		editMode="";
	});
</script>
</body>
</html>