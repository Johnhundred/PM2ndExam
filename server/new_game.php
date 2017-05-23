<?php

include_once 'includes/inc2.php';

secure_session_start();

$sData = $_POST['data'];
$jData = json_encode($sData);
$jData = json_decode($jData);

if(checkCSRFToken($jData->token)){
    $time = time() + 15;
//$result = date(DATE_COOKIE,$time);

    echo $time;
}

?>


