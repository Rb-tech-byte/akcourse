<?php

namespace App\Http\Controllers\Gateway\selcom;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use App\Services\SelcomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProcessController extends Controller {

    /**
     * Selcom Payment Processor (Push USSD or Redirect)
     */
    public static function process($deposit) {
        $selcom = new SelcomService();

        $user = $deposit->user;
        $orderId = $deposit->trx;
        $amount = (int) round($deposit->final_amount);
        $phone = $deposit->phone ?? ($user ? $user->mobile : '255754000000');
        $name = $user ? ($user->fullname ?: $user->username) : 'Customer';
        $email = $user ? $user->email : 'support@ak23studiokits.com';

        // Call Selcom /create-order-minimal
        $res = $selcom->createOrderMinimal($orderId, $name, $email, $phone, $amount, 'TZS');

        if ($res['success']) {
            $data = $res['data']['data'][0] ?? [];
            $deposit->detail = array_merge((array)$deposit->detail, [
                'selcom_order' => $data,
                'gateway_buyer_uuid' => $data['gateway_buyer_uuid'] ?? null,
                'payment_token' => $data['payment_token'] ?? null,
            ]);
            $deposit->save();

            // If phone provided, immediately trigger Push USSD
            if (!empty($phone) && strlen($phone) >= 9) {
                $pushRes = $selcom->walletPayment(getTrx(), $orderId, $phone);
                Log::info("Selcom Push USSD Attempt for {$orderId}:", $pushRes);
            }
        } else {
            Log::warning("Selcom createOrderMinimal note for {$orderId}:", $res);
        }

        $send['view']            = 'user.payment.selcom';
        $send['deposit']         = $deposit;
        $send['gateway']         = $deposit->gateway;
        $send['gatewayCurrency'] = $deposit->gatewayCurrency();
        return json_encode($send);
    }

    /**
     * Selcom Webhook / IPN Handler
     * Receives callback from Selcom Gateway
     */
    public function ipn(Request $request) {
        $data = $request->all();
        Log::info("Selcom Webhook Received:", $data);

        $orderId = $request->order_id ?? $request->transid ?? $request->reference ?? null;
        if (!$orderId) {
            return response()->json(['result' => 'FAIL', 'message' => 'Missing transaction reference'], 400);
        }

        $deposit = Deposit::where('trx', $orderId)->first();
        if (!$deposit) {
            // Check if orderId matches an order
            return response()->json(['result' => 'FAIL', 'message' => 'Deposit not found'], 404);
        }

        $resultCode = (string) ($request->resultcode ?? '');
        $status = strtoupper($request->payment_status ?? $request->result ?? '');

        if ($resultCode === '000' || $status === 'COMPLETED' || $status === 'SUCCESS') {
            if ($deposit->status == Status::PAYMENT_SUCCESS) {
                return response()->json(['result' => 'SUCCESS', 'message' => 'Already reconciled'], 200);
            }

            $deposit->detail = array_merge((array)$deposit->detail, $data);
            $deposit->save();

            PaymentController::userDataUpdate($deposit);

            return response()->json([
                'result' => 'SUCCESS',
                'resultcode' => '000',
                'message' => 'Reconciled successfully',
            ], 200);
        }

        return response()->json([
            'result' => 'PENDING',
            'resultcode' => $resultCode,
            'message' => 'Payment not yet finalized',
        ], 200);
    }
}
