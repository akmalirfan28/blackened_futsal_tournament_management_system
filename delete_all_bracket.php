<?php
session_start();
include "connection.php";
$tournamentID = $_SESSION['tournamentID'];

$sql = "DELETE FROM tournament_brackets WHERE tournament_id = '$tournamentID'";


$conn->query($sql);
header('location:create_bracket.php');    

?>