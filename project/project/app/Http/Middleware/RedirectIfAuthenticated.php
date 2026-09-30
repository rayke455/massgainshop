<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            $currentDomain = $request->getHost();
            try {
                $tenant = DB::table('child_panels')->where('domain', $currentDomain)
                    ->where('status', 1)
                    ->first();
            } catch (\Exception $e) {
                $tenant = null;
            }
            if (Auth::guard($guard)->check()) {
                if ($guard == 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($guard == 'child_panel') {
                    return redirect()->route('child.panel.dashboard');
                } else {
                    if ($tenant) {
                        return redirect()->route('child.panel.user.dashboard');
                    } else {
                        return redirect()->route('user.dashboard');
                    }
                }

            }
        }
        return $next($request);
    }
}
