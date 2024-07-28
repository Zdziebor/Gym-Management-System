<?php

session_start();
require "../urls.php";

$response = $_POST['g-recaptcha-response'];
$recaptcha_secret = "6Le92K0pAAAAAIUS4jXpSgCsnHHJC_2Yhkt1cglr";

$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$response}");
$captcha_success = json_decode($verify);

    //jeśli nieprawidłowa weryfikacja captcha - przekieruj użytkownika do widoku o błędzie
    if ($captcha_success->success == false) {
    
        header("Location: " . ERROR_CAPTCHA);
        exit();
        //jeśli weryfikacja poprawna, kontynuuj
    } else if ($captcha_success->success == true) {


//przypisanie podanego adresu e-mail do zmiennej
$email = $_POST["email"];
$mysqli = require __DIR__ . "/../config.php";


        $sql = "SELECT * FROM user WHERE email = ?";

        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Database error");
        }

        $stmt->bind_param("s", $email);

        $stmt->execute();

        // Przypisanie do zmiennej danych użytkownika o podanym adresie e-mail
        $result = $stmt->get_result();

        //Sprawdzenie czy pod podanym adresem e-mail są już jakieś dane
        //Jeśli tak - wyświetl informację o błędzie
        if ($result->num_rows == 0) {
            $_SESSION["forgot_password_error"] = "Konto z podanym adresem E-mail nie istnieje";
            header("Location: forgot-password.php");
            exit;
        }





$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

//30 minut 
$expiry = date("Y-m-d H:i:s", time() + 60 * 30); 

$mysqli = require __DIR__ . "/../config.php";
//ustaw token resetu hasła i czas ważności
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


    $mail = require __DIR__ . "/../mailer.php";
    //Przygotuj maila z linkiem resetującym hasło
    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

    Click <a href="http://localhost/pracainz/login/reset-password.php?token=$token">here</a> 
    to reset your password.

    END;

    try {

        //Wyślij maila
        $mail->send();
        header("Location: " . EMAIL_SENT );

    } catch (Exception $e) {

        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";

    }

} else {
    echo "Email not found in the database. Please ensure you have entered the correct email address.";

}

    }