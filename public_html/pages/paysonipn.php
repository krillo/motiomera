<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once '../php/libs/payson/paysonapi.php';
// Get the POST data
$postData = file_get_contents("php://input");

// Set up API
$credentials = new PaysonCredentials("11654", "a86549bf-cb16-41e8-a86d-f1c79522becd");
$api = new PaysonApi($credentials);

// Validate the request
$response =  $api->validate($postData);

if($response->isVerified()){
    // IPN request is verified with Payson

    // Check details to find out what happened with the payment
    $details = $response->getPaymentDetails();
}

?>