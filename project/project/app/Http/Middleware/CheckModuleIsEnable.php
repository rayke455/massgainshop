<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ChildPanel\App\Models\ChildPanel;
use Symfony\Component\HttpFoundation\Response;
use Module;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;


class CheckModuleIsEnable
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $mainSite = config('app.url');
        $mainDomain = parse_url($mainSite);
        $childDomain = request()->getHost();
        $modulePath = base_path('modules/ChildPanel');

        try {
            $tenant = DB::table('child_panels')->where('domain', $childDomain)
                ->where('status', 1)->first();
        } catch (\Exception $e) {
            $tenant = null;
        }

        if (File::exists($modulePath) && $tenant) {
            if (Module::isEnabled('ChildPanel')) {
                return $next($request);
            }
        } else {
            if (isset($mainDomain['host']) && $childDomain == $mainDomain['host']) {
                return $next($request);
            }
            abort(404);
        }
        abort(404);
    }
}
