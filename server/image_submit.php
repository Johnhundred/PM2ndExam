<?php

include_once "includes/db_connect.php";
include_once 'includes/functions.php';

secure_session_start();

if(login_check($pdo) == true && checkCSRFToken($_POST['token']) && !empty($_POST['button-imagesubmit']) && !empty($_FILES['image']) && $_FILES['image']['error'] == 0){
    $uploaddir = 'uploads/';

    /* Generates random filename and extension */
    function tempnam_sfx($path, $suffix){
        do {
            $file = $path."/".generateUniqueId().mt_rand().generateUniqueId().$suffix;
            $fp = @fopen($file, 'x');
        }
        while(!$fp);

        fclose($fp);
        return $file;
    }

    $verifyimg = getimagesize($_FILES['image']['tmp_name']);
    var_dump($verifyimg['mime']);

    if($verifyimg['mime'] == 'image/png' || $verifyimg['mime'] == 'image/jpg' || $verifyimg['mime'] == 'image/jpeg') {

        /* Rename both the image and the extension */
        //$uploadfile = tempnam_sfx($uploaddir, ".tmp");

        // change $_FILES['image'] to $uploadfile if renaming can be fixed
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploaddir . $_FILES['image']['name'])) {
            try{
                $stmt = $pdo->prepare("INSERT INTO uploads (name, original_name, mime_type) VALUES (:name, :oriname, :mime)");
                // change $_FILES['image']['name'] to $uploadfile in :name if renaming can be fixed
                $stmt->bindValue(':name', basename($_FILES['image']['name']));
                $stmt->bindValue(':oriname', basename($_FILES['image']['name']));
                $stmt->bindValue(':mime', $_FILES['image']['type']);
                $stmt->execute();
                generalLog("image_submit.php: User (" . $_SERVER['REMOTE_ADDR'] . ") submitted a profile picture.");
            } catch(PDOException $e) {
                unlink($uploadfile);
                generalLog("image_submit.php: ERROR: User: " . $_SERVER['REMOTE_ADDR'] . ". Exception: " . $e->getMessage());
            }

            $ppid = "";

            try{
                $stmt = $pdo->prepare("SELECT id FROM uploads WHERE name = :name");
                // change $_FILES['image']['name'] to $uploadfile in :name if renaming can be fixed
                $stmt->bindValue(':name', basename($_FILES['image']['name']));
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $ppid = $row['id'];
            } catch(PDOException $e) {
                generalLog("image_submit.php: ERROR: User: " . $_SERVER['REMOTE_ADDR'] . ". Exception: " . $e->getMessage());
            }

            try{
                $stmt = $pdo->prepare("UPDATE members SET ppid = :ppid WHERE id = :id");
                $stmt->bindValue(':ppid', $ppid);
                $stmt->bindValue(':id', $_SESSION['user_id']);
                $stmt->execute();
            } catch(PDOException $e) {
                generalLog("image_submit.php: ERROR: User: " . $_SERVER['REMOTE_ADDR'] . ". Exception: " . $e->getMessage());
            }
        } else {
            generalLog("image_submit.php: ERROR: User (" . $_SERVER['REMOTE_ADDR'] . ") failed to submit a profile picture because the server could not move it. !INVESTIGATE!");
        }

        // Upon successful submission, return user to profile page
        header("Location: ../profile.php");
    } else {
        generalLog("image_submit.php: User (" . $_SERVER['REMOTE_ADDR'] . ") tried to submit an image with an unaccepted MIME type.");
        header("Location: ../login.php");
    }

} else {
    // Attempted use without being logged in, or with a mismatched token (CSRF attack)
    header("Location: ../login.php");
}

?>


