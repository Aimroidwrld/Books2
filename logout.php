<?php
include("auth.php");
session_unset();
session_destroy();
session_start();
$_SESSION['flash'] = "You have been logged out.";
header("Location: index.php");
exit();
?>
