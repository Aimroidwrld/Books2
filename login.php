<?php
require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include("auth.php");
include("db.php");

$username_field = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_field = trim($_POST['username']);
    $password = $_POST['password'];

    $u = $mysqli->real_escape_string($username_field);
    $sql = "SELECT * FROM users WHERE username = '$u' LIMIT 1";
    $result = $mysqli->query($sql);

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['flash'] = "Welcome, " . $row['username'] . "!";
            header("Location: books-list.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}

$username_session = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
unset($_SESSION['flash']);

echo $twig->render('login.html', array(
    'username' => $username_session,
    'flash' => $flash,
    'error' => $error,
    'username_field' => $username_field
));
?>
