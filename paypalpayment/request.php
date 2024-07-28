<?php

session_start();


use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\ItemList;

require 'config.php';

if (empty($_POST['item_number'])) {
    die("Something went wrong");
}


if (!isset($_SESSION["user_id"])) {
    header("Location: http://localhost/pracainz/login/login.php");
    die("Login required");

}

//Sprawdzenie, czy wybrany produkt istnieje w bazie danych. 
$mysqli = require __DIR__ . "/../config.php";

$sql = "SELECT id, price, name FROM products";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Database error");
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

var_dump($products);

$pairExists = false;
foreach ($products as $product) {
    if ($product['id'] == $_POST['item_number'] && $product['price'] == $_POST['amount'] && $product['name'] == $_POST['item_name']) {
        $pairExists = true;
        break;
    }
}

if (!$pairExists) {
    die("Invalid Product");
} 






$payer = new Payer();
$payer->setPaymentMethod('paypal');

// Pobierz dane płatności
$currency = 'PLN';
$item_qty = 1;
$amountPayable = $_POST['amount'];
$product_name = $_POST['item_name'];
$item_code = $_POST['item_number'];
$description = 'Paypal transaction';
$invoiceNumber = uniqid();
$my_items = array(
	array('name'=> $product_name, 'quantity'=> $item_qty, 'price'=> $amountPayable, 'sku'=> $item_code, 'currency'=> $currency)
);


	
$amount = new Amount();
$amount->setCurrency($currency)
    ->setTotal($amountPayable);

$items = new ItemList();
$items->setItems($my_items);
	
$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setDescription($description)
    ->setInvoiceNumber($invoiceNumber)
	->setItemList($items);

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl($paypalConfig['return_url'])
    ->setCancelUrl($paypalConfig['cancel_url']);

$payment = new Payment();
$payment->setIntent('sale')
    ->setPayer($payer)
    ->setTransactions([$transaction])
    ->setRedirectUrls($redirectUrls);

try {
    $payment->create($apiContext);
} catch (Exception $e) {
    throw new Exception('Unable to create link for payment');
}

header('location:' . $payment->getApprovalLink());
exit(1);