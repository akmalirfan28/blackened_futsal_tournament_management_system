<?php
include "connection.php";


$username = $firstName = $lastName = $phoneNum = $email = $password = $role = "";
// define variables and set to empty values
$userErr = $firstNameErr = $lastNameErr = $phoneNumErr = $emailErr = $passErr = $roleErr = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["username"]))) {
    $userErr = "Username is required";
  } else {
    $username = trim($_POST["username"]);
  }
    
  if (empty(trim($_POST["firstname"]))) {  
        $firstNameErr = "First Name is required";  
    } else {  
        $firstName = trim($_POST["firstname"]); 
            // check if name only contains letters and whitespace  
            if (!preg_match("/^[a-zA-Z ]*$/",trim($_POST["firstname"]))) {  
                $firstNameErr = "Only alphabets and white space are allowed";  
            }  
    }  

    if (empty(trim($_POST["lastname"]))) {  
        $lastNameErr = "Last Name is required";  
    } else {  
        $lastName = trim($_POST["lastname"]); 
            // check if name only contains letters and whitespace  
            if (!preg_match("/^[a-zA-Z ]*$/",trim($_POST["lastname"]))) {  
                $lastNameErr = "Only alphabets and white space are allowed";  
            }  
    }  

    if (empty(trim($_POST["phonenumber"]))) {  
        $phoneNumErr = "Phone Number is required";  
    } else {  
            $phoneNum = trim($_POST["phonenumber"]);  
             
            if(!preg_match('/^[0-9]{10}+$/', trim($_POST["phonenumber"]))) {
                $phoneNumErr = "Invalid Phone Number";
            }
    }  

    if (empty(trim($_POST["email"]))) {  
        $emailErr = "Email is required";  
    } else {  
            $email = trim($_POST["email"]);  
            // check if name only contains letters and whitespace  
            if (!preg_match( 
                "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", trim($_POST["email"]))) {  
                $emailErr = "Invalid email address";  
            }  
    }  

    
   if(empty(trim($_POST["password"]))){
    $passErr = "Please enter a password.";     
} elseif(strlen(trim($_POST["password"])) < 6){
    $passErr = "Password must have atleast 6 characters.";
} else{
    $password = trim($_POST["password"]);
}

if (empty(trim($_POST["role"]))) {
    $roleErr = "Roll is required";
  } else {
    $roll = trim($_POST["role"]);
  }


if(empty($userErr) && empty($firstNameErr) && empty($lastNameErr) && empty($phoneNumErr) && empty($emailErr) && empty($passErr) && empty($roleErr)){
        
    // Prepare an insert statement
    $sql = "INSERT INTO player_viewer (username, firstName, lastName, phoneNumber, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
     
    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, 'sssssss', $param_username, $param_first_name, $param_last_name, $param_phone_num, $param_email, $param_password, $param_role);
        
        // Set parameters
        $param_username = $username;
        $param_first_name = $firstName;
        $param_last_name = $lastName;
        $param_phone_num = $phoneNum;
        $param_email = $email;
        $param_password = $password; // Creates a password hash
        $param_role = $roll;
    
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
                 // Redirect to login page
            header("location: index.php");
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
mysqli_close($conn);
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  body { background-image: url('image/futsal.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      position: relative; font-family: 'Poppins', sans-serif; }
  .register-container { max-width: 450px; margin: 50px auto; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
  .register_btn { background-color: #4f52ba; color: #fff; font-weight: 600; width: 100%; }
  .register_btn:hover { background-color: #3941a1; color: #fff; }
  .input-group-text { background-color: #4f52ba; color: #fff; border: none; }
</style>
</head>
<body>

<div class="container">
  <div class="register-container">
    <h3 class="text-center mb-4 fw-bold">Register</h3>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="input-group mb-3">
        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
        <input type="text" name="username" class="form-control <?php echo (!empty($userErr)) ? 'is-invalid' : ''; ?>" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>">
      </div>
      <div class="text-danger mb-2 small"><?php echo $userErr; ?></div>

      <div class="input-group mb-3">
        <span class="input-group-text"><i class="fas fa-user"></i></span>
        <input type="text" name="firstname" class="form-control <?php echo (!empty($firstNameErr)) ? 'is-invalid' : ''; ?>" placeholder="First Name" value="<?php echo htmlspecialchars($firstName); ?>">
      </div>
      <div class="text-danger mb-2 small"><?php echo $firstNameErr; ?></div>

      <div class="input-group mb-3">
        <span class="input-group-text"><i class="fas fa-user"></i></span>
        <input type="text" name="lastname" class="form-control <?php echo (!empty($lastNameErr)) ? 'is-invalid' : ''; ?>" placeholder="Last Name" value="<?php echo htmlspecialchars($lastName); ?>">
      </div>
      <div class="text-danger mb-2 small"><?php echo $lastNameErr; ?></div>

      <div class="input-group mb-3">
        <span class="input-group-text"><i class="fas fa-phone"></i></span>
        <input type="text" name="phonenumber" class="form-control <?php echo (!empty($phoneNumErr)) ? 'is-invalid' : ''; ?>" placeholder="Phone Number" value="<?php echo htmlspecialchars($phoneNum); ?>">
      </div>
      <div class="text-danger mb-2 small"><?php echo $phoneNumErr; ?></div>

      <div class="input-group mb-3">
        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
        <input type="text" name="email" class="form-control <?php echo (!empty($emailErr)) ? 'is-invalid' : ''; ?>" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
      </div>
      <div class="text-danger mb-2 small"><?php echo $emailErr; ?></div>

      <div class="input-group mb-3">
        <span class="input-group-text"><i class="fas fa-lock"></i></span>
        <input type="password" name="password" class="form-control <?php echo (!empty($passErr)) ? 'is-invalid' : ''; ?>" placeholder="Password">
      </div>
      <div class="text-danger mb-2 small"><?php echo $passErr; ?></div>

      <div class="mb-3 d-flex justify-content-center gap-3">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="role" id="player" value="Player" <?php echo ($role=='Player') ? 'checked' : ''; ?> required>
          <label class="form-check-label" for="player">Player</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="role" id="viewer" value="Viewer" <?php echo ($role=='Viewer') ? 'checked' : ''; ?> required>
          <label class="form-check-label" for="viewer">Viewer</label>
        </div>
      </div>
      <div class="text-danger mb-2 small text-center"><?php echo $roleErr; ?></div>

      <button type="submit" name="submit" class="btn register_btn">Register</button>
    </form>

    <div class="text-center mt-3">
      <small>Already registered? <a href="index.php">Login</a></small>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
