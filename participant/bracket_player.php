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
<title>Knockout Bracket</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
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
        font-size: 1.2em;
        margin-bottom: 16px;
    }
    .bracket {
        display: flex;
        gap: 20px;
        overflow-x: auto;
    }
    .round {
        display: flex;
        flex-direction: column;
        gap: 15px;
        min-width: 180px;
    }
    .match {
        background: #e9f2ff;
        border: 1px solid #4f52ba;
        border-radius: 5px;
        padding: 10px;
        font-size: 0.9em;
    }
    .match-title {
        background: #4f52ba;
        color: #fff;
        font-weight: 600;
        padding: 4px 6px;
        border-radius: 3px;
        margin-bottom: 6px;
        text-align: center;
    }
    .team {
        padding: 3px 0;
        display: flex;
        justify-content: space-between;
    }
    .waiting-message {
        color: #555;
        font-style: italic;
        font-size: 0.85em;
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
</style>
</head>
<body>
<div class="table-container">
    <h2>Knockout Phase</h2>
    <div class="bracket">
        <?php
        $round_keys = array_keys($bracket);
        foreach ($bracket as $round => $matches):
            $round_index = array_search($round, $round_keys);
            $prev_matches = $round_index > 0 ? $bracket[$round_keys[$round_index - 1]] : [];
        ?>
        <div class="round">
            <h3 style="text-align:center;"><?php echo htmlspecialchars($round); ?></h3>
            <?php foreach ($matches as $i => $match): ?>
                <div class="match">
                    <div class="match-title">Match <?= $match['match_number'] ?></div>
                    <?php if (!empty($match['team1_id']) && !empty($match['team2_id'])): ?>
                        <div class="team">
                            <?= isset($teamName[$match['team1_id']]) ? htmlspecialchars($teamName[$match['team1_id']]) : '-' ?>
                            <span><?= $match['team1_score'] ?? 0 ?></span>
                        </div>
                        <div class="team">
                            <?= isset($teamName[$match['team2_id']]) ? htmlspecialchars($teamName[$match['team2_id']]) : '-' ?>
                            <span><?= $match['team2_score'] ?? 0 ?></span>
                        </div>
                    <?php elseif ($round_index > 0): ?>
                        <div class="waiting-message">
                            <?php
                            $relevant = array_slice($prev_matches, $i * 2, 2);
                            foreach ($relevant as $rm):
                                echo !empty($rm['winner_id'])
                                    ? htmlspecialchars($teamName[$rm['winner_id']] ?? 'TBD')
                                    : "Winner of Match {$rm['match_number']}";
                                echo "<br>";
                            endforeach;
                            ?>
                        </div>
                    <?php else: ?>
                        <div class="waiting-message">Teams not assigned yet.</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
    
</div>
</body>
</html>