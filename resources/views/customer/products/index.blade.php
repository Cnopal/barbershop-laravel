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
    @if($errors->any())
        <div class="shop-alert error">{{ $errors->first() }}</div>
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

    @php
        $oldProductIds = collect(old('product_ids', []))->map(fn ($id) => (int) $id)->all();
    @endphp

    <form method="POST" action="{{ route('customer.products.checkout') }}" class="shop-cart-form">
        @csrf

        @if($products->count() > 0)
            <div class="shop-cart-bar">
                <div>
                    <strong>Cart Checkout</strong>
                    <span>{{ $products->total() }} product(s) available</span>
                </div>
                <button class="shop-btn primary" type="submit">
                    <i class="fas fa-credit-card"></i> Checkout Selected
                </button>
            </div>
        @endif

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
                        <label class="shop-card-select" for="product_{{ $product->id }}">
                            <input
                                id="product_{{ $product->id }}"
                                type="checkbox"
                                name="product_ids[]"
                                value="{{ $product->id }}"
                                {{ in_array($product->id, $oldProductIds, true) ? 'checked' : '' }}
                            >
                            <span>{{ $product->category ?: 'General' }}</span>
                        </label>
                        <h3>{{ $product->name }}</h3>
                        <p>{{ Str::limit($product->description ?: 'Premium grooming product ready for pickup after checkout.', 95) }}</p>
                        <div class="shop-card-footer">
                            <div>
                                <div class="shop-price">RM{{ number_format($product->price, 2) }}</div>
                                <div class="shop-stock">{{ $product->stock }} in stock</div>
                            </div>
                            <a href="{{ route('customer.products.show', $product) }}" class="shop-btn">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                        <div class="shop-card-qty">
                            <label for="quantity_{{ $product->id }}">Qty</label>
                            <input
                                id="quantity_{{ $product->id }}"
                                class="shop-input"
                                type="number"
                                name="quantities[{{ $product->id }}]"
                                min="1"
                                max="{{ $product->stock }}"
                                value="{{ old('quantities.' . $product->id, 1) }}"
                            >
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
    </form>

    @if($products->hasPages())
        <div class="shop-pagination">{{ $products->links('pagination::bootstrap-4') }}</div>
    @endif
</div>
@endsection
