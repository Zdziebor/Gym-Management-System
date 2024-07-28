<?php

include "checkAdmin.php";

error_reporting(0);

echo "<a href='" . ADMIN_INDEX . "'>Powrót do menu</a>";


$mysqli = require __DIR__ . "/config.php";

//Edycja profilu użytkownika

$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$sql_mebmership = "SELECT * FROM membership_remaining WHERE Buyer_ID = ?";
$stmt = $mysqli->prepare($sql_mebmership);
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$result = $stmt->get_result();
$user_membership = $result->fetch_assoc();

//Jeśli użytkownik istnieje i nie kupił wcześniej karnetu
//ustaw ilość dni na 0 co pozwoli na ewentualną edycję dni karnetu użytkownika
if ($user["id"] !== NULL && $user_membership["Buyer_ID"] == NULL) {

    $insert_sql = "INSERT INTO membership_remaining (Buyer_ID, daysLeft)
    VALUES (?, 0)";
    $insert_stmt = $mysqli->prepare($insert_sql);
    $insert_stmt->bind_param("i", $_GET["id"]);
    $insert_stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //aktualizacja danych użytkownika
    if (isset($_POST["data_update"])) {
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $phone_number = $_POST["phone_number"];
        $PESEL = $_POST["PESEL"];
        $city = $_POST["city"];
        $street = $_POST["street"];
        $zip_code = $_POST["zip_code"];
        $email = $_POST["email"];




        $update_sql = "UPDATE user SET name = ?, surname = ?, phone_number = ?,  PESEL = ?, city = ?, street = ?, zip_code = ?, email = ? WHERE id = ?";

        $update_stmt = $mysqli->prepare($update_sql);

        $update_stmt->bind_param("ssssssssi", $name, $surname, $phone_number, $PESEL, $city, $street, $zip_code, $email, $_GET["id"]);




        if ($update_stmt->execute()) {
            var_dump($update_stmt->error);
            header("Location: editprofile.php?id=" . $_GET['id']);
            var_dump($update_stmt);

            exit();
        } else {
            echo "Error updating record: " . $mysqli->error;
        }
    }

    //zablokowanie lub odblokowanie użytkownika
    if (isset($_POST["block_unblock"])) {
        $isBlocked = $user["isBlocked"] ? 0 : 1;

        $block_unblock_sql = "UPDATE user SET isBlocked = ? WHERE id = ?";

        $block_unblock_stmt = $mysqli->prepare($block_unblock_sql);

        $block_unblock_stmt->bind_param("ii", $isBlocked, $_GET["id"]);

        if ($block_unblock_stmt->execute()) {
            header("Location: editprofile.php?id=" . $_GET['id']);
            exit();
        } else {
            echo "Error updating record: " . $mysqli->error;
        }
    }

    //edycja dni karnetu użytkownika
    if (isset($_POST["membership_update"])) {

        $membership = $_POST["membership_update"];

        $update_sql = "UPDATE membership_remaining SET daysLeft = ? WHERE Buyer_ID = ?";

        $membership_stmt = $mysqli->prepare($update_sql);

        $membership_stmt->bind_param("ii", $membership, $_GET["id"]);

        if ($membership_stmt->execute()) {
            header("Location: editprofile.php?id=" . $_GET['id']);
            exit();
        } else {
            echo "Error updating record: " . $mysqli->error;
        }
    }
}



?>

<!DOCTYPE html>
<html>

<head>
    <title>Edytuj profil</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://unpkg.com/almond.css@latest/dist/almond.lite.min.css" />
</head>

<body>

    <h1>Profil</h1>
    <form method="POST" name="edit" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $_GET['id']; ?>" id="editForm">
        <label for="name">Imię</label><br>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user["name"]) ?>" required>
        <?php if (isset($errors['name'])) : ?>
            <span style="color: red;"><?= $errors['name'] ?></span>
        <?php endif; ?>
        <br><br>
        <label for="surname">Nazwisko</label><br>
        <input type="text" id="surname" name="surname" value="<?= htmlspecialchars($user["surname"]) ?>" required>
        <?php if (isset($errors['surname'])) : ?>
            <span style="color: red;"><?= $errors['surname'] ?></span>
        <?php endif; ?>
        <br><br>
        <label for="phone_number">Numer telefonu</label><br>
        <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user["phone_number"]) ?>" required>
        <?php if (isset($errors['phone_number'])) : ?>
            <span style="color: red;"><?= $errors['phone_number'] ?></span>
        <?php endif; ?>

        <br><br>
        <label for="PESEL">PESEL</label><br>
        <input type="text" id="PESEL" name="PESEL" value="<?= htmlspecialchars($user["PESEL"]) ?>" required>
        <?php if (isset($errors['PESEL'])) : ?>
            <span style="color: red;"><?= $errors['PESEL'] ?></span>
        <?php endif; ?>
        <br><br>
        <label for="city">Miasto:</label><br>
        <input type="text" id="city" name="city" value="<?= htmlspecialchars($user["city"]) ?>" required>
        <?php if (isset($errors['city'])) : ?>
            <span style="color: red;"><?= $errors['city'] ?></span>
        <?php endif; ?>
        <br><br>
        <label for="street">Ulica</label><br>
        <input type="text" id="street" name="street" value="<?= htmlspecialchars($user["street"]) ?>" required>
        <?php if (isset($errors['street'])) : ?>
            <span style="color: red;"><?= $errors['street'] ?></span>
        <?php endif; ?>
        <br><br>
        <label for="zip_code">Kod pocztowy</label><br>
        <input type="text" id="zip_code" name="zip_code" value="<?= htmlspecialchars($user["zip_code"]) ?>" required>
        <?php if (isset($errors['zip_code'])) : ?>
            <span style="color: red;"><?= $errors['zip_code'] ?></span>
        <?php endif; ?>
        <br><br>
        <label for="email">e-mail:</label><br>
        <input type="text" id="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>" required>
        <?php if (isset($errors['email'])) : ?>
            <span style="color: red;"><?= $errors['email'] ?></span>
        <?php endif; ?>
        <br><br>

        <input type="submit" name="data_update" value="Zapisz">
        <br>
        <br>
        <label for="membership_update">Dni karnetu</label><br>
        <input type="text" id="membership_update" name="membership_update" value="<?= htmlspecialchars($user_membership["daysLeft"]) ?>" required><br><br>
        <input type="submit" value="Wykonaj">


        <br>
        <br>
        <label for="block_unblock">Użytkownik: <b><?php echo $user['isBlocked'] ? '<span style="color:red">Zablokowany</span>' : '<span style="color:green">Aktywny</span>'; ?></b></label><br><br>
        <input type="submit" name="block_unblock" value="<?php echo $user['isBlocked'] ? 'Anuluj dezaktywację konta' : 'Dezaktywuj konto'; ?>">

    </form>

</body>

</html>