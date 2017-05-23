<?php

if(LOCAL){
    include_once 'db_access.php';
} else {
    include_once '' . $connUrlVar . 'db_access.php';
}

try{
    $pdo = new PDO(PDO_HOST_STRING, USER,PASSWORD, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(Exception $e){
    die();
}

?>