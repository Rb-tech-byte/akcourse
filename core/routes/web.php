<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('cron', 'CronController@cron')->name('cron');

// Gracefully handle Cloudflare RUM (Real User Monitoring) / Web Analytics beacon pings
Route::any('cdn-cgi/{any}', function () {
    return response('', 204);
})->where('any', '.*');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('CartController')->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/calculate', 'calculate')->name('calculate');
    Route::post('/', 'store')->name('store');
    Route::post('/quick-add', 'quickAdd')->name('quick_add');
    Route::delete('/{id}', 'delete')->name('delete');
    Route::get('/toggle-extended/{id}', 'toggleExtended')->name('extended.toggle');
    Route::match(['get', 'post'], '/apply-coupon', 'applyCoupon')->name('apply.coupon');
    Route::match(['get', 'post'], '/remove-coupon', 'removeCoupon')->name('remove.coupon');
});

Route::controller('SiteController')->group(function () {
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/products', 'products')->name('products');
    Route::get('/courses', 'products')->name('courses');
    Route::get('/checkout/{sku?}', 'checkout')->name('order.checkout');
    Route::post('/checkout/{sku?}', 'processCheckout')->name('order.process');
    Route::get('/checkout/status/{trx}', 'checkPaymentStatus')->name('order.check_status');
    Route::get('/certificate/{code}', 'viewCertificate')->name('user.certificate');
    Route::get('/verify-license/{code?}', 'verifyLicense')->name('license.verify');
    Route::get('/free-products', 'freeProducts')->name('free.products');
    Route::get('/products/{slug}', 'productDetails')->name('product.details');
    Route::get('/products/{slug}/reviews', 'productReviews')->name('product.reviews');
    Route::get('/products/{slug}/comments', 'productComments')->name('product.comments');
    Route::get('/products/{slug}/changelog', 'productChangelog')->name('product.changelog');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode','maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    Route::post('click/{id}', 'incrementClick')->name('advertisement.click');

    Route::get('/', 'index')->name('home');
    Route::get('/{slug}', 'pages')->where('slug', '[A-Za-z0-9\-_]+')->name('pages');
});


