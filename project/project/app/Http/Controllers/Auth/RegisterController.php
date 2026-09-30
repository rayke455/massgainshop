<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UserSystemInfo;
use App\Http\Controllers\Controller;
use App\Models\ContentDetails;
use App\Models\User;
use App\Models\UserLogin;
use App\Rules\PhoneLength;
use App\Traits\Notify;
use App\Traits\PageSeo;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Facades\App\Services\Google\GoogleRecaptchaService;

class RegisterController extends Controller
{
    use RegistersUsers, PageSeo, Notify;

    protected $maxAttempts = 3; // Change this to 4 if you want 4 tries
    protected $decayMinutes = 5; // Change this according to your

    protected $redirectTo = '/user/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sponsor($sponsor = '')
    {
        $data['footer'] = footerData();
        $basic = basicControl();
        if ($basic->registration == 0) {
            return redirect('/')->with('warning', 'Registration Has Been Disabled.');
        }

        $data['countries'] = config('country');
        $country_code = null;
        if (!empty($info['code'])) {
            $country_code = @$info['code'][0];
        }

        session()->put('sponsor', $sponsor);
        $data['pageSeo'] = $this->pageSeoData('register', basicControl()->theme);
        $data['registerContent'] = ContentDetails::with('content')
            ->whereHas('content', function ($query) {
                $query->where('theme', basicControl()->theme)->where('name', 'register')->where('type', 'single');
            })
            ->first();
        return view(template() . 'auth.register', $data, compact('country_code'));
    }


    protected function validator(array $data)
    {
        $phoneLength = 15;
        foreach (config('country') as $country) {
            if ($country['phone_code'] == $data['phone_code']) {
                $phoneLength = $country['phoneLength'];
                break;
            }
        }

        $basicControl = basicControl();
        if ($basicControl->strong_password == 0) {
            $rules['password'] = ['required', 'min:6', 'confirmed'];
        } else {
            $rules['password'] = ["required", 'confirmed',
                Password::min(6)->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()];
        }

        $rules['first_name'] = ['required', 'string', 'max:91'];
        $rules['last_name'] = ['required', 'string', 'max:91'];
        $rules['username'] = ['required', 'alpha_dash', 'min:5',
            Rule::unique('users', 'username')->whereNull('child_panel_id')];
        $rules['email'] = ['required', 'string', 'email', 'max:255',
            Rule::unique('users', 'email')->whereNull('child_panel_id')];
        $rules['password'] = ['required', 'string', 'min:6', 'confirmed'];
        $rules['country_code'] = ['nullable', 'string'];
        $rules['phone_code'] = ['nullable', 'string'];
        $rules['phone'] = ['required', 'string', 'unique:users,phone', new PhoneLength($phoneLength)];

        if ($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_login == 1) {
            GoogleRecaptchaService::responseRecaptcha($data['g-recaptcha-response']);
            $rules['g-recaptcha-response'] = 'sometimes|required';
        }

        if (($basicControl->manual_recaptcha == 1) && ($basicControl->manual_recaptcha_register == 1)) {
            $rules['captcha'] = ['required',
                Rule::when((!empty(request()->captcha) && strcasecmp(session()->get('captcha'), $_POST['captcha']) != 0), ['confirmed']),
            ];
        }

        return Validator::make($data, $rules, [
            'g-recaptcha-response.required' => 'The google recaptcha is required.',
        ]);
    }

    protected function create(array $data)
    {
        $basic = basicControl();
        $sponsor = session()->get('sponsor');
        if ($sponsor != null) {
            $sponsorId = User::where('username', $sponsor)->first();
        } else {
            $sponsorId = null;
        }

        return User::create([
            'firstname' => $data['first_name'],
            'lastname' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'country_code' => $data['country_code'] ?? null,
            'phone_code' => $data['phone_code'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'email_verification' => ($basic->email_verification) ? 0 : 1,
            'sms_verification' => ($basic->sms_verification) ? 0 : 1,
            'referral_id' => ($sponsorId != null) ? $sponsorId->id : null,
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());


        $msg = [
            'username' => $user->username,
        ];
        $action = [
            "link" => route('admin.user.view.profile', $user->id),
            "image" => getFile($user->image_driver, $user->image),
        ];

        $this->adminMail('ADDED_USER', $msg);
        $this->adminPushNotification('ADDED_USER', $msg, $action);
        $this->adminFirebasePushNotification('ADDED_USER', $msg, $action);

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        if ($request->ajax()) {
            return route('user.home');
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        $user->last_login = Carbon::now();
        $user->last_seen = Carbon::now();
        $user->two_fa_verify = ($user->two_fa == 1) ? 0 : 1;
        $user->save();

        $info = @json_decode(json_encode(getIpInfo()), true);
        $ul['user_id'] = $user->id;

        $ul['longitude'] = (!empty(@$info['long'])) ? implode(',', $info['long']) : null;
        $ul['latitude'] = (!empty(@$info['lat'])) ? implode(',', $info['lat']) : null;
        $ul['country_code'] = (!empty(@$info['code'])) ? implode(',', $info['code']) : null;
        $ul['location'] = (!empty(@$info['city'])) ? implode(',', $info['city']) . (" - " . @implode(',', @$info['area']) . "- ") . @implode(',', $info['country']) . (" - " . @implode(',', $info['code']) . " ") : null;
        $ul['country'] = (!empty(@$info['country'])) ? @implode(',', @$info['country']) : null;

        $ul['ip_address'] = UserSystemInfo::get_ip();
        $ul['browser'] = UserSystemInfo::get_browsers();
        $ul['os'] = UserSystemInfo::get_os();
        $ul['get_device'] = UserSystemInfo::get_device();

        UserLogin::create($ul);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
