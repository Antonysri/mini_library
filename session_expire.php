<?php
session_start();
if(!isset($_SESSION['id'])&& empty($_SESSION['id'])){
    header("Location:log_in.php");
}
// Session expired time
$expiredTime = 3600; // 30 minutes

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $expiredTime)) {
    session_unset();
    session_destroy();
    header("Location: log_in.php");
    exit;
} else {
    // Update last_activity in the session
    $_SESSION['last_activity'] = time();
}



?>