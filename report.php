<?php
// require 'vendor/autoload.php'; Composer autoload
session_start();
include "connection.php";
$tournamentID = $_SESSION['tournamentID'];

// 1. List of teams
$teams = $conn->query("SELECT teamName FROM team WHERE tournament_id = '$tournamentID'");

// 2. Biggest win/loss
$biggestMatch = $conn->query("
    SELECT m.match_id, t1.teamName as team1, t2.teamName as team2, 
           COALESCE(m.team1_score, 0) AS team1_score, 
           COALESCE(m.team2_score, 0) AS team2_score,
           ABS(COALESCE(m.team1_score, 0) - COALESCE(m.team2_score, 0)) AS goal_diff
    FROM match_schedule m
    JOIN team t1 ON m.team1_id = t1.teamID
    JOIN team t2 ON m.team2_id = t2.teamID
    WHERE m.tournament_id = '$tournamentID'
    ORDER BY goal_diff DESC
    LIMIT 1
");

if ($biggestMatch->num_rows == 0) {
    $biggestMatch = $conn->query("SELECT 'No matches found' as team1, 'No matches found' as team2, 0 as team1_score, 0 as team2_score");
    $biggestMatchData = $biggestMatch->fetch_assoc();
} else {
    $biggestMatchData = $biggestMatch->fetch_assoc();
    if ($biggestMatchData['team1_score'] == null) {
        $biggestMatchData['team1_score'] = 0;
    } 
    if ($biggestMatchData['team2_score'] == null){
      $biggestMatchData['team2_score'] = 0;
    }
}


// 3. Top standings
$topStanding = $conn->query("
    SELECT t.teamName, s.point 
    FROM standings s 
    JOIN team t ON s.team_id = t.teamID 
    WHERE s.tournament_id = '$tournamentID' 
    ORDER BY s.point DESC LIMIT 1
")->fetch_assoc();
if (!$topStanding) {
    $topStanding = ['teamName' => 'No team found', 'point' => 0];
}

// 4. Winner bracket
$brackets = $conn->query("
    SELECT b.round, t.teamName as winner 
    FROM tournament_brackets b 
    JOIN team t ON b.winner_id = t.teamID
    WHERE b.tournament_id = '$tournamentID' 
    ORDER BY b.match_number ASC
");

// 5. Most goals
$mostGoals = $conn->query("
    SELECT p.name, COUNT(*) as goals 
    FROM match_events me 
    JOIN player p ON me.player_id = p.playerID 
    WHERE me.event_type = 'goal' 
    AND me.tournament_id = '$tournamentID' 
    GROUP BY me.player_id 
    ORDER BY goals DESC LIMIT 1
")->fetch_assoc();
if (!$mostGoals) {
    $mostGoals = ['name' => 'No player found', 'goals' => 0];
} 

// 6. Most yellow cards
$mostYellows = $conn->query("
    SELECT p.name, COUNT(*) as yellow_cards 
    FROM match_events me 
    JOIN player p ON me.player_yellow_id = p.playerID 
    WHERE me.event_type = 'yellow_card' 
    AND me.tournament_id = '$tournamentID'
    GROUP BY me.player_yellow_id 
    ORDER BY yellow_cards DESC LIMIT 1
")->fetch_assoc();
if (!$mostYellows) {
    $mostYellows = ['name' => 'No player found', 'yellow_cards' => 0];
}

// 7. Most red cards
$mostReds = $conn->query("
    SELECT p.name, COUNT(*) as red_cards 
    FROM match_events me 
    JOIN player p ON me.player_red_id = p.playerID 
    WHERE me.event_type = 'red_card' 
    AND me.tournament_id = '$tournamentID'
    GROUP BY me.player_red_id 
    ORDER BY red_cards DESC LIMIT 1
")->fetch_assoc();
if (!$mostReds) {
    $mostReds = ['name' => 'No player found', 'red_cards' => 0];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Report</title>
  
  <!-- Linking Google Font Link For Icons -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
  margin-left: 150px;
  margin-right: 150px;
  margin-top: 20px;
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
  padding: 8px 16px;
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
h2 { text-align: center; }
table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 14px;
            text-align: center;
            justify-content: center;
        }

        th {
            background: #007BFF;
            color: white;
            text-transform: uppercase;
            font-size: 0.85em;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
  background-color: #f1f1f1;
}

        @media (max-width: 768px) {
            .player-grid {
                grid-template-columns: 1fr;
            }
        }

        .btn-group { text-align: center; margin-top: 20px; }
        .btn-group a {
            background: #28a745; color: white; padding: 10px 20px; margin: 0 10px;
            text-decoration: none; border-radius: 5px;
        }
        .btn-group a.export-pdf { background: #dc3545; }
        .player-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .player-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
            text-align: center;
        }

        .player-card:hover {
            transform: translateY(-5px);
        }

        .player-name {
            font-size: 1.2em;
            font-weight: 600;
            margin-bottom: 5px;
            color: #222;
        }

        .player-team {
            font-size: 0.95em;
            color: #666;
            margin-bottom: 10px;
            text-align: center;
        }

        .stat {
            font-size: 1.1em;
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
  <div class="table-container">
  <h2>Futsal Tournament Report</h2>

  <div class="btn-group">
      <a href="export_pdf.php" target="_blank" class="export-pdf">Export to PDF</a>
  </div>
  <br>

  <h2>List of Teams</h2>
  <table>
    <tr>
      <th>No.</th>
      <th>Team Name</th>
    </tr>
    <?php 
    $i = 1;
    $teams->data_seek(0); // Reset pointer if needed
    while ($row = $teams->fetch_assoc()){ ?>
      <tr>
        <td><?= $i++; ?></td>
        <td><?= $row['teamName']; ?></td>
      </tr>
    <?php } ?>
  </table>
  <br>

  <h2>Biggest Win Match</h2>
  <table>
    <tr>
      <th>Team 1</th>
      <th>Score</th>
      <th>Team 2</th>
    </tr>
    <tr>
      <td><?= $biggestMatchData['team1']; ?></td>
      <td><?= $biggestMatchData['team1_score'] . ' - ' . $biggestMatchData['team2_score']; ?></td>
      <td><?= $biggestMatchData['team2']; ?></td>
    </tr>
  </table>
  <br>

  <h2>Biggest Loss Match</h2>
  <table>
    <tr>
      <th>Team 1</th>
      <th>Score</th>
      <th>Team 2</th>
    </tr>
    <tr>
      <td><?= $biggestMatchData['team1']; ?></td>
      <td><?= $biggestMatchData['team1_score'] . ' - ' . $biggestMatchData['team2_score']; ?></td>
      <td><?= $biggestMatchData['team2']; ?></td>
    </tr>
  </table>
  <br>

  <h2>Top of Standings</h2>
  <table>
    <tr>
      <th>Team Name</th>
      <th>Points</th>
    </tr>
    <tr>
      <td><?= $topStanding['teamName']; ?></td>
      <td>
        <?php 
          if ($topStanding['point'] == null){
            echo "0";
          } else {
            echo $topStanding['point'];
          }
        ?>
      </td>
    </tr>
  </table>
  <br>

  <h2>Knockout Winner Bracket</h2>
  <table>
    <tr>
      <th>Round</th>
      <th>Winner</th>
    </tr>
    <?php 
    $brackets->data_seek(0); // Reset pointer if needed
    while ($row = $brackets->fetch_assoc()) { ?>
      <tr>
        <td><?= $row['round']; ?></td>
        <td><?= $row['winner']; ?></td>
      </tr>
    <?php } ?>
  </table>
  <br>

  <h2>Most Goals by a Player</h2>
  <table>
    <tr>
      <th>Player Name</th>
      <th>Goals</th>
    </tr>
    <tr>
      <td><?= $mostGoals['name']; ?></td>
      <td><?= $mostGoals['goals']; ?></td>
    </tr>
  </table>
  <br>

  <h2>Most Yellow Cards</h2>
  <table>
    <tr>
      <th>Player Name</th>
      <th>Yellow Cards</th>
    </tr>
    <tr>
      <td><?= $mostYellows['name']; ?></td>
      <td><?= $mostYellows['yellow_cards']; ?></td>
    </tr>
  </table>
  <br>

  <h2>Most Red Cards</h2>
  <table>
    <tr>
      <th>Player Name</th>
      <th>Red Cards</th>
    </tr>
    <tr>
      <td><?= $mostReds['name']; ?></td>
      <td><?= $mostReds['red_cards']; ?></td>
    </tr>
  </table>
</div>
</body>
</html>