<?php
session_start();
include "connection.php";

// Function to generate round-robin schedule with times and courts
function generateSchedule($teams) {
    $num_teams = count($teams);
    $matches = array();
    
    // If odd number of teams, add a "bye" team
    if ($num_teams % 2 != 0) {
        $teams[] = "BYE";
        $num_teams++;
    }
    
    $rounds = $num_teams - 1;
    $half = $num_teams / 2;
    
    // Set initial match time (9:00 AM)
    $match_time = strtotime("09:00:00");
    $match_duration = 60 * 30; // 1 hour per match
    
    // Create initial team ordering
    $schedule = array();
    for ($round = 0; $round < $rounds; $round++) {
        $round_matches = array();
        $match_count = 0;
        
        // Reset match time for each round
        
        
        // Match teams for this round
        for ($match = 0; $match < $half; $match++) {
            $team1 = $teams[$match];
            $team2 = $teams[$num_teams - 1 - $match];
            
            // Don't schedule if one team has a bye
            if ($team1 != "BYE" && $team2 != "BYE") {
                // Alternate between Court A and Court B
                $court = ($match_count % 2 == 0) ? 'A' : 'B';
                
                $round_matches[] = array(
                    'team1' => $team1,
                    'team2' => $team2,
                    'time' => date('H:i', $match_time),
                    'court' => $court
                );
                
                $match_count++;
                
                // If both courts are used, move to next time slot
                if ($match_count % 2 == 0) {
                    $match_time += $match_duration;
                }
            }
        }
        
        $schedule[] = $round_matches;
        
        // Rotate teams for next round (keep first team fixed)
        $last_team = array_pop($teams);
        array_splice($teams, 1, 0, $last_team);
    }
    
    return $schedule;
}

// Add regenerateSchedule function here, outside of any conditional blocks
function regenerateSchedule($tournament_id, $conn) {
    // Fetch all teams for this tournament
    $teams = array();
    $sql = "SELECT teamID, teamName FROM team WHERE tournament_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $teams[$row['teamID']] = $row['teamName'];
    }
    
    if (empty($teams)) {
        return false;
    }
    
    // Generate new schedule
    $schedule = generateSchedule(array_keys($teams));
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Delete existing schedule
        $delete_sql = "DELETE FROM match_schedule WHERE tournament_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param('i', $tournament_id);
        $delete_stmt->execute();
        
        // Insert new schedule
        $insert_sql = "INSERT INTO match_schedule (tournament_id, round_number, team1_id, team2_id, match_time, court) VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        
        foreach ($schedule as $round_num => $round) {
            foreach ($round as $match) {
                $round_number = $round_num + 1;
                $team1_id = $match['team1'];
                $team2_id = $match['team2'];
                $match_time = $match['time'];
                $match_court = $match['court'];
                
                $insert_stmt->bind_param('iiisss', 
                    $tournament_id,
                    $round_number,
                    $team1_id,
                    $team2_id,
                    $match_time,
                    $match_court
                );
                $insert_stmt->execute();
            }
        }
        
        // Commit transaction
        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        return false;
    }
}

// Get tournament ID from session
$tournament_id = $_SESSION['tournamentID'];

// Check for regenerate request - place this before any schedule generation
$regenerate = isset($_POST['regenerate']) ? true : false;

if ($regenerate) {
    $success = regenerateSchedule($tournament_id, $conn);
    if ($success) {
        echo "<div class='alert alert-success'>Schedule has been regenerated successfully!</div>";
        // Redirect to refresh the page and show new schedule
        header("Location: schedule.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to regenerate schedule.</div>";
    }
}

// Fetch teams for this tournament
$teams = array();
if ($tournament_id) {
    $sql = "SELECT teamID, teamName FROM team WHERE tournament_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $tournament_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $teams[$row['teamID']] = $row['teamName'];
    }
}

// Generate schedule if teams exist
$schedule = array();
if (!empty($teams)) {
    // Check if schedule already exists for this tournament
    $check_sql = "SELECT COUNT(*) as count FROM match_schedule WHERE tournament_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('i', $tournament_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result()->fetch_assoc();
    
    if ($check_result['count'] > 0) {
        // Fetch existing schedule
        $fetch_sql = "SELECT * FROM match_schedule WHERE tournament_id = ? ORDER BY round_number, match_time";
        $fetch_stmt = $conn->prepare($fetch_sql);
        $fetch_stmt->bind_param('i', $tournament_id);
        $fetch_stmt->execute();
        $matches = $fetch_stmt->get_result();
        
        // Organize matches into rounds
        while ($match = $matches->fetch_assoc()) {
            $schedule[$match['round_number']][] = array(
                'team1' => $match['team1_id'],
                'team2' => $match['team2_id'],
                'time' => $match['match_time'],
                'court' => $match['court'],
                'match_id' => $match['match_id'],
                'round_number' => $match['round_number'],
                'knockout_round' => $match['knockout_round']
            );
        }
    } else {
        // Add this code after getting tournament_id
        $regenerate = isset($_POST['regenerate']) ? true : false;
        
        if ($regenerate) {
            $success = regenerateSchedule($tournament_id, $conn);
            if ($success) {
                echo "<div class='alert alert-success'>Schedule has been regenerated successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Failed to regenerate schedule.</div>";
            }
        }
        
        
        
        // Store new schedule in database
        $insert_sql = "INSERT INTO match_schedule (tournament_id, round_number, team1_id, team2_id, match_time, court) VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        
        foreach ($schedule as $round_num => $round) {
            foreach ($round as $match) {
                // Create variables for binding
                $round_number = $round_num + 1;
                $team1_id = $match['team1'];
                $team2_id = $match['team2'];
                $match_time = $match['time'];
                $match_court = $match['court'];
                
                $insert_stmt->bind_param('iiisss', 
                    $tournament_id,
                    $round_number,
                    $team1_id,
                    $team2_id,
                    $match_time,
                    $match_court
                );
                $insert_stmt->execute();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Match Schedule</title>
  
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
  margin-left: 150px;
  margin-right: 150px;
  margin-top: 20px;
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            padding: 14px;
            text-align: center;
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

button.btn {
  padding: 10px 20px;
  font-size: 1rem;
  background-color:rgb(13, 170, 13);
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button.btn:hover {
  background-color:rgb(55, 187, 77);
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
        <div class='table-container'>
        <form method='POST' style='margin-bottom: 20px;'>
        <button type='submit' name='regenerate' class='btn'>Regenerate Schedule</button>
        </form>
  <?php if (empty($teams)): ?>
            <p>No teams registered for this tournament yet.</p>
            </div>
        <?php else: ?>
          <?php foreach ($schedule as $round_num => $round): ?>
    <!-- Table Section -->
  <div class="table-container">
    
    <h2><?php if ($round[0]['round_number'] == null){
      echo "Knockout Round";
    } else {
      echo 'Round ' . htmlspecialchars($round[0]['round_number']);
    } ?></h2><br>
    <table>
      <thead>
        <tr>
          <th>Time</th>
          <th>Court</th>
          <th>Team A</th>
          <th>VS</th>
          <th>Team B</th>
          <th>Edit</th>
      </thead>
      <?php foreach ($round as $match): ?>
      <tbody>
          <tr>
                  <td><?php echo $match['time']; ?></td>
                  <td>Court <?php echo $match['court']; ?></td>
                  <td><?php echo htmlspecialchars($teams[$match['team1']]); ?></td>
                  <td>VS</td>
                  <td><?php echo htmlspecialchars($teams[$match['team2']]); ?></td>
                  <td><a href="edit_schedule.php?NAME=<?php echo $match['match_id']; ?>">
                        <button class="btn btn-sm">Edit</button>
                    </a></td>
      </tr>
    </tbody>
    <?php endforeach; ?>
  </table>
</div>
<?php endforeach; ?>
<?php endif; ?>
</body>
</html>