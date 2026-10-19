<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\Service;
use App\Models\VipLevel;
use App\Services\Growth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Admin → Growth: coupons, deposit bonuses, VIP levels, reviews and the related settings. */
class GrowthController extends Controller
{
    public function index(Request $request)
    {
        $tab = in_array($request->query('tab'), ['coupons', 'bonuses', 'vip', 'reviews', 'settings']) ? $request->query('tab') : 'coupons';
        return view('admin.growth.index', [
            'tab' => $tab,
            'settings' => Growth::all(),
            'coupons' => Coupon::latest()->get(),
            'levels' => VipLevel::orderBy('min_spent')->get(),
            'reviews' => Review::with('user:id,username', 'order:id,service_id')->latest()->paginate(20, ['*'], 'page'),
            'pendingReviews' => Review::where('status', Review::PENDING)->count(),
            'services' => Service::where('service_status', 1)->orderBy('service_title')->get(['id', 'service_title', 'price']),
            'base' => basicControl()->base_currency,
        ]);
    }

    // ---- coupons
    public function couponStore(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|alpha_dash|max:40|unique:coupons,code',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0.01|max:100000',
            'min_deposit' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'per_user' => 'nullable|integer|min:1|max:100',
            'first_deposit_only' => 'nullable|boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);
        if ($data['type'] === 'percent' && $data['value'] > 100) {
            return back()->withInput()->with('error', 'A percentage bonus can be at most 100%.');
        }
        $data['code'] = strtoupper($data['code']);
        $data['min_deposit'] = $data['min_deposit'] ?? 0;
        $data['per_user'] = $data['per_user'] ?? 1;
        $data['first_deposit_only'] = $request->boolean('first_deposit_only');
        $data['status'] = true;
        Coupon::create($data);
        return redirect()->route('admin.growth.index', ['tab' => 'coupons'])->with('success', "Coupon {$data['code']} created.");
    }

    public function couponToggle(Coupon $coupon)
    {
        $coupon->update(['status' => !$coupon->status]);
        return back()->with('success', "Coupon {$coupon->code} is now " . ($coupon->status ? 'active' : 'off') . '.');
    }

    public function couponDelete(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', "Coupon {$coupon->code} deleted.");
    }

    // ---- deposit bonuses
    public function bonusSave(Request $request)
    {
        $request->validate([
            'first_deposit_percent' => 'nullable|numeric|min:0|max:100',
            'tiers' => 'nullable|array|max:10',
            'tiers.*.min' => 'nullable|numeric|min:0',
            'tiers.*.percent' => 'nullable|numeric|min:0|max:100',
        ]);
        $tiers = collect($request->input('tiers', []))
            ->filter(fn($t) => is_numeric($t['min'] ?? null) && is_numeric($t['percent'] ?? null) && $t['percent'] > 0)
            ->map(fn($t) => ['min' => (float) $t['min'], 'percent' => (float) $t['percent']])
            ->sortBy('min')->values()->all();
        Growth::set(['bonus.first_deposit_percent' => (float) $request->input('first_deposit_percent', 0), 'bonus.tiers' => $tiers]);
        return redirect()->route('admin.growth.index', ['tab' => 'bonuses'])->with('success', 'Deposit bonuses saved.');
    }

    // ---- VIP levels
    public function vipSave(Request $request)
    {
        $request->validate([
            'enabled' => 'nullable|boolean',
            'levels' => 'nullable|array|max:12',
            'levels.*.name' => 'nullable|string|max:60',
            'levels.*.min_spent' => 'nullable|numeric|min:0',
            'levels.*.discount_percent' => 'nullable|numeric|min:0|max:90',
            'levels.*.color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);
        DB::transaction(function () use ($request) {
            VipLevel::query()->delete();
            foreach ($request->input('levels', []) as $l) {
                if (trim((string) ($l['name'] ?? '')) === '') {
                    continue;
                }
                VipLevel::create(['name' => trim($l['name']), 'min_spent' => (float) ($l['min_spent'] ?? 0),
                    'discount_percent' => (float) ($l['discount_percent'] ?? 0), 'color' => $l['color'] ?? '#2563eb']);
            }
        });
        Growth::set(['vip.enabled' => $request->boolean('enabled')]);
        return redirect()->route('admin.growth.index', ['tab' => 'vip'])->with('success', 'VIP levels saved.');
    }

    // ---- reviews
    public function reviewStatus(Request $request, Review $review)
    {
        $request->validate(['status' => 'required|in:1,2']);
        $review->update(['status' => (int) $request->status]);
        return back()->with('success', $review->status == Review::APPROVED ? 'Review approved. It now shows on the home page.' : 'Review rejected.');
    }

    public function reviewDelete(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }

    // ---- settings
    public function settingsSave(Request $request)
    {
        $request->validate([
            'whatsapp_number' => ['nullable', 'regex:/^\+?[0-9 ]{7,20}$/'],
            'whatsapp_message' => 'nullable|string|max:200',
            'trial_service_id' => 'nullable|integer|exists:services,id',
            'trial_quantity' => 'nullable|integer|min:1|max:100000',
            'signups_per_ip_day' => 'nullable|integer|min:0|max:100',
        ]);
        Growth::set([
            'whatsapp.number' => preg_replace('/[^0-9]/', '', (string) $request->whatsapp_number),
            'whatsapp.message' => (string) $request->input('whatsapp_message', ''),
            'notify.order_completed' => $request->boolean('notify_order_completed'),
            'reviews.enabled' => $request->boolean('reviews_enabled'),
            'trial.enabled' => $request->boolean('trial_enabled') && $request->filled('trial_service_id'),
            'trial.service_id' => $request->input('trial_service_id') ? (int) $request->trial_service_id : null,
            'trial.quantity' => (int) $request->input('trial_quantity', 100),
            'fraud.block_disposable' => $request->boolean('block_disposable'),
            'fraud.signups_per_ip_day' => (int) $request->input('signups_per_ip_day', 3),
            'loss.auto_disable' => $request->boolean('loss_auto_disable'),
            'report.daily' => $request->boolean('report_daily'),
        ]);
        return redirect()->route('admin.growth.index', ['tab' => 'settings'])->with('success', 'Settings saved.');
    }
}
