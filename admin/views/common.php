<?php
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\FunctionModel.php');
if (isset($_SESSION['role'])) {
	$role=$_SESSION['role'];
	$m=new FunctionModel;
	$displayedFunctions=$m->getFunctions($role);
	$fullFunctions=$m->getFullFunctions($role);

	$url=explode("/",$_SERVER['REQUEST_URI']);
	$current_url=$url[count($url)-1];

	if ($current_url!='login.php' && $current_url!='logout.php') {
		$has_permission=false;
		foreach($fullFunctions as $f) {
			// echo $current_url." ".$f->url." ".strpos($current_url,$f->url)."<br/>";
			if (strpos($current_url,$f->url) !== false) {
				$has_permission=true;
				break;
			}
		}
		if (!$has_permission) {
			// echo "does not have right";
			header("location:../login.php");
		}
	}
}
?>
<meta charset="utf-8">
<base href="<?=ADMIN_URL?>">
<link href="favicon.ico" rel="icon" />
<link href="libraries/css/bootstrap.min.css" rel="stylesheet">
<link href="libraries/css/bootstrap-reset.css" rel="stylesheet">
<link href="libraries/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href="libraries/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" href="libraries/css/owl.carousel.css" type="text/css">
<link href="libraries/css/slidebars.css" rel="stylesheet">
<link href="libraries/css/style.css" rel="stylesheet">
<link href="libraries/css/style-responsive.css" rel="stylesheet" />
<script src="libraries/ckeditor/ckeditor.js"></script>
<script src="libraries/ckfinder/ckfinder.js"></script>

<link href="libraries/css/jquery-ui-1.10.4.css" rel="stylesheet">
<script src="libraries/js/jquery-1.10.2.js"></script>
<script src="libraries/js/jquery-ui-1.10.4.js"></script>
<script src="libraries/js/my-utilities.js"></script>

<div id='loading-screen' style='z-index:10000;display:none;background:#000;opacity:0.7;color:#fff;position:fixed;top:0;left:0;width:100%;height:100%;text-align:center;margin:0 auto;'><span style='position:absolute;top:50%;'>Đang xử lý...</span></div>

<script>
function loading_on() { $("#loading-screen").css("display","block"); }
function loading_off() { $("#loading-screen").css("display","none"); }
</script>