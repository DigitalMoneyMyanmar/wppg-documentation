<img src="./paywave.png" style="zoom:67%;" />



#  1. Introduction

Pay with Wave Redirect API is an online payment solution by Digital Money Myanmar Limited (Wave Money) that allows online merchants to accept payments securely and instantly.

This document covers the essential information, processes, and other relevant aspects for merchant technical integration with the Pay with Wave Platform. This document contains confidential information and is not intended to be viewed by unapproved external parties. All information and processes in the document are subject to edits and changes from Wave Money.



# 2. Technical Integration Aspect

## 2.1 How it works

WavePay Payment Gateway allows merchant to integrate to WavePay Payment Gateway with minimum effort and enjoy the full benefits of enhanced security and full suite of payment options. Accepting payment is easier with WavePay Payment Gateway.



<img src="./how-it-works.png" style="zoom:67%;" />



## 2.2 Security

Merchants who wish to have access to the API are required to provide the client_id and client_secret, which they Requests to access the API is only accepted on presentation of the correct credentials. All requests from merchants systems to WavePay Payment Gateway API take place over HTTPS. Certificate for HTTPS endpoints for callback URL need to be from recognized Certificate Authorities (CAs), i.e., not self-signed and must implemented with standard port 443. 



## 2.3 Environment

Merchants can use the testing environment to do their functional integrations. Once the integration testing in test environment is finished, the switch to our production system can be made. This mean that all endpoints for both environments and the credentials have to be obtained for both. 

| **Environment** | **URL**                                      |
| --------------- | -------------------------------------------- |
| Testing         | https://devpayment.wavemoney.io/payment:8107 |
| Production      | https://payment.wavemoney.io/payment         |

We will be provided the Client ID and Client Secret to access both environments. 



## 2.4 Payment Request

 The Header of Payment Request Header will have the following content.

| **Name**              | **Description**                                  | **Type** | **Mandatory** |
| --------------------- | ------------------------------------------------ | -------- | ------------- |
| merchant_id           | Merchant ID provided by Wave Money               | string   | Mandatory     |
| order_id              | Order ID provided by Merchant                    | string   | Mandatory     |
| merchant_reference_id | Unique ID for every transaction                  | string   | Mandatory     |
| frontend_result_url   | Merchant's Website URL                           | string   | Mandatory     |
| backend_result_url    | Merchant's Web Service callback URL              | string   | Mandatory     |
| amount                | Total Amount                                     | string   | Mandatory     |
| timeToLiveSeconds     | Time to Live for transaction ( seconds )         | string   | Man           |
| payment_description   | Payment Description to display on Payment Screen | string   | Mandatory     |
| merchant_name         | Merchant Name to display on Payment Screen       | string   | Mandatory     |
| items                 | Items to display on Payment Screen               | string   | Mandatory     |
| Hash                  | Needed for Hash Validating                       | string   | Mandatory     |

 

# 3. How to integrate

WavePay Payment Gateway allows merchant to integrate to WavePay Payment Gateway with minimum effort and enjoy the full benefits of enhanced security and full suite of payment options. Accepting payment is easier with WavePay Payment Gateway.



## 3.1 Prepare Pay with Wave button

First, setup Merchant Credentials and Payload that require for Request

```php
$data = [
    // Time to Live for Transaction in seconds
    'timeToLiveSeconds' => 5000,

    // string - Merchant Name for Payment Screen
    'merchant_name' => "Wave Merchant",

    // string - Merchant id provided by Wave Money
    'merchant_id' => "test",

    // unsigned integer - Order id provided Merchant
    'order_id' => rand(1000000, 9999999),

    // unsigned integer - Total Amount of transaction
    'amount' => 50,

    // string - mendatory backend url for Payment Service
    'backend_result_url' => "https://wave-merchant.com/backend-callback",

    // string - mendatory frontend url for Payment Service
    'frontend_result_url' => "https://wave-merchant.com",

    // string - Unique Merchant Reference ID for Transaction
    'merchant_reference_id' => "wavemerchant-" . rand(1000000, 9999999),

    // string - Payment Description for Payment Screen from Merchant
    'payment_description' => "Buying things from Wave Merchant"
];
```



then prepare Items to display in our Payment Screen.

```php
$items = json_encode([
		['name' => "Test Product 1", 'amount' => 1000],
  	['name' => "Test Product 2", 'amount' => 500]
]);
```



Secret Key provided by Wave Money

```php
$secret_key = "f6298a18c678e5e683f407169c59e721ff6bd33b1995d74e78039f4fca0b8044";
```



Generate hash that require for Payload.

```php
$hash = hash_hmac('sha256', implode("", [
    $data['timeToLiveSeconds'],
    $data['merchant_id'],
    $data['order_id'],
    $data['amount'],
    $data['backend_result_url'],
    $data['merchant_reference_id'],
]), $secret_key);
```



Use  those Payload and required Parameters in an HTML Form to request for Payment to Wave.

```php+HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wave Merchant Integration</title>
</head>
<body>
    <form action="https://pww.test/payment" method="POST">
        <input type="hidden" name="timeToLiveSeconds" value="<?php echo $data['timeToLiveSeconds']; ?>">
        <input type="hidden" name="merchant_id" value="<?php echo $data['merchant_id']; ?>">
        <input type="hidden" name="order_id" value="<?php echo $data['order_id']; ?>">
        <input type="hidden" name="merchant_reference_id" value="<?php echo $data['merchant_reference_id']; ?>">
        <input type="hidden" name="frontend_result_url" value="<?php echo $data['frontend_result_url']; ?>">
        <input type="hidden" name="backend_result_url" value="<?php echo $data['backend_result_url']; ?>">
        <input type="hidden" name="amount" value="<?php echo $data['amount']; ?>">
        <input type="hidden" name="payment_description" value="<?php echo $data['payment_description']; ?>">
        <input type="hidden" name="merchant_name" value="<?php echo $data['merchant_name']; ?>">
        <input type="hidden" name="items" value='<?php echo $items; ?>'>
        <input type="hidden" name="hash" value="<?php echo $hash; ?>">
        <button class="btn btn-primary">Pay with Wave</button>
    </form>
</body>
</html>
```



### Call Back

Wave System will call the “call-back URL” that will be provided in the request form.

| HTTP Method | POST             |
| ----------- | ---------------- |
| Type        | application/json |



### Request JSON Example

```json
{
  "status": "PAYMENT_CONFIRMED",
  "merchantId": "food2u",
  "orderId": "7",
  "merchantReferenceId": "7",
  "frontendResultUrl": "http://test-dev:8080/frontendurl",
  "backendResultUrl": "http://test-dev:8080/callbackurl",
  "initiatorMsisdn": "9791009080",
  "amount": 1,
  "timeToLiveSeconds": 300,
  "paymentDescription": "shirts for Men",
  "currency": "MMK",
  "hashValue": "29e9486e727ac0e4f185c3b757cf8892e59eb8d292c23f11d13926bb0bdae798",
  "additionalField1": null,
  "additionalField2": null,
  "additionalField3": null,
  "additionalField4": null,
  "additionalField5": null,
  "transactionId": "360",
  "paymentRequestId": "360",
  "requestTime": "2019-11-06T15:38:56"  
}
```

 

| Property Name       | Property          | Description                                                  |
| ------------------- | ----------------- | ------------------------------------------------------------ |
| Status              | mandatory         | Please see the status description below                      |
| merchantId          | mandatory         | merchant_id that will be defined and agreed on both sides. example - food2u, sgshop |
| orderId             | optional          | kinda `invoice_id` from merchant side. there will be only one `order_id` for one order whilst there can be many `merchantReferenceId` |
| merchantReferenceId | mandatory, unique | It should be a unique string every call.                     |
| frontendResultUrl   | mandatory         | redirect url reponse to web                                  |
| backendResultUrl    | mandatory         | call back url to merchant server                             |
| initiatorMsisdn     | mandatory         | purchaser msisdn                                             |
| amount              | mandatory         | total amount                                                 |
| timeToLiveSeconds   | mandatory         | time out amount. Limit 10 minutes                            |
| paymentDescription  | optional          | a brief title or description of the buying item. example - 3 Tuna Sandwiches |
| currency            | optional          |                                                              |
| transactionId       |                   | MFS billcollect transactionId                                |
| paymentRequestId    |                   | payment_request primary key                                  |
| requestTime         |                   | now() -- serverTime                                          |
| hashValue           |                   | hash_hmac(merchantId+orderId+amount+backendResultUrl+merchantReferenceId+initiatorMsisdn+transactionId+paymentRequestId+requestTime) -- with hash secret key |



```
status   

{ 

  OTP_REQUESTED 

  OTP_CONFIRMED 

  PAYMENT_INITIATED 

  PAYMENT_CONFIRMED 

  PAYMENT_REQUEST_CANCELLED 

  INVALID_HASH 

  OTP_GENERATION_FAILED 

  OTP_CONFIRMATION_FAILED 

  INSUFFICIENT_BALANCE 

  INVALID_PIN 

  ACCOUNT_LOCKED 

  BILL_COLLECTION_FAILED -- errors apart from INSUFFICIENT_BALANCE,INVALID_PIN,ACCOUNT_LOCKED 

  PAYMENT_CALLBACK_FAILED 

  PAYMENT_CALLBACK_SUCCESS 

  PAYMENT_CONFIRMED 

  TRANSACTION_TIMED_OUT -- transaction has timed out when committing the payment 

  PAYMENT_RETRIEVAL_FAILED -- issue in retrieving payment details 

  MERCHANT_RETRIEVAL_FAILED -- issue in retrieving merchant details 

} 
```



# 4. FAQs and Troubleshooting 

## As a Customer 

1. When I am at checkout/confirmation page and select “Pay with Wave Money”, do I need to enter full Wave Money Account Mobile Number? 

    - No, customer only required to enter Mobile Number prefix with ‘9’, mobile number prefix ‘0’ and country code ‘(+95)’ are not required. 

    - For e.g. 979100999,978560801 

 

## As a Developer  

1. Does Wave Money take responsibility for the communication between client & server? 
    - No, communication between the client (web/app) and the server (merchant server) is the developer responsibility. 

 

2. Can I share Client_ID and Client_secret to publicly? 
    - No, you should not your Client_ID and Client_Secret to publicly, you must be kept secret and never appear anywhere publicly, otherwise anyone could make transactions on your behalf. 

