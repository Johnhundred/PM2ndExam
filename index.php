<?php

include_once 'server/includes/db_connect.php';
include_once 'server/includes/functions.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';



?>

<div class="center-block logo logo-lg">
    <img src="assets/img/logo.png">
</div>

<button class="btn btn-info btn-play">PLAY</button>

<?php

include_once 'modules/footer.php';

?>