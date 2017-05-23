<?php

if(LOCAL){
    include_once 'db_connect.php';
    include_once 'functions.php';
} else {
    include_once '' . $connUrlVar . 'db_connect.php';
    include_once '' . $connUrlVar . 'functions.php';
}

$sErrorMsg = "";
$bError = FALSE;

// ALERT: As long as we do not use HTTPS, even though the password is hashed, it can still be stolen and used with a man-in-the-middle attack. We SHOULD use HTTPS!

if(isset($_POST["username"], $_POST["email"], $_POST["p"])){
    if(LOCAL){
        include_once 'reg.php';
    } else {
        include_once '' . $connUrlVar . 'reg.php';
    }
}

?>