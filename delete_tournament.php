<?php
session_start();
include "connection.php";

$tournamentID = $_GET['NAME'];

$sql = "DELETE FROM tournament WHERE tournamentID='$tournamentID'";


$conn->query($sql);
header('location:list_tournament.php');    

?> 