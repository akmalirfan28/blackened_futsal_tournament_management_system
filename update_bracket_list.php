<?php
session_start();
include "connection.php";
$tournamentID = $_SESSION['tournamentID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bracket_id = (int) $_POST['bracket_id']; 
    $team1_id = (int) $_POST['team1_id'];
    $team2_id = (int) $_POST['team2_id'];
    $stmt_update_score = $conn->prepare("UPDATE tournament_brackets SET team1_id = ?, team2_id = ? WHERE bracket_id = ?");
  $stmt_update_score->bind_param("iii", $team1_id, $team2_id, $bracket_id);
  $stmt_update_score->execute();
  $stmt_update_score->close();
}

$result = $conn->query("SELECT * FROM tournament_brackets WHERE bracket_id = '$bracket_id'");
while ($row = $result->fetch_assoc()) {
    $round = $row['round'];
}

$query = "INSERT INTO match_schedule (tournament_id, bracket_id, knockout_round, team1_id, team2_id) 
                VALUES (?, ?, ?, ?, ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('iisii', $tournamentID, $bracket_id, $round, $team1_id, $team2_id);
                    $stmt->execute();   

header('location:manage_bracket.php');    

?> 