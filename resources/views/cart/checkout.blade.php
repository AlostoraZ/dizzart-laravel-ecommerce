@extends('layouts.app')

@section('title', 'Checkout | Dizzart')

@section('content')
    <h1 style="font-family: 'Playfair Display', serif;" class="mb-4">Checkout</h1>

    <div class="row g-5">
        <div class="col-md-7">
            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf

                <h5 class="mb-3">Shipping Details</h5>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', auth()->user()->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', auth()->user()->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+20 1xx xxx xxxx" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Shipping Address</label>
                    <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                </div>

                <h5 class="mb-3">Payment (Fawry / Visa &mdash; Simulated)</h5>
                <p class="small text-muted">This is a mock payment gateway for demo purposes. No real transaction is processed and no card data is stored.</p>
                <div class="mb-3">
                    <label class="form-label">Card Number</label>
                    <input type="text" name="card_number" class="form-control" placeholder="4111 1111 1111 1111" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Expiry (MM/YY)</label>
                        <input type="text" name="card_expiry" class="form-control" placeholder="12/28" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">CVV</label>
                        <input type="text" name="card_cvv" class="form-control" placeholder="123" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-dizzart btn-lg w-100 mt-3">Place Order &mdash; {{ number_format($total, 2) }} EGP</button>
            </form>
        </div>

        <div class="col-md-5">
            <div class="card p-4">
                <h5 class="mb-3">Order Summary</h5>
                @foreach($items as $item)
                    <div class="d-flex justify-content-between mb-2 small">
                        <span>{{ $item['product']->name }} &times; {{ $item['quantity'] }}</span>
                        <span>{{ number_format($item['line_total'], 2) }} EGP</span>
                    </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <span>{{ number_format($subtotal, 2) }} EGP</span>
                </div>
                @if($discountAmount > 0)
                    <div class="d-flex justify-content-between text-success">
                        <span>Discount ({{ $promo->code }})</span>
                        <span>-{{ number_format($discountAmount, 2) }} EGP</span>
                    </div>
                @endif
                <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                    <span>Total</span>
                    <span>{{ number_format($total, 2) }} EGP</span>
                </div>
            </div>
        </div>
    </div>
@endsection
