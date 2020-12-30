<?php
    session_start();
	if (!isset($_SESSION['login_email'])) header("location:../login.php");
	include_once('..\model\ImportModel.php');
	require_once ('..\helper\constants.php');
	$model=new ImportModel;
	$suppliers=$model->getSuppliers();
	$soluongmathang=10;
?>
<head>
    <title>Tạo đặt hàng</title>
    <?php include_once('..\views\common.php'); ?>
</head>
<style>
#themMatHang{color:green;cursor:pointer;}
#themMatHang:hover{color:red;}
input[type='text'],input[type='number']{width:120px;}
#tblProductsForFileExport td{border:1px solid #ccc;padding:7px;}
</style>
<body>

	<div id='formXuatFile' class='absolute_center'>
		<div class="panel-heading">
			<b>Tạo phiếu đặt hàng thành công</b>
		</div><br />
		<div id='importedProducts'>
			<table class='table table-bordered table-hover'>
				<thead>
					<tr>
						<th style='text-align:center;'>Số lượng</th>
						<th style='text-align:center;'>Sản phẩm</th>
						<th style='text-align:center;'>Đơn giá</th>
					</tr>
				</thead>
			</table>
		</div>
		<center><button id='btnExportFile'><i class='fa fa-upload'></i> Xuất file</button></center>
		<br />
		<button id='btnClose3' class='btnClose'><i class="fa fa-times"></i> Đóng</button>
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
					<b>Tạo đặt hàng</b>
				</div>
				<div class="panel-body">
						<table class='table table-bordered table-hover'>
							<tr>
								<td width='150px'>Mã phiếu đặt</td>
								<td>
									<input name='place_order_code' type='text' />
								</td>
							</tr>
							<tr>
								<td>Nhà cung cấp</td>
								<td>
									<select name='supp_code'>
										<option value=''></option>
										<?php foreach($suppliers as $s) {
											echo "<option value='".$s->supp_code."'>".$s->name."</option>";
										} ?>
									<select>
								</td>
							</tr>
							<tr>
								<td>Người tạo</td>
								<td><input type='hidden' name='id_employee' value='<?=$_SESSION['login_id']?>' readonly /><b id='name_employee'><?=$_SESSION['login_name']?></b></td>
							</tr>
							<tr>
								<td>Ngày đặt</td>
								<td><input type="text" id="date_created" name="created_at" readonly /></td>
							</tr>
							<tr>
								<td>Sản phẩm</td>
								<td>
									<div id='products'>
										<table class='table table-bordered table-hover'>
											<thead>
												<tr>
													<th style='text-align:center;'>Mặt hàng</th>
													<th style='text-align:center;'>Số lượng</th>
													<th style='text-align:center;'>Đơn giá (USD)</th>
													<th style='text-align:center;'>Tùy chọn</th>
												</tr>
											</thead>
										</table>
									</div>
									<center><button disabled id='btnThem'><i class='fa fa-plus'></i> Thêm sản phẩm</button></center></td>
							</tr>
							<tr>
								<td colspan="2" style="text-align:center;">
									<button id='btnDatHang' name="datHang"><i class='fa fa-plus'></i>&nbsp;Đặt hàng</button>
								</td>
							</tr>
						</table>
					<!--/form-->
				</div>
            </div>
        </section>
    </div>
</section>
</section>
<?php include_once('footer.php'); ?>
</section>
<script>
	$(document).ready(function(){

		let products="";
		function addNewRow() {
			let res="";
			res+="<tr class='products2order'>";
			res+="<td>"+products+"</td>";
			res+="<td><input class='qty' type='number' value='10' /></td>"
			res+="<td><input class='price' type='number' step='0.01' value='10.50'/></td>";
			res+="<td style='text-align:center;'><button class='btnXoa' onclick='$(this).parent().parent().remove();'><i class='fa fa-times'></i> Xóa</button></td>";
			res+="</tr>";
			return res;
		}

		$("select[name='supp_code']").change(function(e) {
			let supp_code=$(this).val();
			if (supp_code=="") {
				$(".products2order").remove();
				$("#btnThem").prop( "disabled", true );
				return false;
			}
			$.ajax({
				url: "controller/PlaceOrderController.php",
				type: "POST",
				dataType: "JSON",
				data: {
					action: "loadProductsBySuppCode",
					supp_code: supp_code
				},
				success: function(res) {
					console.log(res);
					products="";
					products+="<select name='product_code'>";
					for (var i=0;i<res.length;i++)
						products+="<option value='"+res[i].product_code+"'>"+res[i].product_code+": "+res[i].name+"</option>";
					products+="</select>";
					$(".products2order").remove();
					$("#btnThem").prop( "disabled", false );
				}
			});
		});

		$("#btnThem").click(function(e) {
			$("#products table thead").parent().append(addNewRow());
		});

		$("input[name='place_order_code']").val(generateRandomCode());

		let d=new Date();
		let current_date=d.getDate()+"-"+(d.getMonth()+1)+"-"+d.getFullYear();
		$("#date_created").val(current_date);

        function checkDuplicatedProducts(){
            let checks = {};
            $(".products2order").each(function(e) {
				let product_code=$($(this).find("select[name='product_code']")).val();
				if (checks[product_code] == undefined)
					checks[product_code] = 1;
				else
					checks[product_code]++;
			});
            console.log(checks);

            let duplicatedProduct = false;
			for (x in checks)
				if (checks[x]>1) {
					duplicatedProduct=true;
					break;
                }

            return duplicatedProduct?"Vui lòng không chọn sản phẩm giống nhau!\n":"";
        }

		$("#btnDatHang").click(function(e) {
			let place_order_code=$("input[name='place_order_code']").val();
			let supp_code=$("select[name='supp_code']").val();
			let supp_name=$("select[name='supp_code'] option:selected").text();
			let id_employee=$("input[name='id_employee']").val();
			let name_employee=$("#name_employee").text();
			let created_at=$("input[name='created_at']").val();
			let p="";
			let total=0;

			let errorLog="";
            errorLog+=($(".products2order").length==0)?"Vui lòng chọn ít nhất 1 sản phẩm!\n":"";
            errorLog+=checkDuplicatedProducts();
            errorLog+=(supp_code=="")?"Vui lòng chọn NCC\n":"";
			errorLog+=validatePlaceOrderCode(place_order_code);

			$(".products2order").each(function(e) {
				let product_code=$($(this).find("select[name='product_code']")).val();
				let qty=$($(this).find("input[class='qty']")).val();
				let price=$($(this).find("input[class='price']")).val();
				price=normalizePriceInput(price);
				$($(this).find("input[class='price']")).val(price);

				errorLog+=validateQuantity(qty);
				errorLog+=validatePriceInput(price);

				let delimiter="<?=DELIMITER?>";
				p+=product_code+delimiter+qty+delimiter+price+"|";
				total+=parseInt(qty)*parseFloat(price);
			});

			if (errorLog!="") {
				alert(errorLog);
				return false;
			}

			p=p.slice(0, -1);//remove the last character
			console.log(p);
			$.ajax({
				url: "controller/PlaceOrderController.php",
				type: "POST",
				data: {
					action: "datHang",
					supp_code: supp_code,
					place_order_code: place_order_code,
					id_employee: id_employee,
					created_at: created_at,
					products: p
				},
				success: function(res) {
					if (res!="ok"){
						alert(res);
						return;
					}
					$("#formXuatFile").css("display", "block");
					let html="<center><table id='tblProductsForFileExport' class='table table-bordered table-hover'>";
					html+="<thead>";
					html+="<tr>";
					html+="<th style='text-align:center;'>Mã SP</th>";
					html+="<th style='text-align:center;'>Tên SP</th>";
					html+="<th style='text-align:center;'>Số lượng</th>";
					html+="<th style='text-align:center;'>Đơn giá</th>";
					html+="<th style='text-align:center;'>Trị giá</th>";
					html+="</tr>";
					html+="</thead>";
					let total_price=0,total_qty=0;
					$(".products2order").each(function(e) {
						let product_code=$($(this).find("select[name='product_code']")).val();
						let name=$($(this).find("select option:selected")).text().split(":")[1].trim();
						let qty=$($(this).find("input[class='qty']")).val();
						let price=$($(this).find("input[class='price']")).val();
						html+="<tr class='products2export'>";
						html+="<td><span class='product_code'>"+product_code+"</span></td>";
						html+="<td><span class='name'>"+name+"</span></td>";
						html+="<td style='text-align:center;'><span class='qty'>"+qty+"</span></td>";
						html+="<td style='text-align:right;'><span class='price pricePick'>"+price+"</span></td>";
						html+="<td style='text-align:right;'><span class='price'>"+formatMoney((parseInt(qty)*parseFloat(price)).toFixed(2))+"</span></td>";
						total_price+=(parseFloat(price)*parseInt(qty));
						total_qty+=parseInt(qty);
						html+="</tr>";
					});
					html+="<tr><td></td><td></td><td style='text-align:center;'>"+total_qty+"</td><td></td><td style='text-align:right;'><span class='price'>"+formatMoney(total_price.toFixed(2))+"</span></td></tr>";
					html+="</table></center><br />";

					$("#importedProducts").html(html);
					let text="";
					text+="Mã phiếu đặt: <b class='place_order_code'>"+place_order_code+"</b><br/>";
					text+="Nhân viên tạo: <b class='name_employee'>"+name_employee+"</b><br/>";
					text+="Nhà cung cấp: <b class='supp_name'>"+supp_name+"</b> (<span class='supp_code'>"+supp_code+"</span>)<br/>";
					text+="Ngày tạo: <b class='created_at'>"+created_at+"</b><br/><br/>";
					$("#importedProducts").prepend(text);
				},
				error: function(res) {
					alert(res);
				}
			});
		});

		$("#btnClose3").click(function(e) {
			let formXuatFile=$("#formXuatFile");
			if ($(formXuatFile).css("display")=="block") {
				$(formXuatFile).css("display","none");
				location.href='views/list-orders.php?status=0';
			}
		});

		$("#btnExportFile").click(function(e) {
			loading_on();
			let p="",total=0;
			let place_order_code=$("#importedProducts .place_order_code").text();
			let supp_code=$("#importedProducts .supp_code").text();
			let supp_name=$("#importedProducts .supp_name").text();
			let created_at=$("#importedProducts .created_at").text();
			let name_employee=$("#importedProducts .name_employee").text();

			$("#importedProducts .products2export").each(function(e) {
				let product_code=$($(this).find("span[class='product_code']")).text();
				let name=$($(this).find("span[class='name']")).text();
				let qty=$($(this).find("span[class='qty']")).text();
				let price=$($(this).find("span[class='price pricePick']")).text();
				let delimiter="<?=DELIMITER?>";
				p+=product_code+delimiter+name+delimiter+qty+delimiter+price+"|";
				total+=parseInt(qty)*parseFloat(price);
			});
			p=p.slice(0, -1);//remove the last character
			console.log(p);
			$.ajax({
				url: "controller/ExportFileController.php",
				type: "POST",
				data: {
					action: "exportFile",
					products: p,
					total: total,
					place_order_code: place_order_code,
					supp_code: supp_code,
					supp_name: supp_name,
					created_at: created_at,
					name_employee: name_employee
				},
				success: function(res) {
					loading_off();
					location.href=res;
				}
			});
		});
	});
</script>
</body>
</html>