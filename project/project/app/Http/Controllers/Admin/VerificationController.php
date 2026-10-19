<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

class VerificationController extends Controller
{
    use Notify;

    public function checkValidCode($admin, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$admin->sent_at) return false;
        if (Carbon::parse($admin->sent_at)->addMinutes($add_min) < Carbon::now()) return false;
        if ($admin->verify_code !== $code) return false;
        return true;
    }

    public function check()
    {
        $admin = auth()->guard('admin')->user();

        if (!$admin->status) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
        } elseif (!$admin->two_fa_verify) {
            return view('admin.auth.verification.two_step_security', compact('admin'));
        }
        // Already verified: go to the admin dashboard (this used to send admins to the customer dashboard).
        return redirect()->route('admin.dashboard');
    }


    public function twoStepVerify(Request $request)
    {
        try {
            $request->validate([
                'code1' => 'required|digits:1',
                'code2' => 'required|digits:1',
                'code3' => 'required|digits:1',
                'code4' => 'required|digits:1',
                'code5' => 'required|digits:1',
                'code6' => 'required|digits:1',
            ]);

            $code = $request->code1 .
                $request->code2 .
                $request->code3 .
                $request->code4 .
                $request->code5 .
                $request->code6;

            $admin = auth()->guard('admin')->user();
            $secret = $admin->two_fa_code;

            $google2fa = new Google2FA();
            $isValid = $google2fa->verifyKey($secret, $code);


            if ($isValid) {
                $admin->two_fa_verify = 1;
                $admin->save();

                return redirect()->intended(route('admin.dashboard'));
            }

            throw ValidationException::withMessages([
                'code' => ['The verification code is incorrect.'],
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
