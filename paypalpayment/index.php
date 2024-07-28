<?php 
session_start();
require "../urls.php";
include_once 'db_connection.php'; 



//sb-476ch830233880@personal.example.com - mail konta testowego
//6t=WHG^0 - hasło konta testowego 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ekran płatności Just Fit">
    <title>Oferta</title>
    <link rel="stylesheet" href="../html-elements/header.css">
    <link rel="stylesheet" href="../html-elements/footer.css">
    <link rel="stylesheet" href="../html-elements/payment/index.css">
</head>
<body class="App">
<?php require "../html-elements/header.php"?>


  <h1>Wybierz karnet</h1>
  <div class="wrapper">
    <?php 
		  $results = mysqli_query($db_conn,"SELECT * FROM products where status=1");
		  while($row = mysqli_fetch_array($results)){
    ?>
	    <div class="col__box">
	      <h2 class ="name"><?php echo $row['name']; ?></h2>
        <h3 class = "price">Cena: <span style="color:red"> <b> <?php echo $row['price']; ?> zł.</b> </span> </h3>
        <h4 class = "day_price"> <span style="color:grey"> <b> <?php echo round($row['price'] / $row['days'], 2); ?>zł</b> /dzień  </span> </h4>
        <form class="paypal" action="request.php" method="post" id="paypal_form">
          <input type="hidden" name="item_number" value="<?php echo $row['id']; ?>" >
          <input type="hidden" name="item_name" value="<?php echo $row['name']; ?>" >
          <input type="hidden" name="amount" value="<?php echo $row['price']; ?>" >
          <input type="hidden" name="currency_code" value="PLN" >
          <input type="submit" name="submit" value="Kup teraz" class="btn__default">
        </form>
	    </div>
    <?php } ?>
  </div>
  <?php require "../html-elements/footer.php"?>

</body>
</html>