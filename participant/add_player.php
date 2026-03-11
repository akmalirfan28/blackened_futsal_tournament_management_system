<?php
include "connection.php";
session_start();
$_SESSION['teamID'] = $_GET['NAME'];

$name = $email = $phone = "";
$nameErr = $emailErr = $phoneErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["name"]))) {
        $nameErr = "Name is required";
    } else {
        $name = trim($_POST["name"]);
    }
    if (empty(trim($_POST["email"]))) {
        $emailErr = "Email is required";
    } else {
        $email = trim($_POST["email"]);
    }
    if (empty(trim($_POST["phone"]))) {
        $phoneErr = "Phone is required";
    } else {
        $phone = trim($_POST["phone"]);
    }
    
    if (empty($nameErr) && empty($emailErr) && empty($phoneErr)) {
        $sql = "INSERT INTO player (name, phoneNumber, email, teamID) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 'sssi', $name, $phone, $email, $_SESSION['teamID']);
            if (mysqli_stmt_execute($stmt)) {
                header("location: player_register.php");
            } else {
                echo "Registration failed. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Player</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    h3 {
      text-align: center;
      margin-bottom: 24px;
      font-weight: 600;
    }
    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1em;
    }
    .error {
      color: #e74c3c;
      font-size: 0.9em;
      margin-bottom: 8px;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #4f52ba;
      color: #fff;
      border: none;
      border-radius: 5px;
      font-size: 1em;
      cursor: pointer;
      margin-bottom: 8px;
    }
    button:hover {
      background: #2f32a0;
    }
    .back-btn {
      background: #6c757d;
    }
    .back-btn:hover {
      background: #5a6268;
    }
  </style>
</head>
<body>
  <div class="container">
    <h3>Add Player</h3>
    <form method="post" action="">
      <label for="name">Name</label>
      <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">
      <div class="error"><?php echo $nameErr; ?></div>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
      <div class="error"><?php echo $emailErr; ?></div>

      <label for="phone">Phone</label>
      <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>">
      <div class="error"><?php echo $phoneErr; ?></div>

      <button type="submit">Add Player</button>
      <a href="player_register.php"><button type="button" class="back-btn">Back to Player List</button></a>
    </form>
  </div>
</body>
</html>