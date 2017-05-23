<?php

include_once 'includes/inc2.php';

secure_session_start();

if(login_check($pdo) == true){

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

//
    $newfile = $row['original_name'];
//
    /* Send headers and file to visitor */
    header('Content-Length: ' . filesize($uploaddir.$row['name']));
    header("Content-Type: " . $row['mime_type']);
//    readfile($uploaddir.$row['name']);

    $contents = "";
    $handle=fopen($uploaddir.$row['name'],"r");
    while (!feof($handle)) {
        @$contents.= fread($handle,8192);
    }
    echo $contents;

    //$img = $contents;
}