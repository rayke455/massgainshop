<?php
namespace App\Http\Middleware;
use App\Http\Controllers\RentalStorefrontController;
use App\Models\RentalPanel;
use App\Services\PanelRentals;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class ResolveRentalHost {
    /** First path segments a panel customer may use (customer area, auth, payments). Everything else is 404. */
    private const CUSTOMER_PATHS = ['login', 'login-submit', 'logout', 'register', 'password', 'forget-password', 'captcha', 'user',
        'payment', 'mpesa', 'snippe', 'khalti', 'kora-payment', 'set-currency-cookie', 'language', 'switch', 'success', 'failed', 'manifest.json', 'offline'];

    /** Parts of the customer area that are for main-site customers only. */
    private const BLOCKED_USER = ['user/panels', 'user/api', 'user/keyGenerate', 'user/api-key-view', 'user/referral', 'user/mass', 'user/draft-order',
        'user/mass-order', 'user/edit/draft-order', 'user/child-panel', 'user/free-trial', 'user/ticket', 'user/notice'];

    public function handle(Request $request, Closure $next) {
        $host = strtolower($request->getHost());
        $mainHost = strtolower((string) parse_url(config('app.url'), PHP_URL_HOST));
        if ($host === $mainHost || !Schema::hasTable('rental_panels')) {
            // Panel customers' accounts only work on their panel.
            if ($request->hasSession() && Auth::check() && Auth::user()->rental_panel_id) {
                Auth::logout();
                $request->session()->invalidate();
                return redirect('/login')->with('error', 'This account belongs to another panel. Please log in there.');
            }
            return $next($request);
        }
        $panel = RentalPanel::with('owner')->where('subdomain', $host)->orWhere('custom_domain', $host)->first();
        if (!$panel) return $next($request);
        abort_unless($panel->available(), 403, 'This panel is currently unavailable.');
        if ($host === $panel->custom_domain) abort_unless($panel->domain_verified_at && $panel->allows('custom_domain'), 403, 'This domain is not active.');

        $request->attributes->set('rental_host', true);
        app()->instance('rental.panel', $panel);
        View::share('basicControl', basicControl());   // branded copy (name, logo) for every view

        // Only this panel's customers may be logged in here.
        if ($request->hasSession() && Auth::check() && (int) Auth::user()->rental_panel_id !== (int) $panel->id) {
            Auth::logout();
            $request->session()->invalidate();
            return redirect('/login');
        }

        $controller = app(RentalStorefrontController::class);
        $path = trim($request->path(), '/');
        if ($path === 'api/panel/v1' && $request->isMethod('POST') && $panel->allows('accounts')) return $next($request);
        if (Auth::check() && !Auth::user()->status && $path !== 'logout') {
            abort(403, 'Your account is suspended. Contact your panel support team.');
        }
        if (($path === 'register' || str_starts_with($path, 'register/')) && !$panel->workspace()['registration']) {
            abort(403, 'Registration is closed on this panel.');
        }
        if ($request->isMethod('GET') && in_array($path, ['terms', 'privacy'], true)) {
            abort_unless($panel->workspace()[$path], 404);
            return response()->view('rentals.content-page', [
                'panel' => $panel, 'title' => $path === 'terms' ? 'Terms of service' : 'Privacy policy',
                'body' => $panel->workspace()[$path],
            ]);
        }
        if ($request->isMethod('GET') && preg_match('#^pages/([a-z0-9]+(?:-[a-z0-9]+)*)$#', $path, $match)) {
            return app(\App\Http\Controllers\RentalContentController::class)->page($panel, $match[1]);
        }
        if ($request->isMethod('GET') && $path === 'faq') {
            return app(\App\Http\Controllers\RentalContentController::class)->faq($panel);
        }
        if ($request->isMethod('GET') && ($path === '' || $path === '/')) return response($controller->show($panel->slug));
        if ($request->isMethod('GET') && preg_match('#^track/([a-f0-9-]{36})$#', $path, $match)) return $controller->track($panel->slug, $match[1]);
        if ($request->isMethod('GET') && preg_match('#^pay-status/([a-f0-9-]{36})$#', $path, $match)) {
            $key = 'rental-paystatus:'.$panel->id.':'.$request->ip();
            abort_if(RateLimiter::tooManyAttempts($key, 40), 429); RateLimiter::hit($key, 60);
            return $controller->payStatus($panel->slug, $match[1]);
        }
        if ($request->isMethod('POST') && $path === 'panel-order') {
            $key = 'rental-request:'.$panel->id.':'.$request->ip();
            abort_if(RateLimiter::tooManyAttempts($key, 5), 429); RateLimiter::hit($key, 60);
            return $controller->order($request, $panel->slug);
        }

        // Customer accounts: the regular customer area, branded as this panel.
        $first = explode('/', $path)[0];
        if ($panel->allows('accounts') && in_array($first, self::CUSTOMER_PATHS, true)) {
            foreach (self::BLOCKED_USER as $blocked) {
                if ($path === $blocked || str_starts_with($path, $blocked . '/')) abort(404);
            }
            return $next($request);
        }
        abort(404);
    }
}
