<?php

include_once 'server/includes/db_connect.php';
include_once 'server/includes/functions.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';

?>

<div class="center-block logo logo-md">
    <img src="assets/img/logo.png">
    <h2>LOGIN</h2>
</div>

<div class="container login-box">
    <?php
    if (isset($_GET['error'])) {
        echo '<p class="error">Error Logging In!</p>';
    }

    if (login_check($pdo) == true) {
        echo '<div class="container white-box">';
        echo "<p>Currently logged in as " . htmlentities($_SESSION["username"]) . ". Go to the member <a href='hub.php'>hub</a>.</p>";
        echo '<p>Do you want to change user? <a href="server/logout.php">Log out.</a>.</p>';
        echo "</div>";
    } else {
    ?>

    <form class="white-box col-md-12" action="server/process_login.php" method="post" name="login-form">

       
        <div class="form-group form-group-login label-floating">
            <label class="control-label margin-centerer label-login" for="username-input">EMAIL</label>
            <input class="form-control text-center input-login" id="username-input" type="text" name="email" autocomplete="off">
            <span class="help-block margin-centerer help-login">Please enter a valid email</span>
        </div>

        <div class="form-group form-group-login label-floating">
            <label class="control-label margin-centerer label-login" for="pass-input">PASSWORD</label>
            <input class="text-center form-control input-login" id="pass-input" type="password" name="password" autocomplete="off">
            <span class="help-block margin-centerer help-login">Please enter a valid password</span>
        </div>


        <div class="captcha-wrap text-center">
            <div class="g-recaptcha" data-sitekey="6LfqYx0UAAAAAMD4r0uQ9vLOtCwS-lW_LHQ992MT"></div>
        </div>

        <input type="hidden" name="token" value="<?php echo htmlentities(newCSRFToken()); ?>">

        <input class="center-block btn btn-raised btn-info btn-login" type="button" name="button-login" value="LOGIN">

    </form>
    <?php
        }
    ?>
</div>

<?php

include_once 'modules/footer.php';

?>