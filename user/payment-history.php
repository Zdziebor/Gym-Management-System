<?php require "../urls.php";
session_start();
require "checkUserLogin.php";

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


?>
<!DOCTYPE html>
<html>
<head>
    <title>Historia Płatności</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Historia płatności klienta klubu Just Fit">
    
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/user/payment-history.css">
</head>

<body>
<?php require "../html-elements/header.php"?>

<div class="image-1">
            <img src="../assets/user/payment-history.png" alt = "dollars-image">
</div>

<div class ="user">
<h1 class="hello-user">Witaj <span style="color:#90A9FF"><?= htmlspecialchars($user["name"]) ?></span></h1>

    </div>
    <div class ="transaction-history">
    <h2>Historia Płatności</h2>
    </div>
<?php

include __DIR__ . "/../config.php";

//Płatności na stronę = 5
$paymentsPerPage = 5;

//Obecny numer strony, jesli brak - ustaw 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;
//Numer transakcji od którego zaczynamy wyświetlanie 5 następnych transakcji w zależności od numeru strony
$start = ($page - 1) * $paymentsPerPage;

$query = "SELECT * FROM payments WHERE buyer_id = {$_SESSION["user_id"]} ORDER BY createdtime DESC LIMIT $start, $paymentsPerPage";
$result = mysqli_query($mysqli, $query);

if (!$result) {
    die("Error fetching messages: " . mysqli_error($mysqli));
}


echo "<div class ='transactions'>";
while ($payment = mysqli_fetch_assoc($result)) {
    echo "<div class ='transaction'>";
    echo "    <div class='transaction-id-container'>";
    echo "<div class ='transaction-id'>";
    echo "<p><span style='color:#464343'>ID Transakcji:&nbsp&nbsp</span> <b> {$payment['id']} </b><br></p>";
    
    echo "</div>";
    echo "</div>";
   
    echo "<div class ='transaction-price'>";
    echo "<p><br><br><span style='color:#464343'>Kwota:&nbsp&nbsp</span> <b>{$payment['payment_amount']} </b>   </p>";
    echo "</div>";
    echo "<div class ='transaction-date'>";
    echo "<p><br><br> <span style='color:#464343'>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp  &nbsp &nbsp &nbsp Data &nbsp&nbsp</span> <b>{$payment['createdtime']}</b></p>";
    echo "</div>";
    echo "<div class ='transaction-name'>";
    echo "<p><br><br><span style='color:#464343'>Nazwa:&nbsp&nbsp</span> <b> {$payment['product_name']} </b></p>";
   
    echo "</div>";

    echo "</div>";
    echo "<hr class='hr-break'>";
}
echo "</div>";



//Nawigacja po stronach w postaci drop down menu
$query = "SELECT COUNT(*) AS total FROM payments WHERE buyer_id = {$_SESSION["user_id"]}";
$result = mysqli_query($mysqli, $query);
$row = mysqli_fetch_assoc($result);
$totalPayments = $row['total'];
$totalPages = ceil($totalPayments / $paymentsPerPage);

echo "<div class ='page-number'>";
echo "<form action='' method='GET'>";
echo "<label for='select-page'>Strona </label>";
echo "<select id = 'select-page' name='page' onchange='this.form.submit()'>";
for ($i = 1; $i <= $totalPages; $i++) {
    echo "<option value='$i'";
    if ($page == $i) {
        echo " selected";
    }
    echo ">$i</option>";
}
echo "</select>";
echo "</form>";
echo "</div>";

?>

</div>

<div class="links">
            <a class="edit-profile" href="<?php echo USER_EDIT ?>">Edytuj Profil</a>
            <a class="change-password" href="<?php echo USER_SEND_PASSWORD_RESET ?>">Zmień hasło</a>
            <a class="change-mail" href="<?php echo USER_CHANGE_EMAIL ?>">Zmień maila</a>
            <a class="user-profile" href="<?php echo USER_PROFILE ?>">Profil</a>
            <a class="deactivate-account" href="<?php echo USER_DEACTIVATE ?>">Dezaktywuj konto</a>

        </div>

<?php require "../html-elements/footer.php"?>

</body>
</html>
