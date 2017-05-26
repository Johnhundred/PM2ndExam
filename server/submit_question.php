<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);

// Insert data received from frontend into the questions table.
if(login_check($pdo) == true && checkCSRFToken($jData->token)){
    $stmt = $pdo->prepare("INSERT INTO questions (question, answers, correct_answer) VALUES(:question, :answers, :correct)");
    $stmt->bindValue(":question", $jData->question);
    $stmt->bindValue(":answers", json_encode($jData->answers));
    $stmt->bindValue(":correct", $jData->correct_answer);
    $stmt->execute();
    generalLog("submit_question.php: User (" . $_SERVER['REMOTE_ADDR'] . ") submitted a question.");
}

?>