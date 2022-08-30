<?php
    require_once('functions.php');
    if(isset($_COOKIE['news'])){
        setcookie("login", '', time()-1);
    }
    redirect('index.php');
?>