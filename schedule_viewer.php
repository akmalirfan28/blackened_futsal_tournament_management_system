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
    <title>Player Schedule</title>
    <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="style.css" />
  <style>
    /* Importing Google font - Poppins */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");


        body {
            background: #f0f4ff;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }
        .table-container {
            max-width: 400px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 24px;
        }
        h2 {
            text-align: center;
            margin-bottom: 24px;
            font-size: 1em;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
        }
        input, select {
            width: 95%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }
        .error {
            color: #e74c3c;
            font-size: 0.95em;
            margin-bottom: 8px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #4f52ba;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
        }
        @media (max-width: 500px) {
            .container {
                margin: 10px;
                padding: 12px;
            }
            h2 {
                font-size: 1em;
            }
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 10px;
            text-align: center;
            font-size: 0.7em;
        }

        th {
            background: #007BFF;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

tr:hover {
  background-color: #f1f1f1;
}
    </style>
</head>
<body>
<div class="table-container">
    
    
    <h2>Match Schedule</h2><br>
    <table>
      <thead>
        <tr>
          <th>Round</th>
          <th>Time</th>
          <th>Court</th>
          <th>Team A</th>
          <th>VS</th>
          <th>Team B</th>
          
      </thead>
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
          
          while($data = $result->fetch_assoc()) {
          ?>
      <tbody>
          <tr>
                  <td><?php if ($data['round_number'] == null){
                    echo $data['knockout_round'];
                  } else { echo 'Round ' . $data['round_number']; }?></td>
                  <td><?php echo $data['match_time']; ?></td>
                  <td>Court <?php echo $data['court']; ?></td>
                  <td><?php echo $data['team1_name']; ?></td>
                  <td>VS</td>
                  <td><?php echo $data['team2_name']; ?></td>
      </tr>
    </tbody>
    <?php
          }
    ?>
  </table>
</div>
</body>
</html>