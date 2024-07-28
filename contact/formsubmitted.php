<?php 

session_start();
require "../urls.php";
?>

<!DOCTYPE html>

<html>

<head>
    <title>Kontakt</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">

    <link rel="stylesheet" href="../html-elements/contact/formsubmitted.css">
</head>

<body>
    <?php require "../html-elements/header.php"?>
    <div class ="content">
    <div class="image-1">
            <img src="../assets/contact/formsubmitted.png">
    </div>

    <h1 class="text-1">Wiadomość wysłana</h1>
    <p class = "text-2">Odpowiemy na nią tak szybko, jak to możliwe!</p>
    <br>
    <a class = "menu-button" href="<?php echo INDEX ?>">Powrót do menu</a>

    </div>

   
    <?php require "../html-elements/footer.php"?>

</body>
</html>