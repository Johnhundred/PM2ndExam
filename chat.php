<?php

include_once 'server/includes/inc.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';

?>

    <div class="center-block logo logo-md">
        <img src="assets/img/logo.png">
        <h2>MEMBER CHAT</h2>
    </div>

    <div class="container white-box member-box">
        <?php
        // Get an array of messages, format each message and put the final string into HTML.
        if (login_check($pdo) == true) {
            ?>

            <div class="member-msg-list">
                <?php
                $msg = getHubMessages($pdo);
                $output = "";
                foreach($msg as $post){
                    $output .= "<div class='member-msg container'><p><span class='member-msg-time'>" . htmlentities($post['time']) . "</span>";
                    $output .= "<span class='member-msg-username'> " . htmlentities($post['username']) . ":</span>";
                    $output .= "<span class='member-msg-msg'> " . htmlentities($post['message']) . "</span>";
                    if(admin_check($pdo) == true){
                        $output .= " --<span data-msg-id='". htmlentities($post['id']) ."'>ADMIN: Delete this message?</span>";
                    }
                    $output .= "</p></div>";
                }
                echo $output;
                ?>
            </div>

            <div class="member-msg-submit">
                <form action="server/hubchatsubmit.php" method="post" name="hubchatsubmit">
                    <textarea name='hubchatmsg' id='hubchatmsg'></textarea><br>
                    <input type="hidden" name="token" value="<?php echo htmlentities(newCSRFToken()); ?>">
                    <input class="center-block btn btn-raised btn-info btn-hubchatsubmit" type="submit" name="button-hubchatsubmit" value="SUBMIT">
                </form>
            </div>

            <?php
        } else {
            ?>

            <p class="login-error">You are not allowed to view the contents of this page. Please <a href="index.php">log in</a> to gain access.</p>

            <?php
        }
        ?>
    </div>

<?php

include_once 'modules/footer.php';

?>