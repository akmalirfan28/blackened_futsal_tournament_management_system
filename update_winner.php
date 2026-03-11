<?php
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $match_id = (int) $_POST['match_id'];
    $winner = $conn->real_escape_string($_POST['winner']);

    // 1. Update current match winner
    $conn->query("UPDATE matches SET winner = '$winner' WHERE id = $match_id");

    // 2. Fetch next match info
    $res = $conn->query("SELECT next_match_id, position FROM matches WHERE id = $match_id");
    if ($row = $res->fetch_assoc()) {
        $next_id = $row['next_match_id'];
        $pos = $row['position'];

        if ($next_id && $pos) {
            // 3. Update winner into next match
            $conn->query("UPDATE matches SET `$pos` = '$winner' WHERE id = $next_id");
        }
    }

    header("Location: manage_bracket.php");
    exit();
}
?>
