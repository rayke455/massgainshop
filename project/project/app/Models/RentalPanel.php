<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RentalPanel extends Model {
    protected $guarded = ['id'];
    protected $casts = ['features' => 'array', 'workspace_settings' => 'array', 'expires_at' => 'datetime', 'domain_verified_at' => 'datetime'];
    public function workspace(): array {
        return array_replace([
            'registration' => true, 'registration_fields' => [], 'show_spent' => true, 'show_orders' => true,
            'announcement' => '', 'seo_title' => '', 'seo_description' => '', 'terms' => '', 'privacy' => '',
        ], $this->workspace_settings ?? []);
    }
    public function available(): bool { return $this->status === 'active' && $this->expires_at->isFuture(); }
    public function allows(string $feature): bool { return $this->available() && in_array($feature, $this->features ?? [], true); }
    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
}
