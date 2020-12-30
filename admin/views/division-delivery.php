<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once('..\model\UserModel.php');
if (!isset($_SESSION['login_email'])) header("location:../login.php");
$model=new UserModel;
$divisions=$model->getDivision();
$districts=$model->getDistricts();
$shippers=$model->getShippers();
?>
<head>
    <title>Phân công giao hàng</title>
    <?php include_once('..\views\common.php'); ?>
<style>
#editPhanCong select{width:100%;}
#editPhanCong td:nth-child(1){font-weight:bold;width:150px;}
#btnAddDivision{margin-bottom:7px;}
</style>
</head>
<body>
	<div id='editPhanCong' class='absolute_center'>
		<div class="panel-heading">
			<b id='editPhanCongHeader'></b>
		</div><br />
		<table class='table table-bordered table-hover'>
			<tr>
				<td width="20%">Quận/Huyện</td>
				<td>
					<select id='editDistrictCode'>
					<?php
						foreach($districts as $d)
							echo "<option value='$d->district_code'>".$d->name."</option>";
					?>
					</select>
					<input type='hidden' id='original_district_code' />
				</td>
			</tr>
			<tr>
				<td>Nhân viên</td>
				<td>
					<select id='editIdEmployee'>
					<?php
						foreach($shippers as $s)
							echo "<option value='$s->id'>".$s->name." ($s->username)</option>";
					?>
					</select>
					<input type='hidden' id='original_id_employee' />
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
					<b>Danh sách phân công giao hàng</b>
				</div>
				<div class="panel-body">
				<button id='btnAddDivision'><i class="fa fa-plus"></i> Thêm phân công</button><br/>
				<?php
					echo "<table class='table table-bordered table-hover'>";
					echo "<thead>";
					echo "<tr>";
					echo "<th width='5%' style='text-align:center;'>STT</th>";
					echo "<th style='text-align:center;'>Quận/Huyện</th>";
					echo "<th style='text-align:center;'>Tên NV</th>";
					echo "<th width='10%' style='text-align:center;'>Tùy chọn</th>";
					echo "</tr>";
					echo "</thead>";
					$stt=1;
					foreach ($divisions as $d) {
						echo "<tr>";
						echo "<td style='text-align:center;'>".$stt++."</td>";
						echo "<td>".$d->district_name."</td>";
						echo "<td>$d->name <i>($d->username)</i></td>";
						echo "<td><span class='optionButtons'>";
						echo "<button class='btn btn-success btnEdit' district_code='$d->district_code' id_employee='$d->id'>Sửa</button>";
						echo "<button district_code='$d->district_code' id_employee='$d->id' district_name='$d->district_name' name='$d->name' class='btn btn-danger btnDelete'>Xóa</button>";
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

	$("#btnAddDivision").click(function(e) {
		editMode=ADD;
		$("#editDistrictCode").val("");
		$("#editIdEmployee").val("");

		$("#editPhanCong").css("display", "block");
		$("#editPhanCongHeader").text("Thêm phân công mới");
		$("#btnEdit").text("Thêm");
	});

	$(".btnEdit").click(function(e) {
		editMode=EDIT;
		let district_code=$(this).attr("district_code");
		let id_employee=$(this).attr("id_employee");
		$("#original_district_code").val(district_code);
		$("#original_id_employee").val(id_employee);

		original_info=district_code+id_employee;

		$("#editDistrictCode").val(district_code);
		$("#editIdEmployee").val(id_employee);

		$("#editPhanCong").css("display", "block");
		$("#editPhanCongHeader").text("Chỉnh sửa phân công");
		$("#btnEdit").text("Cập nhật");
	});

	$(".btnDelete").click(function(e) {
		let district_code=$(this).attr("district_code");
		let id_employee=$(this).attr("id_employee");
		let ans=confirm("Bạn có thật sự muốn xóa phân công này?");
		if (!ans) return;
		$.ajax({
			url: "controller/UserController.php",
			type: "POST",
			data: {
				action: "deletePhanCong",
				district_code: district_code,
				id_employee: id_employee
			},
			success: function(res) {
				if (res!="ok"){
					alert(res);
					return;
				}
				alert("Xóa thành công!");
				location.reload();
			}
		});
	});

	$("#btnEdit").click(function(e) {
		let district_code=$("#editDistrictCode").val();
		let id_employee=$("#editIdEmployee").val();
		let original_district_code=$("#original_district_code").val();
		let original_id_employee=$("#original_id_employee").val();

		new_info=district_code+id_employee;
		if (original_info===new_info) {
			alert("Không có gì thay đổi!");
			return false;
		}

		if (district_code==""||district_code==null) {
			alert("Bạn chưa chọn quận/huyện!");
			return;
		}

		if (id_employee==""||id_employee==null) {
			alert("Bạn chưa chọn nhân viên!");
			return;
		}

		let action = (editMode == EDIT) ? "editPhanCong" : "addPhanCong";
		$.ajax({
			url: "controller/UserController.php",
			type: "POST",
			data: {
				action: action,
				district_code: district_code,
				id_employee: id_employee,
				original_district_code: original_district_code,
				original_id_employee: original_id_employee
			},
			success: function(res) {
				if (res != "ok") {
					alert(res);
					return;
				}
				let msg=(editMode==EDIT)?"Cập nhật thành công!":"Thêm thành công!";
				alert(msg);
				location.reload();
			}
		});
	});

	$("#btnCloseEdit").click(function(e) {
		let editPhanCong=$("#editPhanCong");
		if ($(editPhanCong).css("display")=="block") {
			$(editPhanCong).css("display","none");
			editMode="";
		}
	});
</script>
</body>
</html>