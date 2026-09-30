<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\UserSystemInfo;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class TwoFaSecurityController extends Controller
{
    use Notify;

    public function twoStepSecurity()
    {
        $admin = auth()->guard('admin')->user();
        $google2fa = new Google2FA();
        $secret = $admin->two_fa_code ?? $this->generateSecretKeyForUser($admin);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            auth()->user()->username,
            basicControl()->site_title,
            $secret
        );
        return view('admin.two_fa_security.index', compact('secret', 'qrCodeUrl'));
    }

    private function generateSecretKeyForUser(Admin $admin)
    {

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        $admin->update(['two_fa_code' => $secret]);

        return $secret;
    }

    public function twoStepEnable(Request $request)
    {
        try {
            $admin = auth()->guard('admin')->user();
            $secret = $admin->two_fa_code;

            $google2fa = new Google2FA();
            $valid = $google2fa->verifyKey($secret, $request->code);

            if ($valid) {
                $admin['two_fa'] = 1;
                $admin['two_fa_verify'] = 1;
                $admin->save();

                $this->adminMail($admin, 'TWO_STEP_ENABLED', [
                    'action' => 'Enabled',
                    'code' => $request->code,
                    'ip' => request()->ip(),
                    'browser' => UserSystemInfo::get_browsers() . ', ' . UserSystemInfo::get_os(),
                    'time' => date('d M, Y h:i:s A'),
                ]);

                return back()->with('success', 'Google Authenticator Has Been Enabled.');
            } else {
                return back()->with('error', 'Wrong Verification Code.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, Please try again.');
        }
    }

    public function twoStepDisable(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|string|max:191',
            ]);

            if (!Hash::check($request->password, auth()->user()->password)) {
                return back()->with('error', 'Incorrect password. Please try again.');
            }

            $admin = auth()->guard('admin')->user();
            $admin->update([
                'two_fa' => 0,
                'two_fa_verify' => 1,
            ]);
            return redirect()->back()->with('success', 'Two-step authentication disabled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, Please try again.');
        }
    }

    public function twoStepRegenerate()
    {
        $admin = auth()->guard('admin')->user();
        $admin->update([
            'two_fa_code' => null
        ]);
        session()->flash('success', 'Re-generate code successfully.');
        return redirect()->route('admin.two.step.security');
    }

}
