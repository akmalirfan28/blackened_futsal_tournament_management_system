<?php
include "connection.php";
session_start();
$name = $tournament = "";
$nameErr = $tournamentErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["name"]))) {
        $nameErr = " Team Name is required";
    } else {
        $name = trim($_POST["name"]);
    }
    if (empty(trim($_POST["tournament"]))) {
        $tournamentErr = "Tournament is required";
    } else {
        $tournament = trim($_POST["tournament"]);
    }
    if (empty($nameErr) && empty($tournamentErr)) {
        $records = mysqli_query($conn, "SELECT teamID FROM team WHERE teamName = '$name' AND tournament_id = '$tournament'");
                while($data = mysqli_fetch_array($records))
                        {
                            $_SESSION['teamID'] = $data['teamID'];
                            $_SESSION['tournamentID'] = $tournament;
                        }
        if (isset($_SESSION['teamID'])){
            header("location: dashboard_player.php");
        } else {
        $sql = "INSERT INTO team (teamName, tournament_id) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 'si', $name, $tournament);
            if (mysqli_stmt_execute($stmt)) {
                $records = mysqli_query($conn, "SELECT teamID FROM team WHERE teamName = '$name' AND tournament_id = '$tournament'");
                while($data = mysqli_fetch_array($records))
                        {
                            $_SESSION['teamID'] = $data['teamID'];
                            $_SESSION['tournamentID'] = $tournament;
                        }
                $query = "INSERT INTO standings (tournament_id, team_id) 
                        VALUES (?, ?)"; 
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ii', $_SESSION['tournamentID'], $_SESSION['teamID']);
                $stmt->execute();
                header("location: player_register.php");
            } else {
                echo "Registration failed. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Team Registration</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { background-image: url('image/futsal.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      position: relative; font-family: 'Poppins', sans-serif; }
  .container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; }
  h2 { text-align: center; margin-bottom: 24px; }
  label { margin-bottom: 4px; font-weight: 500; }
  .form-control { margin-bottom: 10px; }
  .error { color: #e74c3c; font-size: 0.9em; margin-bottom: 10px; }
  button { width: 100%; background: #4f52ba; color: #fff; border-radius: 5px; padding: 10px; font-size: 1.05em; }
  button:hover { background: #3c3f90; }
  @media (max-width: 500px) { .container { margin: 10px; padding: 16px; } }
</style>
</head>
<body>
  <div class="container">
    <h2>Team Registration</h2>
    <form method="post" action="">
      <label for="name">Team Name</label>
      <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
      <div class="error"><?php echo $nameErr; ?></div>

      <label for="tournament">Tournament</label>
      <select name="tournament" id="tournament" class="form-control" required>
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

      <button type="submit">Register</button>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
