<?php
include "connection.php";
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration</title>
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
        .container {
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
                font-size: 1.3em;
            }
        }
        .card {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    width: 360px;
    text-align: center;
    color: white;
    transition: transform 0.2s ease;
}

.card h2 {
    margin: 0;
    font-size: 2rem;
    color: #333;
}

.card p {
    font-family: 'Poppins', sans-serif;
    margin: 5px 0 0;
    font-size: 1.5rem;
    color: white;
}
.cards :hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard</h2>
            <a href="schedule_viewer.php">
            <div class="card" style="background: Tomato;">
                <span class="material-symbols-outlined" style="font-size: 2rem;"> calendar_month  <p>Schedule</p></span>
            </div></a><br>
            <a href="match_score_viewer.php">
            <div class="card" style="background: MediumSeaGreen;">
                <span class="material-symbols-outlined" style="font-size: 2rem;"> sports_soccer<p>Match Score</p></span>
            </div></a><br>
            <a href="standings_player_viewer.php">
            <div class="card" style="background: orange;">
                <span class="material-symbols-outlined" style="font-size: 2rem;"> sports_score <p>Standings</p></span>
            </div></a><br>
            <a href="bracket_player.php">
            <div class="card" style="background: DodgerBlue;">
                <span class="material-symbols-outlined" style="font-size: 2rem;"> flowchart <p>Bracket</p></span>
            </div></a><br>
            <a href="logout_player_viewer.php">
            <div class="card" style="background: Tomato;">
                <span class="material-symbols-outlined" style="font-size: 2rem;"> logout <p>Logout</p></span>
            </div></a>

        
    </div>
</body>
</html>