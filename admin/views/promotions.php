<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
	include_once('..\model\PromotionModel.php');
	if (!isset($_SESSION['login_email'])) header("location:../login.php");
	$model=new PromotionModel;
    $promotions=$model->getPromotions();
    $products=$model->getProducts();
    //print_r($promotions);
?>
<head>
    <title>Chương trình khuyến mãi</title>
    <?php include_once('..\views\common.php'); ?>
<style>
#editPromotion table td:nth-child(1){font-weight:bold;}
#editPromotion select,#editPromotion input{width:100%;}
.percent{color:red;font-weight:bold;}
#editPromotion .percent{width:80%;margin-right:2px;}
#btnAddPromotion{margin-bottom:7px;}
</style>
</head>
<body>
	<div id='editPromotion' class='absolute_center'>
		<div class="panel-heading">
			<b id='editKhachHangHeader'></b>
		</div><br />
		<table class='table table-bordered table-hover'>
			<tr>
				<td width="20%">Mã chương trình</td>
				<td><input id='promotion_code' type='text'/></td>
			</tr>
			<tr>
				<td>Ngày bắt đầu</td>
				<td><input id='date_start' type='text' readonly/></td>
			</tr>
            <tr>
				<td>Ngày kết thúc</td>
				<td><input id='date_end' type='text' readonly/></td>
			</tr>
			<tr>
				<td>Mô tả</td>
				<td><input id='description' type='text'/></td>
			</tr>
			<tr>
				<td>Sản phẩm áp dụng</td>
				<td>
                <div id='products'>
                    <table class='table table-bordered table-hover'>
                        <thead>
                            <tr>
                                <th width='auto'>Sản phẩm</th>
                                <th width='15%'>Giảm</th>
                                <th width='15%'>Tùy chọn</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <center><button id='btnThem'><i class='fa fa-plus'></i> Thêm sản phẩm</button></center></td>
                </td>
			</tr>
			<tr>
				<td></td>
				<td><button id='btnSave'>Cập nhật</button></td>
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
					<b>Danh sách chương trình</b>
				</div>
				<div class="panel-body">
				<button id='btnAddPromotion'><i class="fa fa-plus"></i> Thêm khuyến mãi</button><br/>
				<?php
					echo "<table class='table table-bordered table-hover'>";
					echo "<thead>";
					echo "<tr>";
					echo "<th style='text-align:center;'>Mã khuyến mãi</th>";
					echo "<th style='text-align:center;'>Ngày bắt đầu</th>";
					echo "<th style='text-align:center;'>Ngày kết thúc</th>";
					echo "<th style='text-align:center;'>Mô tả</th>";
					echo "<th style='text-align:center;'>Sản phẩm áp dụng</th>";
					echo "<th style='text-align:center;'>Nhân viên</th>";
					echo "<th style='text-align:center;'>Tùy chọn</th>";
					echo "</tr>";
					echo "</thead>";
					foreach ($promotions as $p) {
                        $date_start=date_format(date_create($p->date_start),"d-m-Y");
                        $date_end=date_format(date_create($p->date_end),"d-m-Y");
						echo "<tr>";
						echo "<td>".$p->promotion_code."</td>";
						echo "<td>".$date_start."</td>";
						echo "<td>".$date_end."</td>";
                        echo "<td>".$p->description."</td>";
                        echo "<td>";

                        echo "<table class='table table-bordered table-hover'>";
                        echo "<thead><tr>";
                        echo "<th width='15%' style='text-align:center;'>Mã SP</th>";
                        echo "<th width='auto' style='text-align:center;'>Tên SP</th>";
                        echo "<th width='10%' style='text-align:center;'>Giảm</th>";
                        echo "</tr></thead>";

                        $details=$model->getPromotionDetails($p->promotion_code);
                        $prods="";
                        foreach($details as $d) {
                            echo "<tr>";
                            echo "<td>$d->product_code</td>";
                            echo "<td>$d->product_name</td>";
                            echo "<td style='text-align:center;'><span class='percent'>".($d->percent*100)."%</span></td>";
                            echo "</tr>";
                            $prods.=$d->product_code."#".$d->percent."|";
                        }
                        echo "</table>";
                        echo "</td>";
						echo "<td><b>".$p->employee_name."</b></td>";
						echo "<td><span class='optionButtons'><button class='btn btn-sm btn-success btnEdit' promotion_code='$p->promotion_code' date_start='$date_start' date_end='$date_end' description='$p->description' products='".substr($prods,0,-1)."'>Sửa</button>";
						echo "<button promotion_code='$p->promotion_code' class='btn btn-sm btn-danger btnDelete'>Xóa</button>";
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

    $("#date_start").datepicker({dateFormat: "d-m-yy"});
    $("#date_end").datepicker({dateFormat: "d-m-yy"});

    let products="<?php
        echo "<select name='product_code'>";
        foreach($products as $p) {
            echo "<option value=$p->product_code>$p->name</option>";
        }
        echo '</select>';
    ?>";
    function addNewRow() {
        let res="";
        res+="<tr class='products2order'>";
        res+="<td>"+products+"</td>";
        res+="<td><input class='percent' type='number' value='10' />%</td>"
        res+="<td><button class='btnXoa' onclick='$(this).parent().parent().remove();'><i class='fa fa-times'></i> Xóa</button></td>";
        res+="</tr>";
        return res;
    }

    $("#btnThem").click(function(e) {
        $("#products table thead").parent().append(addNewRow());
    });

	$("#btnAddPromotion").click(function(e) {
        editMode=ADD;
        $("#promotion_code").removeAttr("disabled");
		$("#promotion_code").val("");
		$("#date_start").val("");
		$("#date_end").val("");
		$("#description").val("");

		$("#editPromotion").css("display", "block");
		$("#editKhachHangHeader").text("Thêm khuyến mãi mới");
		$("#btnEdit").text("Thêm");
    });

    $(".btnEdit").click(function(e) {
        editMode=EDIT;
        $("#promotion_code").attr("disabled","disabled");
        let promotion_code=$(this).attr("promotion_code");
        let date_start=$(this).attr("date_start");
        let date_end=$(this).attr("date_end");
        let description=$(this).attr("description");
        let products_infos=$(this).attr("products");
        original_info=promotion_code+date_start+date_end+description+products_infos;

        $("#promotion_code").val(promotion_code);
        $("#date_start").val(date_start);
        $("#date_end").val(date_end);
        $("#description").val(description);

        $("#products tbody").remove();
        let products_info=products_infos.split("|");
        products_info.forEach(function(product_info, i) {
            let infos=product_info.split("#");
            let product_code=infos[0];
            let percent=infos[1];
            console.log(i+":"+product_code + ":"+percent);
            $("#products table thead").parent().append(addNewRow());
            $(".products2order:nth-child("+(i+1)+") select").val(product_code);
            $(".products2order:nth-child("+(i+1)+") .percent").val(percent*100);
        });

        $("#editPromotion").css("display", "block");
    });

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

    $("#btnSave").click(function(e) {
        loading_on();
        let promotion_code=$("#promotion_code").val();
        let date_start=$("#date_start").val();
        let date_end=$("#date_end").val();
        let description=$("#description").val();

        let error="";
        error+=validatePromotionCode(promotion_code);
        error+=validateDateRange(date_start,date_end);
        error+=validateGeneralText(description, "mô tả");
        error+=($(".products2order").length==0)?"Vui lòng chọn ít nhất 1 sản phẩm!\n":"";
        error+=checkDuplicatedProducts();

        let products_infos="";
        $(".products2order").each(function(e) {
            let product_code=$(this).find("select[name='product_code']").val();
            let percent=parseInt($(this).find("input[class='percent']").val());
            console.log("percent="+percent);
            error+=validatePromotionPercent(percent);
            products_infos+=product_code+"#"+(percent/100)+"|";
        });

        if (error!="") {
            loading_off();
            alert(error);
            return false;
        }

        products_infos=products_infos.slice(0,-1);
        console.log(products_infos);

        new_info=promotion_code+date_start+date_end+description+products_infos;
        if (original_info==new_info) {
            loading_off();
            alert("Không có gì thay đổi!");
            return false;
        }

        let action = editMode==EDIT?"updatePromotion":"addPromotion";
        $.ajax({
            type: "POST",
            url: "controller/PromotionController.php",
            data: {
                action:action,
                promotion_code:promotion_code,
                date_start:date_start,
                date_end:date_end,
                description:description,
                id_employee:<?=$_SESSION['login_id']?>,
                products_infos:products_infos
            },
            success:function(res) {
                loading_off();
                if (res!='ok') {
                    alert(res);
                    return false;
                }
                alert("Cập nhật khuyến mãi thành công!");
                location.reload();
            }
        });
    });

	$(".btnDelete").click(function(e) {
		let promotion_code=$(this).attr("promotion_code");
		let ans=confirm("Bạn có thật sự muốn xóa '"+promotion_code+"'?");
		if (!ans) return false;
		$.ajax({
			url: "controller/PromotionController.php",
			type: "POST",
			data: {
				action: "deletePromotion",
				promotion_code: promotion_code
			},
			success: function(res) {
				if (res!="ok") {
					alert(res);
					return;
				}
				alert("Xóa chương trình khuyến mãi '"+promotion_code+"' thành công!");
				location.reload();
			}
		});
	});

	$("#btnCloseEdit").click(function(e) {
        let editPromotion=$("#editPromotion");
        $("#products tbody").remove();
		if ($(editPromotion).css("display")=="block") {
			$(editPromotion).css("display","none");
			editMode="";
		}
	});
</script>
</body>
</html>