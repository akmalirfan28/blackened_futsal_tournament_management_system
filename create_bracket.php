<?php
// Move this to the VERY TOP of the file, before any HTML
include 'connection.php';
session_start();
$tournamentID = $_SESSION['tournamentID'];

if (isset($tournamentID)) {
    $result = $conn->query("SELECT COUNT(*) as cnt FROM tournament_brackets WHERE tournament_id = $tournamentID");
    $row = $result->fetch_assoc();
    if ($row['cnt'] > 0) {
        header("Location: manage_bracket.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bracket = $_POST['bracket'];
    // Delete existing bracket data for this tournament
    if (isset($tournamentID)) {
        $conn->query("DELETE FROM tournament_brackets WHERE tournament_id = $tournamentID");
    }
    switch ($bracket) {
        case 'Round-of-32':
            $match_num = 1;
            for ($i = 1; $i <= 16; $i++) {
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Round-of-32', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    $match_num++;
            }
            for ($i = 1; $i <= 8; $i++) {
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Round-of-16', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    $match_num++;
            }
            for ($i = 1; $i <= 4; $i++) {
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Quarter-finals', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    $match_num++;
            }
            for ($i = 1; $i <= 2; $i++) {
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Semi-finals', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    $match_num++;
            }
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Final', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    header('location:manage_bracket.php');    
            break;
        case 'Round-of-16':
            $match_num = 1;
            for ($i = 1; $i <= 8; $i++) {
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Round-of-16', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    $match_num++;
            }
            for ($i = 1; $i <= 4; $i++) {
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Quarter-finals', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    $match_num++;
            }
            for ($i = 1; $i <= 2; $i++) {
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Semi-finals', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    $match_num++;
            }
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Final', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    header('location:manage_bracket.php');    
            break;
        case 'Quarter-finals':
            $match_num = 1;
            for ($i = 1; $i <= 4; $i++) {
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Quarter-finals', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    $match_num++;
            }
            for ($i = 1; $i <= 2; $i++) {
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Semi-finals', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    $match_num++;
            }
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Final', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    header('location:manage_bracket.php');    
            break;
        case 'Semi-finals':
            $match_num = 1;
            for ($i = 1; $i <= 2; $i++) {
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Semi-finals', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    $match_num++;
            }
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Final', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    header('location:manage_bracket.php');   
            break;
        case 'Final':
            $match_num = 1;
                $query = "INSERT INTO tournament_brackets (tournament_id, round, match_number) 
                VALUES (?, 'Final', ?)"; 
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('ii', $tournamentID, $match_num);
                    $stmt->execute();
                    header('location:manage_bracket.php');   
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Knockout Bracket</title>
  
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
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        textarea {
            width: 100%;
            height: 150px;
            padding: 12px 20px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            background-color: #f8f8f8;
            font-size: 12px;
            resize: none;
        }
    </style>
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
  <h2>Knockout Phase</h2>
  <br>
    <form method="POST">
        <label for="bracket">Select Bracket:</label>
        <select id="bracket" name="bracket" required>
            <option value="Final" >Final (2 teams)</option>
            <option value="Semi-finals" >Semi Finals (4 teams)</option>
            <option value="Quarter-finals" >Quarter Finals (8 teams)</option>
            <option value="Round-of-16" >Round of 16 (16 teams)</option>
            <option value="Round-of-32" >Round of 32 (32 teams)</option>
        </select><br><br>
        <button type="submit" class="btn">Create</button>
    </form>
    

</body>
</html>
