<?php
if (!isset($_SESSION)) session_start();
$featuredProduct = $data['featuredProduct'];
$bestSellers = $data['bestSellers'];
$newProducts = $data['newProducts'];
include_once('helper\constants.php');
  // print_r($newProducts);
?>
<!-- Home Slider Start -->
<img style='height:500px;width:100%;object-fit:cover;' id="banner" src='public/source/images/slider/slide_1.jpg'>
<script>
let images = ["public/source/images/slider/slide_2.jpg", "public/source/images/slider/slide_3.jpg",
    "public/source/images/slider/slide_1.jpg"
];
setInterval(changeImage, 3000);

let index = 1;

function changeImage() {
    $("#banner").attr("src", images[index++]);
    if (index > 2) index = 0;
}
</script>

<!-- main container -->
<div class="main-container col1-layout">
    <div class="container">
        <div class="row">

            <!-- Home Tabs  -->
            <div class="col-sm-12 col-md-12 col-xs-12">
                <div class="home-tab">
                    <ul class="nav home-nav-tabs home-product-tabs">
                        <li class="active">
                            <a href="#featured" data-toggle="tab" aria-expanded="false">Sản phẩm nổi bật</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#top-sellers" data-toggle="tab" aria-expanded="false">Sản phẩm khuyến mãi</a>
                        </li>
                    </ul>
                    <div id="productTabContent" class="tab-content">
                        <div class="tab-pane active in" id="featured">
                            <div class="featured-pro">
                                <div class="slider-items-products">
                                    <div id="featured-slider" class="product-flexslider hidden-buttons">
                                        <div class="slider-items slider-width-col4">
                                            <?php foreach($featuredProduct as $p):?>
                                            <div class="product-item">
                                                <div class="item-inner">
                                                    <div class="product-thumbnail">
                                                        <?php if($p->percent != 'null'):?>
                                                        <div class="icon-sale-label sale-left">Sale</div>
                                                        <?php endif?>
                                                        <?php if($p->new == 1):?>
                                                        <div class="icon-new-label new-right">New</div>
                                                        <?php endif?>

                                                        <div class="pr-img-area">
                                                            <!-- detail.php?alias=iphone-x-64gb&id=2 -->
                                                            <a title="<?=$p->name?>" href="<?=$p->product_code?>">
                                                                <figure style='text-align:center;'>
                                                                    <img class="first-img"
                                                                        src="public/source/images/products/<?=$p->image?>"
                                                                        alt="html template">
                                                                </figure>
                                                            </a>
                                                            <button
                                                                selected-qty="<?php if (isset($_SESSION['cart']->items[$p->product_code])) echo $_SESSION['cart']->items[$p->product_code]['totalQtity']; else echo '0'; ?>"
                                                                quantity-exist="<?=$p->quantity_exist?>"
                                                                id-sp="<?=$p->product_code?>" type="button"
                                                                class="add-to-cart-mt">
                                                                <i class="fa fa-shopping-cart"></i>
                                                                <span>Thêm Vào Giỏ Hàng</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="item-info">
                                                        <div class="info-inner">
                                                            <div class="item-title">
                                                                <a title="<?=$p->name?>"
                                                                    href="<?=$p->product_code?>"><?=$p->name?></a>
                                                            </div>
                                                            <div class="item-content">
                                                                <div class="item-price">
                                                                    <div class="price-box">
                                                                        <?php if($p->percent != 'null'):?>
                                                                        <p class="special-price">
                                                                            <span
                                                                                class="price"><?=number_format($p->price-($p->percent*$p->price), PRICE_DECIMALS)?></span>
                                                                        </p>
                                                                        <p class="old-price">
                                                                            <span
                                                                                class="price"><?=number_format($p->price, PRICE_DECIMALS)?></span>
                                                                        </p>
                                                                        <?php else :?>
                                                                        <p class="special-price">
                                                                            <span
                                                                                class="price"><?=number_format($p->price, PRICE_DECIMALS)?></span>
                                                                        </p>
                                                                        <?php endif ?>
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
                        <div class="tab-pane fade" id="top-sellers">
                            <div class="top-sellers-pro">
                                <div class="slider-items-products">
                                    <div id="top-sellers-slider" class="product-flexslider hidden-buttons">
                                        <div class="slider-items slider-width-col4 ">
                                            <?php foreach($bestSellers as $p):?>
                                            <div class="product-item">
                                                <div class="item-inner">
                                                    <div class="product-thumbnail">
                                                        <?php if($p->percent != 'null'):?>
                                                        <div class="icon-sale-label sale-left">Sale</div>
                                                        <?php endif?>
                                                        <?php if($p->new == 1):?>
                                                        <div class="icon-new-label new-right">New</div>
                                                        <?php endif?>

                                                        <div class="pr-img-area">
                                                            <!-- detail.php?alias=iphone-x-64gb&id=2 -->
                                                            <a title="<?=$p->name?>" href="<?=$p->product_code?>">
                                                                <figure style='text-align:center;'>
                                                                    <img class="first-img"
                                                                        src="public/source/images/products/<?=$p->image?>"
                                                                        alt="html template">
                                                                </figure>
                                                            </a>
                                                            <button id-sp="<?=$p->product_code?>"
                                                                selected-qty="<?php if (isset($_SESSION['cart']->items[$p->product_code])) echo $_SESSION['cart']->items[$p->product_code]['totalQtity']; else echo '0'; ?>"
                                                                quantity-exist="<?=$p->quantity_exist?>" type="button"
                                                                class="add-to-cart-mt">
                                                                <i class="fa fa-shopping-cart"></i>
                                                                <span>Thêm Vào Giỏ Hàng</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="item-info">
                                                        <div class="info-inner">
                                                            <div class="item-title">
                                                                <a title="<?=$p->name?>"
                                                                    href="<?=$p->product_code?>"><?=$p->name?></a>
                                                            </div>
                                                            <div class="item-content">
                                                                <div class="item-price">
                                                                    <div class="price-box">
                                                                        <?php if($p->percent != 'null'):?>
                                                                        <p class="special-price">
                                                                            <span
                                                                                class="price"><?=number_format($p->price-($p->percent*$p->price), PRICE_DECIMALS)?></span>
                                                                        </p>
                                                                        <p class="old-price">
                                                                            <span
                                                                                class="price"><?=number_format($p->price, PRICE_DECIMALS)?></span>
                                                                        </p>
                                                                        <?php else :?>
                                                                        <p class="special-price">
                                                                            <span
                                                                                class="price"><?=number_format($p->price, PRICE_DECIMALS)?></span>
                                                                        </p>
                                                                        <?php endif ?>
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
                </div>
            </div>

        </div>
    </div>
</div>
<!-- end main container -->

<!--special-products-->

<div class="container">
    <div class="special-products">
        <div class="page-header">
            <h2>Sản phẩm mới</h2>
        </div>
        <div class="special-products-pro">
            <div class="slider-items-products">
                <div id="special-products-slider" class="product-flexslider hidden-buttons">
                    <div class="slider-items slider-width-col4">

                        <?php foreach($newProducts as $p):?>
                        <div class="product-item">
                            <div class="item-inner">
                                <div class="product-thumbnail">
                                    <?php if($p->new == 1):?>
                                    <div class="icon-new-label new-right">New</div>
                                    <?php endif?>

                                    <div class="pr-img-area">
                                        <!-- detail.php?alias=iphone-x-64gb&id=2 -->
                                        <a title="<?=$p->name?>" href="<?=$p->product_code?>">
                                            <figure style='text-align:center;'>
                                                <img class="first-img"
                                                    src="public/source/images/products/<?=$p->image?>"
                                                    alt="html template">
                                            </figure>
                                        </a>
                                        <button id-sp="<?=$p->product_code?>"
                                            selected-qty="<?php if (isset($_SESSION['cart']->items[$p->product_code])) echo $_SESSION['cart']->items[$p->product_code]['totalQtity']; else echo '0'; ?>"
                                            quantity-exist="<?=$p->quantity_exist?>" type="button"
                                            class="add-to-cart-mt">
                                            <i class="fa fa-shopping-cart"></i>
                                            <span>Thêm Vào Giỏ Hàng</span>
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
                                                    <p class="special-price">
                                                        <span
                                                            class="price"><?=number_format($p->price, PRICE_DECIMALS)?></span>
                                                    </p>
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
<!-- category area start -->
<div class="jtv-category-area">

    <!--img src="public/source/images/slider/slideshow.jpg" alt="slidebg1" data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat"-->

</div>
<!-- category-area end -->