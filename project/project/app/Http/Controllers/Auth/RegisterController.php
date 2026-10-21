<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UserSystemInfo;
use App\Http\Controllers\Controller;
use App\Models\ContentDetails;
use App\Models\Currency;
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


    /**
     * Expected national number length(s) for the chosen country (15 = E.164 max when unknown).
     * Match on the ISO country first: dial codes are shared (+1 is the US, Canada, American Samoa, …),
     * so matching on the dial code alone picks whichever country happens to be listed first.
     */
    protected function phoneLengthFor(array $data)
    {
        $iso = strtoupper((string)($data['country_code'] ?? ''));
        $phoneCode = $data['phone_code'] ?? null;

        $byDialCode = null;
        foreach (config('country') as $country) {
            if ($iso && strtoupper($country['code']) === $iso) {
                return $country['phoneLength'];
            }
            if ($country['phone_code'] == $phoneCode) {
                $byDialCode = array_merge($byDialCode ?? [], (array)$country['phoneLength']);
            }
        }
        return $byDialCode ?? 15;
    }

    /**
     * Keep digits only and drop a leading trunk "0" (e.g. Kenyan 0712…, UK 07…) when that is
     * the only reason the number is one digit too long for its country.
     */
    protected function normalizePhone(array $data)
    {
        // The single-field signup supplies an international number; older themes
        // still submit a separate dial code and national number.
        if (empty($data['phone_code'])) {
            $raw = trim((string)($data['phone'] ?? ''));
            $international = preg_replace('/[\s().-]+/', '', $raw);
            $data['country_code'] = null;
            $data['phone_code'] = '';
            if (preg_match('/^\+[1-9][0-9]{6,14}$/', $international)) {
                $countries = collect(config('country'))->sortByDesc(fn ($country) => strlen($country['phone_code']));
                foreach ($countries as $country) {
                    if (str_starts_with($international, $country['phone_code'])) {
                        $data['phone_code'] = $country['phone_code'];
                        $data['phone'] = substr($international, strlen($country['phone_code']));
                        $matches = $countries->where('phone_code', $country['phone_code']);
                        // A shared calling code does not identify a country reliably.
                        $data['country_code'] = $matches->count() === 1 ? strtolower($country['code']) : null;
                        break;
                    }
                }
            }
        }
        $phone = preg_replace('/\D+/', '', (string)($data['phone'] ?? ''));
        $lengths = (array)$this->phoneLengthFor($data);
        if (str_starts_with($phone, '0') && !in_array(strlen($phone), $lengths) && in_array(strlen($phone) - 1, $lengths)) {
            $phone = substr($phone, 1);
        }
        $data['phone'] = $phone;
        return $data;
    }

    protected function validator(array $data)
    {
        $data = $this->normalizePhone($data);
        $phoneLength = $this->phoneLengthFor($data);

        $basicControl = basicControl();
        if ($basicControl->strong_password == 0) {
            $rules['password'] = ['required', 'string', 'min:6'];
        } else {
            $rules['password'] = ["required",
                Password::min(6)->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()];
        }

        $rules['username'] = ['required', 'alpha_dash', 'min:5', 'max:60',
            Rule::unique('users', 'username')->whereNull('child_panel_id')];
        $rules['email'] = ['required', 'string', 'email', 'max:255',
            Rule::unique('users', 'email')->whereNull('child_panel_id')];
        $rules['country_code'] = ['nullable', 'string', 'max:5'];
        $rules['phone_code'] = ['required', 'string', 'max:8'];
        $rules['phone'] = ['required', 'string', 'unique:users,phone', new PhoneLength($phoneLength)];

        if ($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_register == 1) {
            GoogleRecaptchaService::responseRecaptcha($data['g-recaptcha-response']);
            $rules['g-recaptcha-response'] = 'sometimes|required';
        }

        if (($basicControl->manual_recaptcha == 1) && ($basicControl->manual_recaptcha_register == 1)) {
            $rules['captcha'] = ['required',
                Rule::when((!empty(request()->captcha) && strcasecmp(session()->get('captcha'), $_POST['captcha']) != 0), ['confirmed']),
            ];
        }

        if (\App\Services\PanelRentals::current()) {
            $rules = array_merge($rules, \App\Services\RentalRegistration::rules(\App\Services\PanelRentals::current()));
        }
        $validator = Validator::make($data, $rules, [
            'g-recaptcha-response.required' => 'The google recaptcha is required.',
        ]);
        $validator->after(function ($validator) use ($data) {
            if (empty($data['phone_code'])) {
                $validator->errors()->add('phone', __('Enter a valid WhatsApp number including the country code, for example +254712345678.'));
            }
            // Fraud protection (Admin → Growth → Settings)
            if (\App\Services\Growth::get('fraud.block_disposable', true) && \App\Services\Growth::isDisposableEmail($data['email'] ?? '')) {
                $validator->errors()->add('email', __('Please use a real email address (temporary email addresses are not accepted).'));
            }
            $limit = (int) \App\Services\Growth::get('fraud.signups_per_ip_day', 3);
            if ($limit > 0 && (int) \Illuminate\Support\Facades\Cache::get($this->signupKey(), 0) >= $limit) {
                $validator->errors()->add('username', __('Too many accounts were created from this connection today. Please try again tomorrow or contact support.'));
            }
        });
        return $validator;
    }

    /** Sign-ups per internet connection per day. */
    protected function signupKey(): string
    {
        return 'zk_signups_' . sha1((string) request()->ip()) . '_' . now()->format('Ymd');
    }

    /** Local currency for the sign-up country (only if it's switched on in Admin → Currencies). */
    protected function currencyForCountry(?string $iso): ?string
    {
        $code = config('country_currency.' . strtoupper((string) $iso));
        return $code && \App\Models\Currency::where('code', $code)->where('status', 1)->exists() ? $code : null;
    }

    protected function signupCurrency(array $data): ?string
    {
        // A visitor's selected/display currency survives registration. Location
        // is only a preference hint, never used for authentication or permissions.
        $cookie = json_decode((string) request()->cookie('currency'), true);
        $preferred = is_array($cookie) ? ($cookie['code'] ?? null) : null;
        if (($cookie['_source'] ?? 'manual') === 'manual' && is_string($preferred) && Currency::where('code', $preferred)->where('status', 1)->exists()) {
            return $preferred;
        }
        $country = strtoupper((string) ($data['signup_country'] ?? ''));
        return $this->currencyForCountry(preg_match('/^[A-Z]{2}$/', $country) ? $country : null)
            ?? (is_string($preferred) && Currency::where('code', $preferred)->where('status', 1)->exists() ? $preferred : null)
            ?? $this->currencyForCountry($data['country_code'] ?? null);
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

        $data = $this->normalizePhone($data);

        // The form only asks for username/email/phone; the username doubles as the display name.
        return User::create([
            'firstname' => $data['username'],
            'lastname' => null,
            'username' => $data['username'],
            'email' => $data['email'],
            'country_code' => $data['country_code'] ?? null,
            'rental_panel_id' => \App\Services\PanelRentals::currentId(),   // signed up on a rented panel → that panel's customer
            'rental_profile' => \App\Services\RentalRegistration::answers(\App\Services\PanelRentals::current(), $data),
            'currency' => $this->signupCurrency($data),
            'phone_code' => $data['phone_code'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'email_verification' => ($basic->email_verification) ? 0 : 1,
            'sms_verification' => ($basic->sms_verification) ? 0 : 1,
            // The platform's affiliate program doesn't apply to panel customers.
            'referral_id' => ($sponsorId != null && !\App\Services\PanelRentals::currentId()) ? $sponsorId->id : null,
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());
        \Illuminate\Support\Facades\Cache::add($this->signupKey(), 0, now()->endOfDay());
        \Illuminate\Support\Facades\Cache::increment($this->signupKey());


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
