<?php
session_start();
include_once('..\model\StatisticalModel.php');
if (!isset($_SESSION['login_email'])) header("../location:login.php");
?>
<head>
	<title>Báo cáo lợi nhuận</title>
	<?php include_once('..\views\common.php');?>
</head>
<body>
	<section id="container">
	<?php include_once 'header.php'?>
	<?php if (isset($_SESSION['login_email'])) include_once 'menu.php'; ?>
	<section id="main-content">
		<section class="wrapper">
		<div class="panel panel-body" style="height:auto;">
			<center>
				<span>Đến ngày</span> <input type="text" id="start"/>
				<button type="submit" name="submit" class="btn btn-success" id="in">In</button>
			</center>
			<div id='report'></div>
		</div>
		</section>
	</section>

	<?php include_once('footer.php');?>

	</section>
	<script>
		function exportFileLoiNhuan() {
			loading_on();
			let data=$("#export").text();
			let reportTitle=$("#reportTitle").text();
			let created_by="<?=$_SESSION['login_name']?> (<?=$_SESSION['login_username']?>)";
			console.log(data);
			$.ajax({
				url: "controller/ExportFileController.php",
				type: "POST",
				data: {
					action: "exportFileLoiNhuan",
					data: data,
					reportTitle: reportTitle,
					created_by: created_by
				},
				success: function(res) {
					console.log(res);
					loading_off();
					location.href=res;
				}
			});
		}
		
		$(document).ready(function() {
			$("#start").datepicker({
				dateFormat: "d-m-yy"
			}).datepicker("setDate", "0");
			
			$("#in").click(function(){
				var dateStart=$('#start').val();
				
				if(dateStart==""){
					alert('Vui lòng chọn ngày!');
					return;
				}
				$.ajax({
					url: "controller/StatisticalController.php",
					type: "POST",
					data: {
						action: "loinhuan",
						start: dateStart
					},
					success: function(res) {
						console.log(res);
						$("#report").html(res);
					}
				});
			});
		});
	</script>
</body>
</html>
