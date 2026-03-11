<?php
include "connection.php";
session_start();

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
<meta http-equiv="refresh" content="5">
<title>Match Score</title>
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
        margin: 20px auto;
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

    .score-box {
        display: flex;
        justify-content: space-around;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .score-box div {
        text-align: center;
        flex: 1 1 150px;
    }

    .score-box span {
        font-size: 80px;
        font-weight: bold;
        color: #4f52ba;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        margin-bottom: 15px;
    }

    th, td {
        padding: 8px;
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
        border: none;
        border-radius: 5px;
        text-decoration: none;
        text-align: center;
        font-size: 0.9em;
        margin-top: 10px;
    }

    .btn:hover {
        background-color: #333d9b;
    }

    .match-events {
        margin-top: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .events-container {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .team-events {
        flex: 1 1 250px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        min-width: 250px;
    }

    .event-item {
        padding: 8px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.95em;
    }

    .event-time {
        color: #666;
        font-size: 0.8em;
        margin-left: 10px;
    }

    @media (max-width: 768px) {
        .score-box span {
            font-size: 60px;
        }

        h2 {
            font-size: 1.1em;
        }

        .team-events {
            flex: 1 1 100%;
        }
    }

    @media (max-width: 480px) {
        .score-box span {
            font-size: 45px;
        }

        h2 {
            font-size: 1em;
        }

        .event-item {
            font-size: 0.85em;
        }

        .btn {
            font-size: 0.8em;
            padding: 6px 12px;
        }
    }
</style>
</head>
<body>

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
                <h2><?php echo $teamA;?></h2><br>
                <span style="font-size: 100px;"> <?php echo $_SESSION['teamA_score']; ?> </span><br>
            </div>
            <?php
          $records = mysqli_query($conn, "SELECT * FROM team WHERE teamID='$teamB'");
        
          while($data = mysqli_fetch_array($records)) {
            $teamB = $data['teamName'];
          ?>
            <div>
                <h2><?php echo $teamB;?></h2><br>
                <span style="font-size: 100px;"> <?php echo $_SESSION['teamB_score']; ?> </span><br>
            </div>
        </div>
        <!-- Match Events Display -->
        <div class="match-events">
            <h2>Match Events</h2>
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


</body>
</html>
