<?php

use Illuminate\Support\Facades\Route;
use Webkul\PWA\Http\Controllers\Shop\Customer\Account\AddressController;
use Webkul\PWA\Http\Controllers\Shop\Customer\Account\DownloadableProductController;
use Webkul\PWA\Http\Controllers\Shop\Customer\Account\OrderController;
use Webkul\PWA\Http\Controllers\Shop\Customer\Account\WishlistController;
use Webkul\PWA\Http\Controllers\Shop\Customer\CustomerController;
use Webkul\PWA\Http\Controllers\Shop\Customer\ForgotPasswordController;
use Webkul\PWA\Http\Controllers\Shop\Customer\RegistrationController;
use Webkul\PWA\Http\Controllers\Shop\Customer\ResetPasswordController;
use Webkul\PWA\Http\Controllers\Shop\Customer\SessionController;
use Webkul\Shop\Http\Controllers\DataGridController;

Route::group(['middleware' => ['locale', 'theme', 'currency']], function () {

    Route::prefix('mobile/customer')->group(function () {
        /**
         * Forgot password routes.
         */
        Route::controller(ForgotPasswordController::class)->prefix('forgot-password')->group(function () {
            Route::get('', 'create')->name('pwa.customers.forgot_password.create');

            Route::post('', 'store')->name('pwa.customers.forgot_password.store');
        });

        /**
         * Reset password routes.
         */
        Route::controller(ResetPasswordController::class)->prefix('reset-password')->group(function () {
            Route::get('{token}', 'create')->name('pwa.customers.reset_password.create');

            Route::post('', 'store')->name('pwa.customers.reset_password.store');
        });

        /**
         * Login routes.
         */
        Route::controller(SessionController::class)->prefix('login')->group(function () {
            Route::get('', 'show')->name('pwa.customer.session.index');

            Route::post('', 'create')->name('pwa.customer.session.create');
        });

        /**
         * Registration routes.
         */
        Route::controller(RegistrationController::class)->group(function () {
            Route::prefix('register')->group(function () {
                Route::get('', 'index')->name('pwa.customers.register.index');

                Route::post('', 'store')->name('pwa.customers.register.store');
            });

            /**
             * Customer verification routes.
             */
            Route::get('verify-account/{token}', 'verifyAccount')->name('pwa.customers.verify');

            Route::get('resend/verification/{email}', 'resendVerificationEmail')->name('pwa.customers.resend.verification_email');
        });

        /**
         * Customer authenticated routes. All the below routes only be accessible
         * if customer is authenticated.
         */
        Route::group(['middleware' => ['customer']], function () {
            /**
             * Datagrid routes.
             */
            Route::get('datagrid/look-up', [DataGridController::class, 'lookUp'])->name('pwa.customer.datagrid.look_up');

            /**
             * Logout.
             */
            Route::delete('logout', [SessionController::class, 'destroy'])->defaults('_config', [
                'redirect' => 'pwa.customer.session.index',
            ])->name('pwa.customer.session.destroy');

            /**
             * Wishlist.
             */
            Route::get('wishlist', [WishlistController::class, 'index'])->name('pwa.customers.account.wishlist.index');

            /**
             * Customer account. All the below routes are related to
             * customer account details.
             */
            Route::prefix('account')->group(function () {
                /**
                 * Profile.
                 */
                Route::controller(CustomerController::class)->prefix('profile')->group(function () {
                    Route::get('', 'index')->name('pwa.customers.account.profile.index');

                    Route::get('edit', 'edit')->name('pwa.customers.account.profile.edit');

                    Route::post('edit', 'update')->name('pwa.customers.account.profile.update');

                    Route::post('destroy', 'destroy')->name('pwa.customers.account.profile.destroy');

                    Route::get('reviews', 'reviews')->name('pwa.customers.account.reviews.index');
                });

                /**
                 * Addresses.
                 */
                Route::controller(AddressController::class)->prefix('addresses')->group(function () {
                    Route::get('', 'index')->name('pwa.customers.account.addresses.index');

                    Route::get('create', 'create')->name('pwa.customers.account.addresses.create');

                    Route::post('create', 'store')->name('pwa.customers.account.addresses.store');

                    Route::get('edit/{id}', 'edit')->name('pwa.customers.account.addresses.edit');

                    Route::put('edit/{id}', 'update')->name('pwa.customers.account.addresses.update');

                    Route::patch('edit/{id}', 'makeDefault')->name('pwa.customers.account.addresses.update.default');

                    Route::delete('delete/{id}', 'destroy')->name('pwa.customers.account.addresses.delete');
                });

                /**
                 * Orders.
                 */
                Route::controller(OrderController::class)->prefix('orders')->group(function () {
                    Route::get('', 'index')->name('pwa.customers.account.orders.index');

                    Route::get('view/{id}', 'view')->name('pwa.customers.account.orders.view');

                    Route::post('cancel/{id}', 'cancel')->name('pwa.customers.account.orders.cancel');

                    Route::get('print/Invoice/{id}', 'printInvoice')->name('pwa.customers.account.orders.print-invoice');
                });

                /**
                 * Downloadable products.
                 */
                Route::controller(DownloadableProductController::class)->prefix('downloadable-products')->group(function () {
                    Route::get('', 'index')->name('pwa.customers.account.downloadable_products.index');

                    Route::get('download/{id}', 'download')->name('pwa.customers.account.downloadable_products.download');
                });
            });
        });
    });
});
