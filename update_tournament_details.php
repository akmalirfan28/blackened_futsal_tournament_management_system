<?php
session_start();
include "connection.php";

$tournamentID = $_SESSION['tournamentID'];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $format = $_POST['format'];
    $teams = $_POST['teams'];
    $rules = $_POST['rules'];
    $fee = $_POST['fee'];
}

$sql = "UPDATE tournament SET name='$name', date='$date', time='$time', format='$format', 
rules='$rules', numOfTeams='$teams', fee='$fee' WHERE tournamentID='$tournamentID'";


$conn->query($sql);
header('location:list_tournament.php');    

?> 