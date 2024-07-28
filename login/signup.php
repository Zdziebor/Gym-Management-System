<!DOCTYPE html>
<html>
<head>
    <title>Rejestracja</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rejestracja konta klubu Just Fit">

    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="validation.js" defer></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/login/signup.css">
</head>
<body>
<?php 
require "../urls.php";
require "../html-elements/header.php"?>


    <div class = "content">
    <h1>Rejestracja</h1>
    <form action="process-signup.php" method="post" id="signup" novalidate>

        <div class = "data">
        <div class = "personal-data">
        <h2>Dane Osobowe</h2>

        <div class = "name">
            <label for="name">Imię <span style="color:red">*</span></label>
            <br>
            <input type="text" id="name" name="name">
        </div>

        <div class = "surname">
            <label for="surname">Nazwisko <span style="color:red">*</span></label><br>
            <input type="text" id="surname" name="surname">
        </div>

        <div class = "phone-number">
            <label for="phone_number">Numer Telefonu <span style="color:red">*</span></label><br>
            <input type="text" id="phone_number" name="phone_number">
        </div>

        <div class = "PESEL">
            <label for="PESEL">PESEL <span style="color:red">*</span></label><br>
            <input type="text" id="PESEL" name="PESEL">
        </div>
        </div>

        <div class = "address-data">
        <h2>Dane Adresowe</h2>

        <div class = "city">
            <label for="city">Miasto <span style="color:red">*</span></label><br>
            <input type="text" id="city" name="city">
        </div>
        

        <div class = "street">
            <label for="street">Ulica <span style="color:red">*</span></label><br>
            <input type="text" id="street" name="street">
        </div>
        
        <div class = "zip-code">
            <label for="zip_code">Kod Pocztowy <span style="color:red">*</span></label><br>
            <input type="text" id="zip_code" name="zip_code">
        </div>
        </div>

        <div class = "account-data">
        <h2>Konto</h2>

        <div class = "email">
            <label for="email">E-mail <span style="color:red">*</span></label><br>
            <input type="email" id="email" name="email">
        </div>
        
        <div class = "password">
            <label for="password">Hasło <span style="color:red">*</span></label><br>
            <input type="password" id="password" name="password">
        </div>
        
        <div class = "password-confirmation">
            <label for="password_confirmation">Powtórz Hasło <span style="color:red">*</span></label><br>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>
        </div>
        <div class="text-xs-center">

        <div class="g-recaptcha" data-sitekey="6Le92K0pAAAAACLXDvUaCSXRpqd4nxN8M9PuXyG1"></div>
        </div>
        <button class = "button"><b>Zarejestruj</b></button>
    </form>
    </div>
    <?php require "../html-elements/footer.php"?>

</body>
</html>








