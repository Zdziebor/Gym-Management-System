<?php
require "../urls.php";
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

require './autoload.php';


$enableSandbox = true;

// Ustawienia dla stworzonego konta PayPal
$paypalConfig = [
    'client_id' => 'AZ2WAPF7-mzlzW4cBTxhOt3Kpk2Z5Lk5d0293mINRIMoRCaBJYmxKXHh2YHcycRN1eBR8FZ4Q9gXixjR',
    'client_secret' => 'EGkQBpNjMgLfySyQYGdzi2ZA0AMLVtx_UV_2SlUGLkXhEpUbNa5e816HkynAVyoVlukQiPMYF-vbj6gD',
    'return_url' => 'http://localhost/pracainz/paypalpayment/response.php',
    'cancel_url' => 'http://localhost/pracainz/paypalpayment/payment-cancelled.php'
];

//Ustawienia bazy danych
$dbConfig = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'name' => 'gymdb'
];

$apiContext = getApiContext($paypalConfig['client_id'], $paypalConfig['client_secret'], $enableSandbox);

/**
 * Set up a connection to the API
 *
 * @param string $clientId
 * @param string $clientSecret
 * @param bool   $enableSandbox Sandbox mode toggle, true for test payments
 * @return \PayPal\Rest\ApiContext
 */
function getApiContext($clientId, $clientSecret, $enableSandbox = false)
{
    $apiContext = new ApiContext(
        new OAuthTokenCredential($clientId, $clientSecret)
    );

    $apiContext->setConfig([
        'mode' => $enableSandbox ? 'sandbox' : 'live'
    ]);

    return $apiContext;
}
