<?php
namespace App\Services;

use App\Models\{RentalPanel, User};
use Illuminate\Support\Facades\{Auth, DB};

class AdminSupportSession
{
    public static function permitted(): bool
    {
        $admin = Auth::guard('admin')->user();
        return $admin && (int)$admin->id === (int)config('support_session.super_admin_id') && $admin->status && $admin->two_fa_verify;
    }

    public static function start(int $userId): void
    {
        abort_unless(self::permitted(), 403);
        $user = User::findOrFail($userId);
        abort_unless($user->status, 403, 'This user is suspended.');
        if ($user->rental_panel_id) {
            $panel = RentalPanel::findOrFail($user->rental_panel_id);
            abort_unless($panel->allows('accounts'), 403, 'This tenant panel is inactive or has customer accounts disabled.');
        }
        if (session()->has('admin_support_session')) self::end('replaced');
        $id = DB::table('admin_support_sessions')->insertGetId([
            'admin_id' => Auth::guard('admin')->id(), 'user_id' => $user->id, 'panel_id' => $user->rental_panel_id,
            'started_at' => now(), 'expires_at' => now()->addMinutes(config('support_session.minutes')),
        ]);
        Auth::guard('web')->login($user, false);
        session()->regenerate();
        session()->put('admin_support_session', $id);
    }

    public static function current(): ?object
    {
        $id = session('admin_support_session');
        if (!$id) return null;
        $entry = DB::table('admin_support_sessions')->where('id', $id)->whereNull('ended_at')->first();
        if (!self::permitted() || !$entry || (int)$entry->admin_id !== (int)Auth::guard('admin')->id()
            || (int)$entry->user_id !== (int)Auth::guard('web')->id() || now()->gte($entry->expires_at)) {
            self::end('expired_or_revoked');
            return null;
        }
        return $entry;
    }

    public static function end(string $reason = 'returned'): void
    {
        $id = session()->pull('admin_support_session');
        if ($id) {
            DB::table('admin_support_sessions')->where('id', $id)->whereNull('ended_at')->update(['ended_at' => now(), 'end_reason' => $reason]);
            Auth::guard('web')->logout();
            session()->regenerate();
        }
    }
}
