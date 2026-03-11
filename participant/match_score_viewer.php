<?php
include "connection.php";
session_start();
$tournamentID = $_SESSION['tournamentID'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Match Score</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
<style>
    body {
        background-image: url('image/futsal.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      position: relative;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }
    .table-container {
        max-width: 95%;
        margin: 30px auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
    }
    h2 {
        text-align: center;
        margin-bottom: 16px;
        font-size: 1.2em;
        font-weight: 600;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        margin-bottom: 15px;
    }
    th, td {
        padding: 8px;
        text-align: center;
        font-size: 0.8em;
    }
    th {
        background: #4f52ba;
        color: #fff;
        text-transform: uppercase;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    .btn {
        display: inline-block;
        padding: 8px 16px;
        background-color: #4f52ba;
        color: #fff;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        text-align: center;
        font-size: 0.9em;
        margin-top: 10px;
    }
    .btn:hover {
        background-color: #333d9b;
    }
    </style>
</head>
<body>
<div class="table-container">
    
    
    <h2>Match Score</h2>
    <table>
      <thead>
        <tr>
          <th>Round</th>
          <th>Team A</th>
          <th>Score</th>
          <th>Team B</th>
          <th>Action</th>
      </thead>
      <tbody>
      <?php $query = "SELECT m.*, 
                    t1.teamName as team1_name,
                    t2.teamName as team2_name,
                    m.round_number,
                    m.team1_score,
                    m.team2_score,
                    m.match_status
                    FROM match_schedule m
                    LEFT JOIN team t1 ON m.team1_id = t1.teamID
                    LEFT JOIN team t2 ON m.team2_id = t2.teamID
                    WHERE m.tournament_id = ?
                    AND m.match_status = 'Finish'
                    ORDER BY m.round_number, m.match_time";
          
          $stmt = $conn->prepare($query);
          $stmt->bind_param('i', $tournamentID);
          $stmt->execute();
          $result = $stmt->get_result();
          
          if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $team1_score = $data['team1_score'] !== null ? $data['team1_score'] : '0';
                    $team2_score = $data['team2_score'] !== null ? $data['team2_score'] : '0';

                    echo "<tr>";
                    echo "<td>" . (!empty($data['round_number']) ? "Round " . $data['round_number'] : htmlspecialchars($data['knockout_round'])) . "</td>";
                    echo "<td>" . htmlspecialchars($data['team1_name']) . "</td>";
                    echo "<td><strong>{$team1_score} - {$team2_score}</strong></td>";
                    echo "<td>" . htmlspecialchars($data['team2_name']) . "</td>";
                    echo "<td><a href='match_view_player_viewer.php?NAME=" . htmlspecialchars($data['match_id']) . "' class='btn btn-sm btn-success'>View</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No finished matches yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div style="text-align: center;">
        <a href="dashboard_viewer.php" class="btn">Back to Dashboard</a>
    </div>
</div>

</body>
</html>