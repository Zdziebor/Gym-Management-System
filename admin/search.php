<?php
include "checkAdmin.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyszukaj użytkowników</title>
    <link rel="stylesheet" href="https://unpkg.com/almond.css@latest/dist/almond.lite.min.css" />
    <style>
        /* CSS for stacking textboxes */
        form label {
            display: block;
            margin-bottom: 5px;
        }

        form input[type="text"] {
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <a href="<?php echo ADMIN_INDEX ?>">Powrót do menu</a>
    <h1>Wyszukiwanie użytkowników</h1>
    <form method="GET">
        <label for="name">Imię</label>
        <input type="text" id="name" name="name" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>">

        <label for="surname">Nazwisko</label>
        <input type="text" id="surname" name="surname" value="<?php echo isset($_GET['surname']) ? htmlspecialchars($_GET['surname']) : ''; ?>">

        <label for="id">ID</label>
        <input type="text" id="id" name="id" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">

        <label for="PESEL">PESEL</label>
        <input type="text" id="PESEL" name="PESEL" value="<?php echo isset($_GET['PESEL']) ? htmlspecialchars($_GET['PESEL']) : ''; ?>">

        <button type="submit">Szukaj</button>
    </form>

    <?php

    require_once 'config.php';

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $searchResults = [];
        //Sprawdzenie czy przynajmniej jedno z pól jest wypełnione
        if (!empty($_GET['name']) || !empty($_GET['surname']) || !empty($_GET['id']) || !empty($_GET['PESEL'])) {
            $query = "SELECT * FROM user WHERE ";

            //W tej tablicy będą przechowywane części zapytania sql, które potem zostaną połączone
            $conditions = [];

            //Dodaj zapytanie sql dla imienia do tablicy
            if (!empty($_GET['name'])) {
                $conditions[] = "name LIKE '%" . $mysqli->real_escape_string($_GET['name']) . "%'";
            }

            //Dodaj zapytanie sql dla nazwiska do tablicy
            if (!empty($_GET['surname'])) {
                $conditions[] = "surname LIKE '%" . $mysqli->real_escape_string($_GET['surname']) . "%'";
            }

            //Dodaj zapytanie sql dla id do tablicy
            if (!empty($_GET['id'])) {
                $conditions[] = "id = " . $mysqli->real_escape_string($_GET['id']) . "";
            }
            //Dodaj zapytanie sql dla numeru PESEL do tablicy
            if (!empty($_GET['PESEL'])) {
                $conditions[] = "PESEL = '" . $mysqli->real_escape_string($_GET['PESEL']) . "'";
            }

            //Połącz zapytania z tablicy conditions za pomocą AND używając implode w jedno zapytanie
            $query .= implode(" AND ", $conditions);

            //Wykonaj zapytanie
            $result = $mysqli->query($query);

            //Jeśli są jakieś wyniki
            if ($result) {
                //Wyświetl wyniki
                if ($result->num_rows > 0) {
                    echo "<h2>Wyniki wyszukiwania:</h2>";
                    echo "<ul>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>";
                        echo "ID: " . $row['id'] . "<br>";
                        echo "Nazwisko: " . $row['surname'] . "<br>";
                        echo "Imię: " . $row['name'] . "<br>";
                        echo "PESEL: " . $row['PESEL'] . "<br>";

                        echo "<a href='editprofile.php?id=" . $row['id'] . "'>Zobacz profil</a>";
                        echo "</li>";
                        echo "<hr>";
                    }
                    echo "</ul>";
                } else {
                    //Brak wyników
                    echo "<p>Brak wyników wyszukiwania.</p>";
                }
            } else {
                echo "Błąd zapytania: " . $mysqli->error;
            }
        } else {
            //Brak wprowadzonych danych
            echo "<p>Wpisz co najmniej jedno kryterium wyszukiwania.</p>";
        }
    }
    ?>

</body>

</html>