<?php


if (isset($_POST['submit'])) {
    $secret = '6LfqYx0UAAAAAM6OtsL-AkWbQ7vlqP_Go5qaYSIm';
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];

    $username = "user";
    $pass = "pass123";
    $password = $_POST['password'];

    $url = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip");

    $result = json_decode($url, TRUE);
    if ($result['success'] == 1 && $password === $pass)  {
        echo "You have successfully logged in";
    }
}



?>