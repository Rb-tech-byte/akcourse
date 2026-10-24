<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Single Vendor AK23 Academy
|--------------------------------------------------------------------------
*/

Route::namespace('Api')->name('api.')->group(function () {
    // Single Vendor Academy API
    Route::controller('CourseApiController')->prefix('courses')->group(function () {
        Route::get('/', 'courses')->name('courses.all');
        Route::get('/detail', 'courseDetail')->name('courses.detail');
    });

    // One-Time Lifetime License & Certificate Verification API
    Route::controller('LicenseApiController')->prefix('license')->group(function () {
        Route::match(['get', 'post'], '/verify', 'verify')->name('license.verify');
        Route::get('/check/{code}', 'check')->name('license.check');
    });

});
