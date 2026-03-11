<?php
    include "connection.php";
    session_start();
    
    if(isset($_POST['Login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($username != "" && $password != "" && $role != ""){

        $sql_query = "select count(*) as cntUser from player_viewer where username='".$username."' and PASSWORD='".$password."' and role='".$role."'";
        $result = mysqli_query($conn,$sql_query);
        $row = mysqli_fetch_array($result);
        $result = mysqli_query($conn,$sql_query);
        $row = mysqli_fetch_array($result);

        $count = $row['cntUser'];

        if($count > 0){
            $records = mysqli_query($conn, "SELECT * FROM player_viewer WHERE username ='$username'");
    
            while($data = mysqli_fetch_array($records))
            {
                if ($data['role'] == 'Player') {
                    $_SESSION['username'] = $username;
                    header('Location: tournament_register.php');
                }else if ($data['role'] == 'Viewer') {
                    $_SESSION['username'] = $username;
                    header('Location: tournament_selection.php');
                }else{
                    $_SESSION['message']="Role not defined";
                }
                
            }
            
        }else{ 
            $_SESSION['message']="Invalid username and password";
			header('location:index.php');
        }

    }
}
?>