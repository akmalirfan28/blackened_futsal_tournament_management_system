<?php
include "connection.php";


$id = $firstName = $lastName = $phoneNum = $email = $password = "";
// define variables and set to empty values
$idErr = $firstNameErr = $lastNameErr = $phoneNumErr = $emailErr = $passErr = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["id"]))) {
    $idErr = "Id is required";
  } else {
    $id = trim($_POST["id"]);
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


if(empty($idErr) && empty($firstNameErr) && empty($lastNameErr) && empty($phoneNumErr) && empty($emailErr) && empty($passErr)){
        
    // Prepare an insert statement
    $sql = "INSERT INTO admin VALUES (?, ?, ?, ?, ?, ?)";
     
    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, 'ssssss', $param_id, $param_first_name, $param_last_name, $param_phone_num, $param_email, $param_password);
        
        // Set parameters
        $param_id = $id;
        $param_first_name = $firstName;
        $param_last_name = $lastName;
        $param_phone_num = $phoneNum;
        $param_email = $email;
        $param_password = $password; // Creates a password hash
    
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
<html>
<head>
    <title>Register Page</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    
<style>
@import url('https://fonts.googleapis.com/css?family=Numans');

html,body{
background-image: url('image/futsal bg.jpg');
background-color: white;
background-size: cover;
background-repeat: no-repeat;
height: 100%;
font-family: 'Numans', sans-serif;
position: relative;
}

.container{
height: 100%;
align-content: center;
}

.card{
height: 500px;
margin-top: auto;
margin-bottom: auto;
width: 400px;
background-color: rgba(0,0,0,0.5) !important;
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

.card-header h3{
color: white;
}

.social_icon{
position: absolute;
right: 20px;
top: -45px;
}

.input-group-prepend span{
width: 50px;
background-color: #FFC312;
color: black;
border:0 !important;
}

input:focus{
outline: 0 0 0 0  !important;
box-shadow: 0 0 0 0 !important;

}

.remember{
color: white;
}

.remember input
{
width: 20px;
height: 20px;
margin-left: 15px;
margin-right: 5px;
}

.register_btn{
color: black;
background-color: #FFC312;
width: 100px;
}

.register_btn:hover{
color: black;
background-color: white;
}

.links{
color: white;
}

.links a{
margin-left: 4px;
} 
</style>    
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <div class="card-header">
                    <h3>Register</h3>
                </div>
                <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-id-card-alt"></i></span>
                            </div>
                            <input type="text" name="id" class="form-control <?php echo (!empty($idErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $id; ?>" placeholder="User Id" required>
                            <span class="error" style="color: white;">* <?php echo $idErr; ?> </span>  
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="firstname" class="form-control <?php echo (!empty($firstNameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstName; ?>" placeholder="First Name" required>
                            <span class="error" style="color: white;">* <?php echo $firstNameErr; ?> </span>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="lastname" class="form-control <?php echo (!empty($lastNameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastName; ?>" placeholder="Last Name" required>
                            <span class="error" style="color: white;">* <?php echo $lastNameErr; ?> </span>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" name="phonenumber" class="form-control <?php echo (!empty($phoneNumErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $phoneNum; ?>" placeholder="Phone Number" required>
                            <span class="error" style="color: white;">* <?php echo $phoneNumErr; ?> </span>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" name="email" class="form-control <?php echo (!empty($emailErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" placeholder="Email" required>
                            <span class="error" style="color: white;">* <?php echo $emailErr; ?> </span>
                        </div>
                        
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control <?php echo (!empty($passErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" placeholder="Password" required>
                            <span class="error" style="color: white;">* <?php echo $passErr; ?> </span>
                        </div>
                        <div class="form-group">
                        <input type="submit" name="submit" class="btn float-right register_btn">
                        </div>
                    </form>
                    <p style="color:White;">Already register? <a href="index.php">Login</a>.</p>
                </div>
                
            </div>
        </div>
    </div>
</body>

</html>
