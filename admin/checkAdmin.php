<?php
session_start();
require "../urls.php";

$isAdmin = false;

//Sprawdź czy użytkownik jest zalogowany
if (isset($_SESSION["user_id"])) {
    $mysqli = require "config.php";

    $sql = "SELECT * FROM user WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        die("Database error");
    }

    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();

    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    $stmt->close();

    //Jeśli zalogowany użytkownik w bazie danych nie ma ustawionego isAdmin na 1 - przekieruj do menu głównego
    if ($user["isAdmin"] !== 1) {
        header("Location: " . INDEX);
    }
} else {
    //Jeśli użytkownik niezalogowany - przekieruj do loginu
    header("Location: " . LOGIN_LOGIN);
}
