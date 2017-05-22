<?php

include_once "includes/db_connect.php";
include_once 'includes/functions.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);

if(admin_check($pdo) == true){
    $stmt = $pdo->prepare("UPDATE questions SET status = :status WHERE id = :id");
    $stmt->bindValue(":status", $jData->status);
    $stmt->bindValue(":id", $jData->id);
    $stmt->execute();
    $questionStatus = "";
    if($jData->status == 1){
        $questionStatus = "Approved";
    } else if($jData->status == 2){
        $questionStatus = "Rejected";
    }

    generalLog("question_status.php: User (" . $_SERVER['REMOTE_ADDR'] . " - admin) set the status of a question to: ".$questionStatus.".");
}

?>


