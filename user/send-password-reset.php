<?php
require "../urls.php";

session_start();
require "checkUserLogin.php";



if (!isset($_SESSION["user_id"])) {
    echo "Unauthorized access";
    exit;
} else {

    if (isset($_SESSION["user_id"])) {
        $mysqli = require __DIR__ . "/../config.php";
    
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
    
        if (!$stmt) {
            die("Database error");
        }
    
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        $user = $result->fetch_assoc();
    }


$email = $user["email"];

//stworz token resetu hasła
$token = bin2hex(random_bytes(16));

//hash tokenu
$token_hash = hash("sha256", $token);
//ustaw ważność tokenu
$expiry = date("Y-m-d H:i:s", time() + 60 * 30); 

$mysqli = require __DIR__ . "/../config.php";

//wstaw token i ważnośc tokenu do bazy danych
$sql = "UPDATE user
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Failed to prepare statement: " . $mysqli->error);
}

$stmt->bind_param("sss", $token_hash, $expiry, $email);

$stmt->execute();

if ($mysqli->affected_rows) {

    //przygotuj maila
    $mail = require __DIR__ . "/../mailer.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

    Click <a href="http://localhost/pracainz/login/reset-password.php?token=$token">here</a> 
    to reset your password.

    END;

    try {
        //wyslij maila
        $mail->send();
        header("Location: " . EMAIL_SENT );



    } catch (Exception $e) {

        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }

} else {
    echo "Email not found in the database. Please ensure you have entered the correct email address.";

}

var_dump($email);
}