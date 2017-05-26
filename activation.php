<?php

include_once 'server/includes/inc.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';

?>

    <div class="center-block logo logo-md">
        <img src="assets/img/logo.png">
        <h2>MEMBER ACTIVATION</h2>
    </div>

    <div class="container white-box member-box questions-container-back">
        <?php
        if(login_check($pdo) == true){
            echo '<p>You are already an active user of this site. If you wish to activate a new account, please log out first.</p>';
        } else {
            // Get activation code from URL, hash it, compare it to activation codes in the DB, if a match is found, set that user's status to active
            if (isset($_GET['a']) && !empty($_GET['a'])){
                if (filter_var($_GET['a'], FILTER_SANITIZE_STRING)) {
                    $a = hash("sha512", $_GET['a']);

                    $stmt = $pdo->prepare("SELECT id, activation_code FROM members");
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $match = false;
                    $iCounter = Count($rows);
                    for($i = 0; $i < $iCounter; $i++){
                        if($rows[$i]['activation_code'] == $a){
                            // Found a match
                            $stmt = $pdo->prepare("UPDATE members SET activation_code = NULL, activation_status = 1 WHERE id = :id");
                            $stmt->bindValue(":id", $rows[$i]['id']);
                            $stmt->execute();
                            $match = true;
                            echo '<p>Activation successful. You may now <a href="login.php">log in</a>.</p>';
                        }
                    }

                    if(!$match){
                        echo '<p>Invalid activation code.</p>';
                    }
                } else {
                    echo '<p>Invalid activation code.</p>';
                }
            } else {

                echo '<p>You do not have an activation code. If you are waiting for an activation code, please wait up to 15 minutes and check your email again.</p>';

            }

        }
        ?>

    </div>

<?php

include_once 'modules/footer.php';

?>