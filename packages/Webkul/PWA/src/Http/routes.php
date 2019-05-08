<?php
    Route::group(['middleware' => ['web','locale', 'currency']], function ($router) {
        Route::get('/mobile/{any?}', 'Webkul\PWA\Http\Controllers\SinglePageController@index')->where('any', '.*');
    });

?>