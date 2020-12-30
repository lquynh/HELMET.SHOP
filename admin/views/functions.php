<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
	include_once('..\model\FunctionModel.php');
	include_once('..\model\UserModel.php');
	if (!isset($_SESSION['login_email'])) header("location:../login.php");
    $model=new FunctionModel;
    $userModel=new UserModel;
    $functions=null;
    $function_urls=$model->getFunctionUrls();
    $function_categories=$model->getFunctionCategories();
    $roles=$userModel->getRoles();
    $f_type=$_GET['type'];
    if ($f_type==0)
        $functions=$function_urls;
    if ($f_type==1)
        $functions=$function_categories;
    if ($f_type==2)
        $functions=$model->getFullFunctionDetails();
?>
<head>
    <title>Danh sách phân quyền</title>
    <?php include_once('..\views\common.php'); ?>
<style>
#editNhanVien td:nth-child(1){font-weight:bold;width:150px;}
#editNhanVien input,#editNhanVien select{width:100%;}
#btnAddUrl,#btnAddCategory,#btnAddFunction{margin-bottom:7px;}
</style>
</head>
<body>
    <section id="container">
	<?php include_once('header.php')?>
	<?php if(isset($_SESSION['login_email'])) include_once('menu.php'); ?>
	<section id="main-content">
	<section class="wrapper">
    <div class="panel panel-body">
        <section class="content">
            <div class="panel panel-default">
				<div class="panel-heading">
					<b>Danh sách <?php if ($f_type==0) echo "URL"; if ($f_type==1) echo "danh mục"; if ($f_type==2) echo "phân quyền"; ?></b>
				</div>
				<div class="panel-body">

                <?php
                    if ($f_type==0){
                        echo "<button class='btnAdd' id='btnAddUrl'><i class='fa fa-plus'></i> Thêm URL</button><br/>";
                        echo "<table class='table table-bordered table-hover'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th width='3%' style='text-align:center;'style='text-align:center;'>STT</th>";
                        echo "<th width='20%' style='text-align:center;'>URL</th>";
                        echo "<th style='text-align:center;'>Tiêu đề</th>";
                        echo "<th style='text-align:center;'>Danh mục</th>";
                        echo "<th width='10%' style='text-align:center;'>Hiển thị ở trang chủ?</th>";
                        echo "<th width='10%' style='text-align:center;'>Tùy chọn</th>";
                        echo "</tr>";
                        echo "</thead>";
                        $stt=1;
                        foreach ($functions as $f) {
                            echo "<tr>";
                            echo "<td style='text-align:center;'>".$stt++."</td>";
                            echo "<td><b>$f->url</b></td>";
                            echo "<td>$f->title</td>";
                            echo "<td>$f->cate_name</td>";
                            echo "<td>";
                            echo $f->display_on_homepage==1?"Có":"Không";
                            echo "</td>";
                            echo "<td><span class='optionButtons'><button id_function='$f->id_function' url='$f->url' title='$f->title' id_category='$f->id_category' display_on_homepage='$f->display_on_homepage' class='btn btn-success btnEditUrl'>Sửa</button><button id_function='$f->id_function' title='$f->title' class='btn btn-danger btnDeleteUrl'>Xóa</button></span></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                    if ($f_type==1){
                        echo "<button class='btnAdd' id='btnAddCategory'><i class='fa fa-plus'></i> Thêm danh mục</button><br/>";
                        echo "<table class='table table-bordered table-hover'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th width='3%' style='text-align:center;'>STT</th>";
                        echo "<th style='text-align:center;'>Danh mục</th>";
                        echo "<th width='10%' style='text-align:center;'>Thứ tự</th>";
                        echo "<th width='10%' style='text-align:center;'>Tùy chọn</th>";
                        echo "</tr>";
                        echo "</thead>";
                        $stt=1;
                        foreach ($functions as $f) {
                            echo "<tr>";
                            echo "<td style='text-align:center;'>".$stt++."</td>";
                            echo "<td><b>$f->cate_name</b></td>";
                            echo "<td style='text-align:center;'>$f->ordering</td>";
                            echo "<td><span class='optionButtons'><button id_category='$f->id_category' cate_name='$f->cate_name' ordering='$f->ordering' class='btn btn-success btnEditCategory'>Sửa</button><button id_category='$f->id_category' cate_name='$f->cate_name' class='btn btn-danger btnDeleteCategory'>Xóa</button></span></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                    if ($f_type==2){
                        echo "<button class='btnAdd' id='btnAddFunction'><i class='fa fa-plus'></i> Thêm phân quyền</button><br/>";
                        echo "<table class='table table-bordered table-hover'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th width='5%' style='text-align:center;'>STT</th>";
                        echo "<th style='text-align:center;'>Url</th>";
                        echo "<th width='20%' style='text-align:center;'>Quyền</th>";
                        echo "<th width='15%' style='text-align:center;'>Tùy chọn</th>";
                        echo "</tr>";
                        echo "</thead>";
                        $stt=1;
                        foreach ($functions as $f) {
                            echo "<tr>";
                            echo "<td style='text-align:center;'>".$stt++."</td>";
                            echo "<td><b>$f->url</b> <i>(".$f->title.")</i><br/>".$f->cate_name."</td>";
                            echo "<td><b>$f->role_name</b></td>";
                            echo "<td><span class='optionButtons'>";
                            if (strpos($f->url,"functions.php")===false)
                                echo "<button id_function='$f->id_function' title='$f->title' id_role='$f->id_role' role_name='$f->role_name' cate_name='$f->cate_name' class='btn btn-success btnEditFunctionMapping'>Sửa</button><button class='btn btn-danger btnDeleteFunctionMapping' id_function='$f->id_function' id_role='$f->id_role' role_name='$f->role_name' url='$f->url'>Xóa</button>";

                            echo "</span></td>";
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

<style>
#editUrl table td:nth-child(1) {font-weight:bold;}
</style>

    <div id='editUrl' class='absolute_center'>
        <table class='table table-bordered table-hover'>
            <tr>
                <td width='15%'>URL</td>
                <td><input type='txt' id='urlLink'/><input type='hidden' id='urlId'/></td>
            </tr>
            <tr>
                <td>Tiêu đề</td>
                <td><input type='txt' id='urlTitle'/></td>
            </tr>
            <tr>
                <td>Danh mục</td>
                <td>
                    <select id='urlCategory'>
                    <?php
                        foreach($function_categories as $category)
                            echo "<option value='$category->id_category'>$category->cate_name</option>";
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Hiển thị ở trang chủ</td>
                <td>
                    <select id='urlDisplayOnHomepage'>
                        <option value='1'>Có</option>
                        <option value='0'>Không</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button id='btnUpdateUrl'>Cập nhật</button></td>
            </tr>
        </table>
        <button id='btnCloseUrl' class='btnClose'><i class="fa fa-times"></i> Đóng</button>
    </div>

    <div id='editCategory' class='absolute_center'>
        <table class='table table-bordered table-hover'>
            <tr>
                <td width='15%'>Tên danh mục</td>
                <td><input type='txt' id='cate_name'/><input type='hidden' id='id_category' /></td>
            </tr>
            <tr>
                <td>Thứ tự</td>
                <td><input type='number' id='ordering'/></td>
            </tr>
            <tr>
                <td></td>
                <td><button id='btnUpdateCategory'>Cập nhật</button></td>
            </tr>
        </table>
        <button id='btnCloseCategory' class='btnClose'><i class="fa fa-times"></i> Đóng</button>
    </div>

    <div id='editFunctionMapping' class='absolute_center'>
        <table class='table table-bordered table-hover'>
            <tr>
                <td width='15%'>Url</td>
                <td>
                    <select id='function'>
                    <?php
                    foreach($function_urls as $f)
                        echo "<option value='$f->id_function'>$f->url</option>";
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Quyền</td>
                <td>
                    <select id='role'>
                    <?php
                    foreach($roles as $r)
                        echo "<option value='$r->id_role'>$r->name</option>";
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button id='btnUpdateFunctionMapping'>Cập nhật</button></td>
            </tr>
        </table>
        <button id='btnCloseFunction' class='btnClose'><i class="fa fa-times"></i> Đóng</button>
    </div>

<script>
    const ADD = "Adding";
    const EDIT = "Editing";
    let editMode = "";

    let original_url_info="";
    let original_category_info="";
    let original_function_mapping_info="";

    $(document).ready(function(e) {

        $(".btnEditUrl").click(function(e) {
            let id_function = $(this).attr("id_function");
            let url = $(this).attr("url");
            let title = $(this).attr("title");
            let id_category = $(this).attr("id_category");
            let display_on_homepage = $(this).attr("display_on_homepage");
            original_url_info=url+title+id_category+display_on_homepage;

            $("#urlId").val(id_function);
            $("#urlLink").val(url);
            $("#urlTitle").val(title);
            $("#urlCategory").val(id_category);
            $("#urlDisplayOnHomepage").val(display_on_homepage);

            $("#editUrl").css("display","block");
            goToTop();

            editMode = EDIT;
        });

        $(".btnDeleteUrl").click(function(e) {
            let id_function = $(this).attr("id_function");
            let title = $(this).attr("title");
            let ans=confirm("Bạn có thật sự muốn xóa url '"+title+"'?");
            if (!ans) return false;
            $.ajax({
                url: "controller/FunctionController.php",
                type: "POST",
                data: {
                    action: "deleteFunctionUrl",
                    id_function: id_function
                },
                success: function(res) {
                    if (res!="ok") {
                        alert(res);
                        return false;
                    }
                    alert("Xóa thành công!");
                    location.reload();
                }
            });
        });

        $("#btnUpdateUrl").click(function(e) {
            let id_function = $("#urlId").val();
            let url = $("#urlLink").val();
            let title = $("#urlTitle").val();
            let id_category = $("#urlCategory").val();
            let display_on_homepage = $("#urlDisplayOnHomepage").val();
            let new_url_info=url+title+id_category+display_on_homepage;

            if (new_url_info===original_url_info) {
                alert("Không có gì thay đổi!");
                return;
            }

            let error="";
            if (url=="")
                error+="Không được bỏ trống URL!\n";
            if (title=="")
                error+="Không được bỏ trống Tiêu đề!\n";

            if (error != "") {
                alert(error);
                return false;
            }

            let action = (editMode == EDIT) ? "editFunctionUrl" : "addFunctionUrl";
            $.ajax({
                url: "controller/FunctionController.php",
                type: "POST",
                data: {
                    action: action,
                    id_function: id_function,
                    url: url,
                    title: title,
                    id_category: id_category,
                    display_on_homepage: display_on_homepage
                },
                success: function(res) {
                    if (res!="ok") {
                        alert(res);
                        return false;
                    }
                    let msg=(editMode==EDIT)?"Cập nhật thành công!":"Thêm thành công!";
                    alert(msg);
                    location.reload();
                }
            });
        });

        $(".btnEditCategory").click(function(e) {
            let id_category = $(this).attr("id_category");
            let cate_name = $(this).attr("cate_name");
            let ordering = $(this).attr("ordering");
            original_category_info=cate_name+ordering;

            $("#id_category").val(id_category);
            $("#cate_name").val(cate_name);
            $("#ordering").val(ordering);

            $("#editCategory").css("display","block");

            goToTop();
            editMode = EDIT;
        });

        $(".btnDeleteCategory").click(function(e) {
            let id_category = $(this).attr("id_category");
            let cate_name = $(this).attr("cate_name");
            let ans=confirm("Bạn có thật sự muốn xóa danh mục '"+cate_name+"'?");
            if (!ans) return false;
            $.ajax({
                url: "controller/FunctionController.php",
                type: "POST",
                data: {
                    action: "deleteFunctionCategory",
                    id_category: id_category
                },
                success: function(res) {
                    if (res!="ok") {
                        alert(res);
                        return false;
                    }
                    alert("Xóa thành công!");
                    location.reload();
                }
            });
        });

        $("#btnUpdateCategory").click(function(e) {
            let id_category = $("#id_category").val();
            let cate_name = $("#cate_name").val();
            let ordering = $("#ordering").val();
            let new_category_info=cate_name+ordering;

            if (new_category_info===original_category_info) {
                alert("Không có gì thay đổi!");
                return;
            }

            let error="";
            error+=cate_name==""?"Không được bỏ trống Tên danh mục!\n":"";
            error+=validateOrdering(ordering);

            if (error != "") {
                alert(error);
                return false;
            }

            let action = (editMode == EDIT) ? "editFunctionCategory" : "addFunctionCategory";
            $.ajax({
                url: "controller/FunctionController.php",
                type: "POST",
                data: {
                    action: action,
                    id_category: id_category,
                    cate_name: cate_name,
                    ordering: ordering
                },
                success: function(res) {
                    if (res!="ok") {
                        alert(res);
                        return false;
                    }
                    let msg=(editMode==EDIT)?"Cập nhật thành công!":"Thêm thành công!";
                    alert(msg);
                    location.reload();
                }
            });
        });

        let original_id_function="";
        let original_id_role="";
        $(".btnEditFunctionMapping").click(function(e) {
            let id_function=$(this).attr("id_function");
            let id_role=$(this).attr("id_role");
            original_id_function=id_function;
            original_id_role=id_role;
            original_function_mapping_info=id_function+id_role;

            $("#function").val(id_function);
            $("#role").val(id_role);
            $("#editFunctionMapping").css("display","block");

            goToTop();
            editMode=EDIT;
        });

        $("#btnUpdateFunctionMapping").click(function(e) {
            let id_function=$("#function").val();
            let id_role=$("#role").val();
            new_function_mapping_info=id_function+id_role;

            if (new_function_mapping_info==original_function_mapping_info) {
                alert("Không có gì thay đổi!");
                return false;
            }

            let action = (editMode == EDIT) ? "editFunctionMapping" : "addFunctionMapping";
            $.ajax({
                url: "controller/FunctionController.php",
                type: "POST",
                data: {
                    action: action,
                    original_id_function: original_id_function, // only for edit
                    original_id_role: original_id_role, // only for edit
                    id_function: id_function,
                    id_role: id_role
                },
                success: function(res) {
                    if (res!="ok") {
                        alert(res);
                        return false;
                    }
                    let msg=(editMode==EDIT)?"Cập nhật thành công!":"Thêm thành công!";
                    alert(msg);
                    location.reload();
                }
            });
        });

        $(".btnDeleteFunctionMapping").click(function(e) {
            let id_function = $(this).attr("id_function");
            let id_role = $(this).attr("id_role");
            let role_name = $(this).attr("role_name");
            let url = $(this).attr("url");

            let ans=confirm("Bạn có thật sự muốn xóa phân quyền '"+role_name+"' cho url '"+url+"'?");
            if (!ans) return false;
            $.ajax({
                url: "controller/FunctionController.php",
                type: "POST",
                data: {
                    action: "deleteFunctionMapping",
                    id_function: id_function,
                    id_role: id_role
                },
                success: function(res) {
                    if (res!="ok") {
                        alert(res);
                        return false;
                    }
                    alert("Xóa thành công!");
                    location.reload();
                }
            });
        });

        $(".btnAdd").click(function(e) {
            let id=$(this).attr("id");
            if (id=="btnAddUrl") {
                $("#urlId").val("");
                $("#urlLink").val("");
                $("#urlTitle").val("");
                $("#urlDisplayOnHomepage").val(0);
                $("#editUrl").css("display","block");
            }
            if (id=="btnAddCategory") {
                $("#id_category").val("");
                $("#cate_name").val("");
                $("#ordering").val("");
                $("#editCategory").css("display","block");
            }
            if (id=="btnAddFunction") {
                $("#function").val($("#function option:first").val());
                $("#role").val($("#role option:first").val());
                $("#editFunctionMapping").css("display","block");
            }
            editMode = ADD;
        });

        $(".btnClose").click(function(e) {
            $("#editUrl").css("display","none");
            $("#editCategory").css("display","none");
            $("#editFunctionMapping").css("display","none");
        });
    });
</script>
</body>
</html>