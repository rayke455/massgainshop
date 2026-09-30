<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreviewThemeView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $themes = array_keys(config('themes'));
        if ($request->has('theme') && in_array(request()->theme, $themes)) {
            $theme = $request->theme;
            session(['theme' => $theme]);
        }
        return $next($request);
    }
}
