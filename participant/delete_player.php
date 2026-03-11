<?php
session_start();
include "connection.php";
$teamID = $_SESSION['teamID'];
$playerID = $_GET['NAME'];

$sql = "DELETE FROM player WHERE playerID='$playerID'";


$conn->query($sql);
$_SESSION['teamID'] = $teamID;
header('location:player_register.php');    

?> 