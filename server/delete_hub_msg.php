<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);
$sData = $jData->id;

if(admin_check($pdo) == true && checkCSRFToken($jData->token)){
    $stmt = $pdo->prepare("DELETE FROM hubchat WHERE id = :id");
    $stmt->bindValue(":id", $sData);
    $stmt->execute();
    generalLog("delete_hub_msg.php: User (" . $_SERVER['REMOTE_ADDR'] . ") admin-deleted message with an id of " . htmlentities($sData) . ". If the id looks malformed, someone succeeded at a breach.");
}

?>