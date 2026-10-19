<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * First visit behind Cloudflare: pick the visitor's local currency from their country (CF-IPCountry),
 * so prices show in KES/NGN/EUR… straight away. Never overrides a currency the visitor already chose.
 * (Without Cloudflare the browser does the same after a quick country lookup — see partials/auto_currency.)
 */
class VisitorCurrency
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isMethod('GET') || $request->ajax() || auth()->check() || $request->cookie('currency')) {
            return $next($request);
        }

        $country = strtoupper((string) $request->header('CF-IPCountry', ''));
        $code = config("country_currency.$country");
        if ($code) {
            $currency = Currency::where('code', $code)->where('status', 1)->first();
            if ($currency) {
                $json = json_encode($currency->toArray() + ['_source' => 'automatic']);
                $request->cookies->set('currency', $json);          // this page already uses it
                Cookie::queue('currency', $json, 60 * 24 * 365);     // and the next ones too
            }
        }

        return $next($request);
    }
}
