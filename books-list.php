<?php
require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include("auth.php");
include("db.php");

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
unset($_SESSION['flash']);

require_login();

$sql = "SELECT * FROM books ORDER BY created_at DESC";
$results = mysqli_query($mysqli, $sql);
$num_rows = mysqli_num_rows($results);

echo $twig->render('books-list.html', array(
    'username' => $username,
    'flash' => $flash,
    'results' => $results,
    'num_rows' => $num_rows
));
?>
