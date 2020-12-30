<?php

function createToken(){
    $string = "QWERTYUIOPASDFGJKLZXCVBNMqwertyuioasdfghjkzxcvbnm1234567890";
    $token = '';
    for($i=1;$i<=30;$i++){
        $start = rand(0,strlen($string)-1);
        $token .= substr($string,$start, 1);
    }
    return $token;
}


?>