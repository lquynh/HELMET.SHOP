<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\controller\MenuController.php');
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\helper\constants.php');
	
    $c=new MenuController;
    $product_types=$c->getType();
	
	$menu=[];
	// print_r($displayedFunctions);
	foreach($displayedFunctions as $f) {
		if (!array_key_exists($f->cate_name, $menu))
			$menu[$f->cate_name]=[];
		 
		if ($f->cate_name=='Quản lý sản phẩm') {
			foreach($product_types as $type)
				array_push($menu[$f->cate_name],[$f->url."?type=".$type->cate_code,$type->name]);
		} else {
			array_push($menu[$f->cate_name],[$f->url,$f->title]);
		}
		// if (array_key_exists('Quản lý đơn hàng', $menu))
			// sort($menu['Quản lý đơn hàng']);
	}
?>
<aside>
    <div id="sidebar" class="nav-collapse ">
        <ul class="sidebar-menu" id="nav-accordion">
		<?php
			foreach($menu as $key => $value) {
				echo "<li class='sub-menu'>";
				echo "<a href='javascript:;' ><i class='fa fa-bars'></i><span>".$key."</span></a>";
				echo "<ul class='sub'>";
				foreach($value as $v)
					echo "<li><a href='views/".$v[0]."'>".$v[1]."</a></li>";
				echo "</ul>";
				echo "</li>";
			}
		?>
        </ul>
    </div>
</aside>