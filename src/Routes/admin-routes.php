<?php

use Illuminate\Support\Facades\Route;
use Webkul\PWA\Http\Controllers\Admin\LayoutController;
use Webkul\PWA\Http\Controllers\Admin\PushNotificationController;

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            Route::prefix('pwa')->group(function () {

                Route::controller(PushNotificationController::class)->group(function () {
                    // PushNotifications routes
                    Route::get('pushnotification', 'index')->name('admin.pwa.pushnotification.index');

                    Route::get('pushnotification/create', 'create')->name('admin.pwa.pushnotification.create');

                    Route::post('pushnotification/store', 'store')->name('admin.pwa.pushnotification.store');

                    Route::get('pushnotification/edit/{id}', 'edit')->name('admin.pwa.pushnotification.edit');

                    Route::post('pushnotification/update/{id}', 'update')->name('admin.pwa.pushnotification.update');

                    Route::get('pushnotification/delete/{id}', 'destroy')->name('admin.pwa.pushnotification.delete');

                    Route::get('pushnotification/push/{id}', 'pushtofirebase')->name('pwa.pushnotification.pushtofirebase');
                });

                Route::controller(LayoutController::class)->group(function () {
                    // layout routes
                    Route::get('layout', 'index')->name('admin.pwa.layout');

                    Route::post('layout', 'store')->name('admin.pwa.layout.store')
                        ->defaults('_config', [
                            'redirect' => 'admin.pwa.layout',
                        ]);
                });
            });
        });
    });
});
