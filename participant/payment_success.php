<?php
include "connection.php";
session_start();
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51RaIdkPN2E3kM4PrhYoEJDdT8KhX0u91rH0LwjbkVvfNbma6gGAT6Rcjc5pDxyb65sxj78KcU2sc4UPn7AvtZgpa00MhoZLndy');

$teamID = isset($_SESSION['teamID']) ? intval($_SESSION['teamID']) : 0;

if (!$teamID || empty($_GET['session_id'])) {
    die("Invalid access.");
}

$session_id = $_GET['session_id'];

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);

    if ($session->payment_status === 'paid') {
        $paymentStatus = 'PAID';
        
        $stmt = $conn->prepare("UPDATE team SET payment_status = ? WHERE teamID = ?");
        $stmt->bind_param('si', $paymentStatus, $teamID);
        $stmt->execute();
    } else {
        die("Payment not completed yet.");
    }
} catch (Exception $e) {
    die("Error fetching payment details: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment Success</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url('image/futsal.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      position: relative;
      font-family: 'Poppins', sans-serif;
    }
    .container {
      max-width: 400px;
      margin: 50px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 24px;
      text-align: center;
    }
    button {
      padding: 10px 20px;
      background: #4f52ba;
      color: #fff;
      border: none;
      border-radius: 5px;
      font-weight: 500;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <h3 class="fw-bold mb-3">Payment Successful</h3>
    <p>Thank you for registering. Your payment of <strong>RM <?= number_format($session->amount_total / 100, 2) ?></strong> was successful!</p>
    <a href="dashboard_player.php"><button>Done</button></a>
  </div>
</body>
</html>
