<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Socialite;

class SocialLogin
{
    private $provider;
    private $fromApi;

    public function __construct($provider,$fromApi = false)
    {
        $this->provider = $provider;
        $this->fromApi = $fromApi;
        $this->configuration();
    }

    public function redirectDriver()
    {
        return Socialite::driver($this->provider)->redirect();
    }

    private function configuration()
    {
        $provider      = $this->provider;
        $configuration = @gs('socialite_credentials')->$provider;
        $provider      = $this->fromApi && $provider == 'linkedin' ? 'linkedin-openid' : $provider;

        $clientId     = $configuration->client_id ?? null;
        $clientSecret = $configuration->client_secret ?? null;

        // Fallback to env or config/services.php if general_settings has placeholders or empty
        if (empty($clientId) || str_contains($clientId, '---')) {
            $clientId = env(strtoupper($provider) . '_CLIENT_ID', config("services.{$provider}.client_id"));
        }
        if (empty($clientSecret) || str_contains($clientSecret, '---')) {
            $clientSecret = env(strtoupper($provider) . '_CLIENT_SECRET', config("services.{$provider}.client_secret"));
        }

        Config::set('services.' . $provider, [
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect'      => route('user.social.login.callback', $provider),
        ]);
    }

    public function login()
    {
        $provider = $this->provider;
        $provider = $this->fromApi && $provider == 'linkedin' ? 'linkedin-openid' : $provider;
        $driver   = Socialite::driver($provider);
        if ($this->fromApi) {
            try {
                $user = (object)$driver->userFromToken(request()->token)->user;
            } catch (\Throwable $th) {
                throw new Exception('Something went wrong authenticating with ' . ucfirst($provider));
            }
        } else {
            $user = $driver->user();
        }

        if ($provider == 'linkedin-openid') {
            $user->id = $user->sub;
        }

        // 1. Check existing user by provider_id
        $userData = User::where('provider_id', $user->id)->first();

        // 2. If not linked by provider_id, check if user exists with this email and link
        if (!$userData && !empty($user->email)) {
            $existingUser = User::where('email', $user->email)->first();
            if ($existingUser) {
                $existingUser->provider    = $this->provider;
                $existingUser->provider_id = $user->id;

                if (empty($existingUser->firstname) && @$user->user['given_name']) {
                    $existingUser->firstname = $user->user['given_name'];
                }
                if (empty($existingUser->lastname) && @$user->user['family_name']) {
                    $existingUser->lastname = $user->user['family_name'];
                }
                if (empty($existingUser->avatar) && @$user->avatar) {
                    $existingUser->avatar = $user->avatar;
                }
                $existingUser->profile_complete = 1;
                $existingUser->save();
                $userData = $existingUser;
            }
        }

        // 3. If new user, create account
        if (!$userData) {
            if (!gs('registration')) {
                throw new Exception('New account registration is currently disabled');
            }
            $userData = $this->createUser($user, $this->provider);
        }

        if ($this->fromApi) {
            $tokenResult = $userData->createToken('auth_token')->plainTextToken;
            $this->loginLog($userData);
            return [
                'user'         => $userData,
                'access_token' => $tokenResult,
                'token_type'   => 'Bearer',
            ];
        }

        Auth::login($userData);
        $this->loginLog($userData);

        $redirection = Intended::getRedirection();
        return $redirection ? $redirection : to_route('user.home');
    }

    private function createUser($user, $provider)
    {
        $general  = gs();
        $password = getTrx(12);

        $firstName = @$user->user['given_name'] ?: @$user->first_name;
        $lastName  = @$user->user['family_name'] ?: @$user->last_name;

        if ((!$firstName || !$lastName) && @$user->name) {
            $pieces    = explode(' ', trim($user->name));
            $firstName = array_shift($pieces);
            $lastName  = implode(' ', $pieces) ?: 'User';
        }

        if (!$firstName) {
            $firstName = 'Customer';
            $lastName  = 'User';
        }

        $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode('@', $user->email)[0]));
        if (empty($baseUsername)) {
            $baseUsername = 'user' . rand(100, 999);
        }
        $username = $baseUsername;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $i++;
        }

        $referBy = session()->get('reference');
        $referUser = $referBy ? User::where('username', $referBy)->first() : null;

        $newUser = new User();
        $newUser->provider_id      = $user->id;
        $newUser->provider         = $provider;
        $newUser->email            = $user->email;
        $newUser->username         = $username;
        $newUser->firstname        = $firstName;
        $newUser->lastname         = $lastName;
        $newUser->password         = Hash::make($password);
        $newUser->ref_by           = $referUser ? $referUser->id : 0;
        $newUser->status           = Status::USER_ACTIVE;
        $newUser->kv               = $general->kv ? Status::NO : Status::YES;
        $newUser->ev               = Status::VERIFIED;
        $newUser->sv               = Status::VERIFIED;
        $newUser->ts               = Status::DISABLE;
        $newUser->tv               = Status::VERIFIED;
        $newUser->profile_complete = 1;
        $newUser->address          = 'Ali Hassan Mwinyi Rd';
        $newUser->city             = 'Dar es Salaam';
        $newUser->country_name     = 'Tanzania';
        $newUser->zip              = '14111';
        if (@$user->avatar) {
            $newUser->avatar       = $user->avatar;
        }
        $newUser->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $newUser->id;
        $adminNotification->title     = "New member registered via " . ucfirst($provider);
        $adminNotification->click_url = urlPath('admin.users.detail', $newUser->id);
        $adminNotification->save();

        return $newUser;
    }

    private function loginLog($user)
    {
        try {
            $ip = getRealIP();
            $exist = UserLogin::where('user_ip', $ip)->first();
            $userLogin = new UserLogin();

            //Check exist or not
            if ($exist) {
                $userLogin->longitude =  $exist->longitude;
                $userLogin->latitude =  $exist->latitude;
                $userLogin->city =  $exist->city;
                $userLogin->country_code = $exist->country_code;
                $userLogin->country =  $exist->country;
            } else {
                $info = json_decode(json_encode(getIpInfo()), true);
                $userLogin->longitude =  is_array(@$info['long']) ? implode(',', $info['long']) : (string)(@$info['long'] ?? '');
                $userLogin->latitude =  is_array(@$info['lat']) ? implode(',', $info['lat']) : (string)(@$info['lat'] ?? '');
                $userLogin->city =  is_array(@$info['city']) ? implode(',', $info['city']) : (string)(@$info['city'] ?? '');
                $userLogin->country_code = is_array(@$info['code']) ? implode(',', $info['code']) : (string)(@$info['code'] ?? '');
                $userLogin->country =  is_array(@$info['country']) ? implode(',', $info['country']) : (string)(@$info['country'] ?? '');
            }

            $userAgent = osBrowser();
            $userLogin->user_id = $user->id;
            $userLogin->user_ip =  $ip;

            $userLogin->browser = @$userAgent['browser'];
            $userLogin->os = @$userAgent['os_platform'];
            $userLogin->save();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('UserLogin tracking error in social login: ' . $e->getMessage());
        }
    }
}
