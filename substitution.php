<?php
session_start();
include "connection.php";

$matchID = $_GET['match_id'];
$player_out_id = $_GET['player_out_id'];
$team_id = $_GET['team_id'];
$player_in_id = $_GET['player_in_id'];
$tournamentID = $_SESSION['tournamentID'];

// Record the substitution event
$query = "INSERT INTO match_events (match_id, tournament_id, event_type, team_id, player_out_id, player_in_id) 
          VALUES (?, ?, 'substitution', ?, ?, ?)"; 
$stmt = $conn->prepare($query);
$stmt->bind_param('iiiii', $matchID, $tournamentID, $team_id, $player_out_id, $player_in_id);
$stmt->execute();
header('location:match_management.php');    
    
?>

