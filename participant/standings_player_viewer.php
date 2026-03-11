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
<title>Standings</title>
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
    }
    th, td {
        padding: 10px;
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
        border-radius: 5px;
        text-decoration: none;
        text-align: center;
        margin-top: 10px;
    }
    .btn:hover {
        background-color: #333d9b;
    }
</style>
</head>
<body>

<div class="table-container">
    <h2>Standings</h2>
    <table>
        <thead>
            <tr>
                <th>Pos.</th>
                <th>Team</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->prepare("SELECT t.teamName, s.point 
                                    FROM standings s
                                    JOIN team t ON s.team_id = t.teamID
                                    WHERE s.tournament_id = ?
                                    ORDER BY s.point DESC, t.teamName ASC");
            $stmt->bind_param('i', $tournamentID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $i++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['teamName']) . "</td>";
                    echo "<td>" . ($row['point'] !== null ? $row['point'] : 0) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No teams registered yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>


</div>

</body>
</html>