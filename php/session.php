<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: index.php");
} else {
    $username = $_SESSION['username'];
    $get_user = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    $current_user = mysqli_fetch_array($get_user);
}