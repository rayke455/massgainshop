<?php

namespace App\Traits;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FA\Google2FA;
use App\Helpers\UserSystemInfo;

trait TwoFaSecurity
{
    public function twoFaSecurity($user)
    {
        $google2fa = new Google2FA();
        $data['secret'] = $user->two_fa_code ?? $this->generateSecretKeyForUser($user);

        $data['qrCodeUrl'] = $google2fa->getQRCodeUrl(
            auth()->user()->username,
            generalSetting()->site_title,
            $data['secret']
        );
        return $data;
    }

    private function generateSecretKeyForUser(User $user): string
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        $user->update(['two_fa_code' => $secret]);

        return $secret;
    }

    public function twoStepEnableSubmit($request, $user)
    {
        try {
            $secret = $user->two_fa_code;
            $google2fa = new Google2FA();
            $valid = $google2fa->verifyKey($secret, $request->code);
            if ($valid) {
                $user['two_fa'] = 1;
                $user['two_fa_verify'] = 1;
                $user->save();

                $this->mail($user, 'TWO_STEP_ENABLED', [
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


    public function TwoFaDisable($request, $user)
    {
        try {
            $request->validate([
                'password' => 'required|string|max:191',
            ]);

            if (!Hash::check($request->password, auth()->user()->password)) {
                return back()->with('error', 'Incorrect password. Please try again.');
            }

            $user->update([
                'two_fa' => 0,
                'two_fa_verify' => 1,
            ]);
            return redirect()->route('child.panel.user.dashboard')->with('success', 'Two-step authentication disabled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, Please try again.');
        }
    }
}
