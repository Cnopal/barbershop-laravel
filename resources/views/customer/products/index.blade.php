@extends('customer.sidebar')

@section('content')
@include('customer.products._styles')

<div class="customer-page shop-page">
    <div class="shop-header">
        <div>
            <h1>Shop Products</h1>
            <p>Browse grooming products and checkout online with Stripe.</p>
        </div>
        <a href="{{ route('customer.product-orders.index') }}" class="shop-btn">
            <i class="fas fa-shopping-bag"></i> My Orders
        </a>
    </div>

    @if(session('success'))
        <div class="shop-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="shop-alert error">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('customer.products.index') }}" class="shop-toolbar">
        <input name="search" class="shop-input" value="{{ request('search') }}" placeholder="Search products">
        <select name="category" class="shop-select">
            <option value="all">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>
        <button class="shop-btn primary" type="submit"><i class="fas fa-search"></i> Search</button>
    </form>

    <div class="shop-grid">
        @forelse($products as $product)
            <article class="shop-card">
                <div class="shop-card-media">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    @else
                        <i class="fas fa-box-open"></i>
                    @endif
                </div>
                <div class="shop-card-body">
                    <span class="shop-category">{{ $product->category ?: 'General' }}</span>
                    <h3>{{ $product->name }}</h3>
                    <p>{{ Str::limit($product->description ?: 'Premium grooming product ready for pickup after checkout.', 95) }}</p>
                    <div class="shop-card-footer">
                        <div>
                            <div class="shop-price">RM{{ number_format($product->price, 2) }}</div>
                            <div class="shop-stock">{{ $product->stock }} in stock</div>
                        </div>
                        <a href="{{ route('customer.products.show', $product) }}" class="shop-btn primary">
                            <i class="fas fa-cart-plus"></i> Buy
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="shop-empty">
                <i class="fas fa-box-open"></i>
                <h3>No products available</h3>
                <p>Please check back soon.</p>
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="shop-pagination">{{ $products->links('pagination::bootstrap-4') }}</div>
    @endif
</div>
@endsection
