<?php
session_start();
include "connection.php";

if (isset($_GET['NAME'])) {
  $matchID = $_GET['NAME'];
  $_SESSION['matchID'] = $matchID;
}

$stmt_fetch_scores = $conn->prepare("SELECT team1_score, team2_score FROM match_schedule WHERE match_id = ?");
$stmt_fetch_scores->bind_param("i", $_SESSION['matchID']);
$stmt_fetch_scores->execute();
$result_fetch_scores = $stmt_fetch_scores->get_result();

if ($result_fetch_scores->num_rows > 0) {
    $current_scores = $result_fetch_scores->fetch_assoc();
    $_SESSION['teamA_score'] = $current_scores['team1_score'];
    if ($_SESSION['teamA_score'] == null){
        $_SESSION['teamA_score'] = 0;
    }
    $_SESSION['teamB_score'] = $current_scores['team2_score'];
    if ($_SESSION['teamB_score'] == null){
        $_SESSION['teamB_score'] = 0;
    }
} else {
    // Initialize scores to 0 if match not found or scores are null
    $_SESSION['teamA_score'] = 0;
    $_SESSION['teamB_score'] = 0;
}
$stmt_fetch_scores->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Match Score</title>
  
  <!-- Linking Google Font Link For Icons -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"/>
  
    <style>
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
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 400px;
    text-align: center;
    color: white;
}

.card h2 {
    margin: 0;
    font-size: 2rem;
    color: #333;
}

.card p {
    margin: 5px 0 0;
    color: white;
}
.table-container {
 margin-left: -100px;
  margin-right: -100px;
  margin-top: 100px;
  padding: 80px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

table {
  width: 100%;
  border-collapse: collapse;
}

table th, table td {
  padding: 12px 15px;
  border: 1px solid #ddd;
  text-align: left;
}

table th {
  background-color: #161a2d;
  color: white;
}

table tr:nth-child(even) {
  background-color: #f9f9f9;
}

table tr:hover {
  background-color: #f1f1f1;
}

button.btn {
  padding: 10px 20px;
  font-size: 1rem;
  background-color:rgb(13, 76, 170);
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button.btn:hover {
  background-color:rgb(55, 108, 187);
}

button.btn-sm {
  padding: 16px 20px;
  font-size: 0.875rem;
}

@media (max-width: 768px) {
  button.btn {
    padding: 8px 16px;
    font-size: 0.875rem;
  }

  button.btn-sm {
    padding: 6px 12px;
    font-size: 0.75rem;
  }
}
        body {
            font-family: Arial, sans-serif;
            
        }
        .container2 {
            width: 500px;
            margin: 0 auto;
            text-align: center;
        }
        .score-box {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        .score-box div {
            text-align: center;
        }
        button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .actions {
            margin-top: 20px;
            margin-left: -200px;
            margin-right: -200px;
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
    <div class="container2">
    <div class="table-container">
    <?php
        $matchID = $_SESSION['matchID'];
        $records = mysqli_query($conn, "SELECT * FROM match_schedule WHERE match_id='$matchID'");
        
        while($data = mysqli_fetch_array($records)) {
          $teamA = $data['team1_id'];
          $teamB = $data['team2_id'];
        ?>
        <h2>Match <?php echo $matchID;?></h2>
        <div class="score-box">
          <?php
          $records = mysqli_query($conn, "SELECT * FROM team WHERE teamID='$teamA'");
        
          while($data = mysqli_fetch_array($records)) {
            $teamA = $data['teamName'];
          ?>
            <div>
                <h3><?php echo $teamA;?></h3><br>
                <span style="font-size: 100px;"> <?php echo $_SESSION['teamA_score']; ?> </span><br>
            </div>
            <?php
          $records = mysqli_query($conn, "SELECT * FROM team WHERE teamID='$teamB'");
        
          while($data = mysqli_fetch_array($records)) {
            $teamB = $data['teamName'];
          ?>
            <div>
                <h3><?php echo $teamB;?></h3><br>
                <span style="font-size: 100px;"> <?php echo $_SESSION['teamB_score']; ?> </span><br>
            </div>
        </div>
        <!-- Match Events Display -->
        <div class="match-events">
            <h3>Match Events</h3>
            <div class="events-container">
                <div class="team-events">
                    <h4><?php echo $teamA; ?> Events</h4>
                    <?php
                    $team_a_events = mysqli_query($conn, "SELECT me.*, 
                        p1.name as player_name, 
                        p2.name as player_in_name,
                        p3.name as player_out_name,
                        p4.name as player_yellow,
                        p5.name as player_red
                        FROM match_events me 
                        LEFT JOIN player p1 ON me.player_id = p1.playerID 
                        LEFT JOIN player p2 ON me.player_in_id = p2.playerID 
                        LEFT JOIN player p3 ON me.player_out_id = p3.playerID
                        LEFT JOIN player p4 ON me.player_yellow_id = p4.playerID
                        LEFT JOIN player p5 ON me.player_red_id = p5.playerID
                        WHERE me.match_id = $matchID 
                        AND me.team_id = (SELECT teamID FROM team WHERE teamName = '{$teamA}') 
                        ORDER BY me.event_time ASC");
                    while($event = mysqli_fetch_array($team_a_events)) {
                        echo "<div class='event-item'>";
                        switch($event['event_type']) {
                            case 'goal':
                                echo "⚽ Goal by " . $event['player_name'];
                                break;
                            case 'substitution':
                                echo "🔄 " . $event['player_in_name'] . " IN, " . $event['player_out_name'] . " OUT";
                                break;
                            case 'yellow_card':
                                echo "🟨 Yellow Card - " . $event['player_yellow'];
                                break;
                            case 'red_card':
                                echo "🟥 Red Card - " . $event['player_red'];
                                break;
                        }
                        echo "<span class='event-time'>" . date('H:i', strtotime($event['event_time'])) . "</span>";
                        echo "</div>";
                    }
                    ?>
                </div>
                <div class="team-events">
                    <h4><?php echo $teamB; ?> Events</h4>
                    <?php
                    $team_b_events = mysqli_query($conn, "SELECT me.*, 
                        p1.name as player_name, 
                        p2.name as player_in_name,
                        p3.name as player_out_name,
                        p4.name as player_yellow,
                        p5.name as player_red
                        FROM match_events me 
                        LEFT JOIN player p1 ON me.player_id = p1.playerID 
                        LEFT JOIN player p2 ON me.player_in_id = p2.playerID 
                        LEFT JOIN player p3 ON me.player_out_id = p3.playerID
                        LEFT JOIN player p4 ON me.player_yellow_id = p4.playerID
                        LEFT JOIN player p5 ON me.player_red_id = p5.playerID
                        WHERE me.match_id = $matchID 
                        AND me.team_id = (SELECT teamID FROM team WHERE teamName = '{$teamB}') 
                        ORDER BY me.event_time ASC");
                    while($event = mysqli_fetch_array($team_b_events)) {
                        echo "<div class='event-item'>";
                        switch($event['event_type']) {
                            case 'goal':
                                echo "⚽ Goal by " . $event['player_name'];
                                break;
                            case 'substitution':
                                echo "🔄 " . $event['player_in_name'] . " IN, " . $event['player_out_name'] . " OUT";
                                break;
                            case 'yellow_card':
                                echo "🟨 Yellow Card - " . $event['player_yellow'];
                                break;
                            case 'red_card':
                                echo "🟥 Red Card - " . $event['player_red'];
                                break;
                        }
                        echo "<span class='event-time'>" . date('H:i', strtotime($event['event_time'])) . "</span>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>

        
        <?php
          }
        }
      }
        ?>
    </div>
</div>

<style>
.match-events {
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.events-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.team-events {
    flex: 1;
    padding: 15px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.event-item {
    padding: 8px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.event-time {
    color: #666;
    font-size: 0.9em;
}
</style>
</body>
</html>
