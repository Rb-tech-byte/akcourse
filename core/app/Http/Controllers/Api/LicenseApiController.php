<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class LicenseApiController extends Controller
{
    /**
     * Verify license or certificate from request parameters.
     */
    public function verify(Request $request)
    {
        $code = trim((string)($request->code ?: $request->license_key ?: $request->certificate_number ?: $request->purchase_code));

        if (!$code) {
            return response()->json([
                'status'  => 'error',
                'valid'   => false,
                'message' => 'License key or certificate number parameter is required.',
            ], 422);
        }

        return $this->check($code);
    }

    /**
     * Look up license details by unique key or certificate number.
     */
    public function check($code)
    {
        $cleanCode = trim((string)$code);

        $orderItem = OrderItem::with(['product', 'order', 'buyer'])
            ->where('license_key', $cleanCode)
            ->orWhere('certificate_number', $cleanCode)
            ->orWhere('purchase_code', $cleanCode)
            ->first();

        if (!$orderItem) {
            return response()->json([
                'status'  => 'invalid',
                'valid'   => false,
                'message' => 'Provided license key or certificate was not found in the verified registry.',
            ], 404);
        }

        $product = $orderItem->product;
        $user = $orderItem->buyer;

        return response()->json([
            'status'             => 'valid',
            'valid'              => true,
            'certificate_type'   => 'Certificate of Course Purchase',
            'license_type'       => 'One-Time Perpetual Studio License',
            'renewable'          => false,
            'is_extended'        => false,
            'expires_at'         => 'Never (Lifetime Validity)',
            'license_key'        => $orderItem->license_key ?: $orderItem->purchase_code,
            'certificate_number' => $orderItem->certificate_number,
            'product' => [
                'id'       => $product?->id,
                'title'    => $product?->title ?: $product?->name,
                'sku'      => $product?->sku,
                'software' => $product?->software_name ?: 'Studio Suite',
                'edition'  => $product?->software_edition ?: 'Perpetual Commercial Edition',
            ],
            'licensed_to'        => $user?->fullname ?: ($user?->username ?: 'Verified Buyer'),
            'order_trx'          => $orderItem->order?->trx,
            'purchase_date'      => $orderItem->created_at?->toIso8601String(),
            'certificate_url'    => route('user.certificate', $orderItem->certificate_number ?: $orderItem->purchase_code),
        ]);
    }
}
