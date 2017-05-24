<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);
$id = filter_var($jData->id, FILTER_SANITIZE_STRING);

if(login_check($pdo) == true && checkCSRFToken($jData->token)){
    $time = time() + GAME_START_TIME;
    $future = (string)$time;

    //Gather 5 questions
    $newResult = new stdClass();
    $newResult->question_template = file_get_contents("../templates/question_game.html");
    $newResult->answer_template = file_get_contents("../templates/individual_question.html");
    $newResult->questions = [];
    $aQuestions = getQuestionsForGame($pdo);
    $rows = $aQuestions;

    for($i = 0; $i < Count($aQuestions); $i++){
        $question = new stdClass();
        $question->title = htmlentities($rows[$i]['question']);
        $question->answers = $rows[$i]['answers'];
        array_push($newResult->questions, $question);
    }

    if($stmt = $pdo->prepare("UPDATE active_games SET starting_game = :starting WHERE game_id = :id")){
        $stmt->bindValue(":starting", $future);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
    }

    echo json_encode($newResult);
}

?>


