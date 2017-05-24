<?php

include_once 'server/includes/inc.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';

?>

    <div class="center-block logo logo-md">
        <img src="assets/img/logo.png">
        <h2>LOG</h2>
    </div>

    <div class="container white-box member-box questions-container-back">
        <?php
        if(admin_check($pdo) == true){
            if(isset($_GET['file']) && !empty($_GET['file'])){

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $file = "server/logs/" . filter_var($_GET['file'], FILTER_SANITIZE_STRING);

                $check = finfo_file($finfo, $file);
                $get2 = (string)filter_var($_GET['file'], FILTER_SANITIZE_STRING);
                $count = 0;

                $get2 = preg_replace('/\.\.\//', '', $get2, -1, $count);
                $get2 = "server/logs/" . $get2;
                finfo_close($finfo);

                if(strpos((string)$check, 'plain') !== false && $count < 1){
                    $handle=fopen($get2,"r");
                    while (!feof($handle) && ($line = fgets($handle)) !== false) {
                        if(ENCRYPT_LOG == true){
                            $decrypted = openssl_decrypt($line, LOG_METHOD, LOG_KEY);
                            echo '<p>'.htmlentities($decrypted).'</p>';
                        } else {
                            echo '<p>'.htmlentities($line).'</p>';
                        }
                    }
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