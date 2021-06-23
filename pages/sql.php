<?php

    function sql($query,$password){
    $servername = "localhost";
    $username = "id8300997_nerd";
    $dbname = "id8300997_bot";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } 
    $sql_result=$conn->query($query);
    return $sql_result;
    }



?>