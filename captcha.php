<?php
// captcha.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function generate_captcha_question() {
    $a = rand(1, 9);
    $b = rand(1, 9);
    $_SESSION['captcha_answer'] = $a + $b;
    return "What is $a + $b?";
}

function check_captcha_answer($answer) {
    if (!isset($_SESSION['captcha_answer'])) {
        return false;
    }
    return intval($answer) === $_SESSION['captcha_answer'];
}
?>
