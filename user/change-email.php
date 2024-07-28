<?php

session_start();
require "../urls.php";
require "checkUserLogin.php";


$user = false;

if (isset($_SESSION["user_id"])) {
    $user = true;
}

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $new_email = $_POST["email"];

//     //Sprawdz poprawnosc adresu e-mail
//     if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
//         $email_error = "Invalid email format.";
//     } else {
//         //Sprawdzenie czy adres e-mail znajduje się w bazie
//         $mysqli = require __DIR__ . "/../config.php";

//         $sql = "SELECT * FROM user WHERE email = ?";

//         $stmt = $mysqli->prepare($sql);

//         if (!$stmt) {
//             die("Database error");
//         }

//         $stmt->bind_param("s", $new_email);

//         $stmt->execute();

//         $result = $stmt->get_result();

//         //Sprawdz czy istnieje konto z podanym adresem e-mail
//         if ($result->num_rows > 0) {
//             $email_error = "Email already exists.";
//         }
        
//     }

//     var_dump($result);

//     // If there are no errors, proceed with sending reset email
//     if (empty($email_error)) {
//         // Your code to send reset email goes here
//         // Redirect to success page or display success message
//         header("Location: reset-success.php");
//         exit();
//     }
// }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Zmiana maila</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/user/change-email.css">
</head>
<body>
<?php require "../html-elements/header.php"?>


<?php if ($user): ?>
    <div class = "content">
    <h1>Zmiana maila</h1>

    <form method="post" action="send-email-reset.php" name="change-email" id="change-email">
    <div class = "mail">   
    <label for="email">Nowy mail <span style="color:red">*</span></label><br>
        <input placeholder="Wpisz e-mail.." type="email" name="email" id="email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
      
    </div>
        <br><br>
        <div class = "btn-submit">
        <button class ="button">Zmień</button>
        </div>
    </form>
    </div>

<?php else: ?>
    
<?php endif; ?>

<div style="text-align: center; color: red;">

<br>

<?php 



if (!empty($_SESSION["email_reset_invalid"]))echo $_SESSION["email_reset_invalid"];

$_SESSION["email_reset_invalid"] = array();

if (!empty($_SESSION["email_reset_error"]))echo $_SESSION["email_reset_error"];

$_SESSION["email_reset_error"] = array();


?>

</div>
 
<?php require "../html-elements/footer.php"?>

</body>
</html>
