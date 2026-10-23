<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller {
    public function index() {
        $pageTitle = 'Cart';
        $sessionId = request()->session()->getId();
        $userId    = auth()->id();

        // Consolidate guest cart items if user is authenticated
        if ($userId && $sessionId) {
            Cart::where('session_id', $sessionId)
                ->where(function($q) { $q->whereNull('user_id')->orWhere('user_id', 0); })
                ->update(['user_id' => $userId]);
        }

        $calc = $this->getCartCalculation($userId, $sessionId);
        $cartItems = $calc['cart_items'];
        $subtotal  = $calc['subtotal'];
        $discount  = $calc['discount'];
        $total     = $calc['total'];
        $couponCode = $calc['coupon_code'];

        $isCoupon = Coupon::where('status', Status::YES)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->exists();

        return view('Template::cart', compact('pageTitle', 'cartItems', 'isCoupon', 'subtotal', 'discount', 'total', 'couponCode'));
    }

    public function calculate() {
        $userId = auth()->id();
        $sessionId = request()->session()->getId();

        $calc = $this->getCartCalculation($userId, $sessionId);

        return response()->json(array_merge([
            'status' => 'success',
        ], $calc));
    }

    public function delete($productId) {
        $userId = auth()->id();
        $sessionId = request()->session()->getId();

        $cart = Cart::where(function($q) use ($productId) {
            $q->where('product_id', $productId)->orWhere('id', $productId);
        });

        if ($userId) {
            $cart->where('user_id', $userId);
        } else {
            $cart->where('session_id', $sessionId);
        }

        $item = $cart->first();

        if (!$item) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Item not found in cart',
            ], 404);
        }

        $itemTitle = $item->title;
        $item->delete();

        $calc = $this->getCartCalculation($userId, $sessionId);

        $response = array_merge([
            'status'  => 'success',
            'message' => "{$itemTitle} removed from cart",
        ], $calc);

        return request()->ajax() ? response()->json($response) : back()->withNotify([['success', $response['message']]]);
    }

    private function getDataForCart($product, $license = null, $isExtended = 0, $request = null) {

        $productId         = $product->id;
        $license           = request()->license ?? Status::PERSONAL_LICENSE;
        $isPersonalLicense = $license == Status::PERSONAL_LICENSE;

        $existingCartItem = Cart::where('product_id', $productId);

        if (auth()->check()) {
            $existingCartItem->where('user_id', auth()->id());
        } else {
            $sessionId = $request->session()->getId();
            $existingCartItem->where('session_id', $sessionId);
        }

        $existingCartItem = $existingCartItem->first();

        if ($existingCartItem) {
            return false;
        }

        if ($isPersonalLicense) {
            $price = $product->price;
            if ($product->campaign_product_price[0]) {
                $price = $product->campaign_product_price[1] - $product->personalBuyerFee();
            }
        } else {
            $price = $product->price_cl;
            if ($product->campaign_product_commercial_price[0]) {
                $price = $product->campaign_product_commercial_price[1] - $product->commercialBuyerFee();
            }
        }

        $cart                  = new Cart();
        $cart->product_id      = $product->id;
        $cart->title           = $product->title;
        $cart->category_id     = $product->category_id;
        $cart->category        = @$product->category->name;
        $cart->license         = $license;
        $cart->is_extended     = $isExtended ?? 0;
        $cart->extended_amount = $cart->is_extended ? $product->twelveMonthExtendedFee() : 0;
        $cart->price           = $price;
        $cart->buyer_fee       = $isPersonalLicense ? $product->personalBuyerFee() : $product->commercialBuyerFee();
        $cart->quantity        = 1;

        $cart->user_id    = auth()->check() ? auth()->id() : null;
        $cart->session_id = request()->session()->getId();
        $cart->save();

        return $cart->toArray();
    }

    public function toggleExtended($id) {
        $userId = auth()->id();
        $sessionId = request()->session()->getId();

        $cartQuery = Cart::with('product')->where('id', $id);
        if ($userId) {
            $cartQuery->where('user_id', $userId);
        } else {
            $cartQuery->where('session_id', $sessionId);
        }

        $cart = $cartQuery->first();

        if (!$cart) {
            return response()->json(['status' => 'error', 'message' => 'Item not found in cart'], 404);
        }

        $cart->is_extended     = !$cart->is_extended;
        $cart->extended_amount = $cart->is_extended ? ($cart->product ? $cart->product->twelveMonthExtendedFee() : 0) : 0;
        $cart->save();

        $itemTotal = (float)$cart->price + (float)$cart->buyer_fee + (float)$cart->extended_amount;
        $calc = $this->getCartCalculation($userId, $sessionId);

        return response()->json(array_merge([
            'status'         => 'success',
            'message'        => 'Support extension updated',
            'is_extended'    => (bool)$cart->is_extended,
            'item_price'     => $itemTotal,
            'formatted_item' => 'TZS ' . number_format($itemTotal),
        ], $calc));
    }

    public function applyCoupon(Request $request) {
        $request->validate([
            'coupon_code' => 'required|string|max:40',
        ]);

        $couponCode = strtoupper(trim($request->coupon_code));
        $userId     = auth()->id();
        $sessionId  = $request->session()->getId();

        $cartQuery = Cart::query();
        if ($userId) {
            $cartQuery->where('user_id', $userId);
        } else {
            $cartQuery->where('session_id', $sessionId);
        }
        $carts = $cartQuery->get();

        if ($carts->isEmpty()) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Your cart is empty. Add a product first.',
            ], 422);
        }

        $subtotal = (float)$carts->sum(function($item) {
            return (float)$item->price + (float)$item->buyer_fee + (float)$item->extended_amount;
        });

        $coupon = Coupon::where('code', $couponCode)
            ->where('status', Status::YES)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => "Coupon code '{$couponCode}' not found.",
            ], 404);
        }

        if (!($coupon->start_date <= now() && $coupon->end_date >= now())) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'This coupon has expired.',
            ], 422);
        }

        if ($subtotal < (float)$coupon->minimum_spend) {
            $shortfall = (float)$coupon->minimum_spend - $subtotal;
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Requires minimum order of TZS ' . number_format($coupon->minimum_spend) . ' (Add TZS ' . number_format($shortfall) . ' more).',
            ], 422);
        }

        $totalUses = Order::where('coupon_id', $coupon->id)->count();
        if (!is_null($coupon->usage_limit_per_coupon) && $totalUses >= $coupon->usage_limit_per_coupon) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'This coupon has reached its total usage limit.',
            ], 422);
        }

        if ($userId) {
            $userUses = Order::where('coupon_id', $coupon->id)->where('user_id', $userId)->count();
            if (!is_null($coupon->usage_limit_per_user) && $userUses >= $coupon->usage_limit_per_user) {
                return response()->json([
                    'success' => false,
                    'status'  => 'error',
                    'message' => 'You have already reached the limit for this coupon.',
                ], 422);
            }
        }

        if ($coupon->discount_type == Status::DISCOUNT_PERCENT) {
            $discount = round(($subtotal * (float)$coupon->amount) / 100, 2);
        } else {
            $discount = min((float)$coupon->amount, $subtotal);
        }

        session()->put('coupon_total', $discount);
        session()->put('coupon_id', $coupon->id);
        session()->put('coupon_code', $coupon->code);

        foreach ($carts as $cart) {
            $cart->coupon_id = $coupon->id;
            $cart->save();
        }

        $calc = $this->getCartCalculation($userId, $sessionId);

        return response()->json(array_merge([
            'success'     => true,
            'status'      => 'success',
            'message'     => "Coupon '{$coupon->code}' applied successfully!",
            'coupon_name' => $coupon->name,
        ], $calc));
    }

    public function removeCoupon() {
        $userId    = auth()->id();
        $sessionId = request()->session()->getId();

        $cartQuery = Cart::query();
        if ($userId) {
            $cartQuery->where('user_id', $userId);
        } else {
            $cartQuery->where('session_id', $sessionId);
        }
        $carts = $cartQuery->get();

        foreach ($carts as $cart) {
            $cart->coupon_id = 0;
            $cart->discount = 0;
            $cart->save();
        }

        session()->forget('coupon_total');
        session()->forget('coupon_id');
        session()->forget('coupon_code');

        $calc = $this->getCartCalculation($userId, $sessionId);

        return response()->json(array_merge([
            'success' => true,
            'status'  => 'success',
            'message' => 'Coupon removed.',
        ], $calc));
    }

    /**
     * Unified Single Source of Truth for Cart Calculations
     */
    private function getCartCalculation($userId = null, $sessionId = null) {
        $userId    = $userId ?: auth()->id();
        $sessionId = $sessionId ?: request()->session()->getId();

        $cartQuery = Cart::with('product');
        if ($userId) {
            $cartQuery->where('user_id', $userId);
        } else {
            $cartQuery->where('session_id', $sessionId);
        }

        $cartItems = $cartQuery->get();
        $subtotal = (float)$cartItems->sum(function($item) {
            return (float)$item->price + (float)$item->buyer_fee + (float)$item->extended_amount;
        });

        // Validate applied coupon if present
        $couponId   = session()->get('coupon_id');
        $couponCode = session()->get('coupon_code');
        $couponName = null;
        $discount   = 0;

        if ($couponId && $subtotal > 0) {
            $coupon = Coupon::where('id', $couponId)
                ->where('status', Status::YES)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($coupon && $subtotal >= (float)$coupon->minimum_spend) {
                if ($coupon->discount_type == Status::DISCOUNT_PERCENT) {
                    $discount = round(($subtotal * (float)$coupon->amount) / 100, 2);
                } else {
                    $discount = min((float)$coupon->amount, $subtotal);
                }
                $couponCode = $coupon->code;
                $couponName = $coupon->name;
                session()->put('coupon_total', $discount);
                session()->put('coupon_code', $coupon->code);
                session()->put('coupon_id', $coupon->id);
            } else {
                session()->forget('coupon_total');
                session()->forget('coupon_id');
                session()->forget('coupon_code');
                $couponCode = null;
                $couponId   = null;
            }
        } else {
            session()->forget('coupon_total');
            session()->forget('coupon_id');
            session()->forget('coupon_code');
            $couponCode = null;
            $couponId   = null;
        }

        $total = max(0, $subtotal - $discount);

        return [
            'cart_items'         => $cartItems,
            'cart_count'         => $cartItems->count(),
            'subtotal'           => $subtotal,
            'formatted_subtotal' => 'TZS ' . number_format($subtotal),
            'discount'           => $discount,
            'formatted_discount' => '-TZS ' . number_format($discount),
            'total'              => $total,
            'formatted_total'    => 'TZS ' . number_format($total),
            'has_coupon'         => !empty($couponCode),
            'coupon_code'        => $couponCode,
            'coupon_name'        => $couponName,
        ];
    }

    public function quickAdd(Request $request) {
        $sku = trim((string)$request->sku);
        $productId = $request->product_id;

        $product = null;
        if ($productId) {
            $product = Product::find($productId);
        }

        if (!$product && $sku) {
            // 1. Direct SKU or slug match
            $product = Product::where('sku', $sku)->orWhere('slug', $sku)->first();

            // 2. Numeric lookup
            if (!$product && is_numeric($sku)) {
                $product = Product::find((int)$sku);
            }

            // 3. Suffix lookup for AK23-COURSE- or AK23-BUNDLE-
            if (!$product && str_contains($sku, 'AK23-COURSE-')) {
                $suffix = str_replace('AK23-COURSE-', '', $sku);
                if (is_numeric($suffix)) {
                    $product = Product::find((int)$suffix);
                } else {
                    $slugCandidate = strtolower(str_replace('_', '-', $suffix));
                    $product = Product::where('slug', 'like', "%{$slugCandidate}%")->orWhere('sku', 'like', "%{$suffix}%")->first();
                }
            }

            // 4. Fallback search by title words
            if (!$product) {
                $cleanName = str_replace(['AK23-COURSE-', 'AK23-BUNDLE-', '_', '-'], ' ', $sku);
                $product = Product::where('title', 'like', "%{$cleanName}%")->first();
            }

            // 5. Fallback to ak23 database if needed
            if (!$product) {
                try {
                    $raw = \Illuminate\Support\Facades\DB::connection('ak23')->selectOne('SELECT id, name as title, sku, price_minor as price FROM products WHERE sku = ?', [$sku]);
                    if ($raw) {
                        $product = (object)[
                            'id'          => 1,
                            'title'       => $raw->title,
                            'sku'         => $raw->sku,
                            'price'       => (float)($raw->price ?: 140000),
                            'category_id' => 1,
                        ];
                    }
                } catch (\Throwable $e) {}
            }
        }

        if (!$product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Course could not be resolved. Please try again.',
            ], 404);
        }

        $userId = auth()->id();
        $sessionId = $request->session()->getId();

        // If user is authenticated, consolidate any prior guest cart items
        if ($userId && $sessionId) {
            Cart::where('session_id', $sessionId)
                ->where(function($q) { $q->whereNull('user_id')->orWhere('user_id', 0); })
                ->update(['user_id' => $userId]);
        }

        // Check if item is already in cart for this user or session
        $existing = Cart::where('product_id', $product->id)
            ->where(function ($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId)->orWhere('session_id', $sessionId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->first();

        // Recalculate current cart totals
        $cartQuery = Cart::query();
        if ($userId) {
            $cartQuery->where('user_id', $userId);
        } else {
            $cartQuery->where('session_id', $sessionId);
        }

        if ($existing) {
            $calc = $this->getCartCalculation($userId, $sessionId);

            return response()->json(array_merge([
                'status'       => 'info',
                'message'      => "{$product->title} is already in your cart",
                'cart_url'     => route('cart.index'),
                'checkout_url' => route('order.checkout', ['sku' => @$product->sku ?: @$product->slug]),
            ], $calc));
        }

        $productPrice = (float)($product->price ?: 140000);

        $cart                  = new Cart();
        $cart->product_id      = $product->id;
        $cart->title           = $product->title;
        $cart->category_id     = $product->category_id ?? 1;
        $cart->category        = @$product->category->name ?: 'Audio Production Course';
        $cart->license         = 1;
        $cart->is_extended     = 0;
        $cart->extended_amount = 0;
        $cart->price           = $productPrice;
        $cart->buyer_fee       = 0;
        $cart->quantity        = 1;
        $cart->user_id         = $userId;
        $cart->session_id      = $sessionId;
        $cart->save();

        $calc = $this->getCartCalculation($userId, $sessionId);

        return response()->json(array_merge([
            'status'         => 'success',
            'message'        => "{$product->title} added to cart!",
            'item_title'     => $product->title,
            'item_price'     => $productPrice,
            'formatted_item' => 'TZS ' . number_format($productPrice),
            'cart_url'       => route('cart.index'),
            'checkout_url'   => route('order.checkout', ['sku' => @$product->sku ?: @$product->slug]),
        ], $calc));
    }
}

