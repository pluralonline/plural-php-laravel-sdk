<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __dir__.'/../vendor/autoload.php';

use \Pinelabs\Php\API;

$merchantId = "106600";
$apiAccessCode = "bcf441be-411b-46a1-aa88-c6e852a7d68c";
$secret = "9A7282D0556544C59AFE8EC92F5C85F6";
$isTestMode = true;

$api = new API($merchantId, $apiAccessCode, $secret, $isTestMode);

$callback_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https" : "http" . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'callback.php';

$txn_data = [
    'txn_id' => uniqid(),
    'callback' => $callback_url,
    'amount_in_paisa' => "10000" // amount in paisa
];

$customer_data = [
    'customer_id' => uniqid(),
    'first_name' => 'firstname',
    'last_name' => 'lastname',
    'email_id' => 'test@test.com',
    'mobile_no' => '9999999999'
];

$billing_data = [
    'address1' => '',
    'address2' => '',
    'address3' => '',
    'pincode' => '',
    'city' => '',
    'state' => '',
    'country' => '',
];

$shipping_data = [
    'first_name' => '',
    'last_name' => '',
    'mobile_no' => '',
    'address1' => '',
    'address2' => '',
    'address3' => '',
    'pincode' => '',
    'city' => '',
    'state' => '',
    'country' => '',
];

$udf_data = [
    'udf_field_1' => '',
    'udf_field_2' => '',
    'udf_field_3' => '',
    'udf_field_4' => '',
    'udf_field_5' => '',
];

$payment_modes = [
    'cards' => true,
    'netbanking' => true,
    'wallet' => true,
    'upi' => true, 
    'emi' => false,
    'debit_emi' => false,
    'cardless_emi' => false,
    'bnpl' => false,
    'prebooking' => false,
];

$products_data = [
    [
        "product_code" => "testproduct02",
        "product_amount" => 10000
    ]
];



$response = 'Please pass emi or fetch in url as query parameter';
if(isset($_GET['emi'])){
    $response = $api->EMI()->Calculator($txn_data, $products_data);
}
if(isset($_GET['fetch'])){
    $response = $api->Payment()->Fetch('650d5ada7fa98');
}

echo '<pre>'; 
print_r($response);
