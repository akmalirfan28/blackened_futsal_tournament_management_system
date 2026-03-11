<?php
session_start();
include "connection.php";

if (isset($_GET['NAME'])) {
  $tournamentID = $_GET['NAME'];
  $_SESSION['tournamentID'] = $tournamentID;
  $result = $conn->query("SELECT * FROM tournament WHERE tournamentID = '$tournamentID'");
  while ($row = $result->fetch_assoc()) {
    if ($row['status'] == 'Not Started') {
      $stmt_update_score = $conn->prepare("UPDATE tournament SET status = 'Ongoing' WHERE tournamentID = ?");
      $stmt_update_score->bind_param("i", $tournamentID);
      $stmt_update_score->execute();
      $stmt_update_score->close();
    }
  }
  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Administrator</title>
  <!-- Linking Google Font Link For Icons -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="style.css" />
  <style>
    /* Importing Google font - Poppins */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  min-height: 100vh;
  background: #F0F4FF;
}

.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  width: 85px;
  display: flex;
  overflow-x: hidden;
  flex-direction: column;
  background: #161a2d;
  padding: 25px 20px;
  transition: all 0.4s ease;
}

.sidebar:hover {
  width: 300px;
}

.sidebar .sidebar-header {
  display: flex;
  align-items: center;
}

.sidebar .sidebar-header img {
  width: 42px;
  border-radius: 50%;
}

.sidebar .sidebar-header h2 {
  color: #fff;
  font-size: 1.25rem;
  font-weight: 600;
  white-space: nowrap;
  margin-left: 23px;
}

.sidebar-links h4 {
  color: #fff;
  font-weight: 500;
  white-space: nowrap;
  margin: 10px 0;
  position: relative;
}

.sidebar-links h4 span {
  opacity: 0;
}

.sidebar:hover .sidebar-links h4 span {
  opacity: 1;
}

.sidebar-links .menu-separator {
  position: absolute;
  left: 0;
  top: 50%;
  width: 100%;
  height: 1px;
  transform: scaleX(1);
  transform: translateY(-50%);
  background: #4f52ba;
  transform-origin: right;
  transition-delay: 0.2s;
}

.sidebar:hover .sidebar-links .menu-separator {
  transition-delay: 0s;
  transform: scaleX(0);
}

.sidebar-links {
  list-style: none;
  margin-top: 20px;
  height: 80%;
  overflow-y: auto;
  scrollbar-width: none;
}

.sidebar-links::-webkit-scrollbar {
  display: none;
}

.sidebar-links li a {
  display: flex;
  align-items: center;
  gap: 0 20px;
  color: #fff;
  font-weight: 500;
  white-space: nowrap;
  padding: 15px 10px;
  text-decoration: none;
  transition: 0.2s ease;
}

.sidebar-links li a:hover {
  color: #161a2d;
  background: #fff;
  border-radius: 4px;
}

.user-account {
  margin-top: auto;
  padding: 12px 10px;
  margin-left: -10px;
}

.user-profile {
  display: flex;
  align-items: center;
  color: #161a2d;
}

.user-profile img {
  width: 42px;
  border-radius: 50%;
  border: 2px solid #fff;
}

.user-profile h3 {
  font-size: 1rem;
  font-weight: 600;
}

.user-profile span {
  font-size: 2rem;
  font-weight: 400;
}

.user-detail {
  margin-left: 23px;
  white-space: nowrap;
}

.sidebar:hover .user-account {
  background: #fff;
  border-radius: 4px;
}

@import url('https://fonts.googleapis.com/css?family=Work+Sans:400,600');

.container {
	width: 50%;
	margin: 0 auto;
  float: center;
}

header {
  background: #161a2d;
}

header::after {
  content: '';
  display: table;
  clear: both;
}

.logo {
  float: center;
  padding: 10px 0;
  color: white;
}
.dashboard {
    padding: 20px;
    text-align: center;
}

.cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.card {
    background: #fff;
    padding: 50px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    width: 400px;
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
    margin: 5px 0 0;
    font-size: 1.5rem;
    color: white;
}
.cards :hover {
            transform: translateY(-5px);
        }
        .team-grid {
            display: flex;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .team-card {
            background: #fff;
            border-radius: 16px;
            padding: 50px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
            width: 800px;
        }

        .team-card:hover {
            transform: translateY(-5px);
        }

        .team-name {
            font-size: 2.5em;
            font-weight: 600;
            margin-bottom: 5px;
            color: #222;
        }

        .team-team {
            font-size: 0.95em;
            color: #666;
            margin-bottom: 10px;
        }

        .stat {
            font-size: 2em;
            margin: 4px 0;
        }
</style>
</head>
<body>
<header>
    <div class="container">
      <h1 class="logo">BLACKENED FUTSAL TOURNAMENT MANAGEMENT SYSTEM</h1>
    </div>
  </header>

  <aside class="sidebar">
    <div class="sidebar-header">
      <img src="image/blackened futsal.png" alt="logo" />
      <h2>BLACKENED FUTSAL</h2>
    </div>
    <ul class="sidebar-links">
      <h4>
        <span>Main Menu</span>
        <div class="menu-separator"></div>
      </h4>
      <li>
        <a href="dashboard.php">
          <span class="material-symbols-outlined"> dashboard </span>Dashboard</a>
      </li>
      <li>
        <a href="listOfTeam.php"><span class="material-symbols-outlined"> groups </span>Participants</a>
      </li>
      <li>
        <a href="schedule.php"><span class="material-symbols-outlined"> calendar_month </span>Schedule</a>
      </li>
      <li>
        <a href="list_match.php"><span class="material-symbols-outlined"> sports_soccer </span>Match Management</a>
      </li>
      <li>
        <a href="standings.php"><span class="material-symbols-outlined"> sports_score </span>Standings</a>
      </li>
      <li>
        <a href="create_bracket.php"><span class="material-symbols-outlined"> flowchart </span>Bracket</a>
      </li>
      <li>
        <a href="tournament_information.php"><span class="material-symbols-outlined"> browse_activity </span>Tournament Information</a>
      </li>
      <li>
        <a href="report.php"><span class="material-symbols-outlined"> monitoring </span>Report</a>
      </li>
      <h4>
        <span>Account</span>
        <div class="menu-separator"></div>
      </h4>
      <li>
        <a href="profile.php"><span class="material-symbols-outlined"> account_circle </span>Profile</a>
      </li>
      <li>
        <a href="logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
      </li>
    </ul>
    <div class="user-account">
      <div class="user-profile">
      <span class="material-symbols-outlined"> account_circle </span>
        <div class="user-detail">
        <?php
        include 'connection.php'; 
        $userID = $_SESSION['userID'];
        

        $records = mysqli_query($conn, "SELECT * FROM admin WHERE userID='$userID'");

        while($data = mysqli_fetch_array($records))
        {
        ?>
          <h3><?php echo $data['firstName'];  echo " ".$data['lastName'];?></h3>
          
          <?php
            }
            ?>
        </div>
      </div>
    </div>
  </aside>
  <div class="dashboard">
        <h1>Dashboard</h1><br>
        <div class="cards">
          <a href="listOfTeam.php">
          <div class="card" style="background: DodgerBlue;">
            <span class="material-symbols-outlined" style="font-size: 5rem;"> groups <p>Participants</p></span>
            </div></a>
            <a href="list_match.php">
            <div class="card" style="background: MediumSeaGreen;">
                <span class="material-symbols-outlined" style="font-size: 5rem;"> sports_soccer<p>Match</p></span>
            </div></a>
            <a href="schedule.php">
            <div class="card" style="background: Tomato;">
                <span class="material-symbols-outlined" style="font-size: 5rem;"> calendar_month  <p>Schedule</p></span>
            </div></a>
            <a href="create_bracket.php">
            <div class="card" style="background: DodgerBlue;">
                <span class="material-symbols-outlined" style="font-size: 5rem;"> flowchart <p>Bracket</p></span>
            </div></a>
            <a href="tournament_information.php">
            <div class="card" style="background: MediumSeaGreen;">
                <span class="material-symbols-outlined" style="font-size: 5rem;"> browse_activity <p>Information</p></span>
            </div></a>
            <a href="standings.php">
            <div class="card" style="background: orange;">
                <span class="material-symbols-outlined" style="font-size: 5rem;"> sports_score <p>Standings</p></span>
            </div></a>
            <a href="report.php">
            <div class="card" style="background: Tomato;">
                <span class="material-symbols-outlined" style="font-size: 5rem;"> monitoring <p>Report</p></span>
            </div></a>
        </div>
    <br><br>
    <div class="team-grid">
    <?php 
        $team = mysqli_query($conn, "SELECT 
    COUNT(*) AS team_count
FROM team
WHERE tournament_id = '$_SESSION[tournamentID]';");
    while($event = mysqli_fetch_array($team)) { ?>
      <div class="team-card">
          <div class="stat">Team Registered: </div>
          <div class="team-team"></div>
          <div class="team-name"><?php echo $event['team_count'] . ' Teams'; ?></div>
          
      </div>
      <?php } ?>
      <?php 

$team = mysqli_query($conn, "
    SELECT COUNT(*) AS player_count
    FROM player p
    JOIN team t ON p.teamID = t.teamID
    WHERE t.tournament_id = '$_SESSION[tournamentID]'
");

$event = mysqli_fetch_array($team);
?>
<div class="team-card">
    <div class="stat">Player Registered:</div>
    <div class="team-team"></div>
    <div class="team-name"><?php echo $event['player_count'] . ' Players'; ?></div>
</div>

    </div>
    </div>
</body>
</html>