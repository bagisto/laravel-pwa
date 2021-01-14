<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\SocialLogin\Repositories\CustomerSocialAccountRepository;

/**
 * Cart controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2021 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class LoginController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * CustomerSocialAccountRepository
     *
     * @var \Webkul\SocialLogin\Repositories\CustomerSocialAccountRepository
     */
    protected $customerSocialAccountRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\SocialLogin\Repositories\CustomerSocialAccountRepository  $customerSocialAccountRepository
     * @return void
     */
    public function __construct(CustomerSocialAccountRepository $customerSocialAccountRepository)
    {
        $this->customerSocialAccountRepository = $customerSocialAccountRepository;

        $this->_config = request('_config');
    }

    /**
     * Redirects to the social provider
     *
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        $client_id = core()->getConfigData('pwa.social_login.' . $provider . '.' . strtoupper($provider) . '_CLIENT_ID');
        $client_secret = core()->getConfigData('pwa.social_login.' . $provider . '.' . strtoupper($provider) . '_CLIENT_SECRET');
        $redirect_uri = core()->getConfigData('pwa.social_login.' . $provider . '.' . strtoupper($provider) . '_CALLBACK_URL');

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->route('customer.session.index');
        }
    }

    /**
     * Handles callback
     *
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->intended("http://localhost/mobile/customer/login-register");
        }

        $customer = $this->customerSocialAccountRepository->findOrCreateCustomer($user, $provider);

        auth()->guard('customer')->login($customer, true);

        // Event passed to prepare cart after login
        Event::dispatch('customer.after.login', $customer->email);

        return redirect()->intended("http://localhost/mobile/customer/account/dashboard");
    }
}