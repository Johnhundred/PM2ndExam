<?php

include_once "includes/db_connect.php";
include_once 'includes/functions.php';

secure_session_start();

if(login_check($pdo) == true){

    //change back to just 'uploads/' if going back to old method
    $uploaddir = 'uploads/';

    $stmt = $pdo->prepare("SELECT ppid FROM members WHERE id = :id");
    $stmt->bindValue(':id', $_SESSION['user_id']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $ppid = $row['ppid'];

    $stmt = $pdo->prepare("SELECT name, original_name, mime_type FROM uploads WHERE id=:id");
    $stmt->bindValue(':id', $ppid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $img = $uploaddir.$row['name'];


//    $newfile = $row['original_name'];
//
//    /* Send headers and file to visitor */
//    header('Content-Description: File Transfer');
//    header('Content-Disposition: attachment; filename='.basename($newfile));
//    header('Expires: 0');
//    header('Cache-Control: must-revalidate');
//    header('Pragma: public');
//    header('Content-Length: ' . filesize($uploaddir.$row['name']));
//    header("Content-Type: " . $row['mime_type']);
//    readfile($uploaddir.$row['name']);
//
//    $handle=fopen($uploaddir.$row['name'],"r");
//    while (!feof($handle)) {
//        @$contents.= fread($handle);
//    }

    //$img = $contents;
}