<?php

namespace Database\Seeders;

use App\Models\PromoCode;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@dizzart.com'],
            [
                'name' => 'Dizzart Admin',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $products = [
            [
                'name' => 'Dizzart Oud Royale',
                'description' => 'A rich, opulent oud fragrance blending smoky agarwood with warm amber and a whisper of rose. Perfect for evening wear.',
                'scent_notes' => json_encode([
                    'top' => ['Saffron', 'Bergamot'],
                    'middle' => ['Rose', 'Oud'],
                    'base' => ['Amber', 'Musk'],
                ]),
                'price' => 1850.00,
                'stock_quantity' => 25,
                'image' => 'products/oud-royale.jpg',
            ],
            [
                'name' => 'Dizzart Citrus Bloom',
                'description' => 'A fresh, energetic scent bursting with Mediterranean citrus and white florals, ideal for daytime elegance.',
                'scent_notes' => json_encode([
                    'top' => ['Lemon', 'Mandarin'],
                    'middle' => ['Jasmine', 'Neroli'],
                    'base' => ['White Musk', 'Cedarwood'],
                ]),
                'price' => 1290.00,
                'stock_quantity' => 40,
                'image' => 'products/citrus-bloom.jpg',
            ],
            [
                'name' => 'Dizzart Velvet Noir',
                'description' => 'A seductive, mysterious blend of dark vanilla, tonka bean, and black pepper for a bold statement.',
                'scent_notes' => json_encode([
                    'top' => ['Black Pepper', 'Cardamom'],
                    'middle' => ['Tonka Bean', 'Vanilla'],
                    'base' => ['Sandalwood', 'Patchouli'],
                ]),
                'price' => 1620.00,
                'stock_quantity' => 0,
                'image' => 'products/velvet-noir.jpg',
            ],
            [
                'name' => 'Dizzart Nile Breeze',
                'description' => 'Inspired by the Egyptian Nile at dawn, this aquatic-green fragrance blends papyrus accord with fresh water notes.',
                'scent_notes' => json_encode([
                    'top' => ['Marine Notes', 'Green Tea'],
                    'middle' => ['Papyrus Accord', 'Lotus'],
                    'base' => ['Vetiver', 'Ambergris'],
                ]),
                'price' => 1450.00,
                'stock_quantity' => 15,
                'image' => 'products/nile-breeze.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['name' => $product['name']], $product);
        }

        PromoCode::updateOrCreate(
            ['code' => 'DIZZART10'],
            [
                'discount_percentage' => 10,
                'max_uses' => 100,
                'current_uses' => 0,
                'expires_at' => now()->addMonths(3),
            ]
        );
    }
}
