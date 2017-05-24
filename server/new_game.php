<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);
$id = filter_var($jData->id, FILTER_SANITIZE_STRING);

if(login_check($pdo) == true && checkCSRFToken($jData->token)){
    $time = time() + 15;
    $future = (string)$time;

    if($stmt = $pdo->prepare("UPDATE active_games SET starting_game = :starting WHERE game_id = :id")){
        $stmt->bindValue(":starting", $future);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        echo htmlentities($future);
    }
}

?>


