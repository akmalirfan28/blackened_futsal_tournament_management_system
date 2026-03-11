<?php
session_start();
require_once "connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
    background-image: url('image/futsal.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      position: relative;
      font-family: 'Poppins', sans-serif;
    }
    .login-container {
      max-width: 400px;
      margin: 50px auto;
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .login_btn {
      background-color: #4f52ba;
      color: #fff;
      font-weight: 600;
      width: 100%;
    }
    .login_btn:hover {
      background-color: #3941a1;
      color: #fff;
    }
    .input-group-text {
      background-color: #4f52ba;
      color: #fff;
      border: none;
    }
    @media (max-width: 500px) {
      .login-container {
        margin: 20px 10px;
        padding: 15px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="login-container">
    <h3 class="text-center mb-4 fw-bold">Login</h3>

    <form action="authentication_player_viewer.php" method="post">
      <div class="input-group mb-3">
        <span class="input-group-text"><i class="fas fa-user"></i></span>
        <input type="text" name="username" class="form-control" placeholder="Username" required>
      </div>

      <div class="input-group mb-3">
        <span class="input-group-text"><i class="fas fa-lock"></i></span>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>

      <div class="mb-3 d-flex justify-content-center gap-3">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="role" id="player" value="player" required>
          <label class="form-check-label" for="player">Player</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="role" id="viewer" value="viewer" required>
          <label class="form-check-label" for="viewer">Viewer</label>
        </div>
      </div>

      <button type="submit" name="Login" class="btn login_btn">Login</button>

      <?php if (isset($_SESSION['message'])) { ?>
        <div class="text-danger text-center mt-2"><?php echo $_SESSION['message']; ?></div>
      <?php unset($_SESSION['message']); } ?>
    </form>

    <div class="text-center mt-3">
      <small>Don't have an account? <a href="register_player_viewer.php">Register</a></small>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
