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
        <h2>Add Player</h2>
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

            <button type="submit">Add</button>
        </form>
    </div>
</body>
</html>