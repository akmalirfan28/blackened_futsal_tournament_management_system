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
<title>Match Schedule</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
<link rel="stylesheet" href="style.css" />
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
    <h2>Match Schedule</h2>

    <table>
        <thead>
            <tr>
                <th>Round</th>
                <th>Time</th>
                <th>Court</th>
                <th>Team A</th>
                <th>VS</th>
                <th>Team B</th>
            </tr>
        </thead>
        <tbody>
      <?php
          // Join with team table to get team names and include scores
          $query = "SELECT m.*, 
                    t1.teamName as team1_name,
                    t2.teamName as team2_name,
                    m.round_number
                    FROM match_schedule m
                    LEFT JOIN team t1 ON m.team1_id = t1.teamID
                    LEFT JOIN team t2 ON m.team2_id = t2.teamID
                    WHERE m.tournament_id = ?
                    ORDER BY m.round_number, m.match_time";
          
          $stmt = $conn->prepare($query);
          $stmt->bind_param('i', $tournamentID);
          $stmt->execute();
          $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . (!empty($data['round_number']) ? "Round " . $data['round_number'] : $data['knockout_round']) . "</td>";
                    echo "<td>" . htmlspecialchars($data['match_time']) . "</td>";
                    echo "<td>Court " . htmlspecialchars($data['court']) . "</td>";
                    echo "<td>" . htmlspecialchars($data['team1_name']) . "</td>";
                    echo "<td>VS</td>";
                    echo "<td>" . htmlspecialchars($data['team2_name']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No matches scheduled yet.</td></tr>";
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