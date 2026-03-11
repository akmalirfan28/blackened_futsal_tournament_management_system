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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Player Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-image: url('image/futsal.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      position: relative; font-family: 'Poppins', sans-serif; }
    .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 24px; }
    h2 { text-align: center; margin-bottom: 24px; }
    .btn-custom { background: #4f52ba; color: #fff; font-size: 0.9em; }
    .btn-custom:hover { background: #3d41a1; color: #fff; }
    table { font-size: 0.9em; width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);}
            th, td {
            padding: 10px;
            text-align: center;
        }
    th { background: #4f52ba !important; color: white !important; text-transform: uppercase;}
    tr:nth-child(even) {
            background-color: #f9f9f9;
        }

tr:hover {
  background-color: #f1f1f1;
}
    .registration-fee { font-size: 0.8em; margin-top: 15px; text-align: center; }
    @media (max-width: 768px) {
      .container { padding: 16px; }
      .btn-custom { font-size: 0.8em; }
      table { font-size: 0.8em; }
    }
    p{font-size: 1.5em;}
  </style>
</head>
<body>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold">List Of Players</h2>
    <a href="add_player.php?NAME=<?php echo $teamID; ?>" class="btn btn-custom btn-sm">Add Player</a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Phone Number</th>
          <th>Email</th>
          <th>Update</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $records = mysqli_query($conn, "SELECT * FROM player WHERE teamID = '$teamID'");
        while($data = mysqli_fetch_array($records)) {
        ?>
          <tr>
            <td><?php echo htmlspecialchars($data['name']); ?></td>
            <td><?php echo htmlspecialchars($data['phoneNumber']); ?></td>
            <td><?php echo htmlspecialchars($data['email']); ?></td>
            <td><a href="update_player_details.php?NAME=<?php echo $data['playerID']; ?>" class="btn btn-sm btn-success">Update</a></td>
            <td><a href="delete_player.php?NAME=<?php echo $data['playerID']; ?>" class="btn btn-sm btn-danger">Delete</a></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <?php if ($payment_status == 'Not Paid') { ?>
    <div class="registration-fee">
      <p>Registration Fee: <strong>RM <?php echo $_SESSION['fee']; ?></strong></p>
      <a href="payment.php" class="btn btn-custom btn-sm" style="font-size: 1.3em;">Pay Registration Fee</a>
    </div>
  <?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
