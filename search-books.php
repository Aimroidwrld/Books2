<?php
require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include("auth.php");
include("db.php");

// Username
$username = null;
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

// Flash
$flash = null;
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
}
unset($_SESSION['flash']); 

// Title
$title = "";
if (isset($_GET['title'])) {
    $title = trim($_GET['title']);
}

// Author
$author = "";
if (isset($_GET['author'])) {
    $author = trim($_GET['author']);
}

// Genre
$genre = "";
if (isset($_GET['genre'])) {
    $genre = trim($_GET['genre']);
}

// Year
$year = 0;
if (isset($_GET['year'])) {
    $year = intval($_GET['year']);
}

// Build query
$sql = "SELECT * FROM books WHERE 1=1";

if ($title !== "") {
    $t = $mysqli->real_escape_string($title);
    $sql .= " AND title LIKE '%$t%'";
}

if ($author !== "") {
    $a = $mysqli->real_escape_string($author);
    $sql .= " AND author LIKE '%$a%'";
}

if ($genre !== "") {
    $g = $mysqli->real_escape_string($genre);
    $sql .= " AND genre = '$g'";
}

if ($year > 0) {
    $sql .= " AND pub_year = $year";
}

$sql .= " ORDER BY title";

$results = $mysqli->query($sql);
$num_rows = mysqli_num_rows($results);

// Render Twig
echo $twig->render('search-books.html', array(
    'username' => $username,
    'flash' => $flash,
    'results' => $results,
    'num_rows' => $num_rows,
    'title' => $title,
    'author' => $author,
    'genre' => $genre,
    'year' => $year > 0 ? $year : ""
));
?>
