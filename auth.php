<?php
// auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['flash'] = "Please log in first.";
        header("Location: login.php");
        exit();
    }
}
?>
