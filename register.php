<?php
require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

include("auth.php");
include("db.php");
include("captcha.php");

$username_field = "";
$errors = array();
$captcha_question = generate_captcha_question();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_field = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];
    $captcha_answer = $_POST['captcha'] ?? '';

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    if (!check_captcha_answer($captcha_answer)) {
        $errors[] = "Captcha incorrect.";
    }

    if ($username_field === '' || $password === '') {
        $errors[] = "Username and password are required.";
    }

    if (empty($errors)) {
        $u = $mysqli->real_escape_string($username_field);
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password_hash) VALUES ('$u', '$hash')";
        if ($mysqli->query($sql)) {
            $_SESSION['flash'] = "Registration successful. Please log in.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Username may already be taken.";
        }
    }
}

$username_session = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
unset($_SESSION['flash']);

echo $twig->render('register.html', array(
    'username' => $username_session,
    'flash' => $flash,
    'errors' => $errors,
    'username' => $username_field,
    'captcha_question' => $captcha_question
));
?>
