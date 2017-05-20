<?php

include_once "includes/db_connect.php";
include_once 'includes/functions.php';

secure_session_start();

$sId = htmlentities($_POST['data']);
$proceed = false;
$history = "";
$parent = "";

$stmt = $pdo->prepare("SELECT game_id FROM active_games");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$iCounter = Count($rows);
for($i = 0; $i < $iCounter; $i++){
    if($rows[$i]['game_id'] == $sId){
        $proceed = true;
        $parent = $rows[$i];
    }
}

if($history != ""){
    $conflict = false;
    $history = json_decode($parent->history);

    $iCounter2 = Count($history->users);
    for($j = 0; $j < $iCounter2; $j++){
        if($_SESSION['username'] == $history->users[$j]->name){
            $conflict = true;
        }
    }

    if($conflict == false){
        $insert->name = "" . $_SESSION['username'];
        $insert->points = "0";
        array_push($history->users, $insert);
        $parent->history = $history;
    }
}

$stmt = $pdo->prepare("UPDATE active_games SET history = :history WHERE id = :id");
$stmt->bindValue(":id", $parent["id"]);
$stmt->bindValue(":history", json_encode($parent->history));
$stmt->execute();



$sNewUrl = url();
$sNewUrl = str_replace("server/join_game.php", "game.php?id=" . $sId, $sNewUrl);

$result->status = $proceed;
$result->url = $sNewUrl;

$result = json_encode($result);

echo $result;

?>


