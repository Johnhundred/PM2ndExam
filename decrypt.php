<?php

include_once 'server/includes/inc.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';

?>

    <div class="center-block logo logo-md">
        <img src="assets/img/logo.png">
        <h2>Log List</h2>
    </div>

    <div class="container white-box member-box questions-container-back">
        <?php
        if(admin_check($pdo) == true){

            $dir = opendir ("server/logs/");
            while (false !== ($file = readdir($dir))) {
                if (strpos($file, '.txt',1)) {
                    echo "<a href='showlog.php?file=".htmlentities($file)."'>" . htmlentities($file) . "</a> <br />";
                }
            }

        } else {
            echo '<p>No content.</p>';
        }
        ?>

    </div>

<?php

include_once 'modules/footer.php';

?>