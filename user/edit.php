<?php
//Autoryzacja - GIT
session_start();
require "../urls.php";
require "checkUserLogin.php";

//przechowywanie errorow
$errors = [];


if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/../config.php";
    
    $sql = "SELECT * FROM user
            WHERE id = ?";
    
        $stmt = $mysqli->prepare($sql);
    
    if (!$stmt) {
        die("Database error");
    }

    $stmt->bind_param("i", $_SESSION["user_id"]);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $user = $result->fetch_assoc();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $phone_number = $_POST["phone_number"];
        $city = $_POST["city"];
        $street = $_POST["street"];
      
        $zip_code = $_POST["zip_code"];

        // Walidacja numeru telefonu
        if (!is_numeric($phone_number) || strlen($phone_number) < 9) {
            $errors['phone_number'] = "Numer telefonu musi mieć minimum 9 cyfr";
        } else {
            //Zaktualizuj numer jeśli jest poprawny
            $user["phone_number"] = $phone_number;
        }
        
        //Walidacja miasta czy nie jest puste
        if (empty($city)) {
            $errors['city'] = "Miasto jest wymagane.";
        } else {
            //Zaktualizuj miasto jeśli jest poprawne
            $user["city"] = $city;
        }
        //Walidacja adresu czy nie jest puste
        if (empty($street)) {
            $errors['street'] = "Adres jest wymagany.";
        } else {
            //Zaktualizuj adres jeśli jest poprawny
            $user["street"] = $street;
        }
        //Walidacja kodu pocztowego czy nie jest pusty
        if (empty($zip_code)) {
            $errors['zip_code'] = "Kod pocztowy jest wymagany";
        } else {
            //Zaktualizuj kod pocztowy jeśli jest poprawny
            $user["zip_code"] = $zip_code;
        }
        
        //Jeśli brak errorów kontynuuj
        if (empty($errors)) {
            //Zapytanie SQL
            $update_sql = "UPDATE user
                           SET phone_number = ?, city = ?, street = ?,  zip_code = ?
                           WHERE id = ?";
            
           
            $update_stmt = $mysqli->prepare($update_sql);
            
            //Przypisz parametry
            $update_stmt->bind_param("ssssi", $user["phone_number"], $user["city"], $user["street"],  $user["zip_code"], $_SESSION["user_id"]);
            
            //Wykonaj zapytanie
            if ($update_stmt->execute()) {
                header("Location: edit.php");
                exit();
            } else {
                echo "Error updating record: " . $mysqli->error;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edycja Profilu</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Historia płatności klienta klubu Just Fit">

    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/user/edit.css">

</head>
<body>
<?php require "../html-elements/header.php"?>

    <?php if (isset($user)): ?>
        <div class = "title">
        <h1>Witaj <span style="color:#90A9FF"><?= htmlspecialchars($user["name"]) ?></span></h1>
        </div>
        
</div class = "edit">

<div class = "edit-data">
<div class = "edit-title">
        <h2>Edytuj Dane</h2>
        </div>
        <form method="POST" name="edit" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="editForm">
        <div class="phone_number">
        <?php if(isset($errors['phone_number'])): ?>
                
                <span style="color: red;"><?= $errors['phone_number'] ?></span>
            <?php endif; ?>
            <br>
            <label for="phone_number">Numer telefonu</label><br>
            
            <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user["phone_number"]) ?>">
           

            <br>
        </div>
        <div class = "city">
        <?php if(isset($errors['city'])): ?>
                
                <span style="color: red;"><?= $errors['city'] ?></span>
                <br>
            <?php endif; ?>
           
            <label for="city">Miejscowość</label><br>
           
            <input type="text" id="city" name="city" value="<?= htmlspecialchars($user["city"]) ?>">
           
            <br><br>
        </div>
        <div class = "street">
            <label for="street">Ulica</label><br>
            <input type="text" id="street" name="street" value="<?= htmlspecialchars($user["street"]) ?>">
            <?php if(isset($errors['street'])): ?>
                <br>
                <span style="color: red;"><?= $errors['street'] ?></span>
            <?php endif; ?>
            <br><br>
        </div>

        <div class = "zip-code">
          
            <label for="zip_code">Kod pocztowy</label><br>
            <input type="text" id="zip_code" name="zip_code" value="<?= htmlspecialchars($user["zip_code"]) ?>">
            <?php if(isset($errors['zip_code'])): ?>
                <br>
                <span style="color: red;"><?= $errors['zip_code'] ?></span>
            <?php endif; ?>
      
            <br><br>
            </div>
    
    </div>
        
            <button class ="button"><b>Zatwierdź zmiany</button></b>

    <?php else: ?>
        <h1>Brak loginu</h1>
        <p><a href="login.php">Log in</a> or <a href="signup.html">sign up</a></p>
    <?php endif; ?>


    <div class="links">
            <a class="user-profile" href="<?php echo USER_PROFILE ?>">Profil</a>
            <a class="change-password" href="<?php echo USER_SEND_PASSWORD_RESET ?>">Zmień hasło</a>
            <a class="change-mail" href="<?php echo USER_CHANGE_EMAIL ?>">Zmień maila</a>
            <a class="edit-profile" href="<?php echo USER_EDIT ?>">Edytuj profil</a>
            <a class="deactivate-account" href="<?php echo USER_DEACTIVATE ?>">Dezaktywuj konto</a>
        </div>

<div class="image-1">
    <img src="../assets/user/index-1.png" alt = "gym-equipment-1">
</div>
<div class="image-2">
    <img src="../assets/user/index-2.png" alt = "gym-equipment-2">
</div>

    <?php require "../html-elements/footer.php"?>
</body>
</html>
