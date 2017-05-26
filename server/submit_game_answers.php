<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);

$points = 0;

$answers = $jData->answers;

// Iterate over the answers received. Note the current question and the selected answer. Open the database, get all questions where the question text matches the text of the current question we're looking at.
// For each question we fetched from the database, we check that question text matches, then we get the answers from the DB, and the correct answer, and then decrease it by one so that it will match an array index.
// For each answer, we check that the current answer we're looking at matches the text of the current answer submitted by the user, and then check that the index of the answer matches the correct answer's index.
// If it does, we increment points by 1.
// Finally, we fetch the game, re-encode the history string, and set the game's history to be that string.
if(login_check($pdo) == true){
    for($i = 0; $i < Count($answers); $i++){
        $currentQuestion = filter_var($answers[$i]->question, FILTER_SANITIZE_STRING);
        $currentAnswer = filter_var($answers[$i]->answer, FILTER_SANITIZE_STRING);

        $stmt = $pdo->prepare("SELECT question, answers, correct_answer FROM questions WHERE question = :question");
        $stmt->bindValue(":question", $currentQuestion);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        for($j = 0; $j < Count($rows); $j++){
            if($currentQuestion == $rows[$j]['question']){
                $getAnswers = json_decode($rows[$j]['answers']);
                $actualAnswers = $getAnswers->answers;
                $correctAnswerIndex = $rows[$j]['correct_answer'];
                $correctAnswerIndex--;
                for($m = 0; $m < Count($actualAnswers); $m++){
                    if(filter_var($actualAnswers[$m],FILTER_SANITIZE_STRING) == $currentAnswer && $m == $correctAnswerIndex){
                        $points++;
                        break;
                    }
                }
            }
        }
    }

    $sId = filter_var($jData->id, FILTER_SANITIZE_STRING);
    $stmt = $pdo->prepare("SELECT * FROM active_games WHERE game_id = :id LIMIT 1");
    $stmt->bindValue(":id", $sId);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $history = json_decode($row['history']);
    $users = json_decode($history->users);
    for($j = 0; $j < Count($users); $j++){
        if($users[$j]->name == $_SESSION['username']){
            $users[$j]->points = intval($users[$j]->points) + $points;
        }
    }

    $history->users = json_encode($users);

    $stmt = $pdo->prepare("UPDATE active_games SET history = :history WHERE game_id = :id");
    $stmt->bindValue(":history", json_encode($history));
    $stmt->bindValue(":id", $sId);
    $stmt->execute();
}

?>