<?php

$servername = "localhost";
$username = "u620072222_blckadmin";
$password = "Imeq7*Pyd4#g";
$db = "u620072222_blckendfutsal";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?> 

