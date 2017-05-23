<?php

include_once 'includes/inc2.php';

secure_session_start();

if(login_check($pdo) && checkCSRFToken($_POST['token'])){
    if (isset($_POST["hubchatmsg"])){
        $stmt = $pdo->prepare("INSERT INTO hubchat(username, time, message) VALUES (:username, :time, :msg)");
        $stmt->bindValue(":username", $_SESSION['username'], PDO::PARAM_STR);
        $stmt->bindValue(":time", "" . date("j/n/Y H:i:s"), PDO::PARAM_STR);
        $stmt->bindValue(":msg", $_POST['hubchatmsg']);
        $stmt->execute();
        header('Location: ../chat.php');
    } else {
        header('Location: ../chat.php');
    }
} else {
    generalLog("!ALERT! !BREACHLIKE! ERROR: hubchatsubmit.php: User (" . $_SERVER['REMOTE_ADDR'] . ") attempted to submit a hub message without being logged in. Attempted breach likely.");
    header('Location: ../index.php');
}

?>