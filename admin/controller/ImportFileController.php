<?php
session_start();
require_once ('..\helper\constants.php');
require_once ('../../vendor/autoload.php');
use Phppot\DataSource;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

include_once('..\model\ImportModel.php');
$model=new ImportModel;

function findStt($sheet,$sheetCount) {
	for ($i=0;$i<$sheetCount;$i++) {
		$value=trim($sheet[$i][0]);
		if(strtolower($value)=='stt')
            return $i;
    }
    return -1;
}

if (isset($_POST['action']) && $_POST['action']=='checkProductCodeBelongToSupp') {
    $product_code=$_POST['product_code'];
    $supp_code=$_POST['supp_code'];
    $supp_name=$_POST['supp_name'];
    if (!$model->checkProductCodeBelongToSupp($product_code,$supp_code)) {
        echo "Mã SP '$product_code' không thuộc NCC '$supp_name'!";
        return false;
    }
    echo "ok";
    return true;
}

if (isset($_POST['action']) && $_POST['action']=='checkProductCodeBelongToOrder') {
    $product_code=$_POST['product_code'];
    $place_order_code=$_POST['place_order_code'];
    if (!$model->checkProductCodeBelongToOrder($product_code,$place_order_code)) {
        echo "Mã SP '$product_code' không thuộc đơn hàng '$place_order_code'!";
        return false;
    }
    echo "ok";
    return true;
}

$items = [];
if (isset($_POST) && !empty($_FILES['file'])) {
	$allowedFileType = [
        'application/vnd.ms-excel',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel'
    ];
    $filetype=$_FILES["file"]["type"];
	if (!in_array($filetype, $allowedFileType)) {
		echo "Chỉ hỗ trợ file xls và xlsx!";
		return false;
	}

	$targetPath = '../uploads/' . $_FILES['file']['name'];
	move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

    $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    if ($filetype=="application/vnd.ms-excel")
        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();

    $spreadSheet = $Reader->load($targetPath);
    $excelSheet = $spreadSheet->getSheet(0);

	$sheet = $excelSheet->toArray();
	$sheetCount = count($sheet);

    $i=findStt($sheet,$sheetCount);
    if ($i==-1) {
        echo WRONG_FILE_STRUCTURE;
        return false;
    }

    $kiemtra=isset($sheet[$i][1]) ? $sheet[$i][1] : "";
    if (trim($kiemtra)!='Mã sản phẩm') {
        echo "Không tìm thấy cột 'Mã sản phẩm'!";
        return false;
    }

    $kiemtra=isset($sheet[$i][2]) ? $sheet[$i][2] : "";
    if (trim($kiemtra)!='Tên sản phẩm') {
        echo "Không tìm thấy cột 'Tên sản phẩm'!";
        return false;
    }

    $kiemtra=isset($sheet[$i][3]) ? $sheet[$i][3] : "";
    if (trim($kiemtra)!='Số lượng') {
        echo "Không tìm thấy cột 'Số lượng'!";
        return false;
    }

    $kiemtra=isset($sheet[$i][4]) ? $sheet[$i][4] : "";
    if (trim($kiemtra)!='Đơn giá') {
        echo "Không tìm thấy cột 'Đơn giá'!";
        return false;
    }

	$i++;
	for (;$i<$sheetCount;$i++){
		$product_code=trim($sheet[$i][1]);
		$name=trim($sheet[$i][2]);
		$quantity_exist=trim($sheet[$i][3]);
		$price=trim($sheet[$i][4]);

		if ($product_code=="" || $name=="" || $quantity_exist==""|| $price=="") {
			echo WRONG_FILE_STRUCTURE;
			return false;
        }

        if (!is_numeric($price) || !is_numeric($quantity_exist)) {
            echo WRONG_FILE_STRUCTURE;
			return false;
        }

        if (!$model->checkProductCode($product_code)) {
            echo "Mã sản phẩm '$product_code' không tồn tại!";
			return false;
        }

		$items[$product_code]['product_code']=$product_code;
		$items[$product_code]['name']=$name;
		$items[$product_code]['quantity_exist']=$quantity_exist;
		$items[$product_code]['price']=$price;
	}
	if (empty($items)) {
		echo WRONG_FILE_STRUCTURE;
		return false;
	}

	$res="";
	$res.="<table class='table table-bordered table-hover'>";
	$res.="<thead><tr>";
	$res.="<th style='text-align:center;'>Tên sản phẩm</th>";
	$res.="<th style='text-align:center;'>Số lượng</th>";
	$res.="<th style='text-align:center;'>Đơn giá</th>";
	$res.="<th style='text-align:center;'>Tùy chọn</th>";
	$res.="</tr></thead>";
	foreach($items as $i) {
		$res.="<tr class='products2import' >";
		$res.="<td><input type='hidden' class='order_qty' value='".$i['quantity_exist']."'/><input type='hidden' class='product_code' value='".$i['product_code']."'/><b>".$i['product_code'].": ".$i['name']."</b></td>";
		$res.="<td><input type='number' class='qty' value='".$i['quantity_exist']."'/></td>";
		$res.="<td>$<input type='number' step='0.01' class='price' value='".number_format($i['price'],PRICE_DECIMALS,'.','')."'/></td>";
		$res.="<td style='text-align:center;'><button class='btnXoa' onclick='$(this).parent().parent().remove();'><i class='fa fa-times'></i> Xóa</button></td>";
		$res.="</tr>";
	}
	$res.="</table>";
	echo $res;
	return true;
}

// nhap hang tu file excel
if (isset($_POST['action']) && $_POST['action']=='importProductsFromFile') {
	$place_order_code=$_POST['place_order_code'];
	$import_code=$_POST['import_code'];
	$id_employee=$_POST['id_employee'];
	$created_at=$_POST['created_at'];
	$total=$_POST['total'];
	$products=explode("|",$_POST['products']);
	//print_r($supp_code."+".$place_order_code."+".$import_code."+".$id_employee."+".$created_at."+".$total."+".);die;

	if ($model->checkImportCode($import_code)) {
		echo "Mã nhập hàng đã tồn tại!";
		return;
	}

	$model->insertImport($import_code,$created_at,$id_employee,$total,$place_order_code);
	foreach ($products as $product) {
		$p=explode(DELIMITER,$product);
		$product_code=$p[0];
		$quantity_in=$p[1];
		$price_in=round($p[2], PRICE_DECIMALS);
		$model->insertImportDetail($import_code,$product_code,$price_in,$quantity_in);

		$old_quantity=$model->getProductQuantity($product_code);
		$old_price=$model->getProductPrice($product_code);
        $new_price=$price_in+$price_in*IMPORT_INTEREST;
		$new_price=round($new_price, PRICE_DECIMALS);

		$model->updateOrderProducts($product_code,$old_quantity+$quantity_in,$new_price);
	}
	$model->updateOrderFinish($place_order_code,$import_code);
	echo "ok";
}
?>