<?php
include "checkAdmin.php";

?>
<!DOCTYPE html>

<html>

<head>
    <title>Komunikaty</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://unpkg.com/almond.css@latest/dist/almond.lite.min.css" />

</head>

<body>

    <a href='<?php echo ADMIN_INDEX ?>'>Powrót do menu</a>
    <br> <br>
    <span style="text-align:center">
        <h1> <?php if (isset($_POST['showDeleted'])) {
                    echo "Wiadomości usunięte";
                } else {
                    echo "Wiadomości aktualne";
                } ?> </h1>
    </span>
    <form method="post">

        <button type="submit" name="addMessage">Dodaj komunikat</button>


        <button type="submit" name="showActual">Pokaż aktualne komunikaty</button>

        <button type="submit" name="showDeleted">Pokaż usunięte komunikaty</button>

    </form>
</body>

</html>



<?php

include "config.php";

//Przycisk przekierowujący do ekranu dodania komunikatów
if (isset($_POST['addMessage'])) {
    header("Location: addMessage.php");
}


$query = "SELECT * FROM messages WHERE isActual = 1 ORDER BY id DESC";


//Przycisk do wyświetlenie usuniętych komunikatów
if (isset($_POST['showDeleted'])) {
    $query = "SELECT * FROM messages WHERE isActual = 0 ORDER BY id DESC";

    //Przycisk do wyświetlenie aktualnych komunikatów
} else if (isset($_POST['showActual'])) {
    $query = "SELECT * FROM messages WHERE isActual = 1 ORDER BY id DESC";
}

$result = mysqli_query($mysqli, $query);

//Wyświetlenie wiadomości
while ($row = mysqli_fetch_assoc($result)) {
    echo "<div style='margin-bottom: 10px;'>";
    echo "<h2>{$row['subject']}</h2>";
    echo "<p style='font-size: 14px;'>{$row['message']}</p>";
    echo "<p style='font-size: 12px; color: #999;'>{$row['date']}";
    if ($row['isEdited'] == 1) {
        echo " (edited)";
    }
    echo "<br>";
    echo "<br>";
    echo "<a href=http://localhost/pracainz/admin/editmessage.php?id=" . $row["id"] . ">Edytuj </a>";
    echo "</p>";
    echo "<hr>";
    echo "</div>";
}

mysqli_close($mysqli);
?>