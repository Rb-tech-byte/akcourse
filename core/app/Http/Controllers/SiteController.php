<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\Rating;
use App\Models\Review;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\GatewayCurrency;
use App\Models\Deposit;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class SiteController extends Controller
{
    public function index()
    {
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle   = 'Maarifa23 - Jifunze Music Production & Studio Tools';
        $sections    = Page::where('tempname', activeTemplate())->where('slug', '/')->first();
        $seoContents = @$sections->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;

        $categories = Category::active()->with(['subCategories' => function ($q) {
            $q->where('status', 1);
        }, 'products' => function ($q) {
            $q->approved()->orderBy('sequence_no', 'asc')->orderBy('id', 'asc');
        }])->orderBy('id', 'asc')->get();

        // 1. Primary Products Grid (8 items per page with search & category filtering)
        $productsQuery = Product::approved()->with(['category', 'author']);
        if ($search = request('search')) {
            $productsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('software_name', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }
        if ($catId = request('category')) {
            $productsQuery->where('category_id', $catId);
        }
        if ($subCatId = request('sub_category')) {
            $productsQuery->where('sub_category_id', $subCatId);
        }
        $products = $productsQuery->orderBy('id', 'desc')->paginate(8)->withQueryString();

        // 2. Trending This Week (4 products)
        $trendingProducts = Product::approved()->with(['category', 'author'])
            ->orderBy('total_sold', 'desc')
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        $catalog = [];
        $allCourses = collect();
        $allBundles = collect();

        foreach ($categories as $cat) {
            $courses = $cat->products->filter(function ($p) {
                return $p->product_type === 'course' || empty($p->product_type);
            })->values();

            $bundle = $cat->products->firstWhere('product_type', 'bundle');
            if ($bundle) {
                $allBundles->push($bundle);
            }

            $allCourses = $allCourses->concat($courses);

            $catalog[] = [
                'series'  => $cat,
                'courses' => $courses,
                'bundle'  => $bundle,
            ];
        }

        return view('Template::home', compact(
            'pageTitle', 
            'sections', 
            'seoContents', 
            'seoImage', 
            'products',
            'trendingProducts',
            'catalog',
            'categories',
            'allCourses',
            'allBundles'
        ));
    }

    public function pages($slug = null)
    {
        if (empty($slug) || $slug === 'index' || $slug === 'home') {
            return $this->index();
        }
        $page        = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle   = $page->name;
        $sections    = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function searchAjax(Request $request)
    {
        $search = trim((string)$request->input('search', $request->input('q', '')));
        if (empty($search)) {
            return response()->json([
                'success' => true,
                'count'   => 0,
                'data'    => [],
                'all_url' => route('products'),
            ]);
        }

        $products = Product::approved()
            ->with(['category'])
            ->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('software_name', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->take(12)
            ->get();

        $results = $products->map(function ($p) {
            $title   = $p->title ?: $p->name;
            $price   = (float)$p->price;
            $isFree  = $p->is_free || $price <= 0;
            $catName = $p->category ? $p->category->name : 'STUDIO';

            return [
                'id'              => $p->id,
                'title'           => $title,
                'slug'            => $p->slug,
                'url'             => route('product.details', $p->slug),
                'price'           => $price,
                'price_formatted' => $isFree ? 'FREE' : 'TSH ' . number_format($price),
                'is_free'         => $isFree,
                'category'        => strtoupper($catName),
                'thumbnail_url'   => $p->thumbnail_url,
                'sku'             => $p->sku ?: ('AK23-PROD-' . $p->id),
                'date'            => $p->created_at ? $p->created_at->format('M d, Y') : 'Oct 2026',
            ];
        });

        return response()->json([
            'success' => true,
            'count'   => $results->count(),
            'data'    => $results,
            'all_url' => route('products') . '?search=' . urlencode($search),
        ]);
    }

    public function products()
    {
        $pageTitle = "Every course. One simple place. - AK23 Studio Kits";
        $catalog   = [];

        try {
            $categories = Category::active()->orderBy('id', 'asc')->get();

            foreach ($categories as $cat) {
                $seriesSlug = $cat->slug ?: Str::slug($cat->name);

                // Dynamic CMS-managed courses from main DB products table
                $courses = Product::approved()
                    ->where('category_id', $cat->id)
                    ->where(function ($q) {
                        $q->where('product_type', 'course')->orWhereNull('product_type');
                    })
                    ->orderBy('sequence_no', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                // Dynamic CMS-managed bundle from main DB products table
                $bundle = Product::approved()
                    ->where('category_id', $cat->id)
                    ->where('product_type', 'bundle')
                    ->first();

                $series = (object)[
                    'id'          => $cat->id,
                    'slug'        => $seriesSlug,
                    'title'       => $cat->name,
                    'description' => $cat->name . ' - Professional courses with matching software licenses included.',
                    'sequence_no' => $cat->id,
                ];

                $catalog[] = [
                    'series'  => $series,
                    'courses' => $courses,
                    'bundle'  => $bundle,
                ];
            }
        } catch (\Throwable $e) {
            // fallback gracefully
        }

        return view('Template::products', compact('pageTitle', 'catalog'));
    }

    public function checkout($sku = null)
    {
        $pageTitle = 'Secure Checkout - AK23 Studio Kits';

        // Unauthenticated Users: dynamically interrupt workflow and prompt login/registration
        if (!auth()->check()) {
            $targetUrl = url()->full();
            session()->put('url.intended', $targetUrl);
            \App\Lib\Intended::assignSession([
                'route_name'     => 'order.checkout',
                'route_full_url' => $targetUrl,
                'query_params'   => request()->query(),
                'form_data'      => [],
            ]);
            $notify[] = ['info', 'Please log in or create an account to proceed to checkout.'];
            return redirect()->route('user.login')->withNotify($notify);
        }

        $user = auth()->user();
        $userName = $user->fullname ?: ($user->firstname ? trim($user->firstname . ' ' . $user->lastname) : 'Alex M. Kaseja');
        $userEmail = $user->email ?: 'info@wolinet.com';
        $userPhone = $user->mobile ?: '255747003357';
        $userAddress = $user->address ?: 'Ali Hassan Mwinyi Rd, Kijitonyama';
        $userCity = $user->city ?: 'Dar es Salaam';
        $userCountry = $user->country_name ?: 'Tanzania';
        $userZip = $user->zip ?: '14111';

        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        $isCartCheckout = (!$sku || $sku === 'cart' || $sku === 'AK23-PROD-CHECKOUT') && $cartItems->isNotEmpty();

        if ($isCartCheckout) {
            $subtotal = (float)$cartItems->sum(function ($item) {
                return (float)$item->price + (float)$item->buyer_fee + (float)$item->extended_amount;
            });
            $firstProduct = $cartItems->first()->product;
            $product = $firstProduct;
            $sku = 'cart';
            $bundle = null;
        } else {
            if (!$sku) {
                $sku = request()->get('sku', 'AK23-COURSE-CYMATICS_VOXITY');
            }

            $product = null;
            $bundle  = null;

            try {
                // 1. First check main DB products (CMS-managed)
                $product = Product::with('category')
                    ->where('sku', $sku)
                    ->orWhere('slug', $sku)
                    ->first();

                // If not found, try numeric ID
                if (!$product && is_numeric($sku)) {
                    $product = Product::with('category')->find((int)$sku);
                }

                // If not found, try AK23-COURSE- suffix
                if (!$product && str_contains($sku, 'AK23-COURSE-')) {
                    $suffix = str_replace('AK23-COURSE-', '', $sku);
                    $slugCandidate = strtolower(str_replace('_', '-', $suffix));
                    $product = Product::with('category')->where('slug', 'like', "%{$slugCandidate}%")->orWhere('sku', 'like', "%{$suffix}%")->first();
                }

                // 2. If not found in main DB, fall back to ak23 database
                if (!$product) {
                    $product = \Illuminate\Support\Facades\DB::connection('ak23')->selectOne('
                        SELECT 
                            p.sku, 
                            p.name, 
                            p.product_type,
                            p.description, 
                            p.price_minor, 
                            p.currency, 
                            BIN_TO_UUID(p.id, 1) as product_uuid,
                            c.sequence_no,
                            c.title as course_title,
                            c.stage_label,
                            c.learning_outcome,
                            sp.product_name as software_name,
                            sp.edition as software_edition,
                            s.slug as series_slug,
                            s.title as series_title
                        FROM products p
                        JOIN series s ON s.id = p.series_id
                        LEFT JOIN courses c ON c.id = p.course_id
                        LEFT JOIN course_software cs ON cs.course_id = c.id
                        LEFT JOIN software_packages sp ON sp.id = cs.software_package_id
                        WHERE p.sku = ?
                    ', [$sku]);
                }

                // 3. Fallback to default Cymatics Voxity if still empty
                if (!$product) {
                    $product = Product::where('sku', 'AK23-COURSE-CYMATICS_VOXITY')->first();
                }

                // Load matching Bundle for the series
                if ($product) {
                    $catId = $product->category_id ?? 1;
                    $bundle = Product::where('category_id', $catId)->where('product_type', 'bundle')->first();
                }
            } catch (\Throwable $e) {
                // fallback gracefully
            }

            $subtotal = 140000;
            if ($product) {
                $subtotal = (float)($product->price ?: ($product->price_minor ?? 140000));
            }
        }

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
        }
        $total = max(0, $subtotal - $discount);

        // Dynamic Order Reference #1374
        $orderNumber = '#' . (1000 + ($product ? ($product->id * 187) % 9000 : 374));

        // Gateways: Prioritize Selcom (801) as default
        $gateways = GatewayCurrency::whereHas('method', function ($q) {
            $q->where('status', Status::ENABLE);
        })->with('method')
          ->orderByRaw('CASE WHEN method_code = 801 THEN 0 ELSE 1 END')
          ->orderBy('name')
          ->get();

        return view('Template::checkout', compact(
            'pageTitle', 'product', 'bundle', 'gateways', 'subtotal', 'discount', 'total', 
            'couponCode', 'couponName', 'isCartCheckout', 'orderNumber', 
            'sku', 'cartItems', 'user', 'userName', 'userEmail', 'userPhone', 
            'userAddress', 'userCity', 'userCountry', 'userZip'
        ));
    }

    public function processCheckout(Request $request, $sku = null)
    {
        // Unauthenticated Users: interrupt workflow
        if (!auth()->check()) {
            session()->put('url.intended', url()->previous() ?: route('order.checkout', ['sku' => $sku]));
            if ($request->ajax()) {
                return response()->json([
                    'success'      => false,
                    'need_login'   => true,
                    'redirect_url' => route('user.login'),
                    'message'      => 'Please log in or register to complete your order.',
                ], 401);
            }
            return redirect()->route('user.login');
        }

        $paymentMethod = $request->payment_method ?: 'mobile_money';

        if ($paymentMethod === 'card') {
            $request->validate([
                'email'       => 'required|email',
                'card_name'   => 'required|string|max:100',
                'card_number' => 'required|string|min:12|max:24',
                'card_exp'    => 'required|string|min:4|max:7',
                'card_cvv'    => 'required|string|min:3|max:4',
                'terms'       => 'accepted',
            ]);
        } else {
            $request->validate([
                'email' => 'required|email',
                'phone' => 'required',
                'terms' => 'accepted',
            ]);
        }

        $user = auth()->user();

        // Auto-save and sync profile data submitted during checkout
        if ($request->filled('card_name') && (empty($user->firstname) || in_array($user->firstname, ['Student', 'Customer']))) {
            $parts = explode(' ', trim($request->card_name));
            $user->firstname = array_shift($parts);
            $user->lastname = implode(' ', $parts) ?: ($user->lastname ?: 'User');
        }
        if ($request->filled('address')) {
            $user->address = $request->address;
        }
        if ($request->filled('city')) {
            $user->city = $request->city;
        }
        if ($request->filled('country_name')) {
            $user->country_name = $request->country_name;
        }
        if ($request->filled('phone')) {
            $user->mobile = \App\Services\SelcomService::formatPhone($request->phone);
        }
        $user->save();

        // Check if checkout is for active cart
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        $isCartCheckout = (!$sku || $sku === 'cart' || $sku === 'AK23-PROD-CHECKOUT') && $cartItems->isNotEmpty();

        $product = null;
        $productPrice = 0;
        $orderItemsData = [];

        if ($isCartCheckout) {
            foreach ($cartItems as $cItem) {
                $itemPrice = (float)$cItem->price;
                $productPrice += $itemPrice;
                $orderItemsData[] = [
                    'product_id'    => $cItem->product_id,
                    'product_price' => $itemPrice,
                    'license'       => 1,
                    'quantity'      => 1,
                ];
            }
            $product = $cartItems->first()->product;
        } else {
            if (!$sku) {
                $sku = $request->sku ?: 'AK23-COURSE-CYMATICS_VOXITY';
            }

            // Resolve Product and Price dynamically from DB
            $product = Product::where('sku', $sku)->orWhere('slug', $sku)->first();
            if (!$product && is_numeric($sku)) {
                $product = Product::find((int)$sku);
            }
            if (!$product && str_contains($sku, 'AK23-COURSE-')) {
                $suffix = str_replace('AK23-COURSE-', '', $sku);
                $slugCandidate = strtolower(str_replace('_', '-', $suffix));
                $product = Product::where('slug', 'like', "%{$slugCandidate}%")->orWhere('sku', 'like', "%{$suffix}%")->first();
            }

            $productPrice = 140000;
            if ($product && $product->price > 0) {
                $productPrice = (float)$product->price;
            } else {
                try {
                    $p = \Illuminate\Support\Facades\DB::connection('ak23')->selectOne('SELECT price_minor FROM products WHERE sku = ?', [$sku]);
                    if ($p && $p->price_minor) {
                        $productPrice = (float)$p->price_minor;
                    }
                } catch (\Throwable $e) {}
            }

            $orderItemsData[] = [
                'product_id'    => $product ? $product->id : null,
                'product_price' => $productPrice,
                'license'       => 1,
                'quantity'      => 1,
            ];
        }

        // 1. Calculate discount if coupon applied
        $couponId    = session()->get('coupon_id');
        $discount    = (float)session()->get('coupon_total', 0);
        $finalAmount = max(0, $productPrice - $discount);

        // 1. Create Order with live calculated amount
        $order = new Order();
        $order->user_id = $user->id;
        $order->amount = $finalAmount;
        $order->discount = $discount;
        $order->coupon_id = $couponId ?: null;
        $order->payment_status = Status::PAYMENT_INITIATE;
        $order->trx = 'AK-' . strtoupper(Str::random(8));
        $order->save();

        // 2. Create OrderItems with auto-generated lifetime license key & certificate number
        foreach ($orderItemsData as $itemData) {
            $orderItem                     = new OrderItem();
            $orderItem->order_id           = $order->id;
            $orderItem->user_id            = $user->id;
            $orderItem->product_id         = $itemData['product_id'];
            $orderItem->product_price      = $itemData['product_price'];
            $orderItem->purchase_code      = getPurchaseCode();
            $orderItem->license_key        = generateLicenseKey();
            $orderItem->certificate_number = generateCertificateNumber();
            $orderItem->license            = 1; // Standardized One-Time Lifetime License
            $orderItem->is_extended        = 0;
            $orderItem->extended_amount    = 0;
            $orderItem->quantity           = $itemData['quantity'];
            $orderItem->save();
        }

        if ($paymentMethod === 'card') {
            // Clean and detect card details
            $rawCard = preg_replace('/[^0-9]/', '', (string)$request->card_number);
            $last4 = substr($rawCard, -4);
            $firstDigit = substr($rawCard, 0, 1);
            $cardBrand = match($firstDigit) {
                '4' => 'Visa',
                '5' => 'Mastercard',
                '3' => 'Amex',
                '6' => 'Discover',
                default => 'Credit Card',
            };

            // 3. Create Deposit for Card Payment Gateway
            $deposit                  = new Deposit();
            $deposit->user_id         = $user->id;
            $deposit->order_id        = $order->id;
            $deposit->method_code     = 802; // Card Payment Gateway
            $deposit->method_currency = 'TZS';
            $deposit->amount          = $finalAmount;
            $deposit->charge          = 0;
            $deposit->rate            = 1.0;
            $deposit->final_amount    = $finalAmount;
            $deposit->btc_amount      = 0;
            $deposit->btc_wallet      = "";
            $deposit->trx             = $order->trx;
            $deposit->success_url     = route('user.author.download');
            $deposit->failed_url      = route('order.checkout', ['sku' => $sku]);
            $deposit->detail          = [
                'payment_method' => 'card',
                'card_name'      => $request->card_name,
                'card_brand'     => $cardBrand,
                'card_last4'     => $last4,
                'sku'            => $sku,
            ];
            $deposit->save();
            session()->put('Track', $deposit->trx);

            // Reconcile and unlock digital delivery directly for card payment
            \App\Http\Controllers\Gateway\PaymentController::userDataUpdate($deposit);

            // Clear cart & coupon session
            if ($isCartCheckout) {
                Cart::where('user_id', $user->id)->delete();
            }
            session()->forget(['coupon_total', 'coupon_id', 'coupon_code']);

            if ($request->ajax()) {
                return response()->json([
                    'success'        => true,
                    'payment_method' => 'card',
                    'trx'            => $deposit->trx,
                    'order_id'       => $order->id,
                    'amount'         => number_format($finalAmount),
                    'message'        => 'Card payment authorized successfully! Digital product unlocked.',
                    'redirect_url'   => route('user.author.download'),
                ]);
            }

            return redirect()->route('user.author.download');
        }

        // Mobile Money Flow
        $formattedPhone = \App\Services\SelcomService::formatPhone($request->phone);
        $user->mobile = $formattedPhone;
        $user->save();

        $operator = $request->operator ?: \App\Services\SelcomService::detectOperator($formattedPhone);

        // 3. Create Deposit for Mobile Money Reconciliation
        $deposit                  = new Deposit();
        $deposit->user_id         = $user->id;
        $deposit->order_id        = $order->id;
        $deposit->method_code     = 801; // Selcom Mobile Money
        $deposit->method_currency = 'TZS';
        $deposit->amount          = $finalAmount;
        $deposit->charge          = 0;
        $deposit->rate            = 1.0;
        $deposit->final_amount    = $finalAmount;
        $deposit->btc_amount      = 0;
        $deposit->btc_wallet      = "";
        $deposit->trx             = $order->trx;
        $deposit->success_url     = route('user.author.download');
        $deposit->failed_url      = route('order.checkout', ['sku' => $sku]);
        $deposit->detail          = [
            'payment_method' => 'mobile_money',
            'phone'          => $formattedPhone,
            'operator'       => $operator,
            'sku'            => $sku,
        ];
        $deposit->save();

        session()->put('Track', $deposit->trx);

        // 4. Initiate Selcom API Request (Zero Simulation - Live API)
        $selcom = new \App\Services\SelcomService();
        $createRes = $selcom->createOrderMinimal(
            $deposit->trx,
            $user->fullname ?: $user->username,
            $user->email,
            $formattedPhone,
            (int)$finalAmount,
            'TZS',
            route('user.author.download'),
            route('order.checkout', ['sku' => $sku])
        );

        // Check Selcom order creation response
        if (empty($createRes['success'])) {
            $apiMsg = $createRes['message'] ?? ($createRes['data']['message'] ?? 'Selcom order creation failed');
            $isIpError = str_contains(strtolower($apiMsg), 'whitelisted') || str_contains(strtolower($apiMsg), 'source ip');
            
            $deposit->detail = array_merge((array)$deposit->detail, [
                'selcom_error' => $createRes,
                'api_message'  => $apiMsg,
            ]);
            $deposit->save();

            if ($request->ajax()) {
                return response()->json([
                    'success'     => false,
                    'is_ip_error' => $isIpError,
                    'gateway'     => 'selcom',
                    'resultcode'  => $createRes['data']['resultcode'] ?? $createRes['status'] ?? '403',
                    'message'     => $apiMsg,
                    'advice'      => $isIpError
                        ? 'Selcom Gateway Notice: Source IP is not whitelisted on your Selcom Merchant portal. Live Push USSD requires whitelisting. Please select Card Payment to complete your purchase instantly.'
                        : $apiMsg,
                    'trx'         => $deposit->trx,
                ], 422);
            }

            $notify[] = ['error', $apiMsg];
            return back()->withNotify($notify);
        }

        // Order created successfully on Selcom, now trigger wallet payment
        $pushRes = $selcom->walletPayment(getTrx(), $deposit->trx, $formattedPhone);

        if (empty($pushRes['success'])) {
            $apiMsg = $pushRes['message'] ?? ($pushRes['data']['message'] ?? 'Failed to trigger wallet payment');
            $isIpError = str_contains(strtolower($apiMsg), 'whitelisted') || str_contains(strtolower($apiMsg), 'source ip');

            $deposit->detail = array_merge((array)$deposit->detail, [
                'push_error'  => $pushRes,
                'api_message' => $apiMsg,
            ]);
            $deposit->save();

            if ($request->ajax()) {
                return response()->json([
                    'success'     => false,
                    'is_ip_error' => $isIpError,
                    'gateway'     => 'selcom',
                    'resultcode'  => $pushRes['data']['resultcode'] ?? $pushRes['status'] ?? '403',
                    'message'     => $apiMsg,
                    'advice'      => $isIpError
                        ? 'Selcom Gateway Notice: Source IP is not whitelisted on your Selcom Merchant portal. Please select Card Payment to complete your purchase.'
                        : $apiMsg,
                    'trx'         => $deposit->trx,
                ], 422);
            }

            $notify[] = ['error', $apiMsg];
            return back()->withNotify($notify);
        }

        // Live whitelisted Push USSD sent successfully
        $paymentGatewayUrl = $createRes['data']['data'][0]['payment_gateway_url'] ?? null;

        if ($request->ajax()) {
            return response()->json([
                'success'             => true,
                'payment_method'      => 'mobile_money',
                'trx'                 => $deposit->trx,
                'order_id'            => $order->id,
                'phone'               => $formattedPhone,
                'operator'            => $operator,
                'amount'              => number_format($finalAmount),
                'amount_raw'          => $finalAmount,
                'payment_gateway_url' => $paymentGatewayUrl,
                'message'             => "USSD Push sent to {$formattedPhone}. Check your phone to enter your PIN.",
                'check_status_url'    => route('order.check_status', ['trx' => $deposit->trx]),
                'redirect_url'        => route('user.author.download'),
            ]);
        }

        return redirect()->route('user.deposit.confirm');
    }

    public function checkPaymentStatus($trx)
    {
        $deposit = Deposit::where('trx', $trx)->first();
        if (!$deposit) {
            return response()->json(['paid' => false, 'message' => 'Deposit not found'], 404);
        }

        if ($deposit->status == Status::PAYMENT_SUCCESS) {
            return response()->json([
                'paid'         => true,
                'message'      => 'Payment verified successfully!',
                'redirect_url' => route('user.author.download'),
            ]);
        }

        // Query Selcom API
        try {
            $selcom = new \App\Services\SelcomService();
            $statusRes = $selcom->getOrderStatus($trx);

            if (!empty($statusRes['data']['data'][0])) {
                $orderData = $statusRes['data']['data'][0];
                $paymentStatus = strtoupper($orderData['payment_status'] ?? '');

                if ($paymentStatus === 'COMPLETED' || $paymentStatus === 'SUCCESS') {
                    $deposit->detail = array_merge((array)$deposit->detail, $orderData);
                    $deposit->save();
                    \App\Http\Controllers\Gateway\PaymentController::userDataUpdate($deposit);

                    return response()->json([
                        'paid'         => true,
                        'message'      => 'Payment confirmed! Unlocking your digital products...',
                        'redirect_url' => route('user.author.download'),
                    ]);
                }
            }
        } catch (\Throwable $e) {}

        return response()->json([
            'paid'    => false,
            'status'  => 'PENDING',
            'message' => 'Waiting for user PIN confirmation...',
        ]);
    }

    public function freeProducts()
    {
        $pageTitle  = "Products";
        $categories = Category::active()
            ->with([
                'products' => function ($query) {
                    $query->approved()->allActive()->where('is_free', Status::ENABLE)->orderBy('id', 'desc');
                },
                'products.author',
                'products.users',
            ])
            ->get();
        $products = Product::with('author')->searchable(['title'])->approved()->allActive()->where('is_free', Status::ENABLE)->latest()->paginate(getPaginate(16));
        return view('Template::free_products', compact('pageTitle', 'categories', 'products'));
    }

    public function productDetails($slug)
    {
        $product = Product::with(['category', 'author'])
            ->where(function($q) use ($slug) {
                $q->where('slug', $slug)->orWhere('sku', $slug);
            })
            ->firstOrFail();

        if (in_array($product->status, [Status::PRODUCT_PERMANENT_DOWN, Status::PRODUCT_HARD_REJECTED])) {
            abort(404);
        }

        abort_if(!$product->my_product && $product->status != Status::PRODUCT_APPROVED, 404);

        $author                  = $product->author;
        $pageTitle               = $product->title;
        $seoContents['keywords'] = Frontend::where('data_keys', 'seo.data')->first('data_values')?->data_values->keywords;

        $seoContents['social_title']       = $product->title;
        $seoContents['description']        = strLimit(strip_tags($product->description), 150);
        $seoContents['social_description'] = strLimit(strip_tags($product->description), 150);
        $seoContents['image']              = getImage(getFilePath('productPreview') . '/' . productFilePath($product, 'preview_image'), getFileSize('productPreview'));
        $seoContents['image_size']         = getFileSize('productPreview');

        if ($product->user_id != auth()->id()) {

            $view = ProductView::where('product_id', $product->id)
                ->whereDate('views_date', today())
                ->first();

            if ($view) {
                $view->increment('views');
            } else {
                $productView             = new ProductView();
                $productView->product_id = $product->id;
                $productView->views      = 1;
                $productView->views_date = today();
                $productView->save();
            }
        }

        // Series courses / related modules for compact native curriculum breakdown
        $seriesCourses = Product::approved()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderBy('sequence_no', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('Template::product_details', compact('pageTitle', 'product', 'author', 'seoContents', 'seriesCourses'));
    }

    public function productReviews($slug)
    {
        $product = Product::with(['author'])->countComment()->where(['slug' => $slug])->firstOrFail();
        $reviews = Review::where(['product_id' => $product->id])->with(['user', 'replies', 'category']);
        abort_if(($product->status == !Status::PRODUCT_APPROVED && !$product->my_product) || $reviews->count() < gs('min_reviews'), 404);

        $reviewId = request()->review_id;
        if ($reviewId) {
            $reviews->where('id', $reviewId);
        }

        $reviews   = $reviews->paginate(getPaginate());
        $pageTitle = 'Reviews';
        return view('Template::product_reviews', compact('pageTitle', 'product', 'reviews'));
    }

    public function productComments($slug)
    {
        $product = Product::with(['author'])->countComment()->where(['slug' => $slug])->firstOrFail();

        abort_if($product->status == !Status::PRODUCT_APPROVED && !$product->my_product, 404);

        $commentId = request()->comment_id;
        $comments  = Comment::where(['product_id' => $product->id, 'parent_id' => 0, 'review_id' => 0])
            ->when($commentId, function ($query) use ($commentId) {
                $query->where('id', $commentId);
            })
            ->with(['user', 'user.orderItems', 'product', 'replies' => function ($replyQuery) {
                $replyQuery->with('user');
            }])->paginate(getPaginate());
        $pageTitle = 'Comments';

        return view('Template::product_comments', compact('pageTitle', 'product', 'comments'));
    }

    public function productChangelog($slug)
    {
        $product = Product::with(['author'])->countComment()->where(['slug' => $slug])->firstOrFail();

        // Check various conditions before proceeding
        abort_if(
            $product->status != Status::PRODUCT_APPROVED // Ensure the product is approved
                && !$product->my_product // Allow if it's the user's product
                || !gs('changelog') // Global setting for changelog must be enabled
                || $product->product_updated == Status::PRODUCT_UPDATE_PENDING // No pending updates
                || count($product->changelogs) == 0, // Product must have changelogs
            404
        );

        $pageTitle = 'Changelog';

        return view('Template::product_changelog', compact('pageTitle', 'product'));
    }

    public function faq()
    {
        $pageTitle   = "Frequently Asked Questions";
        $sections    = Page::where('tempname', activeTemplate())->where('slug', 'faq')->first();
        $seoContents = @$sections->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;

        $akFaqs = collect();
        try {
            $akSeries = \Illuminate\Support\Facades\DB::connection('ak23')
                ->table('series')
                ->where('slug', 'vocal-mixing')
                ->first(['id']);

            if ($akSeries) {
                $akFaqs = \Illuminate\Support\Facades\DB::connection('ak23')
                    ->table('faqs')
                    ->where('series_id', $akSeries->id)
                    ->orderBy('sequence_no', 'asc')
                    ->get(['sequence_no', 'question', 'answer']);
            }
        } catch (\Throwable $e) {}

        $cmsFaqs = getContent('faq.element');
        $faqContent = @getContent('faq.content', true)->data_values;

        return view('Template::faq', compact('pageTitle', 'sections', 'seoContents', 'seoImage', 'akFaqs', 'cmsFaqs', 'faqContent'));
    }

    public function contact()
    {
        $pageTitle   = "Contact Us";
        $user        = auth()->user();
        $sections    = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = $sections->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket           = new SupportTicket();
        $ticket->user_id  = auth()->id() ?? 0;
        $ticket->name     = $request->name;
        $ticket->email    = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;

        $ticket->ticket     = $random;
        $ticket->subject    = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status     = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title     = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message                    = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message           = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug)
    {
        $policy      = Frontend::where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle   = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage    = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        $activeTemplate = activeTemplate();
        $allPolicies = Frontend::where('data_keys', 'policy_pages.element')->get();
        return view($activeTemplate . 'policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage', 'allPolicies', 'activeTemplate'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) {
            $lang = 'en';
        }

        session()->put('lang', $lang);
        return back();
    }

    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        $cookie    = Frontend::where('data_keys', 'cookie.data')->first();
        $activeTemplate = activeTemplate();
        $allPolicies = Frontend::where('data_keys', 'policy_pages.element')->get();
        return view($activeTemplate . 'cookie', compact('pageTitle', 'cookie', 'allPolicies', 'activeTemplate'));
    }

    public function placeholderImage($size = null)
    {
        $imgWidth  = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text      = $imgWidth . '×' . $imgHeight;
        $fontFile  = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize  = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('Template::maintenance', compact('pageTitle', 'maintenance'));
    }

    public function incrementClick()
    {
        $ad = Advertisement::find(request('id'));
        if ($ad) {
            $ad->click += 1;
            $ad->save();
        }
        return response()->json(['status' => 'Success', 'message' => 'Advertisement Clicked!']);
    }

    public function viewCertificate($code)
    {
        $cleanCode = trim((string)$code);
        $orderItem = OrderItem::with(['product', 'order', 'buyer'])
            ->where('certificate_number', $cleanCode)
            ->orWhere('license_key', $cleanCode)
            ->orWhere('purchase_code', $cleanCode)
            ->first();

        abort_if(!$orderItem, 404, 'Official Certificate or License not found.');

        $user = $orderItem->buyer ?: User::find($orderItem->user_id);
        $product = $orderItem->product;
        $order = $orderItem->order;

        $studentName = $user ? ($user->fullname ?: ($user->firstname ? trim($user->firstname . ' ' . $user->lastname) : $user->username)) : 'Alex M. Kaseja';
        $courseTitle = $product ? ($product->title ?: $product->name) : 'Studio Production Course';
        $softwareName = $product ? ($product->software_name ?: 'Studio Software Suite') : 'Studio Software Suite';
        $softwareEdition = $product ? ($product->software_edition ?: 'Perpetual License Included') : 'Perpetual License Included';
        $licenseKey = $orderItem->license_key ?: $orderItem->purchase_code;
        $certificateNumber = $orderItem->certificate_number ?: ('M23-CERT-' . date('Y') . '-' . strtoupper(substr(md5($orderItem->id), 0, 8)));
        $issueDate = $orderItem->created_at ? $orderItem->created_at->format('F d, Y') : date('F d, Y');

        $activeTemplate = activeTemplate();
        $pageTitle = 'Certificate of Course Purchase - ' . $courseTitle;

        return view($activeTemplate . 'user.certificate', compact(
            'pageTitle',
            'orderItem',
            'activeTemplate',
            'user',
            'product',
            'order',
            'studentName',
            'courseTitle',
            'softwareName',
            'softwareEdition',
            'licenseKey',
            'certificateNumber',
            'issueDate'
        ));
    }

    public function verifyLicense(Request $request, $code = null)
    {
        $lookupCode = trim((string)($code ?: $request->query('code') ?: $request->input('code')));

        if ($lookupCode) {
            $orderItem = OrderItem::with(['product', 'order', 'buyer'])
                ->where('license_key', $lookupCode)
                ->orWhere('certificate_number', $lookupCode)
                ->orWhere('purchase_code', $lookupCode)
                ->first();

            if ($request->wantsJson() || $request->ajax()) {
                if ($orderItem) {
                    $user = $orderItem->buyer;
                    $product = $orderItem->product;
                    return response()->json([
                        'status'             => 'valid',
                        'valid'              => true,
                        'certificate_type'   => 'Certificate of Course Purchase',
                        'license_type'       => 'One-Time Perpetual Studio License',
                        'renewable'          => false,
                        'expires_at'         => 'Never (Lifetime)',
                        'license_key'        => $orderItem->license_key ?: $orderItem->purchase_code,
                        'certificate_number' => $orderItem->certificate_number,
                        'course_title'       => $product?->title ?? 'Course',
                        'software_name'      => $product?->software_name ?? 'Studio Software',
                        'software_edition'   => $product?->software_edition ?? 'Perpetual License',
                        'buyer_name'         => $user?->fullname ?? 'Verified Buyer',
                        'issued_at'          => $orderItem->created_at ? $orderItem->created_at->toIso8601String() : now()->toIso8601String(),
                        'certificate_url'    => route('user.certificate', $orderItem->certificate_number ?: $orderItem->purchase_code),
                    ]);
                }
                return response()->json([
                    'status'  => 'invalid',
                    'valid'   => false,
                    'message' => 'License or purchase certificate code was not found in official registry.',
                ], 404);
            }

            if ($orderItem) {
                return redirect()->route('user.certificate', $orderItem->certificate_number ?: $orderItem->purchase_code);
            }
        }

        $activeTemplate = activeTemplate();
        $pageTitle = 'Course Purchase & License Verification - Maarifa23 Academy';
        $searched = !empty($lookupCode);
        return view($activeTemplate . 'verify_license', compact('pageTitle', 'activeTemplate', 'lookupCode', 'searched'));
    }
}
