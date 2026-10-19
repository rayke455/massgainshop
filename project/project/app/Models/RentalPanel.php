<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RentalPanel extends Model {
    protected $guarded = ['id'];
    protected $casts = ['features' => 'array', 'expires_at' => 'datetime', 'domain_verified_at' => 'datetime'];
    public function available(): bool { return $this->status === 'active' && $this->expires_at->isFuture(); }
    public function allows(string $feature): bool { return $this->available() && in_array($feature, $this->features ?? [], true); }
    public function owner() { return $this->belongsTo(User::class, 'owner_id'); }
}
