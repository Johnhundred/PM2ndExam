<?php

include_once "includes/db_connect.php";
include_once 'includes/functions.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_decode($sData);
$sId = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $jData);

if($sId == ""){
    $sId = generateUniqueId();
}

$creator = (string)$_SESSION['username'];

$history = '{"creator": "'.$creator.'", "users": [{"name": "'.$creator.'", "points": "0"}]}';

if(login_check($pdo) == true){
    $current = time();
    $current = (string)$current;
    $stmt = $pdo->prepare("INSERT INTO active_games (game_id, created, history) VALUES(:gameid, :created, :history)");
    $stmt->bindValue(":gameid", $sId);
    $stmt->bindValue(":created", $current);
    $stmt->bindValue(":history", $history);
    $stmt->execute();
    generalLog("create_game.php: User (" . $_SERVER['REMOTE_ADDR'] . ") created a game with an id of " . htmlentities($sId) . ".");
}

$sNewUrl = url();
$sNewUrl = str_replace("server/create_game.php", "game.php?id=" . $sId, $sNewUrl);

echo $sNewUrl;

?>

