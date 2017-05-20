<?php

include_once "includes/db_connect.php";
include_once 'includes/functions.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_decode($sData);

if(admin_check($pdo) == true){
    $stmt = $pdo->prepare("DELETE FROM hubchat WHERE id = :id");
    $stmt->bindValue(":id", $jData);
    $stmt->execute();
    generalLog("delete_hub_msg.php: User (" . $_SERVER['REMOTE_ADDR'] . ") admin-deleted message with an id of " . htmlentities($jData) . ". If the id looks malformed, someone succeeded at a breach.");
}

?>