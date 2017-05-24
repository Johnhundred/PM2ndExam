<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);
$id = htmlentities(filter_var($jData->id, FILTER_SANITIZE_STRING));
$status = htmlentities(filter_var((string)$jData->status, FILTER_SANITIZE_STRING));
$token = htmlentities(filter_var((string)$jData->token, FILTER_SANITIZE_STRING));

if(admin_check($pdo) == true && checkCSRFToken($token)){
    $stmt = $pdo->prepare("UPDATE questions SET status = :status WHERE id = :id");
    $stmt->bindValue(":status", $status);
    $stmt->bindValue(":id", $id);
    $stmt->execute();
    $questionStatus = "";
    if($status == 1){
        $questionStatus = "Approved";
    } else if($status == 2){
        $questionStatus = "Rejected";
    }

    generalLog("question_status.php: User (" . $_SERVER['REMOTE_ADDR'] . " - admin) set the status of a question to: ".$questionStatus.".");
}

?>


