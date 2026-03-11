<?php
session_start();
include "connection.php";


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $format = $_POST['format'];
    $teams = $_POST['teams'];
    $rules = $_POST['rules'];
    $fee = $_POST['fee'];
}

$sql = "INSERT INTO tournament VALUES ('', '$name', '$date', '$time', '$format', '$rules', '$teams', '$fee', 'Not Started')";


$conn->query($sql);
header('location:list_tournament.php');    

?> 