<?php
require "../urls.php";

session_start();

//Sprawdzamy czy użytkownik jest już zalogowany - jeśli tak, przekierowujemy go na własny profil
if (isset($_SESSION["user_id"])) {
    header("Location: " . USER_PROFILE);
    exit;
}

$is_invalid = false;
$is_blocked = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $response = $_POST['g-recaptcha-response'];
    $recaptcha_secret = "6Le92K0pAAAAAIUS4jXpSgCsnHHJC_2Yhkt1cglr";

    //sprawdzenie weryfikacji captcha
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$response}");
    $captcha_success = json_decode($verify);

    //jeśli error weryfikacji captcha przekierowujemy użytkownika do strony informującej o błędzie
    if ($captcha_success->success == false) {

        header("Location: " . ERROR_CAPTCHA);
        exit();

        //jeśli sukces weryfikacji captcha - kontynuujemy proces logowania
    } else if ($captcha_success->success == true) {

        $mysqli = require __DIR__ . "/../config.php";


        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Failed to prepare statement: " . $mysqli->error);
        }

        $stmt->bind_param("s", $_POST["email"]);
        $stmt->execute();

        $result = $stmt->get_result();

        //Pobieramy dane z bazy danych na podstawie wprowadzonego maila
        $user = $result->fetch_assoc();

        //sprawdzamy, czy user istnieje, czy jest aktywowany i czy podane hasło się zgadza
        if ($user && $user["account_activation_hash"] === null && password_verify($_POST["password"], $user["password_hash"])) {
            //jeśli nie jest zablokowany to logujemy
            if ($user["isBlocked"] == 0) {
                session_regenerate_id(true);
                $_SESSION["user_id"] = $user["id"];
                if ($user["isAdmin"] == 1) {
                    header("Location: " . ADMIN_INDEX);
                } else {
                    header("Location: " . USER_PROFILE);
                }
                exit;
            } else {
                $is_blocked = true;
            }
        } else {

            $is_invalid = true;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Logowanie</title>
    <meta charset="UTF-8">
    <meta name="description" content="Ekran logowania klubu Just Fit">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/login/login.css">


    <style>

    </style>


</head>
<body>
<?php require "../html-elements/header.php" ?>


<div class="content">
    <div class="form-content">
        <h1 class="title">Logowanie</h1>

        <?php if ($is_invalid) : ?>
            <b style="color:red">Nieprawidłowe dane logowanie</b>
        <?php endif; ?>

        <?php if ($is_blocked) : ?>
            <b style="color:red">Konto użytkownika jest zablokowane</b>
        <?php endif; ?>


        <form method="post" class="form">
            <div class="email">
                <label for="email">E-mail <span style="color:red">*</span></label><br>
                <input class="email" type="email" name="email" id="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
            </div><br>
            <div class="password">
                <label for="password">Password <span style="color:red">*</span></label></br>
                <input class="password" type="password" name="password" id="password">
            </div>
            <div class="text-xs-center">
                <div class="g-recaptcha" data-sitekey="6Le92K0pAAAAACLXDvUaCSXRpqd4nxN8M9PuXyG1" style="text-align: center;"></div>
            </div>
            <button class="button"><b>Zaloguj</button></b>
        </form>

        <div class="forgot-password">
            <p>Zapomniałeś hasła?&nbsp&nbsp&nbsp <a class="forgot-password" href="forgot-password.php">Resetuj hasło</a> </p>
        </div>

        <div class="create-account">
            <p> Nie masz konta?&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <a class="forgot-password" href="signup.php">Zarejestruj się</a> </p>
        </div>
    </div>
</div>

</body>
<?php require "../html-elements/footer.php" ?>

</html>