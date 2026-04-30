@extends('customer.sidebar')

@section('content')
@include('customer.products._styles')

<div class="customer-page shop-page">
    <div class="shop-header">
        <div>
            <h1>My Product Orders</h1>
            <p>Track online product purchases paid through Stripe.</p>
        </div>
        <a href="{{ route('customer.products.index') }}" class="shop-btn primary">
            <i class="fas fa-store"></i> Shop Products
        </a>
    </div>

    @if(session('success'))
        <div class="shop-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="shop-alert error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="shop-alert error">{{ $errors->first() }}</div>
    @endif

    @php
        $hasOrderFilters = request()->filled('search')
            || request()->filled('date_from')
            || request()->filled('date_to')
            || request('status', 'all') !== 'all'
            || request('sort', 'newest') !== 'newest';
    @endphp

    <form method="GET" action="{{ route('customer.product-orders.index') }}" class="shop-toolbar order-toolbar">
        <div class="order-search-field">
            <i class="fas fa-search"></i>
            <input name="search" class="shop-input" value="{{ request('search') }}" placeholder="Search order number, product, or status">
        </div>

        <input type="date" name="date_from" class="shop-input" value="{{ request('date_from') }}" aria-label="From date">
        <input type="date" name="date_to" class="shop-input" value="{{ request('date_to') }}" aria-label="To date">

        <select name="status" class="shop-select" aria-label="Filter by order status">
            <option value="all">All statuses</option>
            @foreach($orderStatuses as $status => $label)
                <option value="{{ $status }}" {{ request('status', 'all') === $status ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <select name="sort" class="shop-select" aria-label="Sort orders">
            <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest first</option>
            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest first</option>
        </select>

        <button class="shop-btn primary" type="submit">
            <i class="fas fa-filter"></i> Apply
        </button>

        @if($hasOrderFilters)
            <a href="{{ route('customer.product-orders.index') }}" class="shop-btn">
                <i class="fas fa-rotate-left"></i> Clear
            </a>
        @endif
    </form>

    <div class="shop-order-list">
        @forelse($orders as $order)
            <a href="{{ route('customer.product-orders.show', $order) }}" class="shop-order-row">
                <div>
                    <strong>{{ $order->order_number }}</strong>
                    <span>
                        {{ $order->items->sum('quantity') }} item(s)
                        &middot; RM{{ number_format($order->total, 2) }}
                        &middot; {{ $order->created_at->format('M d, Y h:i A') }}
                    </span>
                    <small class="shop-order-products">
                        {{ $order->items->pluck('product_name')->join(', ') }}
                    </small>
                </div>
                <div class="shop-order-badges">
                    <span class="shop-status {{ $order->payment_status }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                    <span class="shop-status order-{{ $order->order_status }}">{{ $order->order_status_label }}</span>
                </div>
            </a>
        @empty
            <div class="shop-empty">
                <i class="fas fa-shopping-bag"></i>
                <h3>{{ $hasOrderFilters ? 'No matching orders' : 'No product orders yet' }}</h3>
                <p>{{ $hasOrderFilters ? 'Try a different search term or date range.' : 'Your online purchases will appear here.' }}</p>
            </div>
        @endforelse
    </div>

    @if($orders->hasPages())
        <div class="shop-pagination">{{ $orders->links('pagination::bootstrap-4') }}</div>
    @endif
</div>
@endsection
