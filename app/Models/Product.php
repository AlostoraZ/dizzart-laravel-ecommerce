<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'scent_notes',
        'price',
        'stock_quantity',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'scent_notes' => 'array',
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
        ];
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function imageUrl(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/placeholder-perfume.png');
    }
}
