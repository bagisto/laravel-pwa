<?php

use Illuminate\Support\Facades\Route;
use Webkul\PWA\Http\Controllers\Admin\LayoutController;
use Webkul\PWA\Http\Controllers\Admin\PushNotificationController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/pwa'], function () {
    /**
     * Notification routes.
     */
    Route::controller(PushNotificationController::class)->prefix('push-notification')->group(function () {
        Route::get('', 'index')->name('admin.pwa.push-notification.index');

        Route::get('create', 'create')->name('admin.pwa.push-notification.create');

        Route::post('store', 'store')->name('admin.pwa.push-notification.store');

        Route::get('edit/{id}', 'edit')->name('admin.pwa.push-notification.edit');

        Route::post('update/{id}', 'update')->name('admin.pwa.push-notification.update');

        Route::get('delete/{id}', 'destroy')->name('admin.pwa.push-notification.delete');

        Route::get('push/{id}', 'pushToFirebase')->name('pwa.push-notification.push-to-firebase');
    });

    /**
     * Pwa Layout routes.
     */
    Route::controller(LayoutController::class)->prefix('layout')->group(function () {
        Route::get('', 'index')->name('admin.pwa.layout');

        Route::post('', 'store')->name('admin.pwa.layout.store');
    });
});