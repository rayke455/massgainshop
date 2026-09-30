<?php

namespace App\Http\Controllers\User;

use App\Helpers\UserSystemInfo;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class TwoFaSecurityController extends Controller
{
    use Notify;

    protected object $user;
    protected string $theme;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function twoStepSecurity(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = auth()->user();
        $google2fa = new Google2FA();
        $secret = $user->two_fa_code ?? $this->generateSecretKeyForUser($user);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            auth()->user()->username,
            basicControl()->site_title,
            $secret
        );
        return view(template() . 'user.two_fa.index', compact('secret', 'qrCodeUrl'));
    }

    private function generateSecretKeyForUser(User $user): string
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        $user->update(['two_fa_code' => $secret]);

        return $secret;
    }

    public function twoStepEnable(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $user = $this->user;
            $secret = auth()->user()->two_fa_code;
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

    public function twoStepDisable(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $request->validate([
                'password' => 'required|string|max:191',
            ]);

            if (!Hash::check($request->password, auth()->user()->password)) {
                return back()->with('error', 'Incorrect password. Please try again.');
            }

            $this->user->update([
                'two_fa' => 0,
                'two_fa_verify' => 1,
            ]);
            return redirect()->route('user.dashboard')->with('success', 'Two-step authentication disabled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, Please try again.');
        }
    }

    public function twoStepRegenerate(): \Illuminate\Http\RedirectResponse
    {
        $this->user->update([
            'two_fa_code' => null
        ]);
        session()->flash('success', 'Re-generate code successfully.');
        return redirect()->route('user.two.step.security');
    }
}
