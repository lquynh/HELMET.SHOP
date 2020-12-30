<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once('..\model\BillModel.php');
if (!isset($_SESSION['login_email'])) header("location:../login.php");
if (!isset($_GET['status'])) header("location:manage-bill.php?status=0");
$status=$_GET['status'];
$model= new BillModel;
$orders=$model->getOrder($status);

$employee=get_object_vars($model->getIdEmploy($_SESSION['login_email']));
$idEmploy=$employee["id"];
$idName=$employee["name"];

$role=$_SESSION['role'];
if($role==ID_ROLE_SHIPPER && $status!=ORDER_MYSHIPORDERS)
    header('location:views/manage-bill.php?status='.ORDER_MYSHIPORDERS);

function getShipperName($idShipper) {
    if ($idShipper==0)
        return "<i>Không có</i>";
    $model= new BillModel;
    return get_object_vars($model->getEmployeeName($idShipper))['name'];
}
?>
<head>
    <title>Quản lý đơn hàng</title>
    <?php include_once('..\views\common.php'); ?>
</head>
<body>
    <section id="container">
    <?php include_once('header.php')?>

    <!--sidebar start-->
    <?php
    if(isset($_SESSION['login_email'])):
    include_once('menu.php');
    endif?>

    <section id="main-content">
    <section class="wrapper">
      <div class="panel panel-body">
        <section class="content">
            <div class="panel panel-default">
                <div class="panel-heading">
                <b>
				<?php
					echo "Danh sách đơn hàng ";
					if ($status==ORDER_PENDING) echo "chưa duyệt";
					// if ($status==ORDER_WAITFORSHIPPER) echo "chờ shipper xác nhận";
					if ($status==ORDER_INDELIVERY) echo "đang giao";
					if ($status==ORDER_FINISHED) echo "đã hoàn tất";
					if ($status==ORDER_CANCELLED) echo "đã bị hủy";
					if ($status==ORDER_MYSHIPORDERS) echo "được giao cho <span style='color:red;'>".$idName."</span>";
				?>
                </b>
                </div>
                <div class="panel-body">
				<?php
				if (empty($orders)) {
					echo "<i>Chưa có đơn hàng :(</i>";
				} else {
					echo "<table class='table table-bordered table-hover'>";
					echo "<thead>";
					echo "<th style='text-align:center;'>Mã</th>";
					echo "<th style='text-align:center;'>Ngày đặt</th>";
					echo "<th style='text-align:center;'>Khách</th>";
					echo "<th style='text-align:center;'>Địa chỉ</th>";
					echo "<th style='text-align:center;'>Sản phẩm</th>";
					echo "<th style='text-align:center;'>Tổng</th>";
					echo "<th style='text-align:center;'>NV giao</th>";
					echo "<th style='text-align:center;'>Tùy chọn</th>";
					echo "</thead>";

					foreach($orders as $o) {
						$products=$model->getProductDetail($status,$o->id);
                        $shippers=$model->getShipper($o->district_code);
                        //print_r($shippers);
						echo "<tr>";
						echo "<td style='text-align:center;'>".$o->id."</td>";
						echo "<td>".date('d-m-Y',strtotime($o->created_at))."</td>";
						echo "<td>".$o->name."</td>";
						echo "<td>".$o->address.", ".$o->district_name."</td>";
						echo "<td>";
						foreach($products as $p)
							echo "▪ ".$p->quantity_out." ".$p->name."<br/>";
						echo "</td>";
						echo "<td><span class='price'>".number_format($o->total,PRICE_DECIMALS,'.','')."</span></td>";
						if ($status==ORDER_PENDING) {
							// nv giao
							echo "<td>";
							if(empty($shippers))
								echo "<i>Không có shipper cho khu vực này</i>";
							else {
								echo "<select style='width:100%;' id='categoryEmploy-".$o->id."'>";
								foreach($shippers as $s)
									echo "<option value='".$s->id_employee."'>".$s->name."_C".$s->DONCHUAGIAO."_H".$s->DONHOANTAT."_T".$s->TONGSODON."</option>";
								echo "</select>";
							}
							echo "</td>";
							// tuy chon
							echo "<td><span class='optionButtons'>";
							echo "<button class='btn btn-success btnDuyet' data-id='".$o->id."' data-status='".ORDER_INDELIVERY."' data-cus='".$o->id_customer."'>Duyệt</button>";
                            echo "<button class='btn btn-danger btnHuy' data-id='".$o->id."' data-cus='".$o->id_customer."' data-status='".ORDER_CANCELLED."'>Hủy đơn</button>";
							echo "</span></td>";
                        }
                        // if ($status==ORDER_WAITFORSHIPPER) {
						// 	echo "<td><i>Đang chờ <b>".getShipperName($o->id_shipper)."</b> xác nhận...</i></td>";
						// 	echo "<td><button class='btn btn-danger btnThuHoi' data-id='".$o->id."' data-cus='".$o->id_customer."' data-status='".ORDER_REVOKE."'>Thu hồi</button></td>";
                        // }
                        if ($status==ORDER_INDELIVERY) {
							echo "<td><b>".getShipperName($o->id_shipper)."</b></td>";
							echo "<td><span class='optionButtons'>";
                            echo "<button id='btnHoanTat' class='btn btn-success btnHoanTat' data-id='".$o->id."' data-status='".ORDER_FINISHED."' data-cus='".$o->id_customer."'>Hoàn Tất</button>";
                            echo "<button class='btn btn-danger btnThuHoi' data-id='".$o->id."' data-cus='".$o->id_customer."' data-status='".ORDER_REVOKE."'>Hủy giao</button>";
							echo " <a href='views/bill.php?id=$o->id'><button class='btn btn-success'>In</button></a>";
							echo "</span></td>";
                        }
                        if ($status==ORDER_FINISHED) {
							echo "<td><b>".getShipperName($o->id_shipper)."</b></td>";
							echo "<td>";
							echo "<a href='views/bill.php?id=$o->id'><button class='btn btn-success'>In</button></a>";
							echo "</td>";
                        }
                        if ($status==ORDER_CANCELLED) {
							echo "<td colspan='2'><i style='text-align:center;'>Đã hủy</i></td>";
                        }
                        if ($status==ORDER_MYSHIPORDERS) { // for shipper accounts only
							echo "<td><b>".getShipperName($o->id_shipper)."</b></td>";
							echo "<td><span class='optionButtons'>";
                            if ($o->status==ORDER_INDELIVERY) {
                                echo "<button class='btn btn-success btnHoanTat' data-id='".$o->id."' data-cus='".$o->id_customer."' data-status='".ORDER_FINISHED."'>Đã giao</button>";
                                echo "<a href='http://google.com/maps/dir/".SHOP_ADDRESS."/$o->address, $o->district_name, TP.HCM' target='_blank'><button class='btn btn-success'>Chỉ đường</button></a>";
                            }
                            if($o->status==ORDER_FINISHED)
								echo "<i style='color:green;font-weight:bold;'>Đã giao thành công</i>";
							echo "</span></td>";
						}
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
    $(document).ready(function () {

    $('.btnDuyet').click(function(e){
        loading_on();
        var idOrder = $(this).attr('data-id');
        var idStatus= $(this).attr('data-status');
        var idCus=$(this).attr('data-cus');
        var idEmploy="<?=$idEmploy?>";
        var idShipper=$('#categoryEmploy-'+idOrder).val();
        if (idShipper==undefined) {
            alert("Không có shipper cho khu vực này!");
            loading_off();
            return;
        }
        $.ajax({
            url:"/helmet_shop/admin/editbill.php",
            type:'POST',
            data:{
                idOrder:idOrder,
                idStatus:idStatus,
                idEmploy:idEmploy,
                idShipper:idShipper,
                idCus:idCus
            },
            success:function(res){
                loading_off();
                console.log(res)
                if($.trim(res)=='Cập nhật thất bại'){
                    alert('Cập nhật thất bại');
					return false;
                }
				if($.trim(res)=='Gửi email thất bại'){
                    alert('Gửi email thất bại');
					return false;
                }
				alert('Đơn hàng đang được giao!');
				location.reload();
            },
            error:function(err){
                console.log(err);
                loading_off();
            }
        })
    });

    $(".btnThuHoi").click(function(event) {
        xuLyDonHang(event, "Đơn hàng được chuyển về trạng thái chờ duyệt!");
    });

    $('.btnHoanTat').click(function(){
        xuLyDonHang(event, "Đơn hàng đã được hoàn tất!");
    });

    $('.btnHuy').click(function(){
        xuLyDonHang(event, "Đơn hàng đã bị hủy!");
    });

    function xuLyDonHang(event, message) {
        loading_on();
        var idOrder=$(event.target).attr('data-id');
        var idStatus=$(event.target).attr('data-status');
        var idCus=$(event.target).attr('data-cus');
        $.ajax({
            url:"/helmet_shop/admin/editbill.php",
            type:'POST',
            data:{
                idOrder:idOrder,
                idStatus:idStatus,
                idCus:idCus
            },
            success:function(res){
                loading_off();
                console.log(res)
                if (res != "ok") {
                    alert(res);
                    return false;
                }
				alert(message);
				location.reload();
            }
        });
    }
})
</script>
</body>
</html>