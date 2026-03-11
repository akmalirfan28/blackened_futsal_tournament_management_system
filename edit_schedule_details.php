<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $match_id = $_SESSION['match_id'];
    $court = $_POST['court'];
    $time = $_POST['time'];
    
    $sql = "UPDATE match_schedule SET court = ?, match_time = ? WHERE match_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $court, $time, $match_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Match schedule updated successfully!');</script>";
        echo "<script>window.location.href='schedule.php';</script>";
    } else {
        echo "<script>alert('Error updating match schedule!');</script>";
        echo "<script>window.location.href='schedule.php';</script>";
    }
}
?>