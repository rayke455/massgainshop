<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request)
    {
        $currentDomain = $request->getHost();
        if (class_exists('Modules\ChildPanel\App\Models\ChildPanel')) {
            $tenant = DB::table('child_panels')
                ->where('domain', $currentDomain)->first();
        }

        $mainDomain = config('app.url');
        $parsedUrl = parse_url($mainDomain);
        $mainSiteDomain = $parsedUrl['host'];

        if (!$request->expectsJson()) {
            if (trim(request()->route()->getPrefix(), '/') == 'admin' && $currentDomain == $mainSiteDomain) {
                return route('admin.login');
            } else if (trim(request()->route()->getPrefix(), '/') == 'admin' && $tenant) {
                if (\Route::has('child.panel.login')) {
                    return route('child.panel.login');
                }
            }
            return route('login');
        }
    }
}
