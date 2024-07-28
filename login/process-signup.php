<?php

require "../urls.php";


$response = $_POST['g-recaptcha-response'];
$recaptcha_secret = "6Le92K0pAAAAAIUS4jXpSgCsnHHJC_2Yhkt1cglr";

$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$response}");
$captcha_success = json_decode($verify);

//Błąd weryfikacji captcha - przekierowanie do odpowiedniego ekranu błędu
if ($captcha_success->success == false) {
    header("Location: " . ERROR_CAPTCHA);
    exit();
} else if ($captcha_success->success == true) {

    //sprawdzenie czy imię jest puste
    if (empty($_POST["name"])) {
        die("Name is required");
    }
    //sprawdzenie czy e-mail jest poprawny
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        die("Valid email is required");
    }

    //sprawdzenie czy hasło ma przynajmniej 8 znaków
    if (strlen($_POST["password"]) < 8) {
        die("Password must be at least 8 characters");
    }

    //sprawdzenie czy hasło ma przynajmniej 1 cyfrę
    if (!preg_match("/[0-9]/", $_POST["password"])) {
        die("Password must contain at least one number");
    }

    //sprawdzenie czy hasła są takie same
    if ($_POST["password"] !== $_POST["password_confirmation"]) {
        die("Passwords must match");
    }
    //sprawdzenie czy numer telefonu ma przynajmniej 9 znaków i zawiera tylko cyfry
    if (empty($_POST["phone_number"]) || !preg_match("/^[0-9]{9,}$/", $_POST["phone_number"])) {
        die("Phone number must be at least 9 numbers long and contain numbers only");
    }

    //sprawdzenie czy numer PESEL ma 11 znaków i zawiera tylko cyfry
    if (empty($_POST["PESEL"]) || !preg_match("/^[0-9]{11}$/", $_POST["PESEL"])) {
        die("PESEL must be exactly 11 numbers long and contain numbers only");
    }
    //sprawdzenie czy miasto jest wprowadzone
    if (empty($_POST["city"])) {
        die("City is required");
    }

    //sprawdzenie czy adres jest wprowadzony
    if (empty($_POST["street"])) {
        die("Street is required");
    }
    //sprawdzenie czy kod pocztowy jest wprowadzony
    if (empty($_POST["zip_code"])) {
        die("Zip code is required");
    }

    //hashowanie hasła
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    //tworzenie tokenu aktywacji
    $activation_token = bin2hex(random_bytes(16));

    //hashowanie tokenu aktywacji
    $activation_token_hash = hash("sha256", $activation_token);


    $mysqli = require __DIR__ . "/../config.php";

    //wprowadzenie danych do bazy
    $sql = "INSERT INTO user (name, surname, phone_number, PESEL, city, street, zip_code, email, password_hash, account_activation_hash)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->stmt_init();

    if (!$stmt->prepare($sql)) {
        die("SQL error: " . $mysqli->error);
    }

    $stmt->bind_param(
        "ssssssssss",
        $_POST["name"],
        $_POST["surname"],
        $_POST["phone_number"],
        $_POST["PESEL"],
        $_POST["city"],
        $_POST["street"],

        $_POST["zip_code"],
        $_POST["email"],
        $password_hash,
        $activation_token_hash
    );

    try {
        if ($stmt->execute()) {
            $mail = require __DIR__ . "/../mailer.php";

            //przygotowanie wiadomości e-mail z linkiem aktywacyjnym                 
            $mail->setFrom("noreply@example.com");
            $mail->addAddress($_POST["email"]);
            $mail->Subject = "Account Activation";
            $mail->Body = <<<END

    Click <a href="http://localhost/pracainz/login/activate-account.php?token=$activation_token">here</a> 
    to activate your account.

    END;

            try {
                //wysłanie wiadomości na podany przy rejestracji adres e-mail
                $mail->send();
            } catch (Exception $e) {

                echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
                exit;
            }

            header("Location: signup-success.php");
            exit;
        } else {
            die("Error: Unable to execute the query");
        }
    } catch (mysqli_sql_exception $exception) {
        if ($exception->getCode() === 1062) {
            die("Email already taken");
        } else {
            die($exception->getMessage());
        }
    }
}
