<?php

include_once 'server/includes/inc.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';

?>

    <div class="center-block logo logo-md">
        <img src="assets/img/logo.png">
        <h2>So Much Trivia</h2>
    </div>

    <div class="container white-box member-box game-container">
        <?php

        if (login_check($pdo) == true) {

            if(gameCheck($pdo, $_GET['id']) == true){
                $sId = "";
                if($stmt = $pdo->prepare("SELECT game_id, created, updated, history FROM active_games WHERE game_id=:id LIMIT 1")) {
                    $stmt->bindValue(":id", $_GET['id']);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($row) {
                        $sId = $row['game_id'];
                        updateGame($pdo, $sId);
                    }
                }


                ?>

                <div class="col-md-4 game-info" data-game-id="<?php echo htmlentities($sId); ?>">
                    <div class="game-users col-md-12">
                        <div class="game-user-list">
                        </div>
                    </div>
                    <div class="game-buttons col-md-12">
                        <button class="btn btn-raised new-game">New Game</button>
                        <button class="btn btn-raised close-room">Close Room</button>
                    </div>
                </div>

                <div class="game-chat col-md-8">
                    <div class="game-msg-list">
                        <?php
                        $msg = getGameMessages($pdo, $sId);
                        $output = "";
                        foreach($msg as $post){
                            $output .= "<div class='game-msg container'><p><span class='game-msg-time'>" . htmlentities($post['time']) . "</span>";
                            $output .= "<span class='game-msg-username'> " . htmlentities($post['username']) . ":</span>";
                            $output .= "<span class='game-msg-msg'> " . htmlentities($post['message']) . "</span>";
                            if(admin_check($pdo) == true){
                                $output .= " --<span data-msg-id='". htmlentities($post['id']) ."'>ADMIN: Delete this message?</span>";
                            }
                            $output .= "</p></div>";
                        }
                        if($output == ""){
                            $output = "<h5>No messages.</h5>";
                        }
                        echo $output;
                        ?>
                    </div>

                    <div class="game-msg-submit">
                        <form action="server/game_chat_submit.php" method="post" name="game_chat_submit">
                            <textarea name='game_chat_msg' id='game_chat_msg'></textarea>
                            <input type="hidden" name="game_chat_id" value="<?php echo htmlentities($sId); ?>">
                            <input type="hidden" name="token" value="<?php echo htmlentities(newCSRFToken()); ?>">
                            <input class="center-block btn btn-raised btn-info btn-hubchatsubmit" type="submit" name="button-game_chat_msg" value="SUBMIT">
                        </form>
                    </div>
                </div>

                <?php
            } else {
                ?>

                <p>Game not found. Please return to the <a href="index.php">front page</a> to create a new game, or join an existing game.</p>

                <?php
            }
        } else {
            ?>

            <p>You are not allowed to view the contents of this page. Please <a href="login.php">log in</a> to gain access.</p>

            <?php
        }
        ?>
    </div>

<div id="wdw-game-modal" class="container">
    <div class="game-container">
        <div class="active-game">
            <p>wat</p>
            <i class="material-icons quitgame">clear</i>
        </div>
    </div>
</div>

<?php

include_once 'modules/footer.php';

?>


