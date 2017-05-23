<?php

include_once 'server/includes/inc.php';

secure_session_start();

generalLog("1");

$to = "significantowlowl@gmail.com";
$subject = "test";
$message = "<a href='google.com'>Test</a>";
generalLog("2");

$header = "From: noreply@example.com\r\n";
$header.= "MIME-Version: 1.0\r\n";
$header.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$header.= "X-Priority: 1\r\n";
generalLog("3");

if(mail($to, $subject, $message, $header)){
    generalLog("test.php: User (" . $_SERVER['REMOTE_ADDR'] . ") sent an email to ".$to.".");
} else {
    generalLog("test.php: User (" . $_SERVER['REMOTE_ADDR'] . ") failed to send an email.");
}
?>