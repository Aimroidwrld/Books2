<?php
include("auth.php");
require_login();
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM books WHERE id = $id";
    $mysqli->query($sql);
    $_SESSION['flash'] = "Book deleted.";
}

header("Location: books-list.php");
exit();
?>
