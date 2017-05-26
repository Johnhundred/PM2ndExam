<?php

include_once 'includes/inc2.php';

// Get JSON string from frontend, decode it to PHP object, retrieve ID from it, get current time
$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);
$id = htmlentities(filter_var($jData->id, FILTER_SANITIZE_STRING));
$time = intval(time());

// Use the received ID to get a game of that ID from the database. If the starting game column is not empty, get it as an int and compare it to the current unix time (on the server). If the starting time is bigger, a game is starting, so we echo the unix time.
if($stmt = $pdo->prepare("SELECT * FROM active_games WHERE game_id = :id")){
    $stmt->bindValue(":id", $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row['starting_game'] != NULL){
        $starting = intval($row['starting_game']);
        if($starting > $time){
            echo htmlentities($row['starting_game']);
        }
    }
}

?>