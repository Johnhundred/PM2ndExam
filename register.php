
<?php

include_once 'server/includes/register.inc.php';
include_once 'server/includes/functions.php';

secure_session_start();

include_once 'modules/head.php';
include_once 'modules/navbar.php';

?>



<div class="center-block logo logo-md">
    <img src="assets/img/logo.png">
    <h2>REGISTER</h2>
</div>

<div class="container-fluid">
    <div class="container" id="register-form">

        <?php

        // If you're already a member/logged in, don't display the form.
        if (login_check($pdo) == true) {
            echo "<p>Currently logged in as " . htmlentities($_SESSION["username"]) . ".</p>";
            echo '<p>Do you want to register a new user? <a href="server/logout.php">Log out.</a>.</p>';
        } else {
            ?>


            <p class="reg-error"></p>

            <?php
            if (!empty($error_msg)) {
                echo htmlentities($error_msg);
            }
            ?>

            <!--<h3>!!! PUT ALL OF THIS INTO HOVERABLE HELP BOXES !!!</h3>
            <ul>
                <li>Usernames may contain only digits, upper and lowercase letters and underscores</li>
                <li>Emails must have a valid email format</li>
                <li>Passwords must be at least 6 characters long</li>
                <li>Passwords must contain
                    <ul>
                        <li>At least one uppercase letter (A..Z)</li>
                        <li>At least one lowercase letter (a..z)</li>
                        <li>At least one number (0..9)</li>
                    </ul>
                </li>
                <li>Your password and confirmation must match exactly</li>
            </ul>-->

            <form class="white-box" action="server/includes/register.inc.php" method="post" name="registration_form">
                <!--        <form action="" method="post" name="registration_form">-->

                <div class="form-group form-group-register label-floating">
                    <label class="control-label margin-centerer label-register" for="username">USERNAME</label>
                    <input class="form-control text-center input-register" id="username" type="text" name="username" autocomplete="off">
                    <span class="help-block margin-centerer help-register">Usernames may contain only digits, upper and lowercase letters and underscores</span>
                </div>

                <div class="form-group form-group-register label-floating">
                    <label class="control-label margin-centerer label-register" for="email">EMAIL</label>
                    <input class="form-control text-center input-register" id="email" type="text" name="email" autocomplete="off">
                    <span class="help-block margin-centerer help-register">Emails must have a valid email format</span>
                </div>

                <div class="form-group form-group-register label-floating">
                    <label class="control-label margin-centerer label-register" for="password">PASSWORD</label>
                    <input class="form-control text-center input-register" id="password" type="password" name="password" autocomplete="off">
                    <span class="help-block margin-centerer help-register">At least one uppercase letter (A..Z) / At least one lowercase letter (a..z) / At least one number (0..9)</span>
                </div>

                <div class="form-group form-group-register label-floating">
                    <label class="control-label margin-centerer label-register" for="confirmpwd">CONFIRM PASSWORD</label>
                    <input class="form-control text-center input-register" id="confirmpwd" type="password" name="confirmpwd" autocomplete="off">
                    <span class="help-block margin-centerer help-register">Your password and confirmation must match exactly</span>
                </div>



                <!--Username: <input type='text' name='username' id='username' /><br>
                Email: <input type="text" name="email" id="email" /><br>
                Password: <input type="password" name="password" id="password"/><br>
                Confirm password: <input type="password" name="confirmpwd" id="confirmpwd" /><br>-->
                <input class="center-block margin-centerer btn btn-raised btn-info btn-register" type="button" name="button-register" value="REGISTER">
            </form>

            <!--
            <p>Return to the <a href="index.php">Front page</a>.</p>-->

            <?php
        }
        ?>
    </div>
</div>

<?php

include_once 'modules/footer.php';

?>