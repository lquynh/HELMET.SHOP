<?php
session_start();
if (!isset($_SESSION['login_email'])) header("location:../login.php");
include_once('..\model\FunctionModel.php');
require_once('..\helper\constants.php');
$model=new FunctionModel;

if (isset($_POST['action']) && $_POST['action']=='editFunctionUrl') {
    $id_function=$_POST['id_function'];
	$url=$_POST['url'];
	$title=$_POST['title'];
	$id_category=$_POST['id_category'];
    $display_on_homepage=$_POST['display_on_homepage'];

    if ($model->checkFunctionUrlEdit($id_function,$url,$title)) {
        echo "Url hoặc Tiêu đề đã tồn tại!";
        return false;
    }
	$model->updateFunctionUrl($id_function,$id_category,$url,$title,$display_on_homepage);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='addFunctionUrl') {
	$url=$_POST['url'];
	$title=$_POST['title'];
	$id_category=$_POST['id_category'];
    $display_on_homepage=$_POST['display_on_homepage'];

    if ($model->checkFunctionUrl($url,$title)) {
        echo "Url hoặc Tiêu đề đã tồn tại!";
        return false;
    }

	$model->addFunctionUrl($id_category,$url,$title,$display_on_homepage);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='deleteFunctionUrl') {
	$id_function=$_POST['id_function'];

    if (!$model->canDeleteFunctionUrl($id_function)) {
        echo "Không thể xóa URL này vì vẫn còn phân quyền đang tham chiếu!";
        return false;
    }

	$model->deleteFunctionUrl($id_function);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='editFunctionCategory') {
    $id_category=$_POST['id_category'];
	$cate_name=$_POST['cate_name'];
	$ordering=$_POST['ordering'];

    if ($model->isFunctionCategoryNameExistingEdit($id_category,$cate_name)) {
        echo "Tên danh mục đã tồn tại!";
        return false;
    }

	$model->updateFunctionCategory($id_category,$cate_name,$ordering);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='addFunctionCategory') {
	$cate_name=$_POST['cate_name'];
    $ordering=$_POST['ordering'];

    if ($model->isFunctionCategoryNameExisting($cate_name)) {
        echo "Tên danh mục đã tồn tại!";
        return false;
    }

	$model->addFunctionCategory($cate_name,$ordering);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='deleteFunctionCategory') {
	$id_category=$_POST['id_category'];

    if (!$model->isFunctionCategoryOkayToDelete($id_category)) {
        echo "Không thể xóa vì vẫn còn URL tham chiếu tới danh mục này!";
        return false;
    }

	$model->deleteFunctionCategory($id_category);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='editFunctionMapping') {
    $original_id_function=$_POST['original_id_function'];
    $original_id_role=$_POST['original_id_role'];
    $id_function=$_POST['id_function'];
    $id_role=$_POST['id_role'];

    if ($model->checkFunctionMapping($id_function,$id_role)) {
        echo "Phân quyền đã tồn tại!";
        return false;
    }

	$model->updateFunctionMapping($original_id_function,$original_id_role,$id_function,$id_role);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='addFunctionMapping') {
	$id_function=$_POST['id_function'];
    $id_role=$_POST['id_role'];

    if ($model->checkFunctionMapping($id_function,$id_role)) {
        echo "Phân quyền đã tồn tại!";
        return false;
    }

	$model->addFunctionMapping($id_function,$id_role);
	echo "ok";
}

if (isset($_POST['action']) && $_POST['action']=='deleteFunctionMapping') {
	$id_function=$_POST['id_function'];
	$id_role=$_POST['id_role'];

	$model->deleteFunctionMapping($id_function,$id_role);
	echo "ok";
}
?>