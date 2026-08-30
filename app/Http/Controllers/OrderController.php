<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function track(Order $order): View
    {
        $this->authorizeOrderAccess($order);

        $order->load('items.product');

        return view('orders.track', [
            'order' => $order,
        ]);
    }

    public function myOrders(): View
    {
        $orders = Auth::user()
            ->orders()
            ->with('items.product')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('orders.my-orders', [
            'orders' => $orders,
        ]);
    }

    private function authorizeOrderAccess(Order $order): void
    {
        $user = Auth::user();

        if (!$user || ($order->user_id !== $user->id && !$user->is_admin)) {
            abort(403, 'You do not have permission to view this order.');
        }
    }
}
