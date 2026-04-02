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

$title = "";
$author = "";
$genre = "";
$pub_year = "";
$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre  = trim($_POST['genre']);
    $pub_year = intval($_POST['pub_year']);

    if ($title === '' || $author === '' || $genre === '' || $pub_year <= 0) {
        $errors[] = "All fields are required and year must be a positive number.";
    }

    if (empty($errors)) {
        $t = $mysqli->real_escape_string($title);
        $a = $mysqli->real_escape_string($author);
        $g = $mysqli->real_escape_string($genre);
        $y = $pub_year;

        $sql = "INSERT INTO books (title, author, genre, pub_year)
                VALUES ('$t', '$a', '$g', $y)";
        if ($mysqli->query($sql)) {
            $_SESSION['flash'] = "Book added successfully.";
            header("Location: books-list.php");
            exit();
        } else {
            $errors[] = "Error adding book.";
        }
    }
}

echo $twig->render('add-book.html', array(
    'username' => $username,
    'flash' => $flash,
    'errors' => $errors,
    'title' => $title,
    'author' => $author,
    'genre' => $genre,
    'pub_year' => $pub_year
));
?>
