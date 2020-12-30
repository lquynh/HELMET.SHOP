<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'\helmet_shop\admin\model\BaseModel.php');
    class FunctionModel extends BaseModel{

        function getFunctions($id_role){
            $sql = "SELECT f.url,f.title,f.display_on_homepage,fc.cate_name FROM function f
					INNER JOIN function_detail fd ON f.id_function=fd.id_function
					INNER JOIN function_categories fc ON f.id_category=fc.id_category
					WHERE (id_role='$id_role'
					AND f.display_on_homepage='1')
					ORDER BY fc.ordering,f.url";
			// print_r($sql);
			return  $this->loadMoreRows($sql);
        }

		function getFullFunctions($id_role){
            $sql = "SELECT f.url,f.title,f.display_on_homepage,fc.cate_name FROM function f
					INNER JOIN function_detail fd ON f.id_function=fd.id_function
					INNER JOIN function_categories fc ON f.id_category=fc.id_category
					WHERE (id_role='$id_role')
					ORDER BY f.url DESC";
			// print_r($sql);
			return  $this->loadMoreRows($sql);
		}

		function getFullFunctionDetails() {
			$sql="SELECT f.id_function,fd.id_role,r.name AS role_name,f.url,f.title,f.display_on_homepage,fc.cate_name,fc.ordering
			FROM function_detail fd
			INNER JOIN function f ON fd.id_function=f.id_function
			INNER JOIN function_categories fc ON f.id_category=fc.id_category
			INNER JOIN role r ON fd.id_role=r.id_role
			ORDER BY fd.id_role,f.id_category,f.url";
			return $this->loadMoreRows($sql);
		}

		function getFunctionUrls() {
			$sql="SELECT f.id_function,f.id_category,f.url,f.title,f.display_on_homepage,fc.cate_name
			FROM function f
			INNER JOIN function_categories fc ON f.id_category=fc.id_category
			ORDER BY f.id_category,f.url";
			return $this->loadMoreRows($sql);
        }

        function checkFunctionUrl($url,$title) {
            $sql="SELECT * FROM function WHERE (url='$url' OR title='$title')";
            return $this->loadOneRow($sql);
        }

        function checkFunctionUrlEdit($id_function,$url,$title) {
            $sql="SELECT * FROM function WHERE (url='$url' OR title='$title') AND id_function!='$id_function'";
            return $this->loadOneRow($sql);
        }

        function addFunctionUrl($id_category,$url,$title,$display_on_homepage) {
            $sql="INSERT INTO function(id_category,url,title,display_on_homepage) VALUES('$id_category','$url','$title','$display_on_homepage')";
            return $this->executeQuery($sql);
        }

        function updateFunctionUrl($id_function,$id_category,$url,$title,$display_on_homepage) {
            $sql="UPDATE function SET id_category='$id_category',title='$title',url='$url',display_on_homepage='$display_on_homepage' WHERE id_function='$id_function'";
            return $this->executeQuery($sql);
        }

        function canDeleteFunctionUrl($id_function) {
			if ($this->loadOneRow("SELECT * FROM function_detail WHERE id_function='$id_function'")) return false;
			return true;
		}

        function deleteFunctionUrl($id_function) {
            $sql="DELETE FROM function WHERE id_function='$id_function'";
            return $this->executeQuery($sql);
        }

        function getFunctionCategories() {
			$sql="SELECT *
			FROM function_categories fc
            ORDER BY ordering";
			return $this->loadMoreRows($sql);
        }

        function isFunctionCategoryNameExisting($cate_name) {
            $sql="SELECT * FROM function_categories WHERE cate_name='$cate_name'";
            return $this->loadOneRow($sql);
        }

        function isFunctionCategoryNameExistingEdit($id_category,$cate_name) {
            $sql="SELECT * FROM function_categories WHERE cate_name='$cate_name' AND id_category!='$id_category'";
            return $this->loadOneRow($sql);
        }

        function addFunctionCategory($cate_name,$ordering) {
            $sql="INSERT INTO function_categories(cate_name,ordering) VALUES('$cate_name','$ordering')";
            return $this->executeQuery($sql);
        }

        function updateFunctionCategory($id_category,$cate_name,$ordering) {
            $sql="UPDATE function_categories SET cate_name='$cate_name',ordering='$ordering' WHERE id_category='$id_category'";
            return $this->executeQuery($sql);
        }

        function isFunctionCategoryOkayToDelete($id_category) {
            $sql="SELECT * FROM function WHERE id_category='$id_category'";
            return !$this->loadOneRow($sql);
        }

        function deleteFunctionCategory($id_category) {
            $sql="DELETE FROM function_categories WHERE id_category='$id_category'";
            return $this->executeQuery($sql);
        }

        function checkFunctionMapping($id_function,$id_role) {
            $sql="SELECT * FROM function_detail WHERE id_function='$id_function' AND id_role='$id_role'";
            return $this->loadOneRow($sql);
        }

        function addFunctionMapping($id_function,$id_role) {
            $sql="INSERT INTO function_detail(id_function,id_role) VALUES('$id_function','$id_role')";
            return $this->executeQuery($sql);
        }

        function updateFunctionMapping($original_id_function,$original_id_role,$id_function,$id_role) {
            $sql="UPDATE function_detail SET id_function='$id_function',id_role='$id_role' WHERE id_function='$original_id_function' AND id_role='$original_id_role'";
            return $this->executeQuery($sql);
        }

        function deleteFunctionMapping($id_function,$id_role) {
            $sql="DELETE FROM function_detail WHERE id_function='$id_function' AND id_role='$id_role'";
            return $this->executeQuery($sql);
        }
    }
?>