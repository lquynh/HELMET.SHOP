<?php
    class CheckController{
        function ktraMin($tag,$name,$number){
            if(strlen($tag)<$number){
                $_SESSION['errormail']=$name.' không được nhỏ hơn '. $number .' kí tự';
                return false;
            }
            return true;
        }
        function ktraPhone($phone){
            $pattern = '/^[0-9]+$/';
            if(!preg_match($pattern, $phone, $matches)){
                $_SESSION['errormail']='SĐT không hợp lệ';
                return false;
            }
            return true;
        }

        function kiemTraMinPhone($phone){
            if(strlen($phone)<10 || strlen($phone)>10){
                $_SESSION['errormail']='SĐT phải có 10 chữ số';
                return false;
            }
            return true;
        }

    }
?>