@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' | Dizzart')

@section('content')
    <h1 style="font-family: 'Playfair Display', serif;" class="mb-1">Order #{{ $order->id }}</h1>
    <p class="text-muted mb-4">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>

    @php
        $steps = \App\Models\Order::TRACKING_STEPS;
        $currentIndex = $order->trackingStepIndex();
    @endphp

    <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between position-relative mb-2">
            <div class="position-absolute top-50 start-0 end-0 translate-middle-y" style="height: 4px; background-color: #e0e0e0; z-index: 0;"></div>
            <div class="position-absolute top-50 start-0 translate-middle-y bg-dizzart-gold" style="height: 4px; background-color: var(--dizzart-gold); z-index: 1; width: {{ $currentIndex === 0 ? '0%' : ($currentIndex / (count($steps) - 1)) * 100 . '%' }};"></div>

            @foreach($steps as $index => $step)
                <div class="text-center position-relative" style="z-index: 2; width: {{ 100 / count($steps) }}%;">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center
                        {{ $index <= $currentIndex ? 'bg-dizzart-gold text-white' : 'bg-white border' }}"
                        style="width: 40px; height: 40px; {{ $index <= $currentIndex ? 'background-color: var(--dizzart-gold);' : '' }}">
                        @if($index < $currentIndex)
                            &#10003;
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    <p class="small mt-2 mb-0 {{ $index <= $currentIndex ? 'fw-bold' : 'text-muted' }}">{{ $step }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card p-4 h-100">
                <h5>Delivery Details</h5>
                <p class="mb-1"><strong>Name:</strong> {{ $order->customer_name }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $order->phone }}</p>
                <p class="mb-1"><strong>Address:</strong> {{ $order->address }}</p>
                <p class="mb-1"><strong>Estimated Delivery:</strong> {{ $order->estimated_delivery->format('d M Y') }}</p>
                <p class="mb-0"><strong>Payment Status:</strong>
                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 h-100">
                <h5>Order Items</h5>
                @foreach($order->items as $item)
                    <div class="d-flex justify-content-between small mb-2">
                        <span>{{ $item->product->name ?? 'Product removed' }} &times; {{ $item->quantity }}</span>
                        <span>{{ number_format($item->lineTotal(), 2) }} EGP</span>
                    </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <span>{{ number_format($order->subtotal, 2) }} EGP</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between text-success">
                        <span>Discount</span>
                        <span>-{{ number_format($order->discount_amount, 2) }} EGP</span>
                    </div>
                @endif
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span>{{ number_format($order->total_amount, 2) }} EGP</span>
                </div>
            </div>
        </div>
    </div>
@endsection
