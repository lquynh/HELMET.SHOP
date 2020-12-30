<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\helper\constants.php');
// print_r($data);
?>
<style>
button {
    width: 20px;
}
</style>
<section class="main-container col1-layout">
    <div class="main container">
        <div class="col-main">
            <div class="cart">
                <div class="page-content page-order">
                    <div class="page-title">
                        <h2>GIỎ HÀNG</h2>
                    </div>
                    <div class="order-detail-content">
                        <div class="table-responsive">
                            <table class="table table-bordered cart_summary">
                                <thead>
                                    <tr>
                                        <th class="cart_product">Sản phẩm</th>
                                        <th>Tên SP</th>
                                        <th>Đơn giá</th>
                                        <th width='150px'>Số lượng</th>
                                        <th width='100px'>Tổng tiền</th>
                                        <th width='100px' class="action"><i class="fa fa-trash-o"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data->items as $idSP=>$sp) : ?>
                                    <tr id="cart-row-<?=$idSP?>">
                                        <td class="cart_product">
                                            <a href="<?= $idSP ?>">
                                                <img src="public/source/images/products/<?=$sp['item']->image?>"
                                                    alt="Product">
                                            </a>
                                        </td>
                                        <td class="cart_description">
                                            <p class="product-name"><a href="<?=$idSP?>"><?=$sp['item']->name?></a></p>
                                        </td>
                                        <td style='text-align:center;'>
                                            <?php if ($sp['item']->percent != null) : ?>
                                            <span
                                                class="price"><?php $price=$sp['item']->price;$unit_price=round($price-($sp['item']->percent*$price),PRICE_DECIMALS); echo number_format($unit_price,PRICE_DECIMALS,'.','');?></span><br>
                                            <del><?=number_format($price,PRICE_DECIMALS,'.','')?></del>
                                            <?php else : ?>
                                            <span
                                                class="price"><?php $unit_price=$sp['item']->price;echo number_format($unit_price,PRICE_DECIMALS,'.','');?></span>
                                            <?php endif ?>
                                        </td>
                                        <td class="qty">
                                            <button class='btnTangGiam' qty=<?=$sp['item']->quantity_exist?>
                                                id-sp=<?=$idSP?>>+</button>
                                            <input id-sp="<?=$idSP?>" id='soluong-<?=$idSP?>' size='4' class="input-sm"
                                                type="text" readonly value="<?=$sp['totalQtity'] ?>">
                                            <button class='btnTangGiam' id-sp=<?=$idSP?>>-</button>
                                        </td>
                                        <td style='text-align:center;'>
                                            <span class="price"
                                                id='tong-tien-giam-<?=$idSP?>'><?=number_format($unit_price*$sp['totalQtity'],PRICE_DECIMALS,'.','')?></span>
                                            <br>
                                            <?php if($sp['item']->percent !=null):?>
                                            <del
                                                id='tong-tien-goc-<?=$idSP?>'><?=number_format($price*$sp['totalQtity'],PRICE_DECIMALS,'.','')?></del>
                                            <?php endif?>
                                        </td>
                                        <td class="action">
                                            <a class="remove-item-cart" id='remove-all-<?=$idSP ?>'
                                                id-sp="<?=$idSP?>"><i class="icon-close"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" rowspan="2"></td>
                                        <td colspan="3">Giá gốc (chưa khuyến mãi)</td>
                                        <td colspan="2"><del class='totalPrice'><?=number_format($data->totalPrice,PRICE_DECIMALS,'.','')?></del></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><strong>Tổng thanh toán</strong></td>
                                        <td colspan="2"><strong class="promtPrice" id='totalPrice'><?=number_format($data->promtPrice,PRICE_DECIMALS,'.','')?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="cart_navigation">
                            <a class="continue-btn" href="./"><i class="fa fa-arrow-left"> </i>&nbsp; Tiếp tục mua
                                sắm</a>
                            <a class="checkout-btn" id="btnDatHang" style='cursor:pointer;'><i class="fa fa-check"></i>
                                Đặt hàng </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="public/source/js/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.remove-item-cart').click(function() {
        var idSP = $(this).attr('id-sp');
        $.ajax({
            url: "cart.php",
            type: "POST",
            data: {
                id: idSP,
                action: "removeAll"
            },
            dataType: "JSON",
            success: function(res) {
                $('#cart-row-' + idSP).hide(0);
                $('.totalPrice').html(res.totalPrice.toFixed(<?=PRICE_DECIMALS?>));
                $('.promtPrice').html(res.promtPrice.toFixed(<?=PRICE_DECIMALS?>));
                $("#cart-total").html(res.totalQtity + " item(s) - $" + res.promtPrice
                    .toFixed(<?=PRICE_DECIMALS?>));
            },
            error: function() {
                console.log('errrrr');
            }
        })
    });

    $(".btnTangGiam").click(function() {
        let btnLabel = $(this)[0].innerText;
        let idSP = $(this).attr('id-sp');
        let soluong = $("#soluong-" + idSP).val();
        let quantity_exist = parseInt($(this).attr("qty"));
        let action = "";
        if (btnLabel == "+") {
            if (soluong >= quantity_exist)
                return false;
            action = "add";
        } else {
            action = "remove";
        }

        $.ajax({
            url: "cart.php",
            type: "POST",
            data: {
                id: idSP,
                action: action
            },
            dataType: "JSON",
            success: function(res) {
                console.log("SP:" + res);
                if (parseInt(res.maxQty) < soluong) {
                    soluong = res.maxQty;
                    res.item.qty = res.maxQty;
                    $("#soluong-" + idSP).val(soluong);
                }
                if (parseInt(res.item.totalQtity) == 0)
                    $("#remove-all-" + idSP).click();

                $("#soluong-" + idSP).val(res.item.totalQtity);
                $('#tong-tien-giam-' + idSP).html(res.item.promtPrice.toFixed(
                    <?=PRICE_DECIMALS?>));
                $('#tong-tien-goc-' + idSP).html(res.item.totalPrice.toFixed(
                    <?=PRICE_DECIMALS?>));
                $('.totalPrice').html(res.totalPrice.toFixed(<?=PRICE_DECIMALS?>));
                $('.promtPrice').html(res.promtPrice.toFixed(<?=PRICE_DECIMALS?>));
                $("#cart-total").html(res.totalQtity + " item(s) - $" + res.promtPrice
                    .toFixed(<?=PRICE_DECIMALS?>));
            },
            error: function(e) {
                console.log("Error adding item to cart: ");
                console.log(e.responseText);
            }
        })
    });

    $('#btnDatHang').click(function(e) {
        let totalPrice = parseInt($("#totalPrice").text());
        if (totalPrice == 0) {
          alert("Bạn chưa có sản phẩm nào trong giỏ hàng!");
          return;
        }
        <?php if(!isset($_SESSION['name'])){ ?>
            window.location.replace('login.html');
        <?php } else { ?>
            window.location.replace('checkout.php');
        <?php }?>
    })
})
</script>