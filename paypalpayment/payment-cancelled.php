<?php 
require "../urls.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Płatność Anulowana</title>
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/payment/payment-cancelled.css">
</head>
<body>
<?php require "../html-elements/header.php"?>

    <div class="content">
    <div class="image-2">
            <img src="../assets/payment/payment-cancelled.png">
</div>
	<h1 class = "text-1">Transakcja anulowana</h1>
    <p class = "text-2">Transakcja została anulowana. <br>Twojego konto nie zostało obciążone <br> W przypadku pytań, <a href="http://localhost/pracainz/contact/"> skontaktuj się z nami</span> </p> <br>
    <a class = "payment-button" href="http://localhost/pracainz/paypalpayment">Powrót do płatności</a>

</div>

    <?php require "../html-elements/footer.php"?>

</body>
</html>
