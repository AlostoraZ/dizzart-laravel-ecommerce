<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const TRACKING_STEPS = [
        'Preparing',
        'Shipped',
        'Out for Delivery',
        'Delivered',
    ];

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'phone',
        'address',
        'subtotal',
        'discount_amount',
        'total_amount',
        'promo_code_id',
        'payment_status',
        'tracking_status',
        'estimated_delivery',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'estimated_delivery' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function trackingStepIndex(): int
    {
        $index = array_search($this->tracking_status, self::TRACKING_STEPS, true);

        return $index === false ? 0 : $index;
    }
}
