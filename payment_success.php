<?php
include "connection.php";
session_start();
require 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_51RaIdkPN2E3kM4PrhYoEJDdT8KhX0u91rH0LwjbkVvfNbma6gGAT6Rcjc5pDxyb65sxj78KcU2sc4UPn7AvtZgpa00MhoZLndy');

$session_id = $_GET['session_id'];

$session = \Stripe\Checkout\Session::retrieve($session_id);

$teamID = $_SESSION['teamID']; 
$paymentStatus = 'PAID';

$conn->query("UPDATE team SET payment_status = '$paymentStatus' WHERE teamID = $teamID");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Player Registration</title>
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
            font-size: 1em;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
        }
        input, select {
            width: 100%;
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
            padding: 5px;
            background: #4f52ba;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: 400;
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
            font-size: 0.5em;
            }

            table th, table td {
            padding: 8px 5px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 1em;
            }

            table th {
            background-color: #161a2d;
            color: white;
            }

            table tr:nth-child(even) {
            background-color: #f9f9f9;
            }

            table tr:hover {
            background-color: #f1f1f1;
            }

    </style>
</head>
<body>
      <!-- Table Section -->
  <div class="container">
  
    <h2>Payment Successful</h2>
    <p>Thank you for registering, your payment of RM <?php echo ($session->amount_total / 100); ?> was successful!</p>
    <a href="dashboard_player.php"><button class="btn btn-sm" >Done</button></a>
  </div>
</body>
</html>