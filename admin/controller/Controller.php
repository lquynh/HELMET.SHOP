<?php
class Controller{

    function loadView($view,$title = 'Home',$data=[]){
        include_once "$view.php";
    }

    function callViewAjax($view,$result){
        include_once "views/ajax/$view.php";
    }
}

?>