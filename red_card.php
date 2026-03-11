<?php
session_start();
include "connection.php";

$matchID = $_GET['match_id'];
$player_id = $_GET['player_id'];
$team_id = $_GET['team_id'];
$tournamentID = $_SESSION['tournamentID'];


$query = "INSERT INTO match_events (match_id, tournament_id, event_type, team_id, player_red_id) 
              VALUES (?, ?, 'red_card', ?, ?)"; 
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiii', $matchID, $tournamentID, $team_id, $player_id);
    $stmt->execute();
    header('location:match_management.php');    


?> 