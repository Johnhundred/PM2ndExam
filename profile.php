<?php

include_once 'server/includes/db_connect.php';
include_once 'server/includes/functions.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';

?>

    <div class="center-block logo logo-md">
        <img src="assets/img/logo.png">
        <h2>PROFILE</h2>
    </div>

    <div class="container white-box member-box questions-container-back">
        <?php

        if (login_check($pdo) == true) {
            $img = "";
            include_once 'server/show_image.php';
            ?>

            <div class="profile-image-display">
                <h5>Your profile image:</h5>
                <img src="server/<?php echo $img; ?>">
            </div>

            <div class="profile-image-form">
                <form action="server/image_submit.php" method="post" name="imagesubmit" enctype="multipart/form-data">
                    <div class="image-picker">
                        <label>Select image to upload (.png, .jpg, .jpeg):</label>
                        <input name="image" id="lblimage" type="file">
                    </div>
                    <input type="hidden" name="token" value="<?php echo htmlentities(newCSRFToken()); ?>">
                    <div class="image-submit">
                        <input class="center-block btn btn-raised btn-info btn-imagesubmit" type="submit" name="button-imagesubmit" value="SUBMIT">
                    </div>
                </form>
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