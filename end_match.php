<?php
session_start();
include "connection.php";
$matchID = $_SESSION['matchID'];
$tournamentID = $_SESSION['tournamentID'];

$stmt_update_score = $conn->prepare("UPDATE match_schedule SET match_status = 'Finish' WHERE match_id = ?");
$stmt_update_score->bind_param("i", $matchID);
$stmt_update_score->execute();
$stmt_update_score->close();

$result = $conn->query("SELECT * FROM match_schedule WHERE match_id = '$matchID'");
while ($row = $result->fetch_assoc()) {
    $bracketID = $row['bracket_id'];
    $round = $row['round_number'];
    $team1_score = $row['team1_score'];
    $team2_score = $row['team2_score'];
    if ($team1_score > $team2_score) {
        $winner = $row['team1_id'];
        $team_id = $row['team1_id'];
    }else {
        $winner = $row['team2_id'];
        $team_id = $row['team2_id'];
    }
}

if (isset($round)){
    $result = $conn->query("SELECT * FROM standings WHERE team_id = '$team_id'");
    while ($row = $result->fetch_assoc()) {
        $point = $row['point'];
        $point += 3; // Assuming a win gives 3 points
        $stmt_update_point = $conn->prepare("UPDATE standings SET point = ? WHERE team_id = ?");
        $stmt_update_point->bind_param("ii", $point, $team_id);
        $stmt_update_point->execute();
        $stmt_update_point->close();
    }
}

$stmt_update_score = $conn->prepare("UPDATE tournament_brackets SET team1_score = ?, team2_score = ?, winner_id = ? WHERE bracket_id = ?");
$stmt_update_score->bind_param("iiii", $team1_score, $team2_score, $winner, $bracketID);
$stmt_update_score->execute();
$stmt_update_score->close();

$result = $conn->query("SELECT * FROM tournament_brackets WHERE tournament_id = '$tournamentID'");
while ($row = $result->fetch_assoc()) {
    if ($row['round'] == 'Final') {
        if (isset($row['winner_id'])){
        $stmt_update_score = $conn->prepare("UPDATE tournament SET status = 'Finish' WHERE tournamentID = ?");
        $stmt_update_score->bind_param("i", $tournamentID);
        $stmt_update_score->execute();
        $stmt_update_score->close();
        }
    }
}

header('location:list_match.php');    
?>