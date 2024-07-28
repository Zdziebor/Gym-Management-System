<?php
require "../urls.php";


//Sprawdzenie czy podany jest token
if (!isset($_GET["token"]) || !is_string($_GET["token"])) {
    die("Invalid token");
}


$token = $_GET["token"];

//stworzenie hashu na podsatwie podanego tokanu
$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/../config.php";

$sql = "SELECT * FROM user
        WHERE account_activation_hash = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Failed to prepare statement: " . $mysqli->error);
}


$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

//pobranie danych uzytkownika na podstawie hashu aktywacji konta
$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

//ustawienie hashu na null - uzytkownik aktywowany
$sql = "UPDATE user
        SET account_activation_hash = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Failed to prepare statement: " . $mysqli->error);
}

$stmt->bind_param("s", $user["id"]);

$stmt->execute();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Aktywacja konta</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">

    <link rel="stylesheet" href="../html-elements/login/activate-account.css">
</head>

<body>
    <?php require "../html-elements/header.php" ?>
    <div class="content">
        <div class="image-2">
            <img src="../assets/payment/paypalsuccess.png">
        </div>
        <h1 class="text-1">Aktywacja konta przebiegła pomyślnie.</h1>
        <br>
        <a class="login-button" href="http://localhost/pracainz/login/login.php">Powrót do logowania</a>

    </div>
    <?php require "../html-elements/footer.php" ?>

</body>

</html>