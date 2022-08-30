<?php
$servername = "us-cdbr-east-06.cleardb.net";
$username = "b96264832b7c31";
$password = "c646ad05";
$db = "heroku_f0508a6630fe0c9";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>