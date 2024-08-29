<?php

namespace Webkul\PWA\Listeners;

use WhichBrowser\Parser;

/**
 * Core Config event handler
 */
class PWAListeners
{
    /**
     * Check device type.
     */
    public function redirectToPWA()
    {
        if (core()->getConfigData('pwa.settings.general.redirect_to_pwa_if_mobile')) {
            $result = new Parser(request()->header('User-Agent'));
            if (
                $result->isType('mobile', 'tablet')
                && request()->url() == route('shop.home.index')
            ) {
                return redirect()->to('/mobile')->send();
            }
        }
    }
}
