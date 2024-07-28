<?php 
require "../urls.php";
session_start();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Zapomniane hasło</title>
    <meta charset="UTF-8">
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="validation.js" defer></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/login/forgot-password.css">
</head>
<body>
<?php require "../html-elements/header.php"?>

    <div class ="content">
    <h1>Przypomnienie hasła</h1>

    <form method="post" action="send-password-reset.php" id="forgot-password">

        <div class ="email">
        <label for="email">E-mail <span style="color:red">*</span></label>
        <br>
        <input type="email" name="email" id="email" >
        </div>
        <div class="text-xs-center">

        <div class="g-recaptcha" data-sitekey="6Le92K0pAAAAACLXDvUaCSXRpqd4nxN8M9PuXyG1"></div>
        </div>
        <button class = "button"><b>Resetuj</b></button>

    </form>
    </div>

    <div style="text-align: center; color: red;">

<br>
    <?php 

//Wyświetlenie komunikatu błędu
if (!empty($_SESSION["forgot_password_error"])) echo $_SESSION["forgot_password_error"];

//Usunięcie komunikatu błędu w przypadku odświeżenie strony
$_SESSION["forgot_password_error"] = array();


?>
    </div>


    <?php require "../html-elements/footer.php"?>

</body>
</html>