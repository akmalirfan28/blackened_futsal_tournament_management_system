<?php
// Initialize the session
session_start();
require_once "connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    
    

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
            
                
                    <h3>Login</h3>
                </div>
                <div class="card-body">
                    <form action="authentication_player_viewer.php" method="post">
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="username" class="form-control" placeholder="Username">

                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <div style="display: flex; justify-content: center; gap: 20px;">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="role" id="player" value="player" required>
                                    <label class="form-check-label" for="player">Player</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="role" id="viewer" value="viewer" required>
                                    <label class="form-check-label" for="viewer">Viewer</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="center">
                                <input type="submit" name="Login" class="btn login_btn" >
                            </div>
                        </div>
                        
                        <?php
                            if (isset($_SESSION['message'])){ ?>
                            <p style="color:red;"><?php echo $_SESSION['message']; ?></p>
                            <?php 
                            }
                            unset($_SESSION['message']);
                        ?>
                    </form>
                    </div>
                    <p style="color:Black;">Doesn't have account? <a href="register_player_viewer.php">Register</a>.</p>
            </div>
            
        </div>
    </div>
</body>
</html>