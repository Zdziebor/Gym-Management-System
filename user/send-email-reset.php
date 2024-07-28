<?php
require "../urls.php";


session_start();
require "checkUserLogin.php";

//walidacja wpisanego maila
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

//Jeśli nieprawidłowy e-mail przypisz error i przekieruj do ekranu zmiany maila
if ($email == false ){
    $_SESSION["email_reset_invalid"] = "Nieprawidłowy adres E-mail";
    header("Location: change-email.php");
    exit;
}

$mysqli = require __DIR__ . "/../config.php";

        //zapytanie o uzytkownika o podanym adresie email
        $sql = "SELECT * FROM user WHERE email = ?";

        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Database error");
        }

        $stmt->bind_param("s", $email);

        //wykonaj zapytanie
        $stmt->execute();

        //przypisz wyniki zapytania do $result
        $result = $stmt->get_result();

        //Jesli istnieja wyniki zapytania - podany adres istnieje - przypisz error i przekieruj do ekranu zmainy maila
        if ($result->num_rows > 0) {
            $_SESSION["email_reset_error"] = "Podany adres E-mail jest już zajęty.";
            header("Location: change-email.php");
            exit;
        }



//Generowanie losowego tokenu zmainy maila
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$mysqli = __DIR__ . "/../config.php";

if (isset($_SESSION["user_id"])) {

    $mysqli = require __DIR__ . "/../config.php";

    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    if ($email === null) {
        //http_response_code(400);
        echo "Invalid email address";
        exit;
    }

    
} else {
    // User is not logged in, handle the error or redirect to login page
    //http_response_code(403);
    echo "Unauthorized access";
    exit;
}


$sql = "UPDATE user
        SET reset_mail_token_hash = ?,
            reset_mail_token_expires_at = ?
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    // Handle the error if the statement preparation fails
    //http_response_code(500);
    echo "Database error";
    exit;
}

$stmt->bind_param("ssi", $token_hash, $expiry, $user["id"]);

$stmt->execute();

if ($mysqli->affected_rows) {

    //przygotuj maila do wysłania
    $mail = require __DIR__ . "/../mailer.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Email Reset";
    $mail->Body = <<<END

    Click <a href="http://localhost/pracainz/user/reset-email.php?token=$token&email=$email">here</a> 
    to reset your email.

    END;

    try {
        //wysłanie maila
        $mail->send();
        $email = null;
       header("Location: " . EMAIL_SENT );


   } catch (Exception $e) {

        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
} else {
    echo "Failed to update user";
}

