<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);
$sData = $jData->id;

// Check that user is logged in and the CSRF tokens match. If true, get the game they want deleted from the DB, then insert its data into closed_games, then delete it from active_games.
if(login_check($pdo) == true && checkCSRFToken($jData->token)){
    $stmt = $pdo->prepare("SELECT id, game_id, created, updated, history FROM active_games WHERE game_id = :id LIMIT 1");
    $stmt->bindValue(":id", htmlentities($sData));
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $current = time();
    $current = (string)$current;
    $stmt = $pdo->prepare("INSERT INTO closed_games (game_id, created, updated, history) VALUES(:gameid, :created, :updated, :history)");
    $stmt->bindValue(":gameid", $row['game_id']);
    $stmt->bindValue(":created", $row['created']);
    $stmt->bindValue(":updated", $current);
    $stmt->bindValue(":history", $row['history']);
    $stmt->execute();

    $stmt = $pdo->prepare("DELETE FROM active_games WHERE id = :id");
    $stmt->bindValue(":id", $row['id']);
    $stmt->execute();
    generalLog("close_game.php: User (" . $_SERVER['REMOTE_ADDR'] . ") closed a game with an id of " . htmlentities($sData) . ".");
}

$sNewUrl = url();
$sNewUrl = str_replace("server/close_game.php", "index.php", $sNewUrl);

echo $sNewUrl;

?>


