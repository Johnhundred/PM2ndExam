<?php

include_once 'includes/inc2.php';

secure_session_start();

$time = time() + 15;
//$result = date(DATE_COOKIE,$time);

echo $time;

?>


