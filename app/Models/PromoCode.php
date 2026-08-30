<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percentage',
        'max_uses',
        'current_uses',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'discount_percentage' => 'integer',
            'max_uses' => 'integer',
            'current_uses' => 'integer',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'promo_code_user')->withTimestamps();
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function hasUsesRemaining(): bool
    {
        return $this->current_uses < $this->max_uses;
    }

    public function isValidForUser(User $user): bool
    {
        return !$this->isExpired()
            && $this->hasUsesRemaining()
            && !$user->hasUsedPromoCode($this->id);
    }
}
