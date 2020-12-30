<?php
session_start();
if (!isset($_SESSION['login_email'])) header("location:../login.php");
include_once('..\model\ImportModel.php');
require_once ('..\helper\constants.php');
$model=new ImportModel;
$imports=$model->getImports();
$suppliers=$model->getSuppliers();
?>
<head>
	<title>Nhập sản phẩm</title>
	<?php include_once('..\views\common.php'); ?>
<style>
#btnCreateNewImport{margin-bottom:7px;}
</style>
</head>
<body>
	<!-- Form Nhap Hang -->
	<div id='formNhapHang' class='absolute_center'>
		<div class="panel-heading">
			<b>Phiếu nhập hàng</b>
		</div><br />
		<table class='table table-bordered table-hover'>
			<tr>
				<td style='width:15%;'>Nhà cung cấp</td>
				<td>
					<select name='supp_code'>
					<option value=''></option>
						<?php foreach($suppliers as $s) {
							echo "<option value='".$s->supp_code."'>".$s->name."</option>";
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Mã phiếu đặt</td>
				<td>
					<select name="place_order_code"><option value=''></option></select>
				</td>
			</tr>
			<tr>
				<td>Mã phiếu nhập</td>
				<td>
					<input type='text' name='import_code'/>
				</td>
			</tr>
			<tr>
				<td>Nhân viên</td>
				<td><input type='hidden' name="id_employee" readonly value='<?=$_SESSION['login_id']?>'/><b id='name_employee'><?=$_SESSION['login_name']?></b></td>
			</tr>
			<tr>
				<td>Ngày đặt</td>
				<td><span id='date_place_order'></span></td>
			</tr>
			<tr>
				<td>Ngày nhập</td>
				<td><input type="text" id="date_created" name="created_at" readonly /></td>
			</tr>
			<tr>
				<td>Sản phẩm</td>
				<td><div id='products'></div></td>
			</tr>
			<tr>
				<td></td>
				<td><button type="submit" name="nhaphang"><i class="fa fa-download"></i> Nhập hàng</button></td>
			</tr>
		</table>
		<button id='btnClose' class='btnClose'><i class="fa fa-times"></i> Đóng</button>
	</div>

	<!-- Form Nhap Hang Tu File -->
	<div id='formNhapHangTuFile' class='absolute_center'>
		<div class="panel-heading">
			<b>Nhập hàng từ file Excel</b>
		</div><br />
			<div>
			<table class='table table-bordered table-hover'>
				<tr>
					<td style='width:15%;'>Nhà cung cấp</td>
					<td>
						<select name='supp_code2'>
						<option value=''></option>
							<?php foreach($suppliers as $s) {
								echo "<option value='".$s->supp_code."'>".$s->name."</option>";
							} ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Mã phiếu đặt</td>
					<td>
						<select name="place_order_code2"><option value=''></option></select>
					</td>
				</tr>
				<tr>
					<td>Mã phiếu nhập</td>
					<td>
						<input type='text' name='import_code2'/>
					</td>
				</tr>
				<tr>
					<td>Nhân viên</td>
					<td><input type='hidden' name="id_employee2" readonly value='<?=$_SESSION['login_id']?>'/><b><?=$_SESSION['login_name']?></b></td>
				</tr>
				<tr>
					<td>Ngày đặt</td>
					<td><span id='date_place_order2'></span></td>
				</tr>
				<tr>
					<td>Ngày nhập</td>
					<td><input type="text" id="date_created2" name="created_at2" readonly /></td>
				</tr>
				<tr>
					<td>Chọn file</td>
					<td>
						<input type="file" name="file" id="file" accept=".xls,.xlsx" style='display:inline;'/>&nbsp;
						<button type="button" id="btnSubmitFile"><i class="fa fa-upload"></i> Tải lên</button>
					</td>
				</tr>
				<tr>
					<td>Sản phẩm</td>
					<td><div id='products2'></div></td>
				</tr>
				<tr>
					<td></td>
					<td><button name="nhaphangtufile"><i class="fa fa-download"></i> Nhập hàng</button></td>
				</tr>
			</table>
			</div>
		<!--/form-->
		<button id='btnClose2' class='btnClose'><i class="fa fa-times"></i> Đóng</button>
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
					<b>Nhập sản phẩm</b>
				</div>
				<div class="panel-body">
					<button id='btnCreateNewImport'><i class="fa fa-plus"></i> Tạo phiếu nhập</button>
					<button id='btnCreateNewFileImport'><i class="fa fa-plus"></i> Nhập file</button>
					<br />
					<?php
						if (empty($imports)) {
							echo "<i>Chưa có phiếu nhập :(</i>";
						} else {
							echo "<table class='table table-bordered table-hover'>";
							echo "<thead>";
							echo "<tr>";
							echo "<th style='text-align:center;'>Mã phiếu nhập</th>";
							echo "<th style='text-align:center;'>Mã phiếu đặt</th>";
							echo "<th style='text-align:center;'>Ngày tạo</th>";
							echo "<th style='text-align:center;'>Chi tiết</th>";
							echo "<th style='text-align:center;'>Nhân viên tạo</th>";
							echo "<th style='text-align:center;'>Nhà cung cấp</th>";
							echo "</tr>";
							echo "</thead>";
							foreach($imports as $ip) {
								echo "<tr>";
								echo "<td>".$ip->import_code."</td>";
								echo "<td>".$ip->place_order_code."</td>";
								echo "<td>".date_format(date_create($ip->created_at),"d-m-Y")."</td>";
								echo "<td>";
								$details=$model->getImportDetails($ip->import_code);
								$total=0;
								$total_qty=0;

								echo "<table class='table table-bordered table-hover'>";
								echo "<thead><tr>";
								echo "<th width='55%' style='text-align:center;'>Tên sản phẩm</th>";
								echo "<th width='15%' style='text-align:center;'>Số lượng</th>";
								echo "<th width='15%' style='text-align:center;'>Đơn giá</th>";
								echo "<th style='text-align:center;'>Trị giá</th>";
								echo "</tr></thead>";
								foreach($details as $d) {
									$total+=$d->quantity_in*$d->price;
									$total_qty+=$d->quantity_in;
									echo "<tr class='$ip->place_order_code-products2export'>";
									echo "<td><span class='name'>".$model->getProductName($d->product_code)."</span> (<span class='product_code'>$d->product_code</span>)</td>";
									echo "<td style='text-align:center;'><span class='qty'/>".$d->quantity_in."</span></td>";
									echo "<td style='text-align:right;'><span class='price'>".number_format($d->price,PRICE_DECIMALS,'.',',')."</span></td>";
									echo "<td style='text-align:right;'><span class='price'>".number_format($d->price*$d->quantity_in,PRICE_DECIMALS,'.',',')."</span></td>";
									echo "</tr>";
								}
								echo "</table>";

								echo "Tổng số lượng: <b>".$total_qty."</b><br/>";
								echo "Tổng tiền: <span class='price'>".number_format($ip->total, PRICE_DECIMALS,'.',',')."</span>";
								echo "</td>";
								echo "<td><b>".$model->getEmployeeName($ip->id_employee)."</b></td>";
								echo "<td>".$model->getSupplierName($ip->supp_code)."</td>";
								echo "</tr>";
							}
							echo "</table>";
						}
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
	$(document).ready(function(){
		$("#btnSubmitFile").click(function(e) {
			let file=$("#file")[0].files[0];
			if (file!=undefined) {
				loading_on();
				let formData=new FormData();
				formData.append("file", file);
				$.ajax({
					url:"controller/ImportFileController.php",
					type:"POST",
					data:formData,
					async:false,
					cache:false,
					contentType:false,
					enctype: 'multipart/form-data',
					processData: false,
					success: function (res) {
						loading_off();
						console.log(res);
						$("#products2").html(res);
					}
				});
			}
		});

		$("#date_created").datepicker({
			dateFormat: "d-m-yy"
		}).datepicker("setDate", "0");
		
		$("#date_created2").datepicker({
			dateFormat: "d-m-yy"
		}).datepicker("setDate", "0");
		
		$("select[name='supp_code']").change(function(e) {
			let supp_code=$(this).val();
			if (supp_code.trim()!="") {
				$.ajax({
					url:"controller/ImportController.php",
					type:"POST",
					data: {
						action:"loadPlaceOrders",
						supp_code:supp_code
					},
					success: function(res){
						console.log(res);
						$("select[name='place_order_code']").html(res);
						$("select[name='place_order_code']").change();
						$("#products").html("");
					}
				});
			} else {
				$("select[name='place_order_code']").html("");
				$("#products").html("");
			}
		});
		
		$("select[name='supp_code2']").change(function(e) {
			let supp_code=$(this).val();
			if (supp_code.trim()!="") {
				$.ajax({
					url:"controller/ImportController.php",
					type:"POST",
					data: {
						action:"loadPlaceOrders",
						supp_code:supp_code
					},
					success: function(res){
						console.log(res);
						$("select[name='place_order_code2']").html(res);
						$("select[name='place_order_code2']").change();
						$("#products2").html("");
					}
				});
			} else {
				$("select[name='place_order_code2']").html("");
				$("#products2").html("");
			}
		});
		
		$("select[name='place_order_code']").change(function(e) {
			let place_order_code=$(this).val();
			if (place_order_code.trim()!="") {
				$.ajax({
					url:"controller/ImportController.php",
					type:"POST",
					data: {
						action:"loadProducts",
						place_order_code:place_order_code
					},
					success: function(res){
						console.log(res);
						$("#products").html(res);
						let d=$($(".products2import")[0]).attr("created_at");
						let ngay_dat=d.split("-");
						$("#date_place_order").text(d);
						$("#date_created").datepicker("option","minDate",new Date(ngay_dat[2],ngay_dat[1]-1,ngay_dat[0]));
						$("#date_created").datepicker("option","maxDate","0");
					}
				});
			}
        });
        
        $("select[name='place_order_code2']").change(function(e) {
			let place_order_code=$(this).val();
			if (place_order_code.trim()!="") {
				$.ajax({
					url:"controller/ImportController.php",
					type:"POST",
					data: {
						action:"loadProducts",
						place_order_code:place_order_code
					},
					success: function(res){
						console.log(res);
						$("#products2").html(res);
						let d=$($(".products2import")[0]).attr("created_at");
						let ngay_dat=d.split("-");
						$("#date_place_order2").text(d);
						$("#date_created2").datepicker("option","minDate",new Date(ngay_dat[2],ngay_dat[1]-1,ngay_dat[0]));
                        $("#date_created2").datepicker("option","maxDate","0");
                        $("#products2").html("");
					}
				});
			}
		});
		
		$("#btnCreateNewImport").click(function(e) {
			let formNhapHang=$("#formNhapHang");
			let formNhapHangTuFile=$("#formNhapHangTuFile");
			if ($(formNhapHang).css("display")=="none" && $(formNhapHangTuFile).css("display")=="none")
				$(formNhapHang).css("display","block");
			$("input[name='import_code']").val(generateRandomCode());
		});
		
		$("#btnCreateNewFileImport").click(function(e) {
			let formNhapHang=$("#formNhapHang");
			let formNhapHangTuFile=$("#formNhapHangTuFile");
			if ($(formNhapHangTuFile).css("display")=="none" && $(formNhapHang).css("display")=="none")
				$(formNhapHangTuFile).css("display","block");
			$("input[name='import_code2']").val(generateRandomCode());
		});
		
		$("#btnClose").click(function(e) {
			let formNhapHang=$("#formNhapHang");
			if ($(formNhapHang).css("display")=="none") return false;
			$(formNhapHang).css("display","none");
			$("select[name='supp_code']").val("");
			$("select[name='supp_code']").change();
			$("#date_place_order").text("");
		});

		$("#btnClose2").click(function(e) {
			let formNhapHangTuFile=$("#formNhapHangTuFile");
			if ($(formNhapHangTuFile).css("display")=="none") return false;
			$(formNhapHangTuFile).css("display","none");
			$("select[name='supp_code2']").val("");
			$("select[name='supp_code2']").change();
			$("#file").val("");
			$("#date_place_order2").text("");
		});

		$("button[name='nhaphang']").click(function(e) {
			let supp_code=$("select[name='supp_code']").val().trim();
			let place_order_code=$("select[name='place_order_code']").val();
			let import_code=$("input[name='import_code']").val();
			let id_employee=$("input[name='id_employee']").val();
			let name_employee=$("#name_employee").text();
			let created_at=$("input[name='created_at']").val();

			let errorLog="";
			let p="";
			let total=0;

			errorLog+=(supp_code=="")?"Bạn chưa chọn nhà cung cấp!\n":"";
			errorLog+=(place_order_code=="")?"Bạn chưa chọn đơn đặt hàng!\n":"";
			errorLog+=validateImportCode(import_code);

			errorLog+=($(".products2import").length==0)?"Vui lòng chọn ít nhất 1 sản phẩm!\n":"";
			$(".products2import").each(function(e) {
                let product_code=$($(this).find("input[class='product_code']")).val();
                let order_qty=$($(this).find("input[class='order_qty']")).val();
				let qty=$($(this).find("input[class='qty']")).val();
				let price=$($(this).find("input[class='price']")).val();
				price=normalizePriceInput(price);
				$($(this).find("input[class='price']")).val(price);

                errorLog+=validateQuantity(qty);
                errorLog+=(parseInt(qty)>parseInt(order_qty))?"Số lượng nhập không được > số lượng đặt!\n":"";
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
			loading_on();
			$.ajax({
				url:"controller/ImportController.php",
				type:"POST",
				data: {
					action:"nhaphang",
					place_order_code:place_order_code,
					import_code:import_code,
					id_employee:id_employee,
					created_at:created_at,
					products:p,
					total:total
				},
				success: function(res){
					loading_off();
					if (res!="ok") {
						alert(res);
						return;
					}
					alert("Tạo phiếu nhập hàng thành công!");
					location.reload();
				},
				error: function(res){
					console.log(res);
				}
			});
        });

        async function isProductBelongToSupp(product_code,supp_code,supp_name) {
            return await $.ajax({
                type: "POST",
                url: "controller/ImportFileController.php",
                data: {
                    action: "checkProductCodeBelongToSupp",
                    product_code: product_code,
                    supp_code: supp_code,
                    supp_name: supp_name
                }
            });
        }

        async function isProductBelongToOrder(product_code,place_order_code) {
            return await $.ajax({
                type: "POST",
                url: "controller/ImportFileController.php",
                data: {
                    action: "checkProductCodeBelongToOrder",
                    product_code: product_code,
                    place_order_code: place_order_code
                }
            });
        }

        async function checkProducts(supp_code) {
            var errorLog = "";
            var products = $(".products2import");
            for (var i=0;i<products.length;i++) {
                var p=products[i];
                let product_code=$($(p).find("input[class='product_code']")).val();

                let result = await isProductBelongToSupp(product_code,supp_code);
                errorLog+= (result==='ok')?"":result;

				let qty=$($(p).find("input[class='qty']")).val().trim();
				let price=$($(p).find("input[class='price']")).val().trim();
				price=normalizePriceInput(price);
				$($(p).find("input[class='price']")).val(price);

				if (qty=="" || price=="") errorLog+="Không được bỏ trống đơn giá hoặc số lượng!\n";
				if (qty<=0 || price<=0) errorLog+="Không được nhập số <=0 cho số lượng hoặc đơn giá!\n";
				errorLog+=validatePriceInput(price);

				let delimiter="<?=DELIMITER?>";
				p+=product_code+delimiter+qty+delimiter+price+"|";
				total+=parseInt(qty)*parseFloat(price);
            }
            return errorLog;
        }

		$("button[name='nhaphangtufile']").click(async function(e) {
            let supp_code=$("select[name='supp_code2']").val().trim();
            let supp_name=$("select[name='supp_code2'] option:selected").text().trim()
			let place_order_code=$("select[name='place_order_code2']").val();
			let import_code=$("input[name='import_code2']").val();
			let id_employee=$("input[name='id_employee2']").val();
			let date_place_order=$("#date_place_order2").text();
			let created_at=$("input[name='created_at2']").val();
			let products=$("#products2").text();

			let errorLog="";
			let p="";
			let total=0;

            errorLog+=(supp_code=="")?"Bạn chưa chọn nhà cung cấp!\n":"";
			errorLog+=(place_order_code=="")?"Bạn chưa chọn đơn đặt hàng!\n":"";
			errorLog+=(products=="")?"Bạn chưa tải file lên!\n":"";
			errorLog+=validateImportCode(import_code);
			errorLog+=validateDateRange(date_place_order,created_at);
			errorLog+=($(".products2import").length==0)?"Vui lòng chọn ít nhất 1 sản phẩm!\n":"";
            var products2import = $(".products2import");
            for (var i=0;i<products2import.length;i++) {
                var product2import=products2import[i];
                let product_code=$($(product2import).find("input[class='product_code']")).val();

                if (supp_code !== "") {
                    let result = await isProductBelongToSupp(product_code,supp_code,supp_name);
                    errorLog+= (result==='ok')?"":result+"\n";
                }

                if (place_order_code !== "") {
                    let result = await isProductBelongToOrder(product_code,place_order_code);
                    errorLog+= (result==='ok')?"":result+"\n";
                }

				let qty=$($(product2import).find("input[class='qty']")).val().trim();
				let order_qty=$($(product2import).find("input[class='order_qty']")).val().trim();
				let price=$($(product2import).find("input[class='price']")).val().trim();
				price=normalizePriceInput(price);
				$($(product2import).find("input[class='price']")).val(price);

                errorLog+=validateQuantity(qty);
                errorLog+=(parseInt(qty)>parseInt(order_qty))?"Số lượng nhập không được > số lượng đặt!\n":"";
				errorLog+=validatePriceInput(price);

				let delimiter="<?=DELIMITER?>";
				p+=product_code+delimiter+qty+delimiter+price+"|";
				total+=parseInt(qty)*parseFloat(price);
            }

            console.log("errorLog="+errorLog);
			if (errorLog!="") {
                alert(errorLog);
                loading_off();
				return false;
            }

            if (p==="") {
                alert("Không nạp được danh sách sản phẩm!");
                loading_off();
                return false;
            }

			console.log("p="+p);
			p=p.slice(0, -1);//remove the last character
			loading_on();
			await $.ajax({
				url:"controller/ImportFileController.php",
				type:"POST",
				data: {
					action:"importProductsFromFile",
					place_order_code:place_order_code,
					import_code:import_code,
					id_employee:id_employee,
					created_at:created_at,
					products:p,
					total:total
				},
				success: function(res){
					loading_off();
					if (res!="ok") {
						alert(res);
						return;
					}
					alert("Tạo phiếu nhập hàng thành công!");
					location.reload();
				},
				error: function(res){
					console.log(res);
				}
			});
		});
	});
</script>
</body>
</html>