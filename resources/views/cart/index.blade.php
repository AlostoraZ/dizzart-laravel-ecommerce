@extends('layouts.app')

@section('title', 'Your Cart | Dizzart')

@section('content')
    <h1 style="font-family: 'Playfair Display', serif;" class="mb-4">Your Cart</h1>

    @if(empty($items))
        <div class="alert alert-info">
            Your cart is empty. <a href="{{ route('shop.index') }}">Continue shopping</a>.
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th style="width: 160px;">Quantity</th>
                        <th>Line Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $item['product']->imageUrl() }}" alt="{{ $item['product']->name }}" style="width: 60px; height: 60px; object-fit: cover;" class="rounded">
                                    <a href="{{ route('shop.show', $item['product']) }}" class="text-decoration-none text-dark">{{ $item['product']->name }}</a>
                                </div>
                            </td>
                            <td>{{ number_format($item['product']->price, 2) }} EGP</td>
                            <td>
                                <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="{{ $item['product']->stock_quantity }}" class="form-control form-control-sm" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
                                </form>
                            </td>
                            <td>{{ number_format($item['line_total'], 2) }} EGP</td>
                            <td>
                                <form method="POST" action="{{ route('cart.remove', $item['product']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end mt-4">
            <div class="col-md-5">
                <div class="card p-4">
                    <h5 class="mb-3">Promo Code</h5>
                    @if($promo)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-success">{{ $promo->code }} ({{ $promo->discount_percentage }}% off)</span>
                            <form method="POST" action="{{ route('cart.promo.remove') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0">Remove</button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('cart.promo.apply') }}" class="d-flex gap-2 mb-3">
                            @csrf
                            <input type="text" name="promo_code" class="form-control" placeholder="Enter promo code">
                            <button type="submit" class="btn btn-outline-dark">Apply</button>
                        </form>
                        @guest
                            <p class="small text-muted">Log in to apply a promo code.</p>
                        @endguest
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span>{{ number_format($subtotal, 2) }} EGP</span>
                    </div>
                    @if($discountAmount > 0)
                        <div class="d-flex justify-content-between text-success">
                            <span>Discount</span>
                            <span>-{{ number_format($discountAmount, 2) }} EGP</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                        <span>Total</span>
                        <span>{{ number_format($total, 2) }} EGP</span>
                    </div>

                    @auth
                        <a href="{{ route('checkout.show') }}" class="btn btn-dizzart w-100 mt-4">Proceed to Checkout</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-dizzart w-100 mt-4">Log in to Checkout</a>
                    @endauth
                </div>
            </div>
        </div>
    @endif
@endsection
