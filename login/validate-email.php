<?php
require "../urls.php";
$mysqli = require __DIR__ . "/../config.php";
//Walidacja podanego przy rejestracji adresu e-mail
//Sprawdzenie czy email został podany i czy jest adresem e-mail
if (isset($_GET["email"]) && filter_var($_GET["email"], FILTER_VALIDATE_EMAIL)) {
  
    //sprawdzenie czy podany adres e-mail znajduje sie w bazie
    $stmt = $mysqli->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $_GET["email"]);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Sprawdzenie czy nie ma użytkownika z takim adresem e-mail
    $is_available = $result->num_rows === 0;

    header("Content-Type: application/json");

    //Wyświetl w formacie json czy email jest wolny - true lub false
    echo json_encode(["available" => $is_available]);
} else {
    //Nieprawidłowy numer e-mail
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => "Invalid email address"]);
}
