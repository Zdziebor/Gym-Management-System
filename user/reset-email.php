<?php

require "../urls.php";
session_start();

//pobrania i walidacja tokenu i adres email
$token = filter_input(INPUT_GET, 'token', FILTER_UNSAFE_RAW);
$email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);

if (!$token || !$email) {
    die("Invalid token or email");
}

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/../config.php";

//pobierz dane uzytkownika o przypisanym tokenie resetu mail
$sql = "SELECT * FROM user
        WHERE reset_mail_token_hash = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Database error");
}

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

//pobierz dane
$user = $result->fetch_assoc();

//sprawdz czy token jest istnieje
if ($user === null) {
    die("token not found");
}

//sprawdz czy token jest wazny
if (strtotime($user["reset_mail_token_expires_at"]) <= time()) {
    die("token has expired");
}


//query aktualizujace maila 
$sql = "UPDATE user
        SET email = ?,
            reset_mail_token_hash = NULL,
            reset_mail_token_expires_at = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Database error");
}

$stmt->bind_param("ss", $email, $user["id"]);

try {

//wykonanie query
$stmt->execute();

} catch (mysqli_sql_exception $e){
 echo "E-mail already taken";
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Zmiana Maila</title>
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
<h1 class = text-1>Adres E-mail został zmieniony</h1>
<br>
<a class = "login-button" href="http://localhost/pracainz/login/login.php ">Powrót do menu</a>

</div>
    
    <?php require "../html-elements/footer.php"?>

</body>
</html>