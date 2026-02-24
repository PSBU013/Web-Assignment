<?php
    $hostname = "sql308.ezyro.com";
    $username = "EZYRO_41238432";
    $password = "Me01741ooo898";
    $dbname = "ezyro_41238432_Blogsystem";

    $conn = new mysqli($hostname,$username,$password,$dbname);

    if($conn->connect_error){
        die("Database Connection failed");
    }
?>
