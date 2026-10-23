<?php
namespace App\Http\Middleware;

use App\Models\RentalPanel;
use App\Services\AdminSupportSession as Support;
use Closure;
use Illuminate\Http\Request;

class AdminSupportSession
{
    public function handle(Request $request, Closure $next)
    {
        $hadSession = $request->session()->has('admin_support_session');
        $entry = Support::current();
        if ($hadSession && !$entry) return redirect()->route('admin.dashboard')->with('error', 'Support session ended.');
        if (!$entry || $request->is('support-session/stop')) return $next($request);
        // Support access is intentionally read-only: no orders, transfers, passwords or API-key rotations.
        abort_unless(in_array($request->method(), ['GET', 'HEAD'], true), 403, 'Support sessions are read-only. Return to admin to exit.');
        // Some legacy GET endpoints mutate state (currency, verification codes, payment polling).
        $path = trim($request->path(), '/');
        $readOnlyPath = $path === '' || preg_match('#^user/(dashboard|profile|password-setting|notification-settings|check|order/create|get-category-service|orders(?:/[a-z -]+)?|panels(?:/[0-9]+(?:/(content|landing|services/import|support|promotions))?)?)$#i', $path);
        abort_unless($readOnlyPath, 403, 'This page is not available in read-only support mode. Use Return to admin to exit.');
        if ($entry->panel_id) {
            $panel = RentalPanel::with('owner')->find($entry->panel_id);
            if (!$panel || !$panel->allows('accounts')) {
                Support::end('panel_unavailable');
                return redirect()->route('admin.dashboard');
            }
            $request->attributes->set('support_panel', $panel);
        }
        $response = $next($request);
        if ($response instanceof \Illuminate\Http\Response && str_contains((string)$response->headers->get('Content-Type'), 'text/html')) {
            $banner = view('rentals.support-session-banner', ['supportUser' => auth()->guard('web')->user(), 'supportExpiry' => $entry->expires_at])->render();
            $response->setContent(preg_replace_callback('/<body\b[^>]*>/i', fn($m) => $m[0].$banner, $response->getContent(), 1));
            $response->headers->set('Cache-Control', 'private, no-store');
        }
        return $response;
    }
}
