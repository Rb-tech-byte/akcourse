<?php

use Illuminate\Support\Facades\Route;

Route::get('paypal-sdk', 'PaypalSdk\ProcessController@ipn')->name('PaypalSdk');
Route::post('stripe-js', 'StripeJs\ProcessController@ipn')->name('StripeJs');
Route::post('stripe-v3', 'StripeV3\ProcessController@ipn')->name('StripeV3');
Route::post('coinpayments', 'Coinpayments\ProcessController@ipn')->name('Coinpayments');
Route::any('selcom', 'selcom\ProcessController@ipn')->name('selcom');
Route::any('pesapal', 'PesapalGateway\ProcessController@ipn')->name('PesapalGateway');
Route::any('dynamic/{alias?}', 'Dynamic\ProcessController@ipn')->name('dynamic');
Route::any('custom/{alias?}', 'Dynamic\ProcessController@ipn')->name('custom');
