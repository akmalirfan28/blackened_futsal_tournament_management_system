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
            header("location: login_player_viewer.php");
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    
    

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    
<style>
@import url('https://fonts.googleapis.com/css?family=Numans');
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
            text-align: center;
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
        .login_btn{
color: #fff;
background: #4f52ba;
align-items: center;
width: 100%;
}

.login_btn:hover{
color: black;
background-color: white;
}
.input-group-prepend span{
width: 50px;
background: #4f52ba;
color: white;
border:0 !important;
}
.social_icon{
position: absolute;
right: 20px;
top: -45px;
}
.social_icon span{
font-size: 60px;
margin-left: 10px;
color: #FFC312;
}

.social_icon span:hover{
color: white;
cursor: pointer;
}
    </style>
</head>
<body>
<div class="container">
        <div class="d-flex justify-content-center h-100">
            
                    <h3>Register</h3>
                </div>
                <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-id-card-alt"></i></span>
                            </div>
                            <input type="text" name="username" class="form-control <?php echo (!empty($userErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" placeholder="Username" required>
                            <span class="error" style="color: red;">* <?php echo $userErr; ?> </span>  
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fab fa-amilia"></i></span>
                            </div>
                            <input type="text" name="firstname" class="form-control <?php echo (!empty($firstNameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstName; ?>" placeholder="First Name" required>
                            <span class="error" style="color: red;">* <?php echo $firstNameErr; ?> </span>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fab fa-amilia"></i></span>
                            </div>
                            <input type="text" name="lastname" class="form-control <?php echo (!empty($lastNameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastName; ?>" placeholder="Last Name" required>
                            <span class="error" style="color: red;">* <?php echo $lastNameErr; ?> </span>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="phonenumber" class="form-control <?php echo (!empty($phoneNumErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $phoneNum; ?>" placeholder="Phone Number" required>
                            <span class="error" style="color: red;">* <?php echo $phoneNumErr; ?> </span>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="email" class="form-control <?php echo (!empty($emailErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" placeholder="Email" required>
                            <span class="error" style="color: red;">* <?php echo $emailErr; ?> </span>
                        </div>
                        
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control <?php echo (!empty($passErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" placeholder="Password" required>
                            <span class="error" style="color: red;">* <?php echo $passErr; ?> </span>
                        </div>
                        <div class="form-group">
                            <div style="display: flex; justify-content: center; gap: 20px;">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="role" id="player" value="Player" required>
                                    <label class="form-check-label" for="player">Player</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="role" id="viewer" value="Viewer" required>
                                    <label class="form-check-label" for="viewer">Viewer</label>
                                </div>
                                <span class="error" style="color: red;">* <?php echo $roleErr; ?> </span>
                            </div>
                        </div>
                        <div class="form-group">
                        <input type="submit" name="submit" class="btn login_btn">
                        </div>
                    </form>
                    <p style="color:black;">Already register? <a href="login_player_viewer.php">Login</a>.</p>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>