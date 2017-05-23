<?php

include_once 'db_access.php';

//Catch all errors in custom error function (puts errors in log rather than in browser)
//set_error_handler("customErrorHandler", E_ALL);
//Catch all exceptions in custom exception function (puts errors in log rather than in browser)
//set_exception_handler("customExceptionHandler");
//Report all errors except for warnings - specifically only set if encryption of the log is turned on
if(ENCRYPT_LOG){
    error_reporting(E_ALL ^ E_WARNING);
}

function secure_session_start(){
    $session_name = 'quizgame';
    session_name($session_name);

    // $secure should be set to true, but this will only work if we use an HTTPS connection.
    $secure = false; // Stops JS from being able to access the session ID, but cookie will only be sent over secure (HTTPS) connections. For development purposes, this is set to false. When live, it should be set to true.
    $httpOnly = true; // Forces sessions to only use cookies.

    if(ini_set("session.use_only_cookies", 1) == FALSE){
        header("Location: ../login.php?err=Could not initiate a safe session.");
        exit();
    }

    $oCookieParams = session_get_cookie_params();
    session_set_cookie_params($oCookieParams["lifetime"],
        $oCookieParams["path"],
        $oCookieParams["domain"],
        $secure,
        $httpOnly);

    session_start();
    session_regenerate_id(true);

    $expiry = SESSION_LIFETIME; // Session expiry set to 30 minutes.
    if (isset($_SESSION['LAST']) && is_numeric($_SESSION['LAST']) && (time() - $_SESSION['LAST'] > $expiry)) {
        session_unset();
        session_destroy();
    } else {
        $_SESSION['LAST'] = time();
    }
}

function login($email, $password, $pdo){
    // Using prepared statements to eliminate the possibility of SQL injection.
    if($stmt = $pdo->prepare("SELECT id, username, password FROM members WHERE email=:email LIMIT 1")){
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row){
            //If the user exists (there is returned data in $row), check if the account is locked
            if(check_bruteforce($row["id"], $pdo) == true){
                // The account is "locked" if more than 5 failed attempts to login have been registered in the last 2 hours.
                // No locking is actually done, the login process is simply cut off and login is returned as unsuccessful.
                // Since CAPTCHA is implemented, it can safely be assumed that the user has forgotten their password at this point, and a reset is in order.
                // TO DO: Implement account locking & email reset/unlock function.
                // TO DO: Implement "Reset Password" page/button?
                return false;
            } else {
                // Account is not locked.
                // Check if password matches DB password.
                if(password_verify($password, $row["password"])){
                    // Password matches.
                    // Update session variables.
                    $sUserBrowser = $_SERVER['HTTP_USER_AGENT'];

                    $sUserId = preg_replace("/[^0-9]+/", "", $row["id"]);
                    $_SESSION['user_id'] = $sUserId;

                    $sUsername = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $row["username"]);
                    $_SESSION['username'] = $sUsername;

                    $_SESSION['login_string'] = hash('sha512', $row["password"] . $sUserBrowser);

                    generalLog("functions.php:login: User (" . $_SERVER['REMOTE_ADDR'] . ") logged in.");
                    // Login successful.
                    return true;
                } else {
                    // Password does not match.
                    // Record login attempt in the database.
                    $now = time();

                    $stmt = $pdo->prepare("INSERT INTO login_attempts(user_id, time) VALUES (':id', ':time')");
                    $stmt->bindValue(":id", $row["id"], PDO::PARAM_INT);
                    $stmt->bindValue(":time", $now, PDO::PARAM_STR);
                    $stmt->execute();

                    generalLog("functions.php:login: User (" . $_SERVER['REMOTE_ADDR'] . ") failed to log in, due to mismatched password.");

                    return false;
                }
            }
        } else {
            // No such user exists.
            return false;
        }
    } else {
        // Database connection failed.
        return false;
    }
}

function check_bruteforce($iId, $pdo){
    // Get current time.
    $now = time();

    // Count login attempts from user id within the last 2 hours.
    $timeCheck = $now - (2*60*60);

    if($stmt = $pdo->prepare("SELECT time FROM login_attempts WHERE user_id=:id AND time > '$timeCheck'")) {
        $stmt->bindValue(":id", $iId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If there have been more than LOGIN_ATTEMPTS tries to login.
        if(Count($rows) > LOGIN_ATTEMPTS_ALLOWED){
            // Yes, bruteforce has been going on.
            return true;
        } else {
            // No, bruteforce has not been going on.
            return false;
        }
    }
}

function login_check($pdo){
    // Check if relevant session variables are set.
    if(isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['login_string'])){
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];

        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if($stmt = $pdo->prepare("SELECT password FROM members WHERE id=:id LIMIT 1")) {
            $stmt->bindValue(":id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row){
                // If the user exists, work with its variables.
                $login_check = hash('sha512', $row["password"] . $user_browser);

                // Better to use the hash_equals function, but this requires PHP 5.6.0+
                // In case of an upgrade, use if(hash_equals($login_check, $login_string)){
                if($login_check == $login_string){
                    // You are logged in.
                    generalLog("functions.php:login_check: User (" . $_SERVER['REMOTE_ADDR'] . ") is logged in.");
                    return true;
                } else {
                    // You are not logged in.
                    generalLog("functions.php:login_check: User (" . $_SERVER['REMOTE_ADDR'] . ") not logged in (1).");
                    return false;
                }
            } else {
                // You are not logged in.
                generalLog("functions.php:login_check: User (" . $_SERVER['REMOTE_ADDR'] . ") not logged in (2).");
                return false;
            }
        } else {
            // You are not logged in.
            generalLog("functions.php:login_check: User (" . $_SERVER['REMOTE_ADDR'] . ") not logged in (3).");
            return false;
        }
    } else {
        // You are not logged in.
        generalLog("functions.php:login_check: User (" . $_SERVER['REMOTE_ADDR'] . ") not logged in (4).");
        return false;
    }
}

function admin_check($pdo){
    // Check if relevant session variables are set.
    if(isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['login_string'])){
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];

        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if($stmt = $pdo->prepare("SELECT password, rank FROM members WHERE id=:id LIMIT 1")) {
            $stmt->bindValue(":id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row){
                // If the user exists, work with its variables.
                $login_check = hash('sha512', $row["password"] . $user_browser);

                // Better to use the hash_equals function for the login comparison, but this requires PHP 5.6.0+
                // In case of an upgrade, use if(hash_equals($login_check, $login_string)){
                if($login_check == $login_string & $row["rank"] == 999){
                    // You are logged in as an admin.
                    generalLog("functions.php:admin_check: User (" . $_SERVER['REMOTE_ADDR'] . ") is logged in as an admin, admin content shown.");
                    return true;
                } else {
                    // You are not logged in.
                    //generalLog("functions.php:admin_check: User (" . $_SERVER['REMOTE_ADDR'] . ") not logged in (1).");
                    return false;
                }
            } else {
                // You are not logged in.
                //generalLog("functions.php:login_check: User (" . $_SERVER['REMOTE_ADDR'] . ") not logged in (2).");
                return false;
            }
        } else {
            // You are not logged in.
            //generalLog("functions.php:login_check: User (" . $_SERVER['REMOTE_ADDR'] . ") not logged in (3).");
            return false;
        }
    } else {
        // You are not logged in.
        //generalLog("functions.php:login_check: User (" . $_SERVER['REMOTE_ADDR'] . ") not logged in (4).");
        return false;
    }
}

function gameCheck($pdo, $sId){
    if($stmt = $pdo->prepare("SELECT game_id FROM active_games WHERE game_id=:id LIMIT 1")) {
        $stmt->bindValue(":id", $sId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function generalLog($entry){
    $logFile = realpath(dirname(__FILE__) . "/../..") . "/server/logs/log_" . date("j.n.Y") . ".txt";
    $logEntry = "" . date("j/n/Y H:i:s") . ": " . $entry;

    // TO DO: ENCRYPT LOG ENTRY HERE!
    // Hashing not good enough, as the log needs to be accessible on request.

    if(ENCRYPT_LOG == true){
        // SIMPLE encryption of log below. Define key & method in db_access
        $logEntry = openssl_encrypt($logEntry, LOG_METHOD, LOG_KEY);
        $logEntry .= PHP_EOL;
    }

    $logEntry .= PHP_EOL;

    // Decryption can be implemented with the below line:
    //$decrypted = openssl_decrypt($logEntry, LOG_METHOD, LOG_KEY);

    if(file_exists($logFile)){
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    } else {
        fopen($logFile, "w");
        file_put_contents($logFile, $logEntry);
    }
}

function esc_url($url){
    if ("" == $url) {
        return $url;
    }

    $url = htmlentities($url, ENT_QUOTES);

    if ($url[0] !== "/") {
        // We only care about relative links.
        generalLog("functions.php:esc_url: URL (" . $url . ") stripped.");
        return '';
    } else {
        generalLog("functions.php:esc_url: URL (" . $url . ") was echoed.");
        return $url;
    }
}

function getHubMessages($pdo, $count = 10){

    $stmt = $pdo->prepare("SELECT id, username, time, message, game_id FROM hubchat ORDER BY id DESC");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $iCounter = Count($rows);
    for($i = 0; $i < $iCounter; $i++){
        if($rows[$i]['game_id'] != NULL){
            array_splice($rows, $i, 1);
            $i--;
            $iCounter--;
        }
    }

    $iCounter2 = Count($rows);
    if($iCounter2 > 10){
        array_splice($rows, 10);
    }

    return $rows;
}

function getGameMessages($pdo, $sId){

    $stmt = $pdo->prepare("SELECT id, username, time, message FROM hubchat WHERE game_id = :id LIMIT 20");
    $stmt->bindValue(":id", $sId);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows;
}

function updateGame($pdo, $sId){
    $current = time();
    $current = (string)$current;
    $stmt = $pdo->prepare("UPDATE active_games SET updated = :updated WHERE game_id = :id");
    $stmt->bindValue(":updated", $current);
    $stmt->bindValue(":id", $sId);
    $stmt->execute();
}

function getQuestionsBackend($pdo, $status = 3){
    // Status 1 = active, status 2 = inactive/rejected, status 3 = under review
    $result = "";

    $stmt = $pdo->prepare("SELECT * FROM questions WHERE status = :status ORDER BY id DESC");
    $stmt->bindValue(":status", $status);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sFileUrl = url();
    $pos = strrpos($sFileUrl, "/");
    $sFileUrl = substr($sFileUrl, 0, $pos + 1);
    $sQuestionListTemplate = file_get_contents($sFileUrl . "templates/question_backend.html");
    $sQuestionTemplate = file_get_contents($sFileUrl . "templates/individual_question.html");

    $iCounter = Count($rows);
    for($i = 0; $i < $iCounter; $i++){
        $result .= str_replace("{{title}}", htmlentities($rows[$i]['question']), $sQuestionListTemplate);

        $sListOfQuestions = "";
        $oQuestions = json_decode($rows[$i]['answers']);
        $iCounter2 = Count($oQuestions->answers);
        for($j = 0; $j < $iCounter2; $j++){
            $iTemp = $j + 1;
            $sListOfQuestions .= str_replace("{{question}}", htmlentities($iTemp . ". " . $oQuestions->answers[$j]), $sQuestionTemplate);
        }

        if(admin_check($pdo) == true){
            $sAdminActions = '<div class="question-admin-actions" data-question-id="{{id}}"><i class="material-icons admin-approve">check_circle</i><i class="material-icons admin-reject">highlight_off</i></div>';
            $result = str_replace("{{admin}}", $sAdminActions, $result);
            $result = str_replace("{{id}}", $rows[$i]['id'], $result);
        } else {
            $result = str_replace("{{admin}}", htmlentities(""), $result);
        }

        $result = str_replace("{{list}}", $sListOfQuestions, $result);
    }

    return $result;
}

// Custom error handling
function customErrorHandler($errNo, $errStr, $errFile, $errLine){
    generalLog("functions.php:customErrorHandler: ERROR: [$errNo] [File: $errFile Line: $errLine] $errStr");
}

function customExceptionHandler($exception){
    generalLog("functions.php:customExceptionHandler: EXCEPTION: " . $exception->getMessage() . "");
    echo '<h2 style="color:red;text-align:center;padding:1em;background-color: rgba(0,0,0,0.5);border:1px solid white;">Something went wrong on the server, please try again, and notify an admin if the problem persists.</h2>';
}

function generateUniqueId(){
    $result = uniqid() . uniqid();
    return $result;
}

function url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function newCSRFToken(){
    if(isset($_SESSION['LAST']) && !empty($_SESSION['LAST'])) {
        $token = generateUniqueId();
        $token = hash("sha512", $token);
        $_SESSION['token'] = $token;
        return $token;
    }
}

// Header to prevent iframe

function checkCSRFToken($token){
    if(isset($_SESSION['token']) && !empty($_SESSION['token'])) {
        if($_SESSION['token'] == $token){
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

?>