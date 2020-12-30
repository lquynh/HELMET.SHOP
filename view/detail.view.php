<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\helper\constants.php');
$product = $data['product'];
$relatedProducts = $data['relatedProducts'];

$selected_qty=0;
$limit_qty=$product->quantity_exist;
if (isset($_SESSION['cart']->items[$product->product_code])) {
	$cart_reflect=$_SESSION['cart']->items[$product->product_code];
	$selected_qty=$cart_reflect['totalQtity'];
}
$limit_qty=$limit_qty-$selected_qty;
//print_r($cart_reflect);
?>
<!-- Main Container -->
<div class="main-container col1-layout">
      <div class="container">
        <div class="row">
          <div class="col-main">
            <div class="product-view-area" style="width:100%">
              <div class="product-big-image col-xs-12 col-sm-5 col-lg-5 col-md-5" style="width:26%;border: solid 1px #fbf8f8;">
                <?php if($product->percent!=null){?>
                <div class="icon-sale-label sale-left">Sale</div>
                <?php }if($product->new ==1){?>
                <div class="icon-new-label new-right">New</div>
                <?php }?>
                <div class="large-image">
                  <!--a href="public/source/images/products/<?=$product->image?>" class="cloud-zoom" id="zoom1" rel="useWrapper: false, adjustY:0, adjustX:20">
                    <img class="zoom-img" src="public/source/images/products/<?=$product->image?>" alt="products"> </a-->
				<a href="public/source/images/products/<?=$product->image?>" id="zoom1" rel="useWrapper: false, adjustY:0, adjustX:20">
				<img  src="public/source/images/products/<?=$product->image?>" alt="products"> </a>
                </div>
              </div>
              <div class="col-xs-12 col-sm-7 col-lg-7 col-md-7 product-details-area">

                <div class="product-name">
                  <h1><?=$product->name?></h1>
                </div>
                <div class="price-box">
                  <?php if($product->percent!=null){?>
                  <p class="special-price">
                    <span class="price-label">Giá khuyến mãi: </span>
                    <span class="price"><?=number_format($product->price-($product->percent*$product->price),PRICE_DECIMALS,'.','')?></span>
                  </p>
                  <p class="old-price">
                    <span class="price-label">Giá gốc: </span>
                    <span class="price"><?=number_format($product->price, PRICE_DECIMALS,'.','')?></span>
                  </p>
                  <?php }else{?>
                  <p class s="special-price">
                    <span class="price-label">Đơn giá: </span>
                    <span class="price"><?=number_format($product->price, PRICE_DECIMALS,'.','')?></span>
                  </p>
                  <?php }?>
                </div>
            <?php if($product->quantity_exist==0){?>
                  <div style="background:#fed700;width:20%">
                    <h3 style="color:red">HẾT HÀNG</h3>
                  </div>
            <?php }?>
                <div class="short-description">
                  <h2>Thông tin chi tiết</h2>
                  <?=$product->description?>

                </div>
                <div class="product-variation">
				<table>
					<!--tr>
						<td width="230px">SỐ LƯỢNG TRONG KHO</td>
						<td><b id='quantity-exist'><?=$product->quantity_exist?></b></td>
					</tr-->
					<tr>
						<td><b>ĐÃ CHỌN</b></td>
						<td style='font-weight:bold'><span product_code='<?=$product->product_code?>' id='selected-qty'><?=$selected_qty?></span>/<span style='color:red;'><?=$product->quantity_exist?></span></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<button id-sp="<?=$product->product_code?>" selected-qty="<?php if (isset($_SESSION['cart']->items[$product->product_code])) echo $_SESSION['cart']->items[$product->product_code]['totalQtity']; else echo '0'; ?>" quantity-exist="<?=$product->quantity_exist?>" class="button pro-add-to-cart" title="Thêm Giỏ Hàng" type="button">
							<span><i class="fa fa-shopping-cart"></i>Thêm Vào Giỏ Hàng</span>
							</button>
						</td>
					</tr>
				</table>
                </div>

              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Main Container End -->
    <!-- Related Product Slider -->
    <section class="upsell-product-area">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">

            <div class="page-header">
              <h2>Sản phẩm liên quan</h2>
            </div>
            <div class="slider-items-products">
              <div id="upsell-product-slider" class="product-flexslider hidden-buttons">
                <div class="slider-items slider-width-col4">
                  <?php foreach($relatedProducts as $p):?>
                  <div class="product-item">
                            <div class="item-inner">
                              <div class="product-thumbnail">
                                <?php if($p->percent != null):?>
                                <div class="icon-sale-label sale-left">Sale</div>
                                <?php endif?>
                                <?php if($p->new == 1):?>
                                <div class="icon-new-label new-right">New</div>
                                <?php endif?>

                                <div class="pr-img-area">
                                  <!-- detail.php?alias=iphone-x-64gb&id=2 -->
                                  <a title="<?=$p->name?>" href="<?=$p->product_code?>">
                                    <figure style='text-align:center'>
                                      <img class="first-img" src="public/source/images/products/<?=$p->image?>" alt="html template">
                                    </figure>
                                  </a>
                                  <button selected-qty="<?php if (isset($_SESSION['cart']->items[$p->product_code])) echo $_SESSION['cart']->items[$p->product_code]['totalQtity']; else echo '0'; ?>" quantity-exist="<?=$p->quantity_exist?>" id-sp="<?=$p->product_code?>" type="button" class="add-to-cart-mt">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>THÊM VÀO GIỎ HÀNG</span>
                                  </button>
                                </div>
                              </div>
                              <div class="item-info">
                                <div class="info-inner">
                                  <div class="item-title">
                                    <a title="<?=$p->name?>" href="<?=$p->product_code?>"><?=$p->name?></a>
                                  </div>
                                  <div class="item-content">
                                  <div class="item-price">
                                    <div class="price-box">
                                      <?php if($p->percent!= null){?>
                                      <p class="special-price">
                                        <span class="price"><?=number_format($p->price-($p->percent*$p->price), PRICE_DECIMALS)?></span>
                                      </p>
                                      <p class="old-price">
                                        <span class="price"><?=number_format($p->price, PRICE_DECIMALS)?></span>
                                      </p>
                                      <?php }else {?>
                                      <p class="special-price">
                                        <span class="price"><?=number_format($p->price, PRICE_DECIMALS)?></span>
                                      </p>
                                      <?php }?>
                                    </div>
                                  </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                  <?php endforeach?>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
    <!-- Related Product Slider End -->
                     