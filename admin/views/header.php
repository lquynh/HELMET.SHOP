<!--header start-->
<header class="header white-bg">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
    </div>
    <div class="top-nav ">
        <!--search & user info start-->
        <b style="top:9px;position:relative;font-size:20px;color:red;">TRANG QUẢN TRỊ <?=SHOP_NAME?></b>
        <ul class="nav pull-right top-menu">
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <!--img alt="" src="admin/img/avatar1_small.jpg"-->
                    <span class="username"><?php if(isset($_SESSION['login_email'])){
                        echo $_SESSION['login_email'];}?>
                    </span>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu extended logout">
                    <li><a href="views/my-account.php"><i class="fa fa-cog"></i> Tài khoản</a></li>
                    <li><a href="logout.php"><i class="fa fa-sign-out"></i>Đăng xuất</a></li>
                </ul>
            </li>
            <!-- user login dropdown end -->
        </ul>
        <!--search & user info end-->
    </div>
</header>
<!--header end-->