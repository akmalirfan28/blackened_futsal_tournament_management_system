<?php
session_start();
include "connection.php";
$tournamentID = $_SESSION['tournamentID'];
$bracketID = $_GET['NAME'];

$sql = "UPDATE tournament_brackets SET team1_id=null, team1_score=null, team2_id=null, team2_score=null, winner_id=null WHERE bracket_id='$bracketID'";
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  $result = $conn->query("SELECT * FROM match_schedule WHERE bracket_id = '$bracketID'");
  while ($row = $result->fetch_assoc()) {
      $matchID = $row['match_id'];
      $sql = "DELETE FROM match_events WHERE match_id = '$matchID'";
      $conn->query($sql);
  }

  $sql = "DELETE FROM match_schedule WHERE bracket_id = '$bracketID'";
$conn->query($sql);

header('location:manage_bracket.php');    

?> 