<?php
require_once 'Controller.php';

class SignUpController extends Controller {
    function getSingin(){
        $this->loadView('login');
    }
}