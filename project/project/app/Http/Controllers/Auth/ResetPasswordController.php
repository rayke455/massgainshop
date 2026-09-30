<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ContentDetails;
use App\Providers\RouteServiceProvider;
use App\Traits\PageSeo;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords, PageSeo;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm(Request $request, $token = null): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data['footer'] = footerData();
        $selectedTheme = getTheme();
        $slug = 'reset-password';
        $data['pageSeo'] = $this->pageSeoData($slug, $selectedTheme);

        $data['backgroundImage'] = ContentDetails::with('content')
            ->whereHas('content', function ($query){
                $query->where('name', 'login')->where('type', 'single');
            })
            ->first();

        return view(template().'auth.passwords.reset', $data)->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

}
