<?php 
require "../urls.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "C:/xampp/htdocs/pracainz/vendor/autoload.php";

$name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$subject = filter_input(INPUT_POST, 'subject', FILTER_UNSAFE_RAW);
$message = filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW);
$response = $_POST['g-recaptcha-response'];
$recaptcha_secret = "6Le92K0pAAAAAIUS4jXpSgCsnHHJC_2Yhkt1cglr";


//Sprawdzenie czy pola imie, email, temat, i wiadomość nie są puste
if (!$name || !$email || !$subject || !$message) {
    //Jeśli puste - przekieruj do ekranu informującym o błędzie
    header("Location: ". ERROR_SCREEN);
    exit();
}

//sprawdzenie weryfikacji captcha
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$response}");
$captcha_success = json_decode($verify);

if ($captcha_success->success == false) {
    //Przekieruj do ekranu informującego o nieprawidłowej weryfikacji captcha
    header("Location: " . ERROR_CAPTCHA);
    exit();
} else if ($captcha_success->success == true) {
    //Jeśli weryfikacja captcha jest pomyślna - wyślij mail
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;

        $mail->Host = "smtp.gmail.com";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->Username = "zdziebor2001@gmail.com";
        $mail->Password = "vhli **** **** ****";

        $mail->setFrom($email, $name);
        $mail->addAddress("zdziebor2001@gmail.com", "Jakub");
        $mail->addReplyTo($email, $name);

        $mail->Subject = $subject;
        $mail->Body = $message;

        //Wysłanie maila
        $mail->send();

        header("Location: formsubmitted.php");
        exit();
    } catch (Exception $e) {
        header("Location: ". ERROR_SCREEN);
        exit();
    }
}
