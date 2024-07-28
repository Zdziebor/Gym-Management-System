<?php require "urls.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Błąd weryfikacji CAPTCHA</title>
    <link rel="stylesheet" href="html-elements/header.css">
    <link rel="stylesheet" href="html-elements/footer.css">

    <link rel="stylesheet" href="html-elements/other/error.css">
</head>
<body>
<?php require "html-elements/header.php"?>

    <div class="content">
    <h1 class="text-1">Błąd weryfikacji CAPTCHA!</h1>
    <p class = "text-2">Jeśli błąd się będzie powtarzał, <a class = "link" href="http://localhost/pracainz/contact/index.php">skontaktuj </a> się z nami.
    <br>
    <br>
    <br>
    <a class = "menu-button" href="<?php echo INDEX ?>">Strona główna</a>
    <div class="image-2">
            <img src="assets/other/error.png">
</div>
</div>
<?php require "html-elements/footer.php"?>

</body>
</html>