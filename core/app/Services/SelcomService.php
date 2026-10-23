<?php

namespace App\Services;

use App\Models\Gateway;
use App\Models\GatewayCurrency;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SelcomService
{
    protected string $vendor;
    protected string $apiKey;
    protected string $apiSecret;
    protected string $baseUrl;
    protected string $webhookUrl;

    public function __construct()
    {
        $gateway = Gateway::where('alias', 'selcom')->first();
        $params = [];

        if ($gateway && $gateway->gateway_parameters) {
            $raw = json_decode($gateway->gateway_parameters, true) ?: [];
            foreach ($raw as $k => $v) {
                $params[$k] = is_array($v) ? ($v['value'] ?? '') : (is_object($v) ? ($v->value ?? '') : $v);
            }
        }

        $this->vendor     = $params['vendor'] ?? 'SW00170027';
        $this->apiKey     = $params['api_key'] ?? 'TILL61237100-62ceeea68ed6426a83e640c5950d117d';
        $this->apiSecret  = $params['api_secret'] ?? '469b1f-f26748-4f6ca1-18b224-b4bde4-1e';
        $this->baseUrl    = rtrim($params['base_url'] ?? 'https://apigw.selcommobile.com/v1', '/');
        $this->webhookUrl = $params['webhook_url'] ?? 'https://www.m23.ak23studiokits.com/webhook_selcom.php';
    }

    /**
     * Format phone number to international 255XXXXXXXXX format without plus
     */
    public static function formatPhone($phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', (string)$phone);
        if (str_starts_with($clean, '0')) {
            $clean = '255' . substr($clean, 1);
        } elseif (str_starts_with($clean, '+255')) {
            $clean = substr($clean, 1);
        } elseif (!str_starts_with($clean, '255')) {
            $clean = '255' . $clean;
        }
        return $clean;
    }

    /**
     * Detect mobile operator from phone number
     */
    public static function detectOperator($phone): string
    {
        $p = self::formatPhone($phone);
        $prefix = substr($p, 3, 2);

        return match ($prefix) {
            '74', '75', '76' => 'MPESA',
            '65', '67', '71', '77' => 'MIXX',
            '68', '69', '78' => 'AIRTEL',
            '62', '61' => 'HALOPESA',
            '73' => 'TTCL',
            default => 'MPESA',
        };
    }

    /**
     * Build signing headers for Selcom HS256 HMAC
     */
    protected function generateHeaders(array $payload, string $method = 'POST'): array
    {
        $timestamp = date('c');
        $authHeader = 'SELCOM ' . base64_encode($this->apiKey);

        $signedFields = implode(',', array_keys($payload));
        $signingString = "timestamp={$timestamp}";
        foreach ($payload as $key => $val) {
            $signingString .= "&{$key}={$val}";
        }

        $digest = base64_encode(hash_hmac('sha256', $signingString, $this->apiSecret, true));

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $authHeader,
            'Timestamp' => $timestamp,
            'Digest-Method' => 'HS256',
            'Digest' => $digest,
        ];

        if (!empty($signedFields)) {
            $headers['Signed-Fields'] = $signedFields;
        }

        return $headers;
    }

    /**
     * Send HTTP request to Selcom Gateway
     */
    protected function request(string $endpoint, string $method = 'POST', array $data = []): array
    {
        $url = $this->baseUrl . $endpoint;
        $headers = $this->generateHeaders($data, $method);

        try {
            $client = Http::withHeaders($headers)->withoutVerifying()->timeout(20);

            if ($method === 'GET') {
                $response = $client->get($url, $data);
            } else {
                $response = $client->post($url, $data);
            }

            $body = $response->json();
            $status = $response->status();

            Log::info("Selcom API Response [$endpoint]:", ['status' => $status, 'body' => $body]);

            if ($response->successful() && is_array($body)) {
                return [
                    'success' => ($body['result'] ?? '') === 'SUCCESS' || ($body['resultcode'] ?? '') === '000',
                    'data' => $body,
                    'status' => $status,
                ];
            }

            return [
                'success' => false,
                'data' => $body ?: ['message' => $response->body()],
                'status' => $status,
                'message' => $body['message'] ?? 'Selcom API request failed with status ' . $status,
            ];
        } catch (\Throwable $e) {
            Log::error("Selcom API Exception [$endpoint]: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status' => 500,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create Order Minimal on Selcom PG
     * POST /v1/checkout/create-order-minimal
     */
    public function createOrderMinimal(
        string $orderId,
        string $buyerName,
        string $buyerEmail,
        string $buyerPhone,
        int $amount,
        string $currency = 'TZS',
        ?string $redirectUrl = null,
        ?string $cancelUrl = null
    ): array {
        $formattedPhone = self::formatPhone($buyerPhone);

        $payload = [
            'vendor'           => $this->vendor,
            'order_id'         => $orderId,
            'buyer_email'      => $buyerEmail,
            'buyer_name'       => $buyerName,
            'buyer_phone'      => $formattedPhone,
            'amount'           => $amount,
            'currency'         => $currency,
            'redirect_url'     => base64_encode($redirectUrl ?: route('user.author.download')),
            'cancel_url'       => base64_encode($cancelUrl ?: route('order.checkout')),
            'webhook'          => base64_encode($this->webhookUrl),
            'buyer_remarks'    => "AK23 Course Order #{$orderId}",
            'merchant_remarks' => "AK23 Technology #{$orderId}",
            'no_of_items'      => 1,
        ];

        return $this->request('/checkout/create-order-minimal', 'POST', $payload);
    }

    /**
     * Trigger Push USSD directly to user's phone
     * POST /v1/checkout/wallet-payment
     */
    public function walletPayment(string $transId, string $orderId, string $phone): array
    {
        $formattedPhone = self::formatPhone($phone);

        $payload = [
            'transid'  => $transId,
            'order_id' => $orderId,
            'msisdn'   => $formattedPhone,
        ];

        return $this->request('/checkout/wallet-payment', 'POST', $payload);
    }

    /**
     * Query order status from Selcom Gateway
     * GET /v1/checkout/order-status?order_id={order_id}
     */
    public function getOrderStatus(string $orderId): array
    {
        return $this->request('/checkout/order-status', 'GET', ['order_id' => $orderId]);
    }

    /**
     * Cancel order before customer payment
     * DELETE /v1/checkout/cancel-order?order_id={order_id}
     */
    public function cancelOrder(string $orderId): array
    {
        return $this->request('/checkout/cancel-order', 'DELETE', ['order_id' => $orderId]);
    }
}
