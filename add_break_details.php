<?php
session_start();
include "connection.php";


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $time = $_POST['time'];
}

$sql = "INSERT INTO break VALUES ('', '$name', '$time')";


$conn->query($sql);
header('location:schedule.php');    

?> 