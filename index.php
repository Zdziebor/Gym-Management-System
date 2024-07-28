<?php

session_start();

require "urls.php";

if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/config.php";
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}


?>
<!DOCTYPE html>
<html>
<head>
<title>Siłownia FitActive</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Oficjalna strona klubu Just Fit. Zapraszamy!">
    <meta charset="UTF-8">

    <link rel="stylesheet" href="../pracainz/html-elements/header.css">
    <link rel="stylesheet" href="../pracainz/html-elements/footer.css">
    <link rel="stylesheet" href="../pracainz/html-elements/login/index.css">



</head>
<body>
<?php require "../pracainz/html-elements/header.php"?>
    <div class="main">
        
    <?php if (isset($user)): ?>
        
     <?php header("Location: ". USER_PROFILE) ?>
        
    <?php else: ?>
        <div class="heading">
        <h1 class="text">Dołącz do <span style="color: #90A9FF";>Just Fit</span></h1>
        <h2 class="text2"> Weź sprawy w swoje ręce! </h2>
        <p class="text3">Uzyskaj dostęp do nowoczesnego sprzętu, wysoko wyszkolonej kadry 
            oraz niesamowitej <br> społeczności już za <span style="font-size: 38px"; ><b>4zł/dzień</span></p>
            <p class="text4">Nie zwlekaj.</p>
            <a class = "login" href= <?php echo LOGIN_SIGNUP ?>>Dołącz teraz</a>
        </div>
        <div class="image">
            <img src="../pracainz/assets/login/index.png" alt = "background_image_women_excersising">
        </div>
        
        
    <?php endif; ?>
    </div>
    <?php require "../pracainz/html-elements/footer.php"?>

</body>
</html>
