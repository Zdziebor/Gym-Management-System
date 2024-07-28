<?php
require "../urls.php";

session_start();

$user = null;

//sprawdzamy czy uzytkownik jest zalogowany 
if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/../config.php";

    $sql = "SELECT * FROM user WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        die("Database error");
    }

    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();

    $result = $stmt->get_result();

    //przypisanie do $user danych z bazy 
    $user = $result->fetch_assoc();

    $stmt->close();

    //pobranie liczby dni karnetu
    $query = "SELECT daysLeft FROM membership_remaining WHERE buyer_id = {$_SESSION["user_id"]}";
    $result_membership_remaining = mysqli_query($mysqli, $query);

    if (!$result) {
        die("Error fetching messages: " . mysqli_error($mysqli));
    }

    $payment = mysqli_fetch_assoc($result_membership_remaining);

    if (empty($payment["daysLeft"])) {
        $payment["daysLeft"] = 0;
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Profil Użytkownika</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Oficjalna strona klubu Just Fit. Zapraszamy!">
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/user/profile.css">



</head>

<body>
    <?php require "../html-elements/header.php" ?>


    <?php if ($user) : ?>



        <h1 class="hello-user">Witaj <span style="color:#90A9FF"><?= htmlspecialchars($user["name"]) ?></span></h1>
        <div class="context">
            <h2 class="your-data">Twoje dane</h2>

            <div class="data">
                <div class="column-1">
                    <div class="name">
                        <p><span style="color:#464343">Imie &nbsp<b></span> <?= htmlspecialchars($user["name"]) ?></b></p>
                    </div>
                    <div class="surname">
                        <p><span style="color:#464343">Nazwisko &nbsp<b></span> <?= htmlspecialchars($user["surname"]) ?></b></p>
                    </div>
                    <div class="phone_number">
                        <p><span style="color:#464343">Numer Telefonu &nbsp<b></span> <?= htmlspecialchars($user["phone_number"]) ?></b></p>
                    </div>
                    <div class="PESEL">
                        <p><span style="color:#464343">PESEL &nbsp<b></span> <?= htmlspecialchars($user["PESEL"]) ?></b></p>
                    </div>
                </div>
                <div class="column-2">

                    <div class="city">
                        <p><span style="color:#464343">Miejscowość &nbsp<b></span> <?= htmlspecialchars($user["city"]) ?></b></p>
                    </div>
                    <div class="street">
                        <p><span style="color:#464343">Adres &nbsp<b></span> <?= htmlspecialchars($user["street"]) ?></b></p>
                    </div>
                    <div class="zip_code">
                        <p><span style="color:#464343">Kod Pocztowy &nbsp<b></span> <?= htmlspecialchars($user["zip_code"]) ?></b></p>
                    </div>
                    <div class="email">
                        <p><span style="color:#464343">E-mail &nbsp<b></span> <?= htmlspecialchars($user["email"]) ?></b></p>
                    </div>
                </div>




                <div class="daysLeft">
                    <p><b>Pozostałe dni karnetu: &nbsp</b> <b><span style="color:red" ;><?= htmlspecialchars($payment["daysLeft"]) ?></span></b></p>
                </div>
                <div class="buy-membership">
                    <a class="membership-button" href="<?php echo PAYMENT_INDEX ?>">Przedłuż karnet</a>
                </div>
            </div>

        <?php else :

        header('Location: ' . LOGIN_LOGIN) ?>
            <h1>Brak loginu</h1>
            <p><a href="login.php">Log in</a> or <a href="signup.html">sign up</a></p>

        <?php endif; ?>


        </div>

        <div class="user-id">
            <p><span style="color:#464343">ID:<b></span> <span style="color:red" ;> <?= htmlspecialchars($user["id"]) ?> </span></b></p>
        </div>

        <div class="links">
            <a class="edit-profile" href="<?php echo USER_EDIT ?>">Edytuj Profil</a>
            <a class="change-password" href="<?php echo USER_SEND_PASSWORD_RESET ?>">Zmień hasło</a>
            <a class="change-mail" href="<?php echo USER_CHANGE_EMAIL ?>">Zmień maila</a>
            <a class="transaction-history" href="<?php echo USER_PAYMENT_HISTORY ?>">Historia Transakcji</a>
            <a class="deactivate-account" href="<?php echo USER_DEACTIVATE ?>">Dezaktywuj konto</a>

        </div>

        <div class="image-1">
            <img src="../assets/user/index-1.png" alt="gym-equipment-1">
        </div>
        <div class="image-2">
            <img src="../assets/user/index-2.png" alt="gym-equipment-2">
        </div>


        <?php require "../html-elements/footer.php" ?>

</body>


</html>