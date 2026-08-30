@extends('layouts.app')

@section('title', 'My Orders | Dizzart')

@section('content')
    <h1 style="font-family: 'Playfair Display', serif;" class="mb-4">My Orders</h1>

    @if($orders->isEmpty())
        <div class="alert alert-info">You haven't placed any orders yet. <a href="{{ route('shop.index') }}">Start shopping</a>.</div>
    @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>{{ number_format($order->total_amount, 2) }} EGP</td>
                            <td><span class="badge bg-dark">{{ $order->tracking_status }}</span></td>
                            <td><a href="{{ route('orders.track', $order) }}" class="btn btn-sm btn-outline-dark">Track</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
@endsection
