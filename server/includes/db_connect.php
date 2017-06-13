<?php

if(LOCAL){
    include_once 'db_access.php';
} else {
    include_once '' . $connUrlVar . 'db_access.php';
}


function dbConnectLog($entry){
    $logFile = realpath(dirname(__FILE__) . "/../..") . "/server/logs/log_" . date("j.n.Y") . ".txt";
    $logEntry = "" . date("j/n/Y H:i:s") . ": " . $entry;

    if(ENCRYPT_LOG == true){
        // SIMPLE encryption of log below. Define key & method in db_access
        $logEntry = openssl_encrypt($logEntry, LOG_METHOD, LOG_KEY);
        $logEntry .= PHP_EOL;
    }

    $logEntry .= PHP_EOL;

    // Decryption can be implemented with the below line:
    //$decrypted = openssl_decrypt($logEntry, LOG_METHOD, LOG_KEY);

    //If the log file already exists, append the entry. If it does not, create the file and append the entry.
    if(file_exists($logFile)){
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    } else {
        fopen($logFile, "w");
        file_put_contents($logFile, $logEntry);
    }
}

// DB connection that all other functions use.
try{
    $pdo = new PDO(PDO_HOST_STRING, USER,PASSWORD, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(Exception $e){
    dbConnectLog("db_connect.php: EXCEPTION: " . $e->getFile() . " (Line: " . $e->getLine() . ") " . $e->getMessage() . "");
    die();
}

?>