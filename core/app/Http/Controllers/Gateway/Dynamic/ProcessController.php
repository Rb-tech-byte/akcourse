<?php

namespace App\Http\Controllers\Gateway\Dynamic;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProcessController extends Controller {

    /**
     * Universal Dynamic Payment Processor
     * Handles any gateway created by admin without requiring manual coding.
     */
    public static function process($deposit) {
        $gateway = $deposit->gateway;
        $gatewayCurrency = $deposit->gatewayCurrency();
        
        $params = [];
        if ($gatewayCurrency && $gatewayCurrency->gateway_parameter) {
            $params = json_decode($gatewayCurrency->gateway_parameter, true) ?: [];
        } elseif ($gateway && $gateway->gateway_parameters) {
            $raw = json_decode($gateway->gateway_parameters, true) ?: [];
            foreach ($raw as $k => $v) {
                $params[$k] = is_array($v) ? ($v['value'] ?? '') : (is_object($v) ? ($v->value ?? '') : $v);
            }
        }

        // Check if an external redirect URL is provided in parameters
        $redirectUrl = $params['checkout_url'] ?? $params['redirect_url'] ?? $params['payment_url'] ?? null;
        if (!empty($redirectUrl)) {
            $placeholders = [
                '{trx}'         => $deposit->trx,
                '{amount}'      => $deposit->final_amount,
                '{currency}'    => $deposit->method_currency,
                '{email}'       => auth()->user()?->email ?? '',
                '{success_url}' => urlencode($deposit->success_url ?? route('user.deposit.history')),
                '{ipn_url}'     => urlencode(route('ipn.dynamic', $gateway->alias ?? 'custom')),
            ];
            $finalRedirect = str_replace(array_keys($placeholders), array_values($placeholders), $redirectUrl);
            
            $send['redirect']     = true;
            $send['redirect_url'] = $finalRedirect;
            return json_encode($send);
        }

        // Default: Render the Universal Responsive Payment Screen
        $send['view']             = 'user.payment.custom';
        $send['deposit']          = $deposit;
        $send['gateway']          = $gateway;
        $send['gatewayCurrency']  = $gatewayCurrency;
        $send['params']           = $params;
        return json_encode($send);
    }

    /**
     * Universal IPN / Webhook handler for dynamic gateways
     */
    public function ipn(Request $request, $alias = null) {
        $data = $request->all();
        Log::info("Dynamic Gateway Webhook Received [{$alias}]:", $data);

        // Extract reference/trx from standard webhook fields
        $trx = $request->trx 
            ?? $request->reference 
            ?? $request->order_id 
            ?? $request->transaction_id 
            ?? $request->client_reference 
            ?? $request->trans_id 
            ?? $request->input('data.reference')
            ?? $request->input('data.id')
            ?? null;

        if (!$trx) {
            return response()->json(['status' => 'error', 'message' => 'Transaction reference missing'], 400);
        }

        $deposit = Deposit::where('trx', $trx)->orWhere('btc_wallet', $trx)->first();
        if (!$deposit) {
            return response()->json(['status' => 'error', 'message' => 'Deposit not found'], 404);
        }

        if ($deposit->status == Status::PAYMENT_SUCCESS) {
            return response()->json(['status' => 'success', 'message' => 'Payment already reconciled'], 200);
        }

        $deposit->detail = $data;
        $deposit->save();

        PaymentController::userDataUpdate($deposit);

        return response()->json(['status' => 'success', 'message' => 'Payment reconciled successfully'], 200);
    }

    /**
     * Customer verification / self-confirmation endpoint for dynamic checkout
     */
    public function verify(Request $request, $trx) {
        $deposit = Deposit::where('trx', $trx)->where('status', Status::PAYMENT_INITIATE)->firstOrFail();

        // Save reference or user feedback if submitted
        if ($request->has('user_reference')) {
            $detail = (array) ($deposit->detail ?? []);
            $detail['customer_reference'] = $request->user_reference;
            $deposit->detail = $detail;
            $deposit->save();
        }

        PaymentController::userDataUpdate($deposit);

        $notify[] = ['success', 'Payment processed successfully!'];
        return redirect($deposit->success_url ?? route('user.author.download'))->withNotify($notify);
    }
}
