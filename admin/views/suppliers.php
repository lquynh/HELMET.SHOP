<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once('..\model\UserModel.php');
if (!isset($_SESSION['login_email'])) header("location:../login.php");
$model=new UserModel;
$suppliers=$model->getSuppliers();
?>
<head>
    <title>Danh sách NCC</title>
    <?php include_once('..\views\common.php'); ?>
<style>
#editNCC input{width:100%;}
#editNCC td:nth-child(1){font-weight:bold;width:150px;}
#btnAddSupplier{margin-bottom:7px;}
</style>
</head>
<body>
	<div id='editNCC' class='absolute_center'>
		<div class="panel-heading">
			<b id='editNCCHeader'></b>
		</div><br />
		<table class='table table-bordered table-hover'>
			<tr>
				<td>Mã NCC</td>
				<td><input id='editSuppCode' type='text'/></td>
			</tr>
			<tr>
				<td>Tên</td>
				<td><input id='editName' type='text'/></td>
			</tr>
			<tr>
				<td>Địa chỉ</td>
				<td><input id='editAddress' type='text'/></td>
			</tr>
			<tr>
				<td>Email</td>
				<td><input id='editEmail' type='text'/></td>
			</tr>
			<tr>
				<td>Số điện thoại</td>
				<td><input id='editPhone' type='text'/></td>
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
					<b>Danh sách Nhà cung cấp</b>
				</div>
				<div class="panel-body">
				<button id='btnAddSupplier'><i class="fa fa-plus"></i> Thêm NCC</button><br/>
				<?php
					echo "<table class='table table-bordered table-hover'>";
					echo "<thead>";
					echo "<tr>";
					echo "<th>Mã NCC</th>";
					echo "<th>Tên</th>";
					echo "<th>Địa chỉ</th>";
					echo "<th>Email</th>";
					echo "<th>SĐT</th>";
					echo "<th>Tùy chọn</th>";
					echo "</tr>";
					echo "</thead>";
					foreach ($suppliers as $s) {
						echo "<tr>";
						echo "<td>".$s->supp_code."</td>";
						echo "<td>".$s->name."</td>";
						echo "<td>".$s->address."</td>";
						echo "<td>".$s->email."</td>";
						echo "<td>".$s->phone."</td>";
						echo "<td><span class='optionButtons'>";
						echo "<button class='btn btn-success btnEdit' supp_code='$s->supp_code' name='$s->name' address='$s->address' email='$s->email' phone='$s->phone'>Sửa</button>";
						echo "<button supp_code='$s->supp_code' name='$s->name' class='btn btn-danger btnDelete'>Xóa</button>";
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
	
	$("#btnAddSupplier").click(function(e) {
		editMode=ADD;
		$("#editSuppCode").val("");
		$("#editName").val("");
		$("#editAddress").val("");
		$("#editEmail").val("");
		$("#editPhone").val("");
		
		$("#editNCC").css("display", "block");
		$("#editSuppCode").prop("disabled", "");
		$("#editSuppCode").prop("readonly", "");
		$("#editNCCHeader").text("Thêm NCC mới");
		$("#btnEdit").text("Thêm");
	});
	
	$(".btnEdit").click(function(e) {
		editMode=EDIT;
		let supp_code=$(this).attr("supp_code");
		let name=$(this).attr("name");
		let address=$(this).attr("address");
		let email=$(this).attr("email");
		let phone=$(this).attr("phone");
		
		original_info=supp_code+name+address+email+phone;
		
		$("#editSuppCode").val(supp_code);
		$("#editName").val(name);
		$("#editAddress").val(address);
		$("#editEmail").val(email);
		$("#editPhone").val(phone);
		
		$("#editNCC").css("display", "block");
		$("#editSuppCode").prop("disabled", "disabled");
		$("#editSuppCode").prop("readonly", "readonly");
		$("#editNCCHeader").text("Chỉnh sửa thông tin nhân viên");
		$("#btnEdit").text("Cập nhật");
	});

	$(".btnDelete").click(function(e) {
		let supp_code=$(this).attr("supp_code");
		let name=$(this).attr("name");
		let ans=confirm("Bạn có thật sự muốn xóa '"+name+"'?");
		if (!ans) return;
		$.ajax({
			url: "controller/UserController.php",
			type: "POST",
			data: {
				action: "deleteNCC",
				supp_code: supp_code
			},
			success: function(res) {
				if (res!="ok"){
					alert(res);
					return;
				}
				alert("Xóa '"+name+"' thành công!");
				location.reload();
			}
		});
	});
	
	$("#btnEdit").click(function(e) {
		let supp_code=$("#editSuppCode").val();
		let name=$("#editName").val();
		let address=$("#editAddress").val();
		let email=$("#editEmail").val();
		let phone=$("#editPhone").val();
		
		new_info=supp_code+name+address+email+phone;
		if ((original_info!="" && new_info!="") && original_info==new_info) {
			alert("Không có gì thay đổi!");
			return false;
		}
		
		let error="";
		error+=validateSuppCode(supp_code);
		error+=validateSuppName(name);
		error+=validateAddress(address);
		error+=validateEmail(email);
		error+=validatePhone(phone);
		
		if (error != "") {
			alert(error);
			return false;
		}
		
		let action = (editMode == EDIT) ? "editNCC" : "addNCC";
		$.ajax({
			url: "controller/UserController.php",
			type: "POST",
			data: {
				action: action,
				supp_code: supp_code,
				name: name,
				address: address,
				email: email,
				phone: phone
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
	
	// $(".btnDelete").click(function(e) {
		// let username=$(this).attr("username");
		// let ans=confirm("Bạn có thật sự muốn khóa '"+username+"'?");
		// if (!ans) return false;
		// $.ajax({
			// url: "controller/UserController.php",
			// type: "POST",
			// data: {
				// action: "deleteNhanVien",
				// username: username
			// },
			// success: function(res) {
				// if (res=='ok') {
					// alert("Khóa '"+username+"' thành công!");
					// location.reload();
				// }
			// }
		// });
	// });
	
	$("#btnCloseEdit").click(function(e) {
		let editNCC=$("#editNCC");
		if ($(editNCC).css("display")=="block") {
			$(editNCC).css("display","none");
			editMode="";
		}
	});
</script>
</body>
</html>