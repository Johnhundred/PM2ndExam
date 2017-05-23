<?php

include_once 'server/includes/inc.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';

?>

    <div class="center-block logo logo-md">
        <img src="assets/img/logo.png">
        <h2>MEMBER HUB</h2>
    </div>

    <div class="container white-box member-box questions-container-back">
        <?php

        if (login_check($pdo) == true) {
        ?>

            <div class="member-question-submit">
                <form action="server/question_submit.php" method="post" name="questionsubmit">
                    <div class="create-question-text">
                        <input name="createquestion" id="lblQuestion" placeholder="Question">
                    </div>
                    <div class="create-question-answers">
                        <input name="createanswer1" id="lblAnswer1" placeholder="Answer 1">
                        <input name="createanswer2" id="lblAnswer2" placeholder="Answer 2">
                        <input name="createanswer3" id="lblAnswer3" placeholder="Answer 3">
                        <input name="createanswer4" id="lblAnswer4" placeholder="Answer 4">
                    </div>
                    <div class="create-question-correct">
                        <input name="createcorrect" id="lblCorrectAnswer" placeholder="Correct Answer">
                    </div>
                    <div class="create-question-submit">
                        <input class="center-block btn btn-raised btn-info btn-questionsubmit" type="submit" name="button-questionsubmit" value="SUBMIT">
                    </div>
                </form>
            </div>

            <div class="member-question-list">
                <?php
                    echo getQuestionsBackend($pdo);
                ?>
            </div>

        <?php
        } else {
            ?>

            <p>You are not allowed to view the contents of this page. Please <a href="login.php">log in</a> to gain access.</p>

            <?php
        }
        ?>
    </div>

<?php

include_once 'modules/footer.php';

?>