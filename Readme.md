# PHP Laravel SDK

Read up here to get started and understand the payment flow.

PHP Integration Guide: https://youtu.be/8n43-5REBQY?si=7BoAKlO5Yw2X4BYg

Sample app: https://github.com/pluralonline/plural-php-laravel-sdk-sampleapp

### Prerequisites
- A minimum of PHP 7.4 up to 8.1


## Installation

-   If your project using composer, run the below command

```
composer require pinelabs/php
```


### Note:
This PHP library follows the following practices:

- Composer Install / Update
- Namespaced under `Pinelabs\Php`
- API throws exceptions instead of returning errors
- Options are passed as an array instead of multiple arguments wherever possible

## Documentation

## Basic Usage

Supported Features

- Create Order
- Fetch Order
- EMI Calculator
- Hash Verification

####  Only Non-Seamless Integration API Supported

    This SDK only supports nonseamless integration, by nonseamless we mean the merchant will always need to redirect the end user to payment gateways where he'll select his preferred payment method and complete payment.



API ENDPOINT :   
```php
[ UAT ] https://uat.pinepg.in/api/

[ PROD ] https://pinepg.in/api/

```

#### Test Merchant Details
```php

$merchantId = "106600";
$apiAccessCode = "bcf441be-411b-46a1-aa88-c6e852a7d68c";
$secret = "9A7282D0556544C59AFE8EC92F5C85F6";
$isTestMode = true;  // false for production ( default false )
```

Create an API instance 
```php
use \Pinelabs\Php\API;

$api = new API($merchantId, $apiAccessCode, $secret, $isTestMode);

```

## 1. Create order API

### Body Parameters

Txn (Order) Data ( Mandatory )
```php
$txn_data = [
    'txn_id' => 'orderId12345',  //Mandatory (unique id)
    'callback' => 'https://httpbin.org/post',  //Mandatory
    'amount_in_paisa' => "10000" // Mandatory  ( amount in paisa )
];
```

Customer Details ( Optional )
```php
$customer_data = [
    'customer_id' =>'custId123',  // Optional 
    'first_name' => 'Ramsharan',  // Optional 
    'last_name' => 'Yadav',  // Optional 
    'email_id' => 'ramsharan@mcsam.in',  // Optional 
    'mobile_no' => '7737291210'  // Optional 
];
```
Billing Details ( Optional )
```php
$billing_data = [
    'address1' => 'mcsam',  // Optional 
    'address2' => 'mm tower',  // Optional 
    'address3' => 'sector 18',  // Optional 
    'pincode' => '122018',  // Optional 
    'city' => 'Gurgaon',  // Optional 
    'state' => 'Haryana',  // Optional 
    'country' => 'India',  // Optional 
];
```

Shipping Details ( Optional )
```php
$shipping_data = [
    'first_name' => 'Ramsharan',  // Optional 
    'last_name' => 'Yadav',  // Optional 
    'mobile_no' => '7737291210',  // Optional 
    'address1' => 'somewhere',  // Optional 
    'address2' => 'mm tower',  // Optional 
    'address3' => 'sector 18',  // Optional 
    'pincode' => '122018',  // Optional 
    'city' => 'Gurgaon',  // Optional 
    'state' => 'Haryana',  // Optional 
    'country' => 'India',  // Optional 
];
```

Udf Fields ( Optional )
```php
$udf_data = [
    'udf_field_1' => 'udf1',  // Optional 
    'udf_field_2' => 'udf2',  // Optional 
    'udf_field_3' => 'udf3',  // Optional 
    'udf_field_4' => 'udf4',  // Optional 
    'udf_field_5' => 'udf5',  // Optional 
];
```

Payment Mode ( Mandatory ) 
```php
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
// Mandatory 

// In these payment modes, the merchant can choose multiple modes that should be enabled on his merchant ID.

```

Product Details (Optional)
```php
$products_data = [
    [
        "product_code" => "testproduct02",  // Optional 
        "product_amount" => 10000  // Optional 
    ],
    .....
];

  // Accept multiple Array 
```
#
#

Create a payment 
```php
$response = $api->Payment()->Create($txn_data, $customer_data, $billing_data, $shipping_data, $udf_data, $payment_modes, $products_data);


echo '<pre>'; print_r($response); die;
```

### Response 

Success Response
``` php
Array
(
    [status] => 1
    [redirect_url] => https://uat.pinepg.in/pinepg/v2/process/payment?token=S01wPSlIH%2bopelRVif7m7e4SgrTRIcKYx25YDYfmgtbPOE%3d
)
```

Failure Response : 

``` php
Fatal error: Uncaught Exception: MERCHANT PASSWORD DOES NOT MATCH
```



## 2. Fetch Order API

### Body Parameters

Order Id ( Mandatory )
```php
$orderId = "orderId12345";

// The order ID which was sent by the user as a unique transaction ID while creating the order will be passed here.
```

#
#


Fetch a payment 
```php
$response = $api->Payment()->Fetch($orderId);

echo '<pre>'; print_r($response); die;
```

### Response

Success Response
```php
{"ppc_MerchantID":"106600","ppc_MerchantAccessCode":"bcf441be-411b-46a1-aa88-c6e852a7d68c","ppc_PinePGTxnStatus":"7","ppc_TransactionCompletionDateTime":"20\/09\/2023 04:07:52 PM","ppc_UniqueMerchantTxnID":"650acb67d3752","ppc_Amount":"1000","ppc_TxnResponseCode":"1","ppc_TxnResponseMessage":"SUCCESS","ppc_PinePGTransactionID":"12069839","ppc_CapturedAmount":"1000","ppc_RefundedAmount":"0","ppc_AcquirerName":"BILLDESK","ppc_DIA_SECRET":"D640CFF0FCB8D42B74B1AFD19D97A375DAF174CCBE9555E40CC6236964928896","ppc_DIA_SECRET_TYPE":"SHA256","ppc_PaymentMode":"3","ppc_Parent_TxnStatus":"4","ppc_ParentTxnResponseCode":"1","ppc_ParentTxnResponseMessage":"SUCCESS","ppc_CustomerMobile":"7737291210","ppc_UdfField1":"","ppc_UdfField2":"","ppc_UdfField3":"","ppc_UdfField4":"","ppc_AcquirerResponseCode":"0300","ppc_AcquirerResponseMessage":"NA"}
```

Failure Response 
```php
Fatal error: Uncaught Exception: INVALID DATA
```

IF Merchant Details Incorrect Then Return Response
```php
"IP Access Denied"
```


## 3. EMI Calculator API

### Body Parameters

Txn (Order) Data ( Mandatory )
```php
$txn_data = [
    'amount_in_paisa' => "10000" // Mandatory  ( amount in paisa ) and sum of product amount
];
```

Product Details (Optional)
```php
$products_data = [
    [
        "product_code" => "testproduct02",  // Mandatory 
        "product_amount" => 10000  // Mandatory 
    ]
];

  // Accept only one Array 
```
#
#

Call EMI Calculator 
```php
$response = $api->EMI()->Calculator($txn_data, $products_data);

echo '<pre>'; print_r($response); die;
```


### Response

Success Response

```php
{"issuer":[{"list_emi_tenure":[{"offer_scheme":{"product_details":[{"schemes":[],"product_code":"testproduct02","product_amount":10000,"subvention_cashback_discount":0,"product_discount":0,"subvention_cashback_discount_percentage":0,"product_discount_percentage":0,"subvention_type":3,"bank_interest_rate_percentage":150000,"bank_interest_rate":251}],"emi_scheme":{"scheme_id":48040,"program_type":105,"is_scheme_valid":true}},"tenure_id":"3","tenure_in_month":"3","monthly_installment":3417,"bank_interest_rate":150000,"interest_pay_to_bank":251,"total_offerred_discount_cashback_amount":0,"loan_amount":10000,"auth_amount":10000},{"offer_scheme":{"product_details":[{"schemes":[],"product_code":"testproduct02","product_amount":10000,"subvention_cashback_discount":0,"product_discount":0,"subvention_cashback_discount_percentage":0,"product_discount_percentage":0,"subvention_type":3,"bank_interest_rate_percentage":150000,"bank_interest_rate":440}],"emi_scheme":{"scheme_id":48040,"program_type":105,"is_scheme_valid":true}},"tenure_id":"6","tenure_in_month":"6","monthly_installment":1740,"bank_interest_rate":150000,"interest_pay_to_bank":440,"total_offerred_discount_cashback_amount":0,"loan_amount":10000,"auth_amount":10000},{"offer_scheme":{"product_details":[{"schemes":[],"product_code":"testproduct02","product_amount":10000,"subvention_cashback_discount":0,"product_discount":0,"subvention_cashback_discount_percentage":0,"product_discount_percentage":0,"subvention_type":3,"bank_interest_rate_percentage":150000,"bank_interest_rate":629}],"emi_scheme":{"scheme_id":48040,"program_type":105,"is_scheme_valid":true}},"tenure_id":"9","tenure_in_month":"9","monthly_installment":1181,"bank_interest_rate":150000,"interest_pay_to_bank":629,"total_offerred_discount_cashback_amount":0,"loan_amount":10000,"auth_amount":10000},{"offer_scheme":{"product_details":[{"schemes":[],"product_code":"testproduct02","product_amount":10000,"subvention_cashback_discount":0,"product_discount":0,"subvention_cashback_discount_percentage":0,"product_discount_percentage":0,"bank_interest_rate_percentage":0,"bank_interest_rate":0}],"emi_scheme":{"scheme_id":48040,"program_type":105,"is_scheme_valid":true}},"tenure_id":"96","tenure_in_month":"1","monthly_installment":0,"bank_interest_rate":0,"interest_pay_to_bank":0,"total_offerred_discount_cashback_amount":0,"loan_amount":10000,"auth_amount":10000}],"issuer_name":"HDFC","is_debit_emi_issuer":false},{"list_emi_tenure":[{"offer_scheme":{"product_details":[{"schemes":[],"product_code":"testproduct02","product_amount":10000,"subvention_cashback_discount":0,"product_discount":0,"subvention_cashback_discount_percentage":0,"product_discount_percentage":0,"subvention_type":3,"bank_interest_rate_percentage":140000,"bank_interest_rate":233}],"emi_scheme":{"scheme_id":48048,"program_type":105,"is_scheme_valid":true}},"tenure_id":"3","tenure_in_month":"3","monthly_installment":3411,"bank_interest_rate":140000,"interest_pay_to_bank":233,"total_offerred_discount_cashback_amount":0,"loan_amount":10000,"auth_amount":10000},{"offer_scheme":{"product_details":[{"schemes":[],"product_code":"testproduct02","product_amount":10000,"subvention_cashback_discount":0,"product_discount":0,"subvention_cashback_discount_percentage":0,"product_discount_percentage":0,"subvention_type":3,"bank_interest_rate_percentage":150000,"bank_interest_rate":440}],"emi_scheme":{"scheme_id":48048,"program_type":105,"is_scheme_valid":true}},"tenure_id":"6","tenure_in_month":"6","monthly_installment":1740,"bank_interest_rate":150000,"interest_pay_to_bank":440,"total_offerred_discount_cashback_amount":0,"loan_amount":10000,"auth_amount":10000},{"offer_scheme":{"product_details":[{"schemes":[],"product_code":"testproduct02","product_amount":10000,"subvention_cashback_discount":0,"product_discount":0,"subvention_cashback_discount_percentage":0,"product_discount_percentage":0,"subvention_type":3,"bank_interest_rate_percentage":140000,"bank_interest_rate":584}],"emi_scheme":{"scheme_id":48048,"program_type":105,"is_scheme_valid":true}},"tenure_id":"9","tenure_in_month":"9","monthly_installment":1176,"bank_interest_rate":140000,"interest_pay_to_bank":584,"total_offerred_discount_cashback_amount":0,"loan_amount":10000,"auth_amount":10000},{"offer_scheme":{"product_details":[{"schemes":[],"product_code":"testproduct02","product_amount":10000,"subvention_cashback_discount":0,"product_discount":0,"subvention_cashback_discount_percentage":0,"product_discount_percentage":0,"subvention_type":3,"bank_interest_rate_percentage":150000,"bank_interest_rate":824}],"emi_scheme":{"scheme_id":48048,"program_type":105,"is_scheme_valid":true}},"tenure_id":"12","tenure_in_month":"12","monthly_installment":902,"bank_interest_rate":150000,"interest_pay_to_bank":824,"total_offerred_discount_cashback_amount":0,"loan_amount":10000,"auth_amount":10000},{"offer_scheme":{"product_details":[{"schemes":[],"product_code":"testproduct02","product_amount":10000,"subvention_cashback_discount":0,"product_discount":0,"subvention_cashback_discount_percentage":0,"product_discount_percentage":0,"bank_interest_rate_percentage":0,"bank_interest_rate":0}],"emi_scheme":{"scheme_id":48048,"program_type":105,"is_scheme_valid":true}},"tenure_id":"96","tenure_in_month":"1","monthly_installment":0,"bank_interest_rate":0,"interest_pay_to_bank":0,"total_offerred_discount_cashback_amount":0,"loan_amount":10000,"auth_amount":10000}],"issuer_name":"ICICI","is_debit_emi_issuer":false},{"list_emi_tenure":[{"offer_scheme":{"product_details":[{"schemes":[],"product_code":"testproduct02","product_amount":10000,"subvention_cashback_discount":0,"product_discount":0,"subvention_cashback_discount_percentage":0,"product_discount_percentage":0,"bank_interest_rate_percentage":0,"bank_interest_rate":0}],"emi_scheme":{"scheme_id":48043,"program_type":105,"is_scheme_valid":true}},"tenure_id":"96","tenure_in_month":"1","monthly_installment":0,"bank_interest_rate":0,"interest_pay_to_bank":0,"total_offerred_discount_cashback_amount":0,"loan_amount":10000,"auth_amount":10000}],"issuer_name":"Kotak Debit","is_debit_emi_issuer":true}],"response_code":1,"response_message":"SUCCESS"}
```


Failure Response 
```php
Fatal error: Uncaught Exception: INVALID DATA,MISMATCH_IN_TOTAL_CART_AMOUNT_AND_TOTAL_PRODUCT_AMOUNT
```


## 4. Hash Verification

### Body Parameters

Hash ( Mandatory )
```php
$receviedHash = "475373549378937GJDFJGD8456834XCJBXJ4538VB67485";

// The hash received in response from Pinelabs.
```

Response Send In verify the request to create a new hash ( Mandatory )

```php

//After removing the hash and the hash type from the response received from Pinelabs, we will send the response to the entity specified in the request hash.

// Sample Callback Response
$requestData = Array
(
    [merchant_id] => 106600
    [merchant_access_code] => bcf441be-411b-46a1-aa88-c6e852a7d68c
    [unique_merchant_txn_id] => 650c8d8ea61a0
    [pine_pg_txn_status] => 4
    [txn_completion_date_time] => 22/09/2023 12:08:29 AM
    [amount_in_paisa] => 10000
    [txn_response_code] => 1
    [txn_response_msg] => SUCCESS
    [acquirer_name] => BILLDESK
    [pine_pg_transaction_id] => 12072123
    [captured_amount_in_paisa] => 10000
    [refund_amount_in_paisa] => 0
    [payment_mode] => 3
    [mobile_no] => 7737291210
    [udf_field_1] => 
    [udf_field_2] => 
    [udf_field_3] => 
    [udf_field_4] => 
    [Acquirer_Response_Code] => 0300
    [Acquirer_Response_Message] => NA
    [parent_txn_status] => 
    [parent_txn_response_code] => 
    [parent_txn_response_message] =>
)
```

#
#

Varify Hash
```php
$varify = $api->Hash()->Verify($receviedHash, $requestData);

echo $varify;
```

### Response

Success Response
```php
true
```

Failure Response
```php
false
```

# Integration Best Practices

Best practices to put into effect for a smooth and secure integration with Plural:

1.	 Signature Verification to avoid data tampering:
This is a mandatory step to confirm the authenticity of the details returned to you on the return URL for successful payments.
- Convert the response received on the return URL into a string (remove secret and secret_type params)
- Sort the string alphabetically
- Hash the payload with your secret key using SHA256
- Match the generated signature with the one received in the response from Plural

2.	 Check payment status before providing services:
Check if the payment status is in the success state .i.e. : ppc_Parent_TxnStatus = 4 and ppc_ParentTxnResponseCode = 1 before providing the services to the customers
- One Inquiry API call (Fetch payment using ppc_UniqueMerchantTxnID) right after the Transaction
- Run Inquiry API periodically for the payments in initiated state

3.	 Webhook Implementation:
Implement webhooks to avoid callback failures (drop offs due to connectivity/network issues)
- Payment.captured
- Payment.failed

4.	TLS Version
We support TLS_v_1.2 and above which is strongly recommended. Kindly ensure you are using higher TLS versions to avoid any transaction failures.





