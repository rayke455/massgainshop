<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * The bundled installer (vendor strilluminate package) stays reachable after installation and its
 * "install-complete" step rewrites the database settings in .env from a public form. The site is
 * installed, so these pages are closed unless INSTALLER_OPEN=true is set in .env.
 */
class BlockInstaller
{
    private const PATHS = ['install', 'install-complete', 'setup-product', 'check-requirements', 'check-permissions', 'license'];

    public function handle(Request $request, Closure $next)
    {
        if (in_array(trim($request->path(), '/'), self::PATHS, true) && !filter_var(env('INSTALLER_OPEN', false), FILTER_VALIDATE_BOOLEAN)) {
            abort(404);
        }
        return $next($request);
    }
}
