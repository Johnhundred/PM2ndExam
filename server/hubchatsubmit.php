<?php

include_once "includes/db_connect.php";
include_once 'includes/functions.php';

secure_session_start();

if(login_check($pdo)){
    if (isset($_POST["hubchatmsg"])){
        $stmt = $pdo->prepare("INSERT INTO hubchat(username, time, message) VALUES (:username, :time, :msg)");
        $stmt->bindValue(":username", $_SESSION['username'], PDO::PARAM_STR);
        $stmt->bindValue(":time", "" . date("j/n/Y H:i:s"), PDO::PARAM_STR);
        $stmt->bindValue(":msg", $_POST['hubchatmsg']);
        $stmt->execute();
        header('Location: ../hub.php');
    } else {
        header('Location: ../hub.php');
    }
} else {
    generalLog("!ALERT! !BREACHLIKE! ERROR: hubchatsubmit.php: User (" . $_SERVER['REMOTE_ADDR'] . ") attempted to submit a hub message without being logged in. Attempted breach likely.");
    header('Location: ../index.php');
}

?>