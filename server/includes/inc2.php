<?php

$connUrlVar = '';
define("LOCAL", true);

if(LOCAL){
    $connUrlVar = 'includes/';
} else if(!LOCAL){
    $connUrlVar = '/var/www/includes/';
}

include_once '' . $connUrlVar . 'db_connect.php';
include_once '' . $connUrlVar . 'functions.php';

?>