<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);
$id = htmlentities(filter_var($jData->id, FILTER_SANITIZE_STRING));

if(login_check($pdo) == true && checkCSRFToken($jData->token)){
    $time = time() + 15;

    if($stmt = $pdo->prepare("UPDATE active_games SET starting = :starting WHERE id = :id")){
        $stmt->bindValue(":starting", $time);
        $stmt->bindValue(":id", $id, PDO::PARAM_STR);
        $stmt->execute();

        echo htmlentities($time);
    }
}

?>


