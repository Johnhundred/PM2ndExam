<?php

include_once 'includes/inc2.php';

secure_session_start();

if (isset($_POST["email"], $_POST["p"]) && checkCSRFToken($_POST['token'])) {
    generalLog("Token (Session): " . $_SESSION['token']);
    generalLog("Token (Form): " . $_POST['token']);
    $email = $_POST["email"];
    $password = $_POST["p"]; // The hashed password.

    // CAPTCHA stuff.
    $secret = '6LfqYx0UAAAAAM6OtsL-AkWbQ7vlqP_Go5qaYSIm';
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];

    $url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip");

    $result = json_decode($url, TRUE);

    if($result['success'] == 1){
        // CAPTCHA passed, proceeding to check login info.
        if (login($email, $password, $pdo) == true) {
            // Login successful.
            generalLog("process_login.php: User (" . $_SERVER['REMOTE_ADDR'] . ") logged in.");
            header("Location: ../hub.php");
        } else {
            // Login failed.
            header("Location: ../login.php");
        }
    } else {
        // CAPTCHA failed, login not checked.
        generalLog("process_login.php: User (" . $_SERVER['REMOTE_ADDR'] . ") failed the login CAPTCHA.");
        header("Location: ../login.php");
    }

} else {
    // The correct POST variables were not sent to this page.
    header("Location: ../login.php");
}

?>

