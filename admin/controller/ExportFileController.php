<?php
session_start();
require_once ('../../vendor/autoload.php');
require_once ($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
require_once ($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\UserModel.php');
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function setCenterAligned($sheet,$col,$row) {
	$sheet->getStyle($col.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
}

function setMergeCells($spreadsheet,$from_col,$to_col,$row) {
	$spreadsheet->getActiveSheet()->mergeCells($from_col.$row.":".$to_col.$row);
}

function setBold($spreadsheet,$col,$row) {
	$spreadsheet->getActiveSheet()->getStyle($col.$row)->getFont()->setBold(true);
}

function setCellValue($sheet,$col,$row,$value) {
	$sheet->setCellValue($col.$row,$value);
}

function setSize($spreadsheet,$col,$row,$size) {
	$spreadsheet->getActiveSheet()->getStyle($col.$row)->getFont()->setSize($size);
}

function setFont($spreadsheet,$font) {
	$spreadsheet->getDefaultStyle()->getFont()->setName(EXCEL_FONT);
}

function setAutoSize($spreadsheet,$col) {
	$spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
}

function setBorder($sheet,$col_start,$row_start,$col_end,$row_end) {
	$styleArray = [
		'borders' => [
			'allBorders' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color' => ['argb' => '00000000'],
			],
		],
	];
	$sheet->getStyle($col_start.$row_start.':'.$col_end.($row_end-1))->applyFromArray($styleArray);
}

if (isset($_POST['action']) && $_POST['action']=='exportFile') {
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet()->setTitle("Sản phẩm");
	setFont($spreadsheet,EXCEL_FONT);
	$place_order_code=$_POST['place_order_code'];
	$supp_code=$_POST['supp_code'];
	$supp_name=$_POST['supp_name'];
	$name_employee=$_POST['name_employee'];
	$created_at=$_POST['created_at'];

	$row=1;
	setCellValue($sheet,"A",$row,SHOP_NAME);
	setCellValue($sheet,"A",++$row,'Địa chỉ: 39 Lê Duẩn, Bến Nghé, Quận 1, Tp.HCM');
	setCellValue($sheet,"A",++$row,'SĐT: 0333.836.639');

	$row++;
	setCellValue($sheet,"A",$row,'PHIẾU ĐẶT HÀNG');
	setMergeCells($spreadsheet,"A","E",$row);
	setBold($spreadsheet,"A",$row);
	setSize($spreadsheet,"A",$row,EXCEL_HEADING_1_SIZE);
	setCenterAligned($sheet,"A",$row);

	setAutoSize($spreadsheet,"B");
	setAutoSize($spreadsheet,"C");
	setAutoSize($spreadsheet,"D");
	setAutoSize($spreadsheet,"E");

    setCellValue($sheet,"A",++$row,'Nhà cung cấp: '.$supp_name.' - '.$supp_code);
    $userModel=new UserModel;
    $supp=$userModel->getSupplier($supp_code);
	setCellValue($sheet,"A",++$row,'SĐT: '.$supp->phone);
	setCellValue($sheet,"A",++$row,'Địa chỉ: '.$supp->address);

	$row-=3;
	setCellValue($sheet,"E",++$row,'Mã phiếu đặt: '.$place_order_code);
	setCellValue($sheet,"E",++$row,'Tên nhân viên: '.$name_employee);
	setCellValue($sheet,"E",++$row,'Ngày tạo: '.$created_at);

	$row+=2;
	setCellValue($sheet,"A",$row,'STT');
	setCellValue($sheet,"B",$row,'Mã sản phẩm');
	setCellValue($sheet,"C",$row,'Tên sản phẩm');
	setCellValue($sheet,"D",$row,'Số lượng');
	setCellValue($sheet,"E",$row,'Đơn giá');

	setCenterAligned($sheet,"A",$row);
	setCenterAligned($sheet,"B",$row);
	setCenterAligned($sheet,"C",$row);
	setCenterAligned($sheet,"D",$row);
	setCenterAligned($sheet,"E",$row);

	setBold($spreadsheet,"A",$row);
	setBold($spreadsheet,"B",$row);
	setBold($spreadsheet,"C",$row);
	setBold($spreadsheet,"D",$row);
	setBold($spreadsheet,"E",$row);

	$products=explode("|",$_POST['products']);
	$row_start=$row++;
	$count=1;
	foreach ($products as $product) {
		$p=explode(DELIMITER,$product);
		$product_code=$p[0];
		$name=$p[1];
		$quantity_in=$p[2];
		$price=number_format($p[3],PRICE_DECIMALS,'.','');

		setCellValue($sheet,"A",$row,$count);
		setCellValue($sheet,"B",$row,$product_code);
		setCellValue($sheet,"C",$row,$name);
		setCellValue($sheet,"D",$row,$quantity_in);
		setCellValue($sheet,"E",$row,$price);
		$count++;
		$row++;
	}
	setBorder($sheet,"A",$row_start,"E",$row);

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
	$EXCEL_FILENAME=$place_order_code.".xlsx";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $EXCEL_FILENAME .'"');
    header('Cache-Control: max-age=0');
	$filePath="uploads/".$EXCEL_FILENAME;
    $writer->save("../".$filePath);
	echo ADMIN_URL.$filePath;
}

if (isset($_POST['action']) && $_POST['action']=='exportFileSanPham') {
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet()->setTitle("Sản phẩm bán ra");
	setFont($spreadsheet, EXCEL_FONT);
	$data=$_POST['data'];
	$reportTitle=$_POST['reportTitle'];
	$created_by=$_POST['created_by'];

	$row=1;
	setMergeCells($spreadsheet,"A","D",$row);
	setCellValue($sheet,"A",$row,$reportTitle);
	setBold($spreadsheet,"A",$row);
	setSize($spreadsheet,"A",$row,EXCEL_HEADING_1_SIZE);
	setCenterAligned($sheet,"A",$row);

	setCellValue($sheet,"A",++$row,SHOP_NAME);
	setCellValue($sheet,"A",++$row,'Ngày tạo: '.date('d-m-Y'));
	setCellValue($sheet,"A",++$row,'Người tạo: '.$created_by);

	$row+=2;
	$row_start=$row;
	setCellValue($sheet,"A",$row,'STT');
	setCellValue($sheet,"B",$row,'Mã SP');
	setCellValue($sheet,"C",$row,'Tên SP');
	setCellValue($sheet,"D",$row,'Số lượng');

	setCenterAligned($sheet,"A",$row);
	setCenterAligned($sheet,"B",$row);
	setCenterAligned($sheet,"C",$row);
	setCenterAligned($sheet,"D",$row);

	setBold($spreadsheet,"A",$row);
	setBold($spreadsheet,"B",$row);
	setBold($spreadsheet,"C",$row);
	setBold($spreadsheet,"D",$row);

	setAutoSize($spreadsheet,"C");
	setAutoSize($spreadsheet,"D");

	$count=1;
	$row++;
	$products=explode("|",$_POST['data']);
	$total=0;
	foreach($products as $product) {
		if ($product=="") continue;
		$p=explode("#",$product);
		setCellValue($sheet,"A",$row,$count++);
		setCenterAligned($sheet,"A",$row);
		setCellValue($sheet,"B",$row,$p[0]);
		setCellValue($sheet,"C",$row,$p[1]);
		setCellValue($sheet,"D",$row,$p[2]);
		$total+=(int)$p[2];
		$row++;
	}
	setCellValue($sheet,"C",$row,"Tổng cộng: ");
	setCellValue($sheet,"D",$row,$total);
	setBold($spreadsheet,"C",$row);
	setBold($spreadsheet,"D",$row);
	$row++;

	setBorder($sheet,"A",$row_start,"D",$row);

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
	$EXCEL_FILENAME=$reportTitle.".xlsx";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $EXCEL_FILENAME .'"');
    header('Cache-Control: max-age=0');
	$filePath="uploads/".$EXCEL_FILENAME;
    $writer->save("../".$filePath);
	echo ADMIN_URL.$filePath;
}

if (isset($_POST['action']) && $_POST['action']=='exportFileDoanhThu') {
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet()->setTitle("Doanh thu");
	setFont($spreadsheet, EXCEL_FONT);
	$data=$_POST['data'];
	$reportTitle=$_POST['reportTitle'];
	$created_by=$_POST['created_by'];

	$row=1;
	setMergeCells($spreadsheet,"A","C",$row);
	setCellValue($sheet,"A",$row,$reportTitle);
	setBold($spreadsheet,"A",$row);
	setSize($spreadsheet,"A",$row,EXCEL_HEADING_1_SIZE);
	setCenterAligned($sheet,"A",$row);

	setCellValue($sheet,"A",++$row,SHOP_NAME);
	setCellValue($sheet,"A",++$row,'Ngày tạo: '.date('d-m-Y'));
	setCellValue($sheet,"A",++$row,'Người tạo: '.$created_by);

	$row+=2;
	$row_start=$row;
	setCellValue($sheet,"A",$row,'STT');
	setCellValue($sheet,"B",$row,'Tháng');
	setCellValue($sheet,"C",$row,'Doanh thu');

	setCenterAligned($sheet,"A",$row);
	setCenterAligned($sheet,"B",$row);
	setCenterAligned($sheet,"C",$row);

	setBold($spreadsheet,"A",$row);
	setBold($spreadsheet,"B",$row);
	setBold($spreadsheet,"C",$row);

	setAutoSize($spreadsheet,"B");
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);

	$count=1;
	$row++;
	$products=explode("|",$_POST['data']);
	$total=0;
	foreach($products as $product) {
		if ($product=="") continue;
		$p=explode("#",$product);
		setCellValue($sheet,"A",$row,$count++);
		setCenterAligned($sheet,"A",$row);
		setCellValue($sheet,"B",$row,$p[0]);
		setCellValue($sheet,"C",$row,number_format($p[1],PRICE_DECIMALS,'.',''));
		$total+=(float)$p[1];
		$row++;
	}
	setCellValue($sheet,"B",$row,"Tổng doanh thu: ");
	setCellValue($sheet,"C",$row,number_format($total,PRICE_DECIMALS,'.',''));
	setBold($spreadsheet,"B",$row);
	setBold($spreadsheet,"C",$row);
	$row++;

	setBorder($sheet,"A",$row_start,"C",$row);

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
	$EXCEL_FILENAME=$reportTitle.".xlsx";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $EXCEL_FILENAME .'"');
    header('Cache-Control: max-age=0');
	$filePath="uploads/".$EXCEL_FILENAME;
    $writer->save("../".$filePath);
	echo ADMIN_URL.$filePath;
}

if (isset($_POST['action']) && $_POST['action']=='exportFileTonKho') {
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet()->setTitle("Tồn kho");
	setFont($spreadsheet, EXCEL_FONT);
	$data=$_POST['data'];
	$reportTitle=$_POST['reportTitle'];
	$created_by=$_POST['created_by'];

	$row=1;
	setMergeCells($spreadsheet,"A","F",$row);
	setCellValue($sheet,"A",$row,$reportTitle);
	setBold($spreadsheet,"A",$row);
	setSize($spreadsheet,"A",$row,EXCEL_HEADING_1_SIZE);
	setCenterAligned($sheet,"A",$row);

	setCellValue($sheet,"A",++$row,SHOP_NAME);
	setCellValue($sheet,"A",++$row,'Ngày tạo: '.date('d-m-Y'));
	setCellValue($sheet,"A",++$row,'Người tạo: '.$created_by);

	$row+=2;
	$row_start=$row;
	setCellValue($sheet,"A",$row,'STT');
	setCellValue($sheet,"B",$row,'Mã SP');
	setCellValue($sheet,"C",$row,'Tên SP');
	setCellValue($sheet,"D",$row,'Tổng nhập');
	setCellValue($sheet,"E",$row,'Tổng xuất');
	setCellValue($sheet,"F",$row,'Số lượng tồn');

	setCenterAligned($sheet,"A",$row);
	setCenterAligned($sheet,"B",$row);
	setCenterAligned($sheet,"C",$row);
	setCenterAligned($sheet,"D",$row);
	setCenterAligned($sheet,"E",$row);
	setCenterAligned($sheet,"F",$row);

	setBold($spreadsheet,"A",$row);
	setBold($spreadsheet,"B",$row);
	setBold($spreadsheet,"C",$row);
	setBold($spreadsheet,"D",$row);
	setBold($spreadsheet,"E",$row);
	setBold($spreadsheet,"F",$row);

	setAutoSize($spreadsheet,"C");
	setAutoSize($spreadsheet,"D");
	setAutoSize($spreadsheet,"E");
	setAutoSize($spreadsheet,"F");

	$sections=explode("\n",$_POST['data']);

	$count=1;
	$allTotal=0;
	$row++;
	foreach ($sections as $s) {
		if ($s=="") continue;
		if (strpos($s, "|") !== false) {
			$products=explode("|",$s);
			$total=0;
			$total_import=0;
    		$total_export=0;
			foreach($products as $product) {
				if ($product=="") continue;
				$p=explode("#",$product);
				setCellValue($sheet,"A",$row,$count++);
				setCenterAligned($sheet,"A",$row);
				setCellValue($sheet,"B",$row,$p[0]);
				setCellValue($sheet,"C",$row,$p[1]);
				setCellValue($sheet,"D",$row,$p[2]);
				setCellValue($sheet,"E",$row,$p[3]);
				setCellValue($sheet,"F",$row,$p[4]);
				$total_import+=(int)$p[2];
				$total_export+=(int)$p[3];
				$total+=(int)$p[4];
				$row++;
			}
			setCellValue($sheet,"D",$row,$total_import);
			setBold($spreadsheet,"D",$row);
			setCellValue($sheet,"E",$row,$total_export);
			setBold($spreadsheet,"E",$row);
			setCellValue($sheet,"F",$row,$total);
			setBold($spreadsheet,"F",$row);
			$row++;
			$allTotal+=$total;
		} else {
			setCellValue($sheet,"A",$row,"Loại: ".$s);
			setMergeCells($spreadsheet,"A","F",$row);
			setBold($spreadsheet,"A",$row);
			$row++;
		}
	}
	setCellValue($sheet,"E",$row,"Tổng cộng: ");
	setCellValue($sheet,"F",$row,$allTotal);
	setBold($spreadsheet,"E",$row);
	setBold($spreadsheet,"F",$row);
	$row++;

	setBorder($sheet,"A",$row_start,"F",$row);

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
	$EXCEL_FILENAME=$reportTitle.".xlsx";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $EXCEL_FILENAME .'"');
    header('Cache-Control: max-age=0');
	$filePath="uploads/".$EXCEL_FILENAME;
    $writer->save("../".$filePath);
	echo ADMIN_URL.$filePath;
}

if (isset($_POST['action']) && $_POST['action']=='exportFileLoiNhuan') {
	$spreadsheet = new Spreadsheet();

	$sheet = $spreadsheet->getActiveSheet()->setTitle("Lợi nhuận");
	setFont($spreadsheet, EXCEL_FONT);

	$data=$_POST['data'];
	$reportTitle=$_POST['reportTitle'];
	$created_by=$_POST['created_by'];

	$row=1;
	setMergeCells($spreadsheet,"A","G",$row);
	setCellValue($sheet,"A",$row,$reportTitle);
	setBold($spreadsheet,"A",$row);
	setCenterAligned($sheet,"A",$row);
	setSize($spreadsheet,"A",$row,EXCEL_HEADING_1_SIZE);

	setCellValue($sheet,"A",++$row,SHOP_NAME);
	setCellValue($sheet,"A",++$row,'Ngày tạo: '.date('d-m-Y'));
	setCellValue($sheet,"A",++$row,'Người tạo: '.$created_by);

	$row+=2;
	$row_start=$row;
	setCellValue($sheet,"A",$row,"STT");
	setCellValue($sheet,"B",$row,"Mã SP");
	setCellValue($sheet,"C",$row,"Tên SP");
	setCellValue($sheet,"D",$row,"Số lượng xuất");
	setCellValue($sheet,"E",$row,"Đơn giá xuất TB");
	setCellValue($sheet,"F",$row,"Đơn giá nhập TB");
	setCellValue($sheet,"G",$row,"Lợi nhuận");

	setCenterAligned($sheet,"A",$row);
	setCenterAligned($sheet,"B",$row);
	setCenterAligned($sheet,"C",$row);
	setCenterAligned($sheet,"D",$row);
	setCenterAligned($sheet,"E",$row);
	setCenterAligned($sheet,"F",$row);
	setCenterAligned($sheet,"G",$row);

	setBold($spreadsheet,"A",$row);
	setBold($spreadsheet,"B",$row);
	setBold($spreadsheet,"C",$row);
	setBold($spreadsheet,"D",$row);
	setBold($spreadsheet,"E",$row);
	setBold($spreadsheet,"F",$row);
	setBold($spreadsheet,"G",$row);

	setAutoSize($spreadsheet,"C");
	setAutoSize($spreadsheet,"D");
	setAutoSize($spreadsheet,"E");
	setAutoSize($spreadsheet,"F");
	setAutoSize($spreadsheet,"G");

	$records=explode("|",$_POST['data']);
	$row_start=$row++;
	$count=1;
	$total_interest=0;
	foreach ($records as $r) {
		$line=explode(DELIMITER,$r);
		setCellValue($sheet,"A",$row,$count++);
		setCenterAligned($sheet,"A",$row);
		setCellValue($sheet,"B",$row,$line[0]);
		setCellValue($sheet,"C",$row,$line[1]);
		setCellValue($sheet,"D",$row,$line[2]);
		setCellValue($sheet,"E",$row,number_format($line[4],PRICE_DECIMALS));
		setCellValue($sheet,"F",$row,number_format($line[3],PRICE_DECIMALS));
		setCellValue($sheet,"G",$row,number_format($line[5],PRICE_DECIMALS));
		$total_interest+=$line[5];
		$row++;
	}
	setCellValue($sheet,"F",$row,"Tổng lợi nhuận:");
	setBold($spreadsheet,"F",$row);
	setCellValue($sheet,"G",$row,number_format($total_interest,PRICE_DECIMALS));
	setBold($spreadsheet,"G",$row);
	setBorder($sheet,"A",$row_start,"G",$row+1);

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
	$EXCEL_FILENAME=$reportTitle.".xlsx";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $EXCEL_FILENAME .'"'); 
    header('Cache-Control: max-age=0');
	$filePath="uploads/".$EXCEL_FILENAME;
    $writer->save("../".$filePath);
	echo ADMIN_URL.$filePath;
}
?>