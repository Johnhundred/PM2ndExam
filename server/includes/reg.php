<?php
// Receives username, email, password from frontend.
// Sanitize username, sanitize email, validate that it is an email.
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$email = filter_var($email, FILTER_VALIDATE_EMAIL);
// If email is not formatted as an email, there is an error.
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Not a valid email
    $sErrorMsg .= '<p class="error">The email address you entered is not valid.</p>';
    $bError = TRUE;
    generalLog("ERROR: register.inc.php: User (" . $_SERVER["REMOTE_ADDR"] . ") attempted to register with an invalid email.");
}

// Sanitize the password. If the password is not 128 characters long, it has been tampered with. We set it to 128 characters long frontend.0
$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
if (strlen($password) != 128) {
    // The hashed pwd should be 128 characters from frontend hashing. If it is not, something weird has happened, and it can't be used.
    $sErrorMsg .= '<p class="error">Invalid password configuration.</p>';
    $bError = TRUE;
    generalLog("!ALERT! ERROR: register.inc.php: User (" . $_SERVER["REMOTE_ADDR"] . ") attempted to register. Invalid password configuration (Not length 128) error. Check that frontend validation is functional. Possible breach attempt.");
}

// Username and password validity are also checked frontend. Nobody gains advantage from breaking those rules, and they are therefore considered an adequate check, even if they can be circumvented if one REALLY wanted to. Because of this, we only check if username/email is already in use.

// Check if email is already in use.
if($stmt = $pdo->prepare("SELECT id FROM members WHERE email=:email LIMIT 1")) {
    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row){
        // If a user with this email already exists.
        $sErrorMsg .= '<p class="error">A user with this email address already exists.</p>';
        $bError = TRUE;
        generalLog("ERROR: register.inc.php: User (" . $_SERVER["REMOTE_ADDR"] . ") attempted to register with an email address already in use.");
    }
}

// Check if username is already in use.
if($stmt = $pdo->prepare("SELECT id FROM members WHERE username=:username LIMIT 1")) {
    $stmt->bindValue(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row){
        // If a user with this username already exists.
        $sErrorMsg .= '<p class="error">A user with this username already exists.</p>';
        $bError = TRUE;
        generalLog("ERROR: register.inc.php: User (" . $_SERVER["REMOTE_ADDR"] . ") attempted to register with a username already in use.");
    }
}

if (!$bError) {
    // No errors encountered during registration information validation.
    // We create a hashed password with the password_hash function. password_hash salts the password with a random salt and can be verified with the password_verify function.
    $password = password_hash($password, PASSWORD_BCRYPT);
    $rString = random_str(50);
    $act_code = hash("sha512", $rString);

    // Insert the new user into the database
    if($stmt = $pdo->prepare("INSERT INTO members (username, email, password, activation_code) VALUES (:username, :email, :password, :ac_code)")) {
        $stmt->bindValue(":username", $username, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password);
        $stmt->bindValue(":ac_code", $act_code);
        $stmt->execute();

        // Send registration email
        $beginUrl = url();
        $url = str_replace("server/includes/register.inc.php", "activation.php?a=" . $rString, $beginUrl);
        $to = $email;
        $subject = "SmT Account Activation";
        $message = "Please follow the link below to activate your newly created account. If you did not sign up for our site, please ignore this email. ".$url;

        if(mail($to, $subject, $message)){
            generalLog("reg.php: User (" . $_SERVER['REMOTE_ADDR'] . ") sent an activation email to ".$to.".");
        } else {
            generalLog("test.php: User (" . $_SERVER['REMOTE_ADDR'] . ") failed to send an activation email.");
        }
    }
    header('Location: ../../login.php');
}
?>