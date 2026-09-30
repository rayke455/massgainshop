<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContentDetails;
use App\Traits\Notify;
use App\Traits\PageSeo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

class VerificationController extends Controller
{

    use Notify, PageSeo;

    public function __construct()
    {
        $this->middleware(['auth']);

        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function checkValidCode($user, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$user->sent_at) return false;
        if (Carbon::parse($user->sent_at)->addMinutes($add_min) < Carbon::now()) return false;
        if ($user->verify_code !== $code) return false;
        return true;
    }

    public function check()
    {

        $user = $this->user;
        if (!$user->status) {
            Auth::guard('web')->logout();
        } elseif (!$user->email_verification) {
            if (!$this->checkValidCode($user, $user->verify_code)) {
                $user->verify_code = code(6);
                $user->sent_at = Carbon::now();
                $user->save();
                $this->verifyToMail($user, 'VERIFICATION_CODE', [
                    'code' => $user->verify_code
                ]);
                session()->flash('success', 'Email verification code has been sent');
            }

            $data['footer'] = footerData();
            $selectedTheme = getTheme();
            $slug = 'email-verification';
            $data['pageSeo'] = $this->pageSeoData($slug, $selectedTheme);

            $data['backgroundImage'] = ContentDetails::with('content')
                ->whereHas('content', function ($query) use ($slug) {
                    $query->where('name', 'login')->where('type', 'single');
                })
                ->first();

            return view(template() . 'auth.verification.email', compact('user'), $data);

        } elseif (!$user->sms_verification) {
            if (!$this->checkValidCode($user, $user->verify_code)) {
                $user->verify_code = code(6);
                $user->sent_at = Carbon::now();
                $user->save();

                $this->verifyToSms($user, 'VERIFICATION_CODE', [
                    'code' => $user->verify_code
                ]);
                session()->flash('success', 'SMS verification code has been sent');
            }

            $data['footer'] = footerData();
            $selectedTheme = getTheme();
            $slug = 'sms-verification';
            $data['pageSeo'] = $this->pageSeoData($slug, $selectedTheme);

            $data['backgroundImage'] = ContentDetails::with('content')
                ->whereHas('content', function ($query) use ($slug) {
                    $query->where('name', 'login')->where('type', 'single');
                })
                ->first();

            return view(template() . 'auth.verification.sms', compact('user'), $data);
        } elseif (!$user->two_fa_verify) {
            $selectedTheme = getTheme();
            $slug = 'two-fa-verification';
            $data['pageSeo'] = $this->pageSeoData($slug, $selectedTheme);

            $data['footer'] = footerData();
            $data['backgroundImage'] = ContentDetails::with('content')
                ->whereHas('content', function ($query) use ($slug) {
                    $query->where('name', 'login')->where('type', 'single');
                })
                ->first();

            return view(template() . 'auth.verification.two_step_security', compact('user'), $data);
        }
        return redirect()->route('user.dashboard');
    }


    public function resendCode()
    {
        $type = request()->type;
        $user = $this->user;
        if ($this->checkValidCode($user, $user->verify_code, 2)) {
            $target_time = Carbon::parse($user->sent_at)->addMinutes(2)->timestamp;
            $delay = $target_time - time();
            throw ValidationException::withMessages(['resend' => 'Please Try after ' . gmdate("i:s", $delay) . ' minutes']);
        }
        if (!$this->checkValidCode($user, $user->verify_code)) {
            $user->verify_code = code(6);
            $user->sent_at = Carbon::now();
            $user->save();
        } else {
            $user->sent_at = Carbon::now();
            $user->save();
        }

        if ($type == 'email') {
            $this->verifyToMail($user, 'VERIFICATION_CODE', [
                'code' => $user->verify_code
            ]);
            return back()->with('success', 'Email verification code has been sent.');
        } elseif ($type == 'mobile') {
            $this->verifyToSms($user, 'VERIFICATION_CODE', [
                'code' => $user->verify_code
            ]);
            return back()->with('success', 'SMS verification code has been sent.');
        } else {
            throw ValidationException::withMessages(['error' => 'Sending Failed']);
        }
    }

    public function mailVerify(Request $request)
    {
        $rules = [
            'code' => 'required',
        ];
        $msg = [
            'code.required' => 'Email verification code is required',
        ];
        $validate = $this->validate($request, $rules, $msg);
        $user = $this->user;
        if ($this->checkValidCode($user, $request->code)) {
            $user->email_verification = 1;
            $user->verify_code = null;
            $user->sent_at = null;
            $user->save();
            return redirect()->intended(route('user.dashboard'));
        }
        throw ValidationException::withMessages(['error' => 'Verification code didn\'t match!']);
    }

    public function smsVerify(Request $request)
    {
        $rules = [
            'code' => 'required',
        ];
        $msg = [
            'code.required' => 'SMS verification code is required',
        ];
        $validate = $this->validate($request, $rules, $msg);
        $user = Auth::user();

        if ($this->checkValidCode($user, $request->code)) {
            $user->sms_verification = 1;
            $user->verify_code = null;
            $user->sent_at = null;
            $user->save();

            return redirect()->intended(route('user.dashboard'));
        }
        throw ValidationException::withMessages(['error' => 'Verification code didn\'t match!']);
    }


    public function twoFaVerify(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ], [
            'code.required' => 'Two FA verification code is required',
        ]);

        try {
            $user = Auth::user();
            $secret = $user->two_fa_code;

            $google2fa = new Google2FA();
            $isValid = $google2fa->verifyKey($secret, $request->code);

            if ($isValid) {
                $user->two_fa_verify = 1;
                $user->save();

                return redirect()->intended(route('user.home'));
            }

            throw ValidationException::withMessages([
                'code' => ['The verification code is incorrect.'],
            ]);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
