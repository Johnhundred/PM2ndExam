<?php

include_once 'server/includes/inc.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';

?>

    <div class="center-block logo logo-md">
        <img src="assets/img/logo.png">
        <h2>Suggest Questions</h2>
    </div>

    <div class="container white-box member-box questions-container-back">
        <?php

        if (login_check($pdo) == true) {
        ?>

            <div class="member-question-submit">


                <form action="server/question_submit.php" method="post" name="questionsubmit">
                    <!--<div class="create-question-text">
                        <input name="createquestion" id="lblQuestion" placeholder="Question">
                    </div>-->



                    <div class="form-group form-group-register label-floating create-question-text">
                        <label class="control-label margin-centerer label-register" for="lblQuestion">Add Question</label>
                        <input class="form-control text-center input-register help-life" id="lblQuestion" type="text" name="createquestion" autocomplete="off">
                        <span class="help-block margin-centerer help-register">Suggest a new Question!</span>
                    </div>

                    <div class="create-question-answers">
                        <input name="createanswer1" id="lblAnswer1" placeholder="Answer 1">
                        <input name="createanswer2" id="lblAnswer2" placeholder="Answer 2">
                        <input name="createanswer3" id="lblAnswer3" placeholder="Answer 3">
                        <input name="createanswer4" id="lblAnswer4" placeholder="Answer 4">
                    </div>

                    <!--<div class="form-group form-group-register label-floating create-question-correct">
                        <label class="control-label margin-centerer label-register" for="lblCorrectAnswer">CORRECT ANSWER TEST</label>
                        <input type="number" name="createcorrect" id="lblCorrectAnswer" placeholder="Correct Answer">
                    </div>-->

                    <div class="form-group form-group-register label-floating create-question-correct">
                        <label class="control-label margin-centerer label-register" for="lblCorrectAnswer">CORRECT ANSWER</label>
                        <input class="form-control text-center input-register help-life" id="lblCorrectAnswer" type="number" name="createcorrect" autocomplete="off">
                        <span class="help-block margin-centerer help-register">Write the number of the correct question</span>
                    </div>

                    <input type="hidden" name="token" value="<?php echo htmlentities(newCSRFToken()); ?>">

                    <div class="create-question-submit margin-centerer">
                        <input class="center-block btn btn-raised btn-info btn-questionsubmit" type="submit" name="button-questionsubmit" value="SUBMIT">
                    </div>
                </form>
            </div>

            <div class="member-question-list">

                <h2 class="subited-title">Submitted Questions</h2>

                <?php
                    // Get questions (formatted backend) and display them.
                    echo getQuestionsBackend($pdo);
                ?>
            </div>

        <?php
        } else {
            ?>

            <p class="login-error">You are not allowed to view the contents of this page. Please <a href="login.php">log in</a> to gain access.</p>

            <?php
        }
        ?>
    </div>

<?php

include_once 'modules/footer.php';

?>