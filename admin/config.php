<?php

//Połączenie z bazą danych

$host = "localhost";
$dbname = "gymdb";
$username = "root";
$password = "";

$mysqli = new mysqli(
    hostname: $host,
    username: $username,
    password: $password,
    database: $dbname
);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;
