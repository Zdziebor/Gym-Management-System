<?php
require "../urls.php";



$mysqli = require __DIR__ . "/../config.php";

//Sprawdzenie czy numer PESEL został podany i czy jest numeryczny
if (isset($_GET["PESEL"]) && is_numeric($_GET["PESEL"])) {
    //Sprawdzenie czy jakiś użytkownik ma już przypisany podany numer PESEL
    $stmt = $mysqli->prepare("SELECT * FROM user WHERE PESEL = ?");

    $stmt->bind_param("s", $_GET["PESEL"]);
    $stmt->execute();
        $result = $stmt->get_result();
    
    //Sprawdzenie czy nie ma użytkownika z takim numerem PESEL
    $is_available = $result->num_rows === 0;

    header("Content-Type: application/json");

    //Wyświetl w formacie json czy PESEL jest wolny - true lub false
    echo json_encode(["available" => $is_available]);
} else {
    //Nieprawidłowy numer e-mail
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => "Invalid number"]);
}
