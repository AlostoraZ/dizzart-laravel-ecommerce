@extends('layouts.app')

@section('title', 'Admin Dashboard | Dizzart')

@section('content')
    <h1 style="font-family: 'Playfair Display', serif;" class="mb-4">Admin Dashboard</h1>

    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">Products</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="promos-tab" data-bs-toggle="tab" data-bs-target="#promos" type="button" role="tab">Promo Codes</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">Orders</button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">

        {{-- ============ PRODUCTS TAB ============ --}}
        <div class="tab-pane fade show active" id="products" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Manage Products</h4>
                <button class="btn btn-dizzart" data-bs-toggle="modal" data-bs-target="#createProductModal">+ Add Product</button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price (EGP)</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td><img src="{{ $product->imageUrl() }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded"></td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->price, 2) }}</td>
                                <td>
                                    @if($product->stock_quantity <= 0)
                                        <span class="badge bg-secondary">Out of Stock</span>
                                    @else
                                        {{ $product->stock_quantity }}
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">Edit</button>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit Product Modal --}}
                            <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit {{ $product->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                @include('admin.partials.product-form', ['product' => $product])
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-dizzart">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Create Product Modal --}}
            <div class="modal fade" id="createProductModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @include('admin.partials.product-form')
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-dizzart">Create Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ PROMO CODES TAB ============ --}}
        <div class="tab-pane fade" id="promos" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Manage Promo Codes</h4>
                <button class="btn btn-dizzart" data-bs-toggle="modal" data-bs-target="#createPromoModal">+ Add Promo Code</button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Uses</th>
                            <th>Expires</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promoCodes as $promo)
                            <tr>
                                <td><span class="badge bg-dark">{{ $promo->code }}</span></td>
                                <td>{{ $promo->discount_percentage }}%</td>
                                <td>{{ $promo->current_uses }} / {{ $promo->max_uses }}</td>
                                <td>{{ $promo->expires_at ? $promo->expires_at->format('d M Y') : 'Never' }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editPromoModal{{ $promo->id }}">Edit</button>
                                    <form method="POST" action="{{ route('admin.promo-codes.destroy', $promo) }}" class="d-inline" onsubmit="return confirm('Delete this promo code?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="editPromoModal{{ $promo->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.promo-codes.update', $promo) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit {{ $promo->code }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                @include('admin.partials.promo-form', ['promo' => $promo])
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-dizzart">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="modal fade" id="createPromoModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.promo-codes.store') }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add Promo Code</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @include('admin.partials.promo-form')
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-dizzart">Create Promo Code</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ ORDERS TAB ============ --}}
        <div class="tab-pane fade" id="orders" role="tabpanel">
            <h4 class="mb-3">All Orders</h4>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Total (EGP)</th>
                            <th>Payment</th>
                            <th>Tracking Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}<br><span class="text-muted small">{{ $order->customer_email }}</span></td>
                                <td>{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="d-flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="tracking_status" class="form-select form-select-sm" style="width: 170px;" onchange="this.form.submit()">
                                            @foreach($trackingOptions as $option)
                                                <option value="{{ $option }}" @selected($order->tracking_status === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
