<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('scent_notes', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $sort = $request->string('sort')->toString();

        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('shop.index', [
            'products' => $products,
            'search' => $request->input('search', ''),
            'sort' => $sort,
        ]);
    }

    public function show(Product $product): View
    {
        return view('shop.show', [
            'product' => $product,
        ]);
    }
}
