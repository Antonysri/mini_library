<?php
session_start();
include_once 'connection.php';



if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    // Update logout_time in the database
    date_default_timezone_set('Asia/Kolkata');
    $logoutTime =date('Y-m-d H-i-s');
    $sql = "UPDATE register SET logout_time = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $logoutTime, $_SESSION['id']);
    $stmt->execute();
    $stmt->close();
}

session_unset();
session_destroy();
header("Location: log_in.php");
exit;
?>
