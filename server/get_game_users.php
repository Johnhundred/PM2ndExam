<?php

include_once "includes/db_connect.php";
include_once 'includes/functions.php';

secure_session_start();

$sData = $_POST['data'];

$sTemplate = file_get_contents("../templates/user_list_user.html");

$stmt = $pdo->prepare("SELECT history FROM active_games WHERE game_id = :id LIMIT 1");
$stmt->bindValue(":id", $sData, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$history = json_decode($row['history']);

$iCounter = Count($history->users);
for($i = 0; $i < $iCounter; $i++){
    $result .= str_replace("{{name}}", htmlentities($history->users[$i]->name), $sTemplate);
    $result = str_replace("{{points}}", htmlentities($history->users[$i]->points), $result);
}

echo $result;

?>


