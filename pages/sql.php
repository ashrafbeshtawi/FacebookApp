<?php

    function sql($query,$password){
    $servername = "removed for security reasons";
    $username = "removed for security reasons";
    $dbname = "removed for security reasons";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } 
    $sql_result=$conn->query($query);
    return $sql_result;
    }



?>