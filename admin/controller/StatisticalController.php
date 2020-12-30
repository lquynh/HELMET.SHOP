<?php
include_once('..\model\StatisticalModel.php');
require_once ($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
$model=new StatisticalModel;

if(isset($_POST['action']) && $_POST['action']=='sanpham'){
	$dateStart=$_POST['start'];
	$newStart = date("d-m-Y", strtotime($dateStart));
	$dateEnd=$_POST['end'];
	$newEnd = date("d-m-Y", strtotime($dateEnd));
	$data=$model->getStatis($dateStart,$dateEnd);

	$res="<center><h3 id='reportTitle'>Báo cáo sản phẩm bán ra từ $newStart đến $newEnd</h3></center>";
	$res.="<center><button id='btnExportFileSanPham' onclick='exportFileSanPham();'><i class='fa fa-upload'></i> Xuất Excel file</button></center>";
	$res.="<table class='table table-bordered table-hover'>";
	$res.="<thead>";
	$res.="<th width='7%' style='text-align:center;'>STT</th>";
	$res.="<th width='10%' style='text-align:center;'>Mã SP</th>";
	$res.="<th style='text-align:center;'>Tên SP</th>";
	$res.="<th width='10%' style='text-align:center;'>Số lượng</th>";
	$res.="</thead>";
	$i=0;
	$export="";
	$total_qty=0;
	foreach ($data as $d) {
		$res.="<tr>";
		$res.="<td style='text-align:center;'>".++$i."</td>";
		$res.="<td>".$d->product_code."</td>";
		$res.="<td>".$d->name."</td>";
		$res.="<td style='text-align:right;'>".$d->SLB."</td>";
		$res.="</tr>";
		$total_qty+=$d->SLB;
		$export.=$d->product_code.DELIMITER.$d->name.DELIMITER.$d->SLB;
		$export.="|";
	}
	$res.="<tr><td></td><td></td><td style='text-align:right;'><b>Tổng cộng:</b></td><td style='text-align:right;'><b>$total_qty</b></td></tr>";
	$res.="</table><div id='export' style='display:none;'>".substr($export,0,-1)."</div>";
	echo $res;
}

if(isset($_POST['action']) && $_POST['action']=='doanhthu'){
	$dateStart=$_POST['start'];
	$newStart = date("d-m-Y", strtotime($dateStart));
	$dateEnd=$_POST['end'];
	$newEnd = date("d-m-Y", strtotime($dateEnd));
	$data=$model->getStatisRevenueByMonth($dateStart,$dateEnd);
	$total_revenue=0;

	$res="<center><h3 id='reportTitle'>Báo cáo doanh thu từ $newStart đến $newEnd</h3></center>";
	$res.="<center><button id='btnExportFileDoanhThu' onclick='exportFileDoanhThu();'><i class='fa fa-upload'></i> Xuất Excel file</button></center>";
	$res.="<table class='table table-bordered table-hover'>";
	$res.="<thead>";
	$res.="<th width='7%' style='text-align:center;'>STT</th>";
	$res.="<th width='10%' style='text-align:center;'>Tháng</th>";
	$res.="<th style='text-align:center;'>Doanh thu</th>";
	$res.="</thead>";
	$i=0;
	$export="";
	$total_qty=0;
	foreach ($data as $d) {
		$res.="<tr>";
		$res.="<td style='text-align:center;'>".++$i."</td>";
		$res.="<td style='text-align:center;'>".$d->THANG."</td>";
		$res.="<td style='text-align:right;' ><span class='price'>".number_format($d->DOANHTHU,2)."</span></td>";
		$res.="</tr>";
		$total_revenue+=$d->DOANHTHU;
		$export.=$d->THANG.DELIMITER.$d->DOANHTHU;
		$export.="|";
	}
	$res.="<tr><td></td><td style='text-align:right;'><b>Tổng cộng:</b></td><td style='text-align:right;'><span class='price'>".number_format($total_revenue,PRICE_DECIMALS,'.','')."</span></td></tr>";
	$res.="</table><div id='export' style='display:none;'>".substr($export,0,-1)."</div>";
	echo $res;
}

if(isset($_POST['action']) && $_POST['action']=='tonkho'){
	$dateStart=$_POST['start'];
	$newStart = date("d-m-Y", strtotime($dateStart));
	$data=$model->getStatisInventory($dateStart);

	$res="<center><h3 id='reportTitle'>Báo cáo tồn kho đến ngày $newStart</h3></center>";
	$res.="<center><button id='btnExportFileTonKho' onclick='exportFileTonKho();'><i class='fa fa-upload'></i> Xuất Excel file</button></center>";
	$res.="<table class='table table-bordered table-hover'>";
	$res.="<thead>";
	$res.="<th style='text-align:center;'>STT</th>";
	$res.="<th style='text-align:center;'>Mã SP</th>";
	$res.="<th style='text-align:center;'>Tên SP</th>";
	$res.="<th style='text-align:center;'>Tổng nhập</th>";
	$res.="<th style='text-align:center;'>Tổng xuất</th>";
	$res.="<th style='text-align:center;'>Số lượng tồn</th>";
	$res.="</thead>";
	$i=0;

	$cate_code="";
    $total=0;
    $total_import=0;
    $total_export=0;
	$totalAll=0;
	$export="";
	foreach ($data as $d) {
		if ($d->cate_code!=$cate_code) {
			if ($cate_code!="") $res.="<tr><td></td><td></td><td></td><td style='text-align:right;'><b>".$total_import."</b></td><td style='text-align:right;'><b>".$total_export."</b></td><td style='text-align:right;'><b>".$total."</b></td></tr>";
			$res.="<tr><td colspan='6'>Loại sản phẩm: <b>".$d->cate_name."</b></td></tr>";
			$cate_code=$d->cate_code;
            $total=0;
            $total_import=0;
            $total_export=0;
			$export.="\n".$d->cate_name."\n";
		}

		$res.="<tr>";
		$res.="<td style='text-align:center;'>".++$i."</td>";
		$res.="<td>".$d->product_code."</td>";
		$res.="<td>".$d->name."</td>";
		$res.="<td style='text-align:right;'>".$d->tongnhap."</td>";
		$res.="<td style='text-align:right;'>".$d->tongxuat."</td>";
		$res.="<td style='text-align:right;'>".$d->tonkho."</td>";
		$res.="</tr>";
        $total+=$d->tonkho;
        $total_import+=$d->tongnhap;
        $total_export+=$d->tongxuat;
		$totalAll+=$d->tonkho;

		$export.=$d->product_code.DELIMITER.$d->name.DELIMITER.$d->tongnhap.DELIMITER.$d->tongxuat.DELIMITER.$d->tonkho;
		$export.="|";
	}
	$res.="<tr><td></td><td></td><td></td><td style='text-align:right;'><b>".$total_import."</b></td><td style='text-align:right;'><b>".$total_export."</b></td><td style='text-align:right;'><b>".$total."</b></td></tr>";
	$res.="<tr><td></td><td></td><td></td><td></td><td style='text-align:right;'><b>Tổng cộng:</b></td><td style='text-align:right;'><b>".$totalAll."</b></td></tr>";
	$res.="</table><div id='export' style='display:none;'>".$export."</div>";
	echo $res;
}

if(isset($_POST['action']) && $_POST['action']=='loinhuan'){
	$dateStart=$_POST['start'];
	$newStart = date("d-m-Y", strtotime($dateStart));

	$data=$model->getStatisInterest($dateStart);

	$res="<center><h3 id='reportTitle'>Báo cáo lợi nhuận đến ngày $newStart</h3></center>";
	$res.="<center><button id='btnExportFileLoiNhuan' onclick='exportFileLoiNhuan();'><i class='fa fa-upload'></i> Xuất Excel file</button></center>";
	$res.="<table class='table table-bordered table-hover'>";
	$res.="<thead>";
	$res.="<th style='text-align:center;'>STT</th>";
	$res.="<th style='text-align:center;'>Mã SP</th>";
	$res.="<th style='text-align:center;'>Tên SP</th>";
	$res.="<th style='text-align:center;'>Số lượng xuất</th>";
	$res.="<th style='text-align:center;'>Đơn giá xuất TB</th>";
	$res.="<th style='text-align:center;'>Đơn giá nhập TB</th>";
	$res.="<th style='text-align:center;'>Lợi nhuận</th>";
	$res.="</thead>";
	$i=0;
	$total=0;
	$export="";
	foreach ($data as $d) {
		$res.="<tr>";
		$res.="<td style='text-align:center;'>".++$i."</td>";
		$res.="<td>".$d->product_code."</td>";
		$res.="<td>".$d->name."</td>";
		$res.="<td style='text-align:right;'>".$d->TSL."</td>";

		$res.=$d->DGXTB==0?"<td style='text-align:right;'>".$d->DGXTB."</td>":"<td style='text-align:right;'><span class='price'>".number_format($d->DGXTB,PRICE_DECIMALS,'.','')."</span></td>";
		$res.=$d->DGNTB==0?"<td style='text-align:right;'>".$d->DGNTB."</td>":"<td style='text-align:right;'><span class='price'>".number_format($d->DGNTB,PRICE_DECIMALS,'.','')."</span></td>";
		$res.=$d->LN==0?"<td style='text-align:right;'>".$d->LN."</td>":"<td style='text-align:right;'><span class='price'>".number_format($d->LN,PRICE_DECIMALS,'.','')."</span></td>";

		$total+=$d->LN;
		$res.="</tr>";

		$export.=$d->product_code.DELIMITER.$d->name.DELIMITER.$d->TSL.DELIMITER.$d->DGNTB.DELIMITER.$d->DGXTB.DELIMITER.$d->LN;
		$export.="|";
	}
	$res.="<tr><td></td><td></td><td></td><td></td><td></td><td style='text-align:right;'><b>Tổng cộng:</b></td><td style='text-align:right;'><span class='price'>".number_format($total,PRICE_DECIMALS,'.','')."</span></td></tr>";
	$res.="</table><div id='export' style='display:none;'>".substr($export,0,-1)."</div>";
	echo $res;
}
?>
