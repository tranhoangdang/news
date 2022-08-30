<?php
    include('database/conn.php');
    require_once('functions.php');

    if(!isset($_COOKIE['news'])){
        redirect('login.php');
    }
    else{
        $article = $_GET['article'];
        $user = $_COOKIE['news'];
        $sql = "SELECT articleID,userID FROM reactions WHERE articleID='$article' AND userID='$user'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $delete = "DELETE FROM reactions WHERE articleID='$article' AND userID='$user'";
            mysqli_query($conn,$delete);
            redirect('article.php?id='.$article);
        }
        else{
            $insert = "INSERT INTO reactions(articleID,userID) VALUES ('$article','$user')";
            mysqli_query($conn,$insert);
            redirect('article.php?id='.$article);
        }
    }
?>