<?php
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

    // Secret Key provided by Wave Money
    $secret_key = "secret-key-from-wave-money";

    $items = json_encode([
        ['name' => "Test Product 1", 'amount' => 1000],
        ['name' => "Test Product 2", 'amount' => 500]
    ]);
                                
    $hash = hash_hmac('sha256', implode("", [
        $data['timeToLiveSeconds'],
        $data['merchant_id'],
        $data['order_id'],
        $data['amount'],
        $data['backend_result_url'],
        $data['merchant_reference_id'],
    ]), $secret_key);
    
?>
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