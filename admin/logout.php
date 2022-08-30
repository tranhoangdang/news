<?php
    session_start();
    require_once('../functions.php');
    session_destroy();
    redirect('login.php');
?>