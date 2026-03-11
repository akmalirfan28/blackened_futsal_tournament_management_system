<?php
session_start();
include "connection.php";
$tournamentID = $_SESSION['tournamentID'];
$matchID = $_GET['NAME'];

        $query = "SELECT m.*, 
                    t1.teamName as team1_name,
                    t2.teamName as team2_name,
                    m.match_time
                    FROM match_schedule m
                    LEFT JOIN team t1 ON m.team1_id = t1.teamID
                    LEFT JOIN team t2 ON m.team2_id = t2.teamID
                    WHERE m.match_id = ?";
          
          $stmt = $conn->prepare($query);
          $stmt->bind_param('i', $matchID);
          $stmt->execute();
          $result = $stmt->get_result();
          
          while($data = $result->fetch_assoc()) {
              $teamA = $data['team1_name'];
              $teamB = $data['team2_name'];
              $time = $data['match_time'];

          $sql = "INSERT INTO match_management (matchID, teamA, teamB, time, status) VALUES ('$matchID', '$teamA', '$teamB', '$time', 'Ongoing')";
          $conn->query($sql);
        }
        $_SESSION['matchID'] = $matchID;
        header('location:match_management.php');    

        
?>