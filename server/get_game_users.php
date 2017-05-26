<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$result = "";

// Get template
$sTemplate = file_get_contents("../templates/user_list_user.html");

// Get history of game room with the game's ID.
$stmt = $pdo->prepare("SELECT history FROM active_games WHERE game_id = :id LIMIT 1");
$stmt->bindValue(":id", $sData, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Decode the JSON string to a PHP object
$history = json_decode($row['history']);

// Decode JSON string inside object to PHP object, insert data into template, echo filled-in template
$users = json_decode($history->users);
$iCounter = Count($history->users);
for($i = 0; $i < $iCounter; $i++){
    $result .= str_replace("{{name}}", htmlentities($users[$i]->name), $sTemplate);
    $result = str_replace("{{points}}", htmlentities($users[$i]->points), $result);
}

echo $result;

?>


