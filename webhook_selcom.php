<?php
// Selcom Webhook Receiver Endpoint
header('Content-Type: application/json');

require __DIR__ . '/core/vendor/autoload.php';
$app = require_once __DIR__ . '/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$response = $app->make(\App\Http\Controllers\Gateway\selcom\ProcessController::class)->ipn($request);

$response->send();
