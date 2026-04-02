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

if (!isset($_GET['id'])) {
    $_SESSION['flash'] = "No book ID provided.";
    header("Location: books-list.php");
    exit();
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM books WHERE id = $id";
$result = $mysqli->query($sql);
$book = $result->fetch_assoc();

if (!$book) {
    $_SESSION['flash'] = "Book not found.";
    header("Location: books-list.php");
    exit();
}

$title  = $book['title'];
$author = $book['author'];
$genre  = $book['genre'];
$pub_year = $book['pub_year'];
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

        $sql = "UPDATE books
                SET title = '$t', author = '$a', genre = '$g', pub_year = $y
                WHERE id = $id";
        if ($mysqli->query($sql)) {
            $_SESSION['flash'] = "Book updated.";
            header("Location: books-list.php");
            exit();
        } else {
            $errors[] = "Error updating book.";
        }
    }
}

echo $twig->render('edit-book.html', array(
    'username' => $username,
    'flash' => $flash,
    'errors' => $errors,
    'title' => $title,
    'author' => $author,
    'genre' => $genre,
    'pub_year' => $pub_year
));
?>
