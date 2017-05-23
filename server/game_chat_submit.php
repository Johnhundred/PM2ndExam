<?php

include_once 'includes/inc2.php';

secure_session_start();

if(login_check($pdo)){
    if (isset($_POST["game_chat_msg"])){
        $stmt = $pdo->prepare("INSERT INTO hubchat(username, time, message, game_id) VALUES (:username, :time, :msg, :id)");
        $stmt->bindValue(":username", $_SESSION['username'], PDO::PARAM_STR);
        $stmt->bindValue(":time", "" . date("j/n/Y H:i:s"), PDO::PARAM_STR);
        $stmt->bindValue(":msg", $_POST['game_chat_msg']);
        $stmt->bindValue(":id", $_POST['game_chat_id']);
        $stmt->execute();
        header('Location: ../game.php?id=' . htmlentities($_POST['game_chat_id']));
    }
} else {
    generalLog("!ALERT! !BREACH! ERROR: game_chat_submit.php: User (" . $_SERVER['REMOTE_ADDR'] . ") attempted to submit a message without being logged in. Attempted breach likely, or breach of login system.");
    header('Location: ../index.php');
}

?>