<?php

$servername = "localhost"; 
$username = "root";
$password = ""; 
$database = "bd_php";


$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

?>
