<?php
    Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
        //Store front home
        Route::get('/', 'Webkul\PWA\Http\Controllers\SinglePageController@home')->name('shop.home.index');
    });

    Route::group(['middleware' => ['web','locale', 'currency']], function ($router) {
        Route::get('/mobile/{any?}', 'Webkul\PWA\Http\Controllers\SinglePageController@index')->where('any', '.*');
    });
?>