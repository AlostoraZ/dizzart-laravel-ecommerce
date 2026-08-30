@extends('layouts.app')

@section('title', $product->name . ' | Dizzart')

@section('content')
    <div class="mb-3">
        <a href="{{ route('shop.index') }}" class="text-decoration-none">&larr; Back to Collection</a>
    </div>

    <div class="row g-5">
        <div class="col-md-6">
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 560px; object-fit: cover;">
        </div>
        <div class="col-md-6">
            <h1 style="font-family: 'Playfair Display', serif;">{{ $product->name }}</h1>
            <p class="fs-3 fw-bold text-dizzart-gold">{{ number_format($product->price, 2) }} EGP</p>

            @if($product->isInStock())
                <span class="badge bg-success mb-3">In Stock ({{ $product->stock_quantity }} available)</span>
            @else
                <span class="badge bg-secondary mb-3">Out of Stock</span>
            @endif

            <p class="text-muted">{{ $product->description }}</p>

            @if(!empty($product->scent_notes))
                <h5 class="mt-4">Scent Notes</h5>
                <ul class="list-unstyled">
                    @foreach(['top' => 'Top Notes', 'middle' => 'Middle Notes', 'base' => 'Base Notes'] as $key => $label)
                        @if(!empty($product->scent_notes[$key]))
                            <li class="mb-1">
                                <strong>{{ $label }}:</strong> {{ implode(', ', $product->scent_notes[$key]) }}
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif

            @if($product->isInStock())
                <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-4 d-flex align-items-end gap-3">
                    @csrf
                    <div>
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock_quantity }}" style="width: 110px;">
                    </div>
                    <button type="submit" class="btn btn-dizzart btn-lg">Add to Cart</button>
                </form>
            @else
                <button class="btn btn-secondary btn-lg mt-4" disabled>Out of Stock</button>
            @endif
        </div>
    </div>
@endsection
