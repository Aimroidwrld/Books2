<?php
require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include("auth.php");
include("db.php");

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
unset($_SESSION['flash']);


echo $twig->render('index.html', array(
    'username' => $username,
    'flash' => $flash
));
?>
