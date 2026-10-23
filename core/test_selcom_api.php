<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$vendor = 'SW00170027';
$apiKey = 'TILL61237100-62ceeea68ed6426a83e640c5950d117d';
$apiSecret = '469b1f-f26748-4f6ca1-18b224-b4bde4-1e';
$baseUrl = 'https://apigw.selcommobile.com/v1';

function callSelcom($path, $method = 'GET', $data = []) {
    global $apiKey, $apiSecret, $baseUrl;

    $timestamp = date('c');
    $authHeader = 'SELCOM ' . base64_encode($apiKey);

    $signedFields = implode(',', array_keys($data));
    $signingString = "timestamp={$timestamp}";
    foreach ($data as $key => $val) {
        $signingString .= "&{$key}={$val}";
    }

    $digest = base64_encode(hash_hmac('sha256', $signingString, $apiSecret, true));

    $headers = [
        'Content-Type: application/json',
        'Authorization: ' . $authHeader,
        'Timestamp: ' . $timestamp,
        'Digest-Method: HS256',
        'Digest: ' . $digest,
    ];
    if (!empty($signedFields)) {
        $headers[] = 'Signed-Fields: ' . $signedFields;
    }

    $url = $baseUrl . $path;
    if ($method === 'GET' && !empty($data)) {
        $url .= '?' . http_build_query($data);
    }

    echo "Request URL: {$url}\n";
    echo "Headers:\n" . implode("\n", $headers) . "\n";
    echo "Payload: " . json_encode($data) . "\n\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'error' => $curlErr,
        'response' => $response,
    ];
}

// Test checking order status of a test ID
echo "Testing Selcom API connectivity...\n";
$res = callSelcom('/checkout/order-status', 'GET', ['order_id' => 'TEST_CHECK_1001']);
echo "HTTP Code: {$res['code']}\n";
echo "Response: {$res['response']}\n";
