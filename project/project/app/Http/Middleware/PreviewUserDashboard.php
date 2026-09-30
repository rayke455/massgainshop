<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreviewUserDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userDashboard = array_keys(config('userdashboard'));
        if ($request->has('user_dashboard') && in_array(request()->user_dashboard, $userDashboard)) {
            $userDashboard = $request->user_dashboard;
            session(['user_dashboard' => $userDashboard]);
        }


        return $next($request);
    }
}
