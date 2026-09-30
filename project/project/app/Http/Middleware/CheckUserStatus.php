<?php

namespace App\Http\Middleware;

use App\Traits\GetChildPanel;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckUserStatus
{
    use GetChildPanel;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user->github_id || $user->google_id || $user->facebook_id) {
            return $next($request);
        }

        if($user->status && $user->email_verification && $user->sms_verification && $user->two_fa_verify){
            return $next($request);
        }

        return redirect(route('user.check'));
    }
}
