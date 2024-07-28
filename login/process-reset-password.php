<?php

session_start();
require "../urls.php";

    //przypisujemy do zmiennych pobrane dane za pomocą POST
    $token = $_POST["token"];
    $password = $_POST["password"];
    $password_confirmation = $_POST["password-confirmation"];

    //Sprawdzamy czy hasło ma mniej niż 8 znaków
    if (strlen($password) < 8) {
        $_SESSION["password_error_length"] = "Hasło musi zawierać przynajmniej 8 znaków";
    }
    
     //Sprawdzamy czy hasło zawiera przynjemniej 1 cyfrę
    if (!preg_match("/[0-9]/", $password)) {
                $_SESSION["password_error_number"] = "Hasło musi zawierać przynajmniej 1 cyfrę";

    }
     //Sprawdzamy czy hasła są takie same
    if ($password !== $password_confirmation) {
        $_SESSION["password_error_match"] = "Hasła nie są takie same";
        
    }
     //Jeśli podane hasło jest nieprawidłowe, przekierowujemy do ekranu zmiany hasła
    if (!empty( $_SESSION["password_error_match"]) || !empty( $_SESSION["password_error_length"]) || !empty( $_SESSION["password_error_number"])){
        header("Location: reset-password.php?token=" . $token );
        exit;
    }

//sprawdzamy token resetu hasła czy jest poprawny i aktualny
$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/../config.php";

$sql = "SELECT * FROM user
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found or expired");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired");
}




$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

//aktualizacja hasła
$sql = "UPDATE user
        SET password_hash = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $password_hash, $user["id"]);

$stmt->execute();

?>


<!DOCTYPE html>
<html>
<head>
    <title>Reset hasła</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/login/signup-success.css">
</head>
<body>
<?php require "../html-elements/header.php"?>
<div class="content">

<div class="image-2">
        <img src="../assets/payment/paypalsuccess.png">
</div>
<h1 class = text-1>Hasło zostało zresetowane</h1>
<br>
<a class = "login-button" href="http://localhost/pracainz/login/login.php ">Powrót</a>

</div>
    
    <?php require "../html-elements/footer.php"?>

</body>
</html>