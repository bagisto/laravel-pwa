<?php

namespace Webkul\PWA\Listeners;

use Detection\MobileDetect;

/**
 * Core Config event handler
 */
class PWAListeners
{
    public function redirectToPWA()
    {
        if (core()->getConfigData('pwa.settings.general.redirect_to_pwa_if_mobile')) {
            $detect = new MobileDetect;

            if (
                $detect->isMobile()
                && request()->url() == route('shop.home.index')
            ) {
                // return redirect()->to('/mobile')->send();
            }
        }
    }
}
