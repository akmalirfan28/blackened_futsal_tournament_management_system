<?php
include "connection.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Viewer</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <style>
    body {
      background: #f0f4ff;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      padding: 16px;
    }

    h2 {
      text-align: center;
      margin-bottom: 24px;
      color: #333;
      font-weight: 600;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }

    .card {
      background: #4f52ba;
      color: #fff;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      text-decoration: none;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }

    .card span {
      font-size: 3rem;
      display: block;
      margin-bottom: 8px;
    }

    .card p {
      margin: 0;
      font-size: 1.2rem;
      font-weight: 500;
    }

    @media (max-width: 500px) {
      .card span {
        font-size: 2rem;
      }
      .card p {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
    <div class="container">
    <h2>Dashboard</h2>
    <div class="cards">
      <a href="schedule_viewer.php" class="card" style="background: Tomato;">
        <span class="material-symbols-outlined">calendar_month</span>
        <p>Schedule</p>
      </a>

      <a href="match_score_viewer.php" class="card" style="background: MediumSeaGreen;">
        <span class="material-symbols-outlined">sports_soccer</span>
        <p>Match Score</p>
      </a>

      <a href="standings_player_viewer.php" class="card" style="background: orange;">
        <span class="material-symbols-outlined">sports_score</span>
        <p>Standings</p>
      </a>

      <a href="bracket_player.php" class="card" style="background: SlateBlue;">
        <span class="material-symbols-outlined">flowchart</span>
        <p>Bracket</p>
      </a>

      <a href="logout_player_viewer.php" class="card" style="background: Crimson;">
        <span class="material-symbols-outlined">logout</span>
        <p>Logout</p>
      </a>
    </div>
  </div>
</body>
</html>