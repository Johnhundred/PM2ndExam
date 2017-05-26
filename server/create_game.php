<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);
$sData = $jData->id;

// Remove any non-alphanumeric characters from ID.
$sId = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $sData);

// If ID is not empty, use it. If ID is empty, generate an ID.
if($sId == ""){
    $sId = generateUniqueId();
}

$creator = (string)$_SESSION['username'];

$history = '{"creator":"'.$creator.'","users":"[{\"name\":\"'.$creator.'\",\"points\":0}]"}';
//$history = json_encode(json_decode($history));

// Take values, create game with them. Spit out URL of new game, to redirect users to the game they just made.
if(login_check($pdo) == true && checkCSRFToken($jData->token)){
    $current = time();
    $current = (string)$current;
    $stmt = $pdo->prepare("INSERT INTO active_games (game_id, created, history) VALUES(:gameid, :created, :history)");
    $stmt->bindValue(":gameid", $sId);
    $stmt->bindValue(":created", $current);
    $stmt->bindValue(":history", $history);
    $stmt->execute();
    generalLog("create_game.php: User (" . $_SERVER['REMOTE_ADDR'] . ") created a game with an id of " . htmlentities($sId) . ".");

$sNewUrl = url();
$sNewUrl = str_replace("server/create_game.php", "game.php?id=" . $sId, $sNewUrl);

echo $sNewUrl;
}

?>

