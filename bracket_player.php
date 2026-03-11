<?php
include "connection.php";
session_start();

$tournamentID = $_SESSION['tournamentID'];

// Fetch matches grouped by round
$result = $conn->query("SELECT * FROM tournament_brackets WHERE tournament_id = $tournamentID ORDER BY FIELD(round, 'Round-of-32', 'Round-of-16', 'Quarter-finals', 'Semi-finals', 'Final'), match_number");

$bracket = [];
while ($row = $result->fetch_assoc()) {
    $bracket[$row['round']][] = $row;
}
// Fetch all teams
$teams = [];
$teamName = [];
$result = $conn->query("SELECT teamID, teamName FROM team WHERE tournament_id = '$tournamentID'");
while ($row = $result->fetch_assoc()) {
    $teams[] = $row;
    $teamName[$row['teamID']] = $row['teamName'];
}
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
.bracket {
            display: flex;
            gap: 40px;
            font-family: Arial, sans-serif;
        }
        .round {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .match {
            border: 1px solid #007bff;
            border-radius: 5px;
            padding: 10px;
            min-width: 150px;
            background-color: #e9f2ff;
        }
        .match-title {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            padding: 5px;
            border-radius: 3px;
            margin-bottom: 5px;
        }
        .team {
            padding: 3px 0;
        }
        .waiting-message {
            padding: 5px;
        }

        .empty {
            color: #666;
            color: #999;
            font-style: italic;
        }
        form { display: inline; }
        
    </style>
</head>
<body>
<div class="table-container">
    
<h2>Knockout Phase</h2><br>
<div class="bracket">
<?php foreach ($bracket as $round => $matches): ?>
    <div class="round">
    <?php 
        $round_index = array_search($round, array_keys($bracket));
        // Only first round can be selected initially
        
        // Check if all teams are set in the first round
        $all_teams_set = true;
        if ($round_index === 0) {
            foreach ($matches as $match) {
                if (empty($match['team1_id']) || empty($match['team2_id'])) {
                    $all_teams_set = false;
                    break;
                }
            }
        }
        
        if ($round_index > 0) {
            // Check if all previous round matches have winners
            $prev_round = array_keys($bracket)[$round_index - 1];
            $prev_matches = $bracket[$prev_round];
            $all_winners_set = true; // Start with true
            foreach ($prev_matches as $pmatch) {
                if (empty($pmatch['winner_id'])) {
                    $all_winners_set = false;
                    
                    break;
                }
            }
            $can_select = $all_winners_set;
        }
    ?>
    <h2><?php echo htmlspecialchars($round); ?></h2>
        <?php foreach ($matches as $match): ?>
            <div class="match">
                <div class="match-title">Match <?= $match['match_number'] ?></div>
                <?php if (!empty($match['team1_id']) && !empty($match['team2_id'])): ?>
                    <div class="team">
                        <?php echo isset($teamName[$match['team1_id']]) ? $teamName[$match['team1_id']] : '-'; ?>
                        <span style="float: right;"><?php echo isset($match['team1_score']) ? $match['team1_score'] : '0'; ?></span>
                        
                    </div>
                    <div class="team">
                        <?php echo isset($teamName[$match['team2_id']]) ? $teamName[$match['team2_id']] : '-'; ?>
                        <span style="float: right;"><?php echo isset($match['team2_score']) ? $match['team2_score'] : '0'; ?></span>
                    </div>
                   
                    
                <?php elseif (isset($can_select)): 
                    $match_index = array_search($match, $matches);
                    $start_index = $match_index * 2;
                    $relevant_matches = array_slice($prev_matches, $start_index, 2); ?>
                    
                    <div class="waiting-message">
                        <?php if (!empty($relevant_matches[0]['winner_id'])): ?>
                            <?php echo isset($teamName[$relevant_matches[0]['winner_id']]) ? $teamName[$relevant_matches[0]['winner_id']] : 'TBD'; ?>
                            
                        <?php else: ?>
                            <span class="empty">Winner of Match <?= $relevant_matches[0]['match_number'] ?></span>
                        <?php endif; ?>
                        <br>
                        <?php if (!empty($relevant_matches[1]['winner_id'])): ?>
                            <?php echo isset($teamName[$relevant_matches[1]['winner_id']]) ? $teamName[$relevant_matches[1]['winner_id']] : 'TBD'; ?>
                        <?php else: ?>
                            <span class="empty">Winner of Match <?= $relevant_matches[1]['match_number'] ?></span>
                        <?php endif; ?>
                        <?php 
                        ?>
                    </div>
                    <?php else: echo 'Bracket is not ready yet';?>
                <?php endif; ?>
            </div>
        <?php endforeach; 
        ?>
    </div>
<?php endforeach; ?>
  </div>
</body>
</html>