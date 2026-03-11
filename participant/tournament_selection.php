<?php
include "connection.php";
session_start();

$tournamentID = "";
$tournamentErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tournamentID = isset($_POST['tournament']) ? intval($_POST['tournament']) : 0;

    if ($tournamentID === 0) {
        $tournamentErr = "Please select a tournament.";
    } else {
        // Verify tournament exists in database
        $stmt = $conn->prepare("SELECT tournamentID FROM tournament WHERE tournamentID = ?");
        $stmt->bind_param("i", $tournamentID);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['tournamentID'] = $tournamentID;
            header("Location: dashboard_viewer.php");
            exit();
        } else {
            $tournamentErr = "Selected tournament not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Select Tournament</title>
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
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1em;
    }
    .error {
        color: #e74c3c;
        font-size: 0.9em;
        margin-bottom: 12px;
    }
    button {
        width: 100%;
        padding: 12px;
        background: #4f52ba;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 1.1em;
        cursor: pointer;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Select Tournament</h2>
    <form method="post" action="">
        <label for="tournament">Tournament</label>
        <select name="tournament" id="tournament" required>
            <option value="" disabled <?= ($tournamentID == 0) ? 'selected' : '' ?>>-- Select Tournament --</option>
            <?php
            $result = $conn->query("SELECT tournamentID, name FROM tournament");
            while ($row = $result->fetch_assoc()) {
                $selected = ($tournamentID == $row['tournamentID']) ? 'selected' : '';
                echo "<option value='{$row['tournamentID']}' $selected>" . htmlspecialchars($row['name']) . "</option>";
            }
            ?>
        </select>
        <?php if (!empty($tournamentErr)): ?>
            <div class="error"><?= $tournamentErr ?></div>
        <?php endif; ?>

        <button type="submit">Enter</button>
    </form>
</div>
</body>
</html>
