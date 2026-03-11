<!DOCTYPE HTML>
<html>
<body>
<?php
    include "connection.php";
    session_start();
    
    if(isset($_POST['Login'])){

    $userID = $_POST['userID'];
    $password = $_POST['password'];

    if ($userID != "" && $password != ""){

        $sql_query = "select count(*) as cntUser from admin where userID='".$userID."' and PASSWORD='".$password."'";
        $result = mysqli_query($conn,$sql_query);
        $row = mysqli_fetch_array($result);

        $count = $row['cntUser'];

        if($count > 0){
            $records = mysqli_query($conn, "SELECT * FROM admin WHERE userID ='$userID'");
    
            while($data = mysqli_fetch_array($records))
            {
                $_SESSION['userID'] = $userID;
                header('Location: list_tournament.php');
            }
            
        }else{ 
            $_SESSION['message']="Invalid user id and password";
			header('location:login.php');
        }

    }
}
?>
</body>
</html>