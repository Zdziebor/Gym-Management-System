<?php
require "../urls.php";
session_start();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Kontakt</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Oficjalna strona klubu Just Fit. Zapraszamy!">

    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/messages/messages.css">
</head>
<body>

<?php require "../html-elements/header.php"; ?>
<h1 class="title">Komunikaty</h1>
<div class="image">
            <img src="../assets/messages/index.png" alt = "megaphone_image">
</div>
<div class="content">
    <?php
    include __DIR__ . "/../config.php";

    $query = "SELECT * FROM messages WHERE isActual = 1 ORDER BY id DESC";
    $result = mysqli_query($mysqli, $query);

    if (!$result) {
        die("Error fetching messages: " . mysqli_error($mysqli));
    }

    //wyświetlenie tematu, treści i daty dodania komunikatu
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='messages'>";
        echo "<div class='subject'>";
        echo "<h2>{$row['subject']}</h2>";
        echo "</div>";
        echo "<div class='message'>";
        echo "<p>{$row['message']}</p>";
        echo "</div>";
        echo "<div class='date'>";

        //jeśli komunikat był edytowany - dodaj "(edytowany)" obok daty dodania komunikatu 
        echo "<p>{$row['date']}";
        if ($row['isEdited'] == 1) {
            echo " (edytowany)";
        }
        echo "</div>";
        echo "</p>";
        echo "<hr>";
        echo "</div>";
    }

    mysqli_close($mysqli);
    ?>
</div>


<?php require "../html-elements/footer.php"?>

</body>

</html>
