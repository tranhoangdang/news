<?php
    include('database/conn.php');
    require_once('functions.php');
    
    $token = $_GET['token'];
    $sql = "SELECT OTP FROM users WHERE OTP='$token'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $time = date('Y-m-d H:i:s');
        $update = "UPDATE users SET is_active=1, verified_at='$time', OTP=NULL WHERE OTP='$token'";
        if($conn->query($update) === TRUE){
            redirect('login.php');
        }
    }
    else{
        redirect('index.php');
    }
?>