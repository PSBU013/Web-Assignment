<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "blogsystem";

    $conn = new mysqli($hostname,$username,$password,$dbname);

    if($conn->connect_error){
        die("Database Connection failed");
    } else {
        echo("Database Connected");
    }
?>