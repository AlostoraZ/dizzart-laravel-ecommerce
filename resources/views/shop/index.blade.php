@extends('layouts.app')

@section('title', 'Dizzart | Shop Egyptian Perfumes')

@section('content')
    <div class="text-center mb-5">
        <h1 style="font-family: 'Playfair Display', serif;">The Dizzart Collection</h1>
        <p class="text-muted">Handcrafted fragrances inspired by the essence of Egypt</p>
        @if($search)
            <p class="small">Showing results for "<strong>{{ $search }}</strong>" &mdash; <a href="{{ route('shop.index') }}">Clear search</a></p>
        @endif
    </div>

    @if($products->isEmpty())
        <div class="alert alert-info text-center">No perfumes matched your search. Try a different term.</div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card card-product h-100">
                        <a href="{{ route('shop.show', $product) }}">
                            <img src="{{ $product->imageUrl() }}" class="card-img-top" alt="{{ $product->name }}" style="height: 260px; object-fit: cover;">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="{{ route('shop.show', $product) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                            </h5>
                            <p class="fw-bold text-dizzart-gold mb-2">{{ number_format($product->price, 2) }} EGP</p>

                            @if(!$product->isInStock())
                                <span class="badge bg-secondary mb-2 align-self-start">Out of Stock</span>
                            @endif

                            <div class="mt-auto">
                                <form method="POST" action="{{ route('cart.add', $product) }}">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-dizzart w-100" @disabled(!$product->isInStock())>
                                        {{ $product->isInStock() ? 'Add to Cart' : 'Out of Stock' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    @endif
@endsection
