<?php

include_once 'server/includes/inc.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';

?>

<div class="center-block logo logo-lg">
    <img src="assets/img/logo.png">
</div>

<button class="btn btn-info btn-play" data-toggle="modal" data-target="#myModal">PLAY</button>

<div class="modal game-screen-1" id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Create or join a room!</h4>
            </div>
            <div class="modal-body">
                <div class="lbl-create-game">
                    <button class="btn btn-raised">Create Game</button>
                </div>
                <div class="lbl-join-game">
                    <form>
                        <input type="hidden" name="token" value="<?php echo htmlentities(newCSRFToken()); ?>">
                        <input type="text" placeholder="Game ID">
                        <input class="btn btn-raised" type="submit" value="Join Game">
                    </form>
                </div>
            </div><!--
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>-->
        </div>
    </div>
</div>

<?php

//include_once '/var/www/test.php';

include_once 'modules/footer.php';

?>