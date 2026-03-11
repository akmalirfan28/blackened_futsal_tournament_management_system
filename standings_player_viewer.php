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
    <title>Player Match Score</title>
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
            text-align: left;
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
    
    <h2>Standings</h2><br>
    <table>
      <thead>
        <tr>
          <th>POS.</th>
          <th>Team</th>
          <th style="text-align: center;">Points</th>
      </thead>
      <tbody>
        <?php
        $i = 1;
        $standings = mysqli_query($conn, "SELECT 
        t.teamName AS team_name,
        me.point AS point_count
        FROM standings me
        JOIN team t ON me.team_id = t.teamID
        WHERE me.tournament_id = '$tournamentID'
        ORDER BY point_count DESC;
        ");
        while($event = mysqli_fetch_array($standings)) {
          ?>
            <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $event['team_name'];?></td>
                    <td style="text-align: center;"><?php if ($event['point_count'] == null){
                        echo 0;
                    }else{
                        echo $event['point_count'];
                    }?></td>
        </tr>
        <?php
        $i++;
          }
        
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>