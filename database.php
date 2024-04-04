<?php

$db_server = "localhost";
$db_user = "admin";
$db_password = "admin";
$db_name = "businessdb";

$conn = "";


try{
    $conn = mysqli_connect(
        $db_server,
        $db_user,
        $db_password,
        $db_name
    );
    
}
catch(mysqli_sql_exception){
    echo "Не удалось подключиться к базе данных<br>";
}