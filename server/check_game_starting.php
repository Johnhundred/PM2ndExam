<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);
$id = htmlentities(filter_var($jData->id, FILTER_SANITIZE_STRING));

if($stmt = $pdo->prepare("SELECT starting FROM active_games WHERE game_id = :id")){
    $stmt->bindValue(":id", $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row['starting'] && $row['starting'] > time()){
        echo htmlentities($row['starting']);
    }
}

?>