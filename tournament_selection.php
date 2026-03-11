<?php
include "connection.php";
session_start();
$tournament = "";
$tournamentErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty(trim($_POST["tournament"]))) {
        $tournamentErr = "Tournament is required";
    } else {
        $tournament = trim($_POST["tournament"]);
    }
    if (empty($tournamentErr)) {
        $_SESSION['tournamentID'] = $tournament;
        header("location: dashboard_viewer.php");
        
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tournament</title>
    <style>
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
    </style>
</head>
<body>
    <div class="container">
        
        <form method="post" action="">

            <label for="tournament">Tournament</label>
            <select name="tournament" id="tournament">
                <option value="">Select Tournament</option>
                <?php
                $tournaments = $conn->query("SELECT tournamentID, name FROM tournament");
                while ($row = $tournaments->fetch_assoc()) {
                    $selected = ($tournament == $row['tournamentID']) ? 'selected' : '';
                    echo "<option value='" . $row['tournamentID'] . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
                }
                ?>
            </select>
            <div class="error"><?php echo $tournamentErr; ?></div>

            <button type="submit">Enter</button>
        </form>
    </div>
</body>
</html>