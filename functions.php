<?php
    function redirect($to){
        header("location:" . $to);
        exit;
    }

    function set_flash_session($key, $value){
        if(empty($_SESSION[$key])){
            $_SESSION[$key] = $value;
        }
    }

    function get_flash_session($key){
        if(!empty($_SESSION[$key])){
            $mess = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $mess;
        }
    }

    
?>