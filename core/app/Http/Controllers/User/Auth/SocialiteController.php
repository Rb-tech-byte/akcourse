<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Lib\SocialLogin;

class SocialiteController extends Controller
{

    public function socialLogin($provider)
    {
        $credentials = @gs('socialite_credentials')->$provider;
        $clientId    = $credentials->client_id ?? null;
        if (empty($clientId) || str_contains($clientId, '---')) {
            $clientId = env(strtoupper($provider) . '_CLIENT_ID');
        }

        if (empty($clientId) || str_contains($clientId, '---')) {
            $notify[] = ['error', ucfirst($provider) . ' Social Login is enabled, but Client ID is not configured yet in Admin Panel (Settings > Social Login Credentials) or .env file.'];
            return back()->withNotify($notify);
        }

        try {
            $socialLogin = new SocialLogin($provider);
            return $socialLogin->redirectDriver();
        } catch (\Throwable $e) {
            $notify[] = ['error', 'Unable to initiate ' . ucfirst($provider) . ' login: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function callback($provider)
    {
        try {
            $socialLogin = new SocialLogin($provider);
            return $socialLogin->login();
        } catch (\Throwable $e) {
            $notify[] = ['error', $e->getMessage()];
            return to_route('user.login')->withNotify($notify);
        }
    }
}
