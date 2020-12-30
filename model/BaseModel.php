<?php
require_once 'DBConnect.php';
class BaseModel extends DBConnect{

    function selectMenu(){
        $sql = "SELECT *
        FROM categories c
        WHERE c.status = '0'
        AND c.cate_code IN( SELECT p.cate_code FROM products p)";
        return $this->loadMoreRows($sql);
    }
}

?>