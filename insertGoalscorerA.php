<?php
session_start();
include "connection.php";


$matchID = $_SESSION['matchID'];
$playerID = $_GET['NAME'];
$_SESSION['teamA_score']++;
$scoreA = $_SESSION['teamA_score'];
$teamID = $_SESSION['teamID'];
$tournamentID = $_SESSION['tournamentID'];

$query = "INSERT INTO match_events (match_id, tournament_id, event_type, team_id, player_id) 
          VALUES (?, ?, 'goal', ?, ?)"; 
$stmt = $conn->prepare($query);
$stmt->bind_param('iiii', $matchID, $tournamentID, $teamID, $playerID);
$stmt->execute();

$stmt_update_score = $conn->prepare("UPDATE match_schedule SET team1_score = ? WHERE match_id = ?");
$stmt_update_score->bind_param("ii", $_SESSION['teamA_score'], $_SESSION['matchID']);
$stmt_update_score->execute();
$stmt_update_score->close();

header('location:match_management.php');    

?> 