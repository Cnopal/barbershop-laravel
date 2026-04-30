@extends('customer.sidebar')

@section('content')
@include('customer.products._styles')

<div class="customer-page shop-page">
    <div class="shop-header">
        <a href="{{ route('customer.products.index') }}" class="shop-btn">
            <i class="fas fa-arrow-left"></i> Back to Shop
        </a>
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

    <div class="shop-detail-layout">
        <div class="shop-detail-media">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            @else
                <i class="fas fa-box-open"></i>
            @endif
        </div>

        <div class="shop-detail-card">
            <span class="shop-category">{{ $product->category ?: 'General' }}</span>
            <h1>{{ $product->name }}</h1>
            <div class="shop-price">RM{{ number_format($product->price, 2) }}</div>
            <p class="shop-description">{{ $product->description ?: 'Premium grooming product available for online purchase.' }}</p>

            <form method="POST" action="{{ route('customer.products.checkout', $product) }}" class="shop-buy-box">
                @csrf
                <div class="shop-qty-row">
                    <label for="quantity"><strong>Quantity</strong></label>
                    <input id="quantity" class="shop-input" type="number" name="quantity" min="1" max="{{ $product->stock }}" value="{{ old('quantity', 1) }}" required>
                </div>
                <div class="shop-stock">{{ $product->stock }} available</div>
                <button type="submit" class="shop-btn primary">
                    <i class="fas fa-credit-card"></i> Checkout with Stripe
                </button>
            </form>
        </div>
    </div>

    @if($relatedProducts->isNotEmpty())
        <div class="shop-header section">
            <div>
                <h1 class="compact">More Products</h1>
            </div>
        </div>
        <div class="shop-grid">
            @foreach($relatedProducts as $related)
                <article class="shop-card">
                    <div class="shop-card-media">
                        @if($related->image_url)
                            <img src="{{ $related->image_url }}" alt="{{ $related->name }}">
                        @else
                            <i class="fas fa-box-open"></i>
                        @endif
                    </div>
                    <div class="shop-card-body">
                        <span class="shop-category">{{ $related->category ?: 'General' }}</span>
                        <h3>{{ $related->name }}</h3>
                        <div class="shop-card-footer">
                            <div class="shop-price">RM{{ number_format($related->price, 2) }}</div>
                            <a href="{{ route('customer.products.show', $related) }}" class="shop-btn">View</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
