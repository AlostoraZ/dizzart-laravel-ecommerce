<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\PromoCodeRequest;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $products = Product::orderByDesc('created_at')->get();
        $promoCodes = PromoCode::orderByDesc('created_at')->get();
        $orders = Order::with('user')->orderByDesc('created_at')->get();

        return view('admin.dashboard', [
            'products' => $products,
            'promoCodes' => $promoCodes,
            'orders' => $orders,
            'trackingOptions' => Order::TRACKING_STEPS,
        ]);
    }

    // ---------------- Products ----------------

    public function storeProduct(ProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = new Product();
        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->scent_notes = [
            'top' => $this->splitNotes($validated['scent_notes_top'] ?? ''),
            'middle' => $this->splitNotes($validated['scent_notes_middle'] ?? ''),
            'base' => $this->splitNotes($validated['scent_notes_base'] ?? ''),
        ];
        $product->price = $validated['price'];
        $product->stock_quantity = $validated['stock_quantity'];

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return back()->with('success', "Product \"{$product->name}\" created.");
    }

    public function updateProduct(ProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->scent_notes = [
            'top' => $this->splitNotes($validated['scent_notes_top'] ?? ''),
            'middle' => $this->splitNotes($validated['scent_notes_middle'] ?? ''),
            'base' => $this->splitNotes($validated['scent_notes_base'] ?? ''),
        ];
        $product->price = $validated['price'];
        $product->stock_quantity = $validated['stock_quantity'];

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return back()->with('success', "Product \"{$product->name}\" updated.");
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    private function splitNotes(string $notes): array
    {
        return collect(explode(',', $notes))
            ->map(fn ($note) => trim($note))
            ->filter()
            ->values()
            ->all();
    }

    // ---------------- Promo Codes ----------------

    public function storePromoCode(PromoCodeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['code'] = strtoupper($validated['code']);

        PromoCode::create($validated);

        return back()->with('success', "Promo code \"{$validated['code']}\" created.");
    }

    public function updatePromoCode(PromoCodeRequest $request, PromoCode $promoCode): RedirectResponse
    {
        $validated = $request->validated();
        $validated['code'] = strtoupper($validated['code']);

        $promoCode->update($validated);

        return back()->with('success', "Promo code \"{$promoCode->code}\" updated.");
    }

    public function destroyPromoCode(PromoCode $promoCode): RedirectResponse
    {
        $promoCode->delete();

        return back()->with('success', 'Promo code deleted.');
    }

    // ---------------- Orders ----------------

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'tracking_status' => ['required', 'in:' . implode(',', Order::TRACKING_STEPS)],
        ]);

        $order->update(['tracking_status' => $validated['tracking_status']]);

        return back()->with('success', "Order #{$order->id} status updated to {$validated['tracking_status']}.");
    }
}
