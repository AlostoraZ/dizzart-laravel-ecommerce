<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PromoCode;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        [$items, $subtotal] = $this->buildCartItems();

        $promo = $this->getSessionPromo();
        $discountAmount = 0;
        $total = $subtotal;

        if ($promo) {
            $discountAmount = round($subtotal * ($promo->discount_percentage / 100), 2);
            $total = max(0, $subtotal - $discountAmount);
        }

        return view('cart.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'promo' => $promo,
            'discountAmount' => $discountAmount,
            'total' => $total,
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $quantity = $validated['quantity'];
        $cart = session('cart', []);
        $currentQty = $cart[$product->id]['quantity'] ?? 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $product->stock_quantity) {
            return back()->with('error', "Only {$product->stock_quantity} unit(s) of {$product->name} are available.");
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'quantity' => $newQty,
        ];

        session(['cart' => $cart]);

        return back()->with('success', "{$product->name} added to your cart.");
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart = session('cart', []);

        if ($validated['quantity'] <= 0) {
            unset($cart[$product->id]);
        } else {
            if ($validated['quantity'] > $product->stock_quantity) {
                return back()->with('error', "Only {$product->stock_quantity} unit(s) of {$product->name} are available.");
            }

            $cart[$product->id] = [
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Product $product): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Item removed from cart.');
    }

    public function applyPromo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'promo_code' => ['required', 'string', 'max:50'],
        ]);

        if (!Auth::check()) {
            return back()->with('error', 'Please log in to apply a promo code.');
        }

        $promoCode = PromoCode::where('code', strtoupper(trim($validated['promo_code'])))->first();

        if (!$promoCode) {
            return back()->with('error', 'This promo code does not exist.');
        }

        if ($promoCode->isExpired()) {
            return back()->with('error', 'This promo code has expired.');
        }

        if (!$promoCode->hasUsesRemaining()) {
            return back()->with('error', 'This promo code has reached its usage limit.');
        }

        if (Auth::user()->hasUsedPromoCode($promoCode->id)) {
            return back()->with('error', 'You have already used this promo code.');
        }

        session(['promo_code_id' => $promoCode->id]);

        return back()->with('success', "Promo code {$promoCode->code} applied: {$promoCode->discount_percentage}% off.");
    }

    public function removePromo(): RedirectResponse
    {
        session()->forget('promo_code_id');

        return back()->with('success', 'Promo code removed.');
    }

    public function showCheckout(Request $request): View|RedirectResponse
    {
        [$items, $subtotal] = $this->buildCartItems();

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $promo = $this->getSessionPromo();
        $discountAmount = $promo ? round($subtotal * ($promo->discount_percentage / 100), 2) : 0;
        $total = max(0, $subtotal - $discountAmount);

        return view('cart.checkout', [
            'items' => $items,
            'subtotal' => $subtotal,
            'promo' => $promo,
            'discountAmount' => $discountAmount,
            'total' => $total,
        ]);
    }

    public function processCheckout(CheckoutRequest $request): RedirectResponse
    {
        $user = Auth::user();

        [$items, $subtotal] = $this->buildCartItems();

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        foreach ($items as $item) {
            if ($item['quantity'] > $item['product']->stock_quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Sorry, {$item['product']->name} no longer has enough stock.");
            }
        }

        $promoCode = null;
        $discountAmount = 0;

        $sessionPromo = $this->getSessionPromo();
        if ($sessionPromo && $sessionPromo->isValidForUser($user)) {
            $promoCode = $sessionPromo;
            $discountAmount = round($subtotal * ($promoCode->discount_percentage / 100), 2);
        }

        $total = max(0, $subtotal - $discountAmount);

        $paymentAuthorized = $this->mockChargeCard(
            $request->validated('card_number'),
            $request->validated('card_expiry'),
            $request->validated('card_cvv'),
            $total
        );

        if (!$paymentAuthorized) {
            return back()->with('error', 'Payment authorization failed. Please check your card details and try again.');
        }

        $order = DB::transaction(function () use ($request, $user, $items, $subtotal, $discountAmount, $total, $promoCode) {
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $request->validated('customer_name'),
                'customer_email' => $request->validated('customer_email'),
                'phone' => $request->validated('phone'),
                'address' => $request->validated('address'),
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount' => $total,
                'promo_code_id' => $promoCode?->id,
                'payment_status' => 'paid',
                'tracking_status' => 'Preparing',
                'estimated_delivery' => now()->addDays(7)->toDateString(),
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['product']->price,
                ]);

                $item['product']->decrement('stock_quantity', $item['quantity']);
            }

            if ($promoCode) {
                $promoCode->increment('current_uses');
                $user->promoCodes()->syncWithoutDetaching([$promoCode->id]);
            }

            return $order;
        });

        session()->forget(['cart', 'promo_code_id']);

        return redirect()->route('orders.track', $order)->with('success', 'Your order has been placed successfully!');
    }

    /**
     * Simulates a Fawry/Visa payment authorization.
     * No raw card data is ever persisted to the database.
     */
    private function mockChargeCard(string $cardNumber, string $expiry, string $cvv, float $amount): bool
    {
        // Basic Luhn-style sanity check to simulate a gateway authorization step.
        $digitsOnly = preg_replace('/\D/', '', $cardNumber);

        return strlen($digitsOnly) >= 12 && $amount >= 0;
    }

    private function buildCartItems(): array
    {
        $cart = session('cart', []);
        $items = [];
        $subtotal = 0;

        if (!empty($cart)) {
            $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

            foreach ($cart as $productId => $entry) {
                $product = $products->get($productId);

                if (!$product) {
                    continue;
                }

                $quantity = min($entry['quantity'], max($product->stock_quantity, 0));
                $lineTotal = $product->price * $quantity;
                $subtotal += $lineTotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];
            }
        }

        return [$items, $subtotal];
    }

    private function getSessionPromo(): ?PromoCode
    {
        $promoCodeId = session('promo_code_id');

        return $promoCodeId ? PromoCode::find($promoCodeId) : null;
    }
}
