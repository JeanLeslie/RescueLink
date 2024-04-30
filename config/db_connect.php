<?php
    //connect to database
    $conn = mysqli_connect('localhost','admin','RescueLink2024','rescuelinkapp');
    if (!$conn){
        echo 'Connection error'.mysqli_connect_error();
    }
?>