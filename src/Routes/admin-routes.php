<?php

use Illuminate\Support\Facades\Route;
use Webkul\PWA\Http\Controllers\Admin\LayoutController;
use Webkul\PWA\Http\Controllers\Admin\PushNotificationController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/pwa'], function () {
    /**
     * Notification routes.
     */
    Route::controller(PushNotificationController::class)->group(function () {
        Route::get('pushnotification', 'index')->name('admin.pwa.pushnotification.index');

        Route::get('pushnotification/create', 'create')->name('admin.pwa.pushnotification.create');

        Route::post('pushnotification/store', 'store')->name('admin.pwa.pushnotification.store');

        Route::get('pushnotification/edit/{id}', 'edit')->name('admin.pwa.pushnotification.edit');

        Route::post('pushnotification/update/{id}', 'update')->name('admin.pwa.pushnotification.update');

        Route::get('pushnotification/delete/{id}', 'destroy')->name('admin.pwa.pushnotification.delete');

        Route::get('pushnotification/push/{id}', 'pushToFirebase')->name('pwa.pushnotification.pushtofirebase');
    });

    /**
     * Pwa Layout routes.
     */
    Route::controller(LayoutController::class)->group(function () {
        Route::get('layout', 'index')->name('admin.pwa.layout');

        Route::post('layout', 'store')->name('admin.pwa.layout.store');
    });
});
