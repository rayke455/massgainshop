<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\ContentDetails;
use App\Models\User;
use App\Traits\Notify;
use App\Traits\PageSeo;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    use PageSeo, Notify;

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        $data['footer'] = footerData();
        $selectedTheme = getTheme();
        $slug = 'forgot-password';
        $data['pageSeo'] = $this->pageSeoData($slug, $selectedTheme);

        $data['forgotPasswordContent'] = ContentDetails::with('content')
            ->whereHas('content', function ($query) use ($selectedTheme) {
                $query->where('theme', $selectedTheme)
                    ->where('name', 'login')->where('type', 'single');
            })
            ->first();
        return view(template() . 'auth.passwords.email', $data);
    }


    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        try {

            $userEmail = $request->email;
            $user = User::where('email', $userEmail)->first();

            $token = Str::random(64);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now()
                ]
            );

            $resetUrl = url('password/reset', $token) . '?email=' . $userEmail;
            $message = 'Your Password Recovery Link: <a href="' . $resetUrl . '" target="_blank">Click To Reset Password</a>';

            $params = [
                'message' => $message
            ];
            $this->mail($user, 'PASSWORD_RESET', $params);
            return back()->with('success', 'We have e-mailed your password reset link!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


}
