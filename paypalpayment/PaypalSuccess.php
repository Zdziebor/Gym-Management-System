<?php 
session_start();
require "../urls.php";
include_once 'db_connection.php'; 

if (!isset($_SESSION["user_id"])){
    header("Location: " . LOGIN_LOGIN);
}


// Sprawdzenie czy paymentid jest ustawione i nie jest puste
if(isset($_GET['payid']) && !empty($_GET['payid'])) {
    // Przygotuj zapytanie
    $stmt = $db_conn->prepare("SELECT * FROM payments WHERE id = ?");
    // Przypisz parametry
    $stmt->bind_param("i", $_GET['payid']);
    // Wykonaj zapytanie
    $stmt->execute();
    // Rezultaty
    $result = $stmt->get_result();
    // Wyciągnij rezultaty do zmiennej
    $row = $result->fetch_assoc();
    // Zamknij zapytanie
    $stmt->close();
} else {
    // Przerwij jeśli paymentid nie zostało podane
    exit("Payment ID is missing.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Płatność udana</title>
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/payment/paypalsuccess.css">
</head>
<body class="App">
<?php require "../html-elements/header.php"?>
  <?php if ($row['buyer_id'] == $_SESSION["user_id"]):?>
      <div class="content">


      <div class="image-2">
            <img src="../assets/payment/paypalsuccess.png">
</div>

              <h1>Dziękujemy za zakup!</h1>
              <div class="payment_info">
              <div class = "invoice_id">
              <p> <br><span style="color:#979797"> Numer zamówienia </span> <br><?php echo $row['invoice_id']; ?></p>
              </div>
              <div class = "transaction_id">
              <p><br><span style="color:#979797">ID Transakcji </span> <br><?php echo $row['transaction_id']; ?></p>
              </div>
              <div class = "payment_amount">
              <p><br><span style="color:#979797">Kwota zapłacona</span> <br><?php echo $row['payment_amount'];  ?> zł.</p>
              </div>
              <div class = "product_name">
              <p><br><span style="color:#979797">Nazwa produktu </span> <br><?php echo $row['product_name']; ?></p>
              </div>
              <div class = "product_id">
              <p><br><span style="color:#979797">ID Produktu </span> <br><?php echo $row['product_id']; ?></p>
              </div>
              </div>
         
              <a class = "payment-button" href="http://localhost/pracainz/paypalpayment/">Powrót do płatności</a>

      </div>



          <?php else: ?>
            <p>Brak dostępu</p>
            <?php endif; ?>
            <?php require "../html-elements/footer.php"?>

</body>
</html>
