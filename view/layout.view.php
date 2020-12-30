<?php 
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<head>

  <title><?=$title?></title>
  <?php include_once('view\common.php'); ?>

  <style>
    .mega-menu-category > .nav > li.active > a{background:#e65d55}
    .top-search{margin-top:0;margin-bottom:-4px}
    .header-container{background:#fff}
    .add-to-cart-mt{background:#e65d55}
    button.button.pro-add-to-cart{background:#e65d55;border:2px #e65d55 solid}
    .page-order .cart_navigation a.checkout-btn{background:#e65d55;border:2px #e65d55 solid}
    .add-to-cart-mt:hover{background:#000}
    button.button{background:#e65d55;border:2px #e65d55 solid}
    .bullet.selected{background:#e65d55!important}
  </style>
</head>
<body class="shop_grid_page">
  <div id="page">
    <!-- Header -->
    <header>
      <div class="header-container">
        <div class="header-top">
          <div class="container">
            <div class="row">
              <div class="col-lg-4 col-sm-4 hidden-xs">
                <!-- Default Welcome Message -->
                <div class="welcome-msg"><?=SHOP_NAME?></div>
                <span class="phone hidden-sm">SĐT: <?=SHOP_PHONE?></span>
              </div>

              <!-- top links -->
              <div class="headerlinkmenu col-lg-8 col-md-7 col-sm-8 col-xs-12">
                <div class="links">
                <?php if(isset($_SESSION['name'])){?>
                  <div class="myaccount">
                    <a title="Tài khoản" href="account.php">
                      <i class="fa fa-user"></i>
                      <span class="hidden-xs"><?php echo $_SESSION['name'];?></span>
                    </a>
                  </div>
				  <div class="myorders">
                    <a title="Theo dõi đơn hàng" href="my-orders.php">
                      <i class="fa fa-location-arrow"></i>
                      <span class="hidden-xs">Đơn hàng</span>
                    </a>
                  </div>
                  <div class="logout">
                    <a href="logout.php">
                      <i class="fa fa-unlock-alt"></i>
                      <span class="hidden-xs">Đăng xuất</span>
                    </a>
                  </div>
                <?php }else{?>
                  <div class="login">
                    <a href="login.html">
                      <i class="fa fa-unlock-alt"></i>
                      <span class="hidden-xs">Đăng nhập</span>
                    </a>
                  </div>
                <?php }?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-sm-3 col-md-3 col-xs-12">
              <!-- Header Logo -->
              <div class="logo">
                <a title="e-commerce" href="index.html">
                  <img alt="responsive theme logo" src="public/source/images/logohelmet.jpg" style="width:170px;height:70px;">
                </a>
              </div>
              <!-- End Header Logo -->
            </div>
            <div class="col-xs-9 col-sm-6 col-md-6">
              <!-- Search -->
              <div class="top-search">
                <div id="search">
                  <form action="search.html">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Tìm kiếm" name="keyword">
                      <button class="btn-search" type="submit">
                        <i class="fa fa-search"></i>
                      </button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- End Search -->
            </div>
            <!-- top cart -->

            <div class="col-lg-3 col-xs-3 top-cart">
              <div class="top-cart-contain">
                <div class="mini-cart">
                  <div class="basket dropdown-toggle">
                    <a href="shopping-cart.php">
                      <div class="cart-icon">
                        <i class="fa fa-shopping-cart"></i>
                      </div>
                      <div class="shoppingcart-inner hidden-xs" id="shoppingcart-inner">
                        <span class="cart-title">Giỏ hàng</span>
                        <span class="cart-total" id="cart-total">
                          <?php 
                          if(isset($_SESSION['cart'])){
                            echo $_SESSION['cart']->totalQtity;
                            echo " item(s) - $";
                            echo number_format($_SESSION['cart']->promtPrice, PRICE_DECIMALS);
                          }else{
                            echo "$0";
                          }
                          ?>
                        </span>
                      </div>
                    </a>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
    <!-- end header -->

    <!-- Navbar -->
    <nav style="background:#e65d55;height:55px;">
      <div class="container">
        <div class="row">
            <div style="text-align:center;">
				<a class='navbtn' href="index.html">Trang Chủ</a>
				<?php foreach($menu as $m):?>
				<a class='navbtn' href="type.php?type=<?=$m->cate_code?>"><?=$m->name?></a>
				<?php endforeach ?>
			</div>
        </div>
      </div>
    </nav>
    <!-- end nav -->

    <?php include_once "$view.view.php" ;?>

    <footer>
      <!--div class="footer-newsletter">
        <div class="container">
          <div class="row">
            <div class="col-md-8 col-sm-7">
              <form id="newsletter-validate-detail" method="post" action="#">
                <h3 class="hidden-sm">Đăng ký nhận tin</h3>
                <div class="newsletter-inner">
                  <input class="newsletter-email" name='Email' placeholder='Nhập email để nhận tin' />
                  <button class="button subscribe" type="submit" title="Subscribe"></button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div-->
      <style>
        #footer-info{display:block;background:#333e48;width:100%;padding:50px;text-align:center;font-size:13px;}
        #footer-info table{width:100%;}
        #footer-info table td{color:#fff;padding:5px;};
      </style>
      <div id='footer-info'>
        <table>
            <tr>
                <td width='28%'>
                    <b>Thông tin</b><br/>
                    <br />
                    <?=SHOP_NAME?> nhận cung cấp sỉ và lẻ mũ bảo hiểm chất lượng cao, nguồn gốc xuất xứ đảm bảo với mức giá cạnh tranh nhất trên thị trường.
                </td>
                <td width='28%'>
                    <b>Lời nhắn</b><br/>
                    <br />
                    <?=SHOP_NAME?> tự hào là nơi mang đến cho bạn những chiếc mũ bảo hiểm đẹp, cao cấp, kiểu dáng trẻ trung, năng động, là người đồng hành với bạn trên mọi cung đường. Cảm ơn bạn đã tin tưởng và lựa chọn <?=SHOP_NAME?>.
                </td>
                <td width='28%'>
                    <b>Liên hệ</b><br/>
                    <br />
                    <b><?=SHOP_NAME?></b><br/>
                    Số điện thoại: <?=SHOP_PHONE?><br/>
                    Địa chỉ: <?=SHOP_ADDRESS?><br/>
                    Email: <?=SHOP_EMAIL?><br/>
                    <br/>
                    Với hình thức liên lạc qua điện thoại, xin vui lòng liên hệ trong giờ hành chính từ thứ 2 tới thứ 6 để nhận được sự hỗ trợ tốt nhất.
                </td>
                <td width='7%'>
                    <b>Liên kết</b><br />
                    <br />
                    <a style='color:#e65d55;' href='admin/login.php' target='_blank'>Trang quản trị</a>
                    <br />
                    <a style='color:#e65d55;' href='https://www.facebook.com/8chuong98' target='_blank'>Facebook</a>
                </td>
            </tr>
        </table>
      </div>
    </footer>
  </div>

  <!-- End Footer -->

  <!-- JS -->
  <script type="text/javascript" src="public/source/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="public/source/js/owl.carousel.min.js"></script>
  <script type="text/javascript" src="public/source/js/jquery.bxslider.js"></script>
  <script type="text/javascript" src="public/source/js/jquery.flexslider.js"></script>
  <script type="text/javascript" src="public/source/js/megamenu.js"></script>
  <script type="text/javascript">
    /* <![CDATA[ */
    var mega_menu = '0';
    /* ]]> */
  </script>
  <script type="text/javascript" src="public/source/js/mobile-menu.js"></script>
  <script type="text/javascript" src="public/source/js/main.js"></script>
  <script type="text/javascript" src="public/source/js/countdown.js"></script>
  <script type="text/javascript" src="public/source/js/cloud-zoom.js"></script>
  <script type="text/javascript" src="public/source/js/jquery.twbsPagination.js"></script>
  <?php if($view=='home'):?>
      <!-- Slider Js -->
      <script type="text/javascript" src="public/source/js/revolution-slider.js"></script>

    <!-- Revolution Slider -->
    <script type="text/javascript">
      $(document).ready(function () {
        $('.tp-banner').revolution(
          {
            delay: 9000,
            startwidth: 1170,
            startheight: 530,
            hideThumbs: 10,

            navigationType: "bullet",
            navigationStyle: "preview1",

            hideArrowsOnMobile: "on",

            touchenabled: "on",
            onHoverStop: "on",
            spinner: "spinner4"
          });
      });
    </script>
  <?php endif?>
  <script>
  $(document).ready(function(){
  // "Thêm Giỏ Hàng", các nút trong các section "Sản phẩm liên quan", "nổi bật"...
    $('.add-to-cart-mt, .pro-add-to-cart').click(function(){
      let thisBtn=$(this);
      var idSP=$(thisBtn).attr('id-sp');
      var soluongdachon=$(thisBtn).attr('selected-qty');
      var soluongtrongkho=$(thisBtn).attr('quantity-exist');
      if ((parseInt(soluongtrongkho)-(parseInt(soluongdachon)+1)) < 0) {
        alert('Số lượng trong kho không đủ để chọn thêm!');
        return false;
      }

      $.ajax({
        url:"cart.php",
        type:"POST",
        dataType: "JSON",
        data:{
          id:idSP,
          action:"add"
        },
        success:function(res){
          console.log(res);
          $("#cart-total").html(res.totalQtity+" item(s) - $"+res.promtPrice.toFixed(<?=PRICE_DECIMALS?>));
          $(thisBtn).attr('selected-qty', ++soluongdachon);
          let soluongdachonText=$("#selected-qty");
          if ($(soluongdachonText).attr("product_code")==idSP)
          $(soluongdachonText).text(soluongdachon);
        },
        error:function(){
          console.log('errr');
        }
      });
    });
  })
</script>
</body>
</html>