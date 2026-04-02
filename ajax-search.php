<?php
// ajax-search.php
include("db.php");

$keywords = isset($_GET['search']) ? $_GET['search'] : "";
$k = $mysqli->real_escape_string($keywords);

if ($k !== "") {
    $sql = "SELECT * FROM books
            WHERE title LIKE '%$k%'
            ORDER BY title
            LIMIT 10";
} else {
    $sql = "SELECT * FROM books ORDER BY title LIMIT 10";
}

$results = $mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($results);
?>

