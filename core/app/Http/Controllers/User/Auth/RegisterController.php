<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\Intended;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    use RegistersUsers;

    public function __construct()
    {
        parent::__construct();
    }

    public function showRegistrationForm()
    {
        $pageTitle = "Register";
        if(gs('registration')){
            Intended::identifyRoute();
            return view('Template::user.auth.register', compact('pageTitle'));
        }else{
            return view('Template::user.auth.registration_disabled', compact('pageTitle'));
        }
    }


    protected function validator(array $data)
    {

        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $agree = 'nullable';
        if (gs('agree')) {
            $agree = 'required';
        }

        $validate     = Validator::make($data, [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|string|email|unique:users',
            'password'  => ['required', 'confirmed', $passwordValidation],
            'captcha'   => 'sometimes|required',
            'agree'     => $agree
        ],[
            'firstname.required'=>'The first name field is required',
            'lastname.required'=>'The last name field is required'
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        if (!gs('registration')) {
            $notify[] = ['error', 'Registration not allowed'];
            return back()->withNotify($notify);
        }
        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }



        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }



    protected function create(array $data)
    {
        $referBy = session()->get('reference');
        if ($referBy) {
            $referUser = User::where('username', $referBy)->first();
        } else {
            $referUser = null;
        }

        //User Create
        $user            = new User();
        $user->email     = strtolower($data['email']);
        $user->firstname = $data['firstname'];
        $user->lastname  = $data['lastname'];
        $user->password  = Hash::make($data['password']);
        $user->ref_by    = $referUser ? $referUser->id : 0;
        $user->kv = gs('kv') ? Status::NO : Status::YES;
        $user->ev = gs('ev') ? Status::NO : Status::YES;
        $user->sv = gs('sv') ? Status::NO : Status::YES;
        $user->ts = Status::DISABLE;
        $user->tv = Status::ENABLE;
        $user->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();


        //Login Log Create
        try {
            $ip        = getRealIP();
            $exist     = UserLogin::where('user_ip', $ip)->first();
            $userLogin = new UserLogin();

            if ($exist) {
                $userLogin->longitude    = $exist->longitude;
                $userLogin->latitude     = $exist->latitude;
                $userLogin->city         = $exist->city;
                $userLogin->country_code = $exist->country_code;
                $userLogin->country      = $exist->country;
            } else {
                $info                    = json_decode(json_encode(getIpInfo()), true);
                $userLogin->longitude    = is_array(@$info['long']) ? implode(',', $info['long']) : (string)(@$info['long'] ?? '');
                $userLogin->latitude     = is_array(@$info['lat']) ? implode(',', $info['lat']) : (string)(@$info['lat'] ?? '');
                $userLogin->city         = is_array(@$info['city']) ? implode(',', $info['city']) : (string)(@$info['city'] ?? '');
                $userLogin->country_code = is_array(@$info['code']) ? implode(',', $info['code']) : (string)(@$info['code'] ?? '');
                $userLogin->country      = is_array(@$info['country']) ? implode(',', $info['country']) : (string)(@$info['country'] ?? '');
            }

            $userAgent          = osBrowser();
            $userLogin->user_id = $user->id;
            $userLogin->user_ip = $ip;

            $userLogin->browser = @$userAgent['browser'];
            $userLogin->os      = @$userAgent['os_platform'];
            $userLogin->save();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('UserLogin tracking error in register: ' . $e->getMessage());
        }


        return $user;
    }

    public function checkUser(Request $request){
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email',$request->email)->exists();
            $exist['type'] = 'email';
            $exist['field'] = 'Email';
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile',$request->mobile)->where('dial_code',$request->mobile_code)->exists();
            $exist['type'] = 'mobile';
            $exist['field'] = 'Mobile';
        }
        if ($request->username) {
            $exist['data'] = User::where('username',$request->username)->exists();
            $exist['type'] = 'username';
            $exist['field'] = 'Username';
        }
        return response($exist);
    }

    public function registered()
    {
        return to_route('user.home');
    }

}
