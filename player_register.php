<?php
include "connection.php";
session_start();
$teamID = $_SESSION['teamID'];
$tournamentID = $_SESSION['tournamentID'];

$records = mysqli_query($conn, "SELECT * FROM team WHERE teamID = '$teamID'");
        
        while($data = mysqli_fetch_array($records))
        {
          $payment_status = $data['payment_status'];
        }
    
$records = mysqli_query($conn, "SELECT * FROM tournament WHERE tournamentID = '$tournamentID'");
        
        while($data = mysqli_fetch_array($records))
        {
          $_SESSION['fee'] = $data['fee'];
        }

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
  
    <h2>List Of Players</h2>
    <a href="add_player.php?NAME=<?php echo $teamID;?>" style="float: right;"><button class="btn btn-sm" style="font-size: 0.5em;">Add Player</button></a><br><br>
    
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Phone Number</th>
          <th>Email</th>
          <th>Update</th>
          <th>Delete</th>
      </thead>
      <tbody>
        <?php
        $records = mysqli_query($conn, "SELECT * FROM player WHERE teamID = '$teamID'");
        
        while($data = mysqli_fetch_array($records))
        {
          ?>
            <tr>
                    <td><?php echo $data['name'];?></td>
                    <td><?php echo $data['phoneNumber'];?></td>
                    <td><?php echo $data['email'];?></td>
                    <td>
                    <a href="update_player_details.php?NAME=<?php echo $data['playerID'];?>"><button class="btn btn-sm">Update</button></a>
                    </td>
                    <td>
                    <a href="delete_player.php?NAME=<?php echo $data['playerID'];?>"><button class="btn btn-sm">Delete</button></a>
                    </td>
        <?php
          }
        
        ?>
      </tbody>
    </table>
    <?php
    if($payment_status == 'Not Paid'){ ?>
       <p style="font-size: 0.7em; text-align: center;">Registration Fee: RM <?php echo $_SESSION['fee']; ?></p>
      <a href="payment.php"><button class="btn btn-sm" id="checkout-button" style="font-size: 0.5em;">Pay Registration Fee</button></a>
    <?php }
    ?>
  </div>
</body>
</html>