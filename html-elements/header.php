<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title></title>
<link rel="stylesheet" href="header.css">
</head>
<body>

<div class="header">
    <div class="logo">JustFit</div>
    <div class="nav">
    <a href="<?php echo PAYMENT_INDEX; ?>"><button>Doładuj Karnet</button></a>
        <a href="<?php echo USER_PROFILE; ?>"><button>Profil</button></a>
        <a href="<?php echo MESSAGES_INDEX; ?>"><button>Komunikaty</button></a>
        <a class = "contact-header" href="<?php echo CONTACT_FORM; ?>"><button>Kontakt</button></a>
    </div>

    <?php if (!isset($_SESSION["user_id"])):?>
    <a href="<?php echo LOGIN_LOGIN; ?>"><button class="login-button">Zaloguj się</button></a>
    <?php else:?>
    <a href="<?php echo LOGIN_LOGOUT; ?>"><button class="logout-btn">Wyloguj się</button></a>
    <?php endif?>

</div>

</body>
</html>
