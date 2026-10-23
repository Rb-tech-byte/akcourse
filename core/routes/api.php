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

});
