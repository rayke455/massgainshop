<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RentalPanel extends Model {
    protected $guarded = ['id'];
    protected $casts = ['features' => 'array', 'workspace_settings' => 'array', 'expires_at' => 'datetime', 'domain_verified_at' => 'datetime'];
    public function workspace(): array {
        return array_replace([
            'registration' => true, 'registration_fields' => [], 'show_spent' => true, 'show_orders' => true,
            'announcement' => '', 'seo_title' => '', 'seo_description' => '', 'seo_keywords' => '', 'terms' => '', 'privacy' => '',
            'price_format' => 'per_k', 'fast_orders' => true, 'auto_cancel_failed' => true, 'show_avg_time' => true,
            'popular_badges' => true, 'drip_feed' => true, 'multi_orders' => true, 'referral_percent' => 5,
            'support_telegram' => '', 'support_email' => '', 'catalogue_mode' => 'all', 'imported_service_ids' => [],
        ], $this->workspace_settings ?? []);
    }
    public function available(): bool { return $this->status === 'active' && $this->expires_at->isFuture(); }
    public function allows(string $feature): bool { return $this->available() && in_array($feature, $this->features ?? [], true); }
    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
}
