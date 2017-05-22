<?php

include_once "includes/db_connect.php";
include_once 'includes/functions.php';

secure_session_start();

$time = time() + 15;
//$result = date(DATE_COOKIE,$time);

echo $time;

?>


