<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Constants\Status;

echo "Seeding Selcom Gateway Credentials...\n";

$vendor = 'SW00170027';
$apiKey = 'TILL61237100-62ceeea68ed6426a83e640c5950d117d';
$apiSecret = '469b1f-f26748-4f6ca1-18b224-b4bde4-1e';
$baseUrl = 'https://apigw.selcommobile.com/v1';
$webhookUrl = 'https://www.m23.ak23studiokits.com/webhook_selcom.php';

$gatewayParams = [
    'vendor' => [
        'title' => 'Vendor ID / Till',
        'global' => true,
        'value' => $vendor,
    ],
    'api_key' => [
        'title' => 'API Key',
        'global' => true,
        'value' => $apiKey,
    ],
    'api_secret' => [
        'title' => 'API Secret',
        'global' => true,
        'value' => $apiSecret,
    ],
    'base_url' => [
        'title' => 'Base URL',
        'global' => true,
        'value' => $baseUrl,
    ],
    'webhook_url' => [
        'title' => 'Webhook URL',
        'global' => true,
        'value' => $webhookUrl,
    ],
];

// 1. Update or create Gateway in `gateways`
$gateway = Gateway::where('code', 801)->orWhere('alias', 'selcom')->first();
if (!$gateway) {
    $gateway = new Gateway();
    $gateway->code = 801;
    $gateway->alias = 'selcom';
}
$gateway->name = 'Selcom Mobile Money';
$gateway->status = Status::ENABLE;
$gateway->gateway_parameters = json_encode($gatewayParams);
$gateway->supported_currencies = json_encode(['TZS' => 'TZS', 'USD' => 'USD']);
$gateway->crypto = 0;
$gateway->description = 'Selcom Mobile Money Checkout & USSD Push (M-Pesa, Mixx, Airtel Money, HaloPesa)';
$gateway->save();

echo "Gateway saved with ID: {$gateway->id}, code: {$gateway->code}\n";

// 2. Update or create GatewayCurrency in `gateway_currencies`
$currency = GatewayCurrency::where('method_code', 801)->first();
if (!$currency) {
    $currency = new GatewayCurrency();
    $currency->method_code = 801;
    $currency->gateway_alias = 'selcom';
}
$currency->name = 'Selcom Mobile Money';
$currency->currency = 'TZS';
$currency->symbol = 'TZS';
$currency->min_amount = 100;
$currency->max_amount = 50000000;
$currency->percent_charge = 0;
$currency->fixed_charge = 0;
$currency->rate = 1.00000000;
$currency->gateway_parameter = json_encode([
    'vendor' => $vendor,
    'api_key' => $apiKey,
    'api_secret' => $apiSecret,
    'base_url' => $baseUrl,
    'webhook_url' => $webhookUrl,
]);
$currency->save();

echo "GatewayCurrency saved with ID: {$currency->id}, currency: {$currency->currency}\n";

// Verify
$gCheck = Gateway::with('currencies')->find($gateway->id);
echo "Gateway verified: {$gCheck->name}, currencies count: " . $gCheck->currencies->count() . "\n";
