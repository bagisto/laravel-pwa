<?php
    Route::group(['middleware' => ['web']], function () {
        //Store front home
        // PushNotifications/', 'Webkul\PWA\Http\Controllers\SinglePageController@home')->name('shop.home.index');
    });

    Route::group(['middleware' => ['web','locale', 'currency']], function ($router) {
        Route::get('/mobile/{any?}', 'Webkul\PWA\Http\Controllers\SinglePageController@index')->where('any', '.*');
    });

    Route::prefix('admin/pwa/pushnotification')->group(function () {
        // PushNotifications routes
        Route::get('/','Webkul\PWA\Http\Controllers\PushNotificationController@index')->defaults('_config', ['view' => 'pwa::PushNotification.index'
        ])->name('pwa.pushnotification.index');
        // create
        Route::get('/create','Webkul\PWA\Http\Controllers\PushNotificationController@create')->defaults('_config', ['view' => 'pwa::PushNotification.create'
        ])->name('pwa.pushnotification.create');
        // store
        Route::post('/store','Webkul\PWA\Http\Controllers\PushNotificationController@store')->defaults('_config', ['redirect' => 'pwa.pushnotification.index'
        ])->name('pwa.pushnotification.store');
        //edit
        Route::get('/edit/{id}','Webkul\PWA\Http\Controllers\PushNotificationController@edit')->defaults('_config', ['view' => 'pwa::PushNotification.edit'
        ])->name('pwa.pushnotification.edit');
        // update
        Route::post('/update/{id}','Webkul\PWA\Http\Controllers\PushNotificationController@update')->defaults('_config', ['redirect' => 'pwa.PushNotification.index'
        ])->name('pwa.pushnotification.update');
        // delete
        Route::get('/delete/{id}','Webkul\PWA\Http\Controllers\PushNotificationController@destroy')->defaults('_config', ['redirect' => 'pwa.PushNotification.index'
        ])->name('pwa.pushnotification.delete');

        //push to firebase
        Route::get('/push/{id}','Webkul\PWA\Http\Controllers\PushNotificationController@pushtofirebase')->defaults('_config', ['redirect' => 'pwa.PushNotification.index'
        ])->name('pwa.pushnotification.pushtofirebase');
    });

    Route::prefix('paypal/standard')->group(function () {
        Route::get('/success', 'Webkul\PWA\Http\Controllers\StandardController@success')->name('paypal.standard.success');

        Route::get('/cancel', 'Webkul\PWA\Http\Controllers\StandardController@cancel')->name('paypal.standard.cancel');
    });

?>