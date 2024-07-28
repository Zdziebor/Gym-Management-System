<?php

session_start();

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

require 'config.php';

if (empty($_GET['paymentId']) || empty($_GET['PayerID'])) {
    die('The response is missing the paymentId and PayerID');
}

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
    $user = $result->fetch_assoc();
}


$paymentId = $_GET['paymentId'];
$payment = Payment::get($paymentId, $apiContext);

$execution = new PaymentExecution();
$execution->setPayerId($_GET['PayerID']);

try {
    //Pobranie płatności
    $payment->execute($execution, $apiContext);

    try {
        $db = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['name']);

        $payment = Payment::get($paymentId, $apiContext);

        $data = [
            'product_id' => $payment->transactions[0]->item_list->items[0]->sku,
            'transaction_id' => $payment->getId(),
            'payment_amount' => $payment->transactions[0]->amount->total,
            'currency_code' => $payment->transactions[0]->amount->currency,
            'payment_status' => $payment->getState(),
            'invoice_id' => $payment->transactions[0]->invoice_number,
            'product_name' => $payment->transactions[0]->item_list->items[0]->name,
            'description' => $payment->transactions[0]->description,
        ];
        if (addPayment($data) !== false && $data['payment_status'] === 'approved') {
            //Płatność pobrana prawidłowo, przekieruj do strony finalizacji płatności i wyślij maila
            $inserids = $db->insert_id;

            $mail = require __DIR__ . "/../mailer.php";

            $mail->setFrom("noreply@example.com");
            $mail->addAddress($user["email"]);
            $mail->Subject = "Dokonanie platnosci";
            $mail->Body = <<<END

Platnosc dokonana



END;

            $mail->send();


            header("location:http://localhost/pracainz/paypalpayment/PaypalSuccess.php?payid=$inserids");
            exit(1);
        } else {
            //Błąd płatności
            header("location:http://localhost/pracainz/paypalpayment/PaypalFailed.php");
            exit(1);
        }
    } catch (Exception $e) {
        //Błąd płatności 

    }
} catch (Exception $e) {
    //Błąd płatności 

}

/**
 * Dodanie płatności do bazy danych
 *
 * @param array $data Payment data
 * @return int|bool ID of new payment or false if failed
 */
function addPayment($data)
{
    global $db;
    $currentDate = date('Y-m-d H:i:s');



    if (is_array($data)) {
        $stmt = $db->prepare('INSERT INTO `payments` (product_id,transaction_id, buyer_id, payment_amount,currency_code, payment_status, invoice_id, product_name, createdtime) 
        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param(
            'isidsssss',
            $data['product_id'],
            $data['transaction_id'],
            $_SESSION['user_id'],
            $data['payment_amount'],
            $data['currency_code'],
            $data['payment_status'],
            $data['invoice_id'],
            $data['product_name'],
            $currentDate

        );
        $stmt->execute();
        $stmt->close();

        return $db->insert_id;
    }

    return false;
}
