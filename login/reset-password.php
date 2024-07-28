<?php

require "../urls.php";

session_start();

//sprawdzenie czy token jest ustawiony
if (!isset($_GET["token"])) {
    die("token not found");
} else {
    $token = $_GET["token"];
}

//hashowanie tokenu
$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/../config.php";

//wyciągnięcie danych użytkownika na podstawie zahashowanego tokenu resetu hasła
$sql = "SELECT * FROM user
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Failed to prepare statement: " . $mysqli->error);
}

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

//sprawdzenie czy token istnieje
if ($user === null || $token === null) {
    die("token not found");
}

//sprawdzenei czy token jest aktualny
if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}



?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Hasła</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/login/reset-password.css">
</head>
<body>
<?php require "../html-elements/header.php"?>


<div class = "content">

<h1>Reset Hasła</h1>




<form method="post" action="process-reset-password.php">

    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

    <div class = "password">

    <label for="password">Nowe hasło<span style="color:red"> *</span></label>
    <br>
    <input type="password" id="password" name="password">
    </div>
    <br>
    <div class = "password-confirmation">

    <label for="password-confirmation">Powtórz hasło<span style="color:red"> *</span></label>
    <br>
    <input type="password" id="password-confirmation" name="password-confirmation">
    </div>
                <br>
    <button class = "button">Resetuj</button>
</form>

</div>


<div style="text-align: center; color: red;">
<br>
<?php 

//wyświetlenie błędu o nie pasujących hasłach
if (!empty($_SESSION["password_error_match"])) echo $_SESSION["password_error_match"];
echo "<br>";
$_SESSION["password_error_match"] = array();

//wyświetlenie błędu o zbyt krótkim haśle
if (!empty($_SESSION["password_error_length"])) echo $_SESSION["password_error_length"];
echo "<br>";
$_SESSION["password_error_length"] = array();

//wyświetlenie błędu o braku cyfr w haśle
if (!empty($_SESSION["password_error_number"])) echo $_SESSION["password_error_number"];
echo "<br>";
$_SESSION["password_error_number"] = array();

?>
</div>

<?php require "../html-elements/footer.php"?>

</body>
</html>
