@extends('staff.sidebar')

@section('content')
@include('admin.products._styles')

<div class="product-admin-page">
    <div class="product-page-header">
        <div>
            <h1>{{ $product->name }}</h1>
            <p>{{ $product->category ?: 'General product' }}</p>
        </div>
        <div class="product-actions">
            <a href="{{ route('staff.products.index') }}" class="product-btn"><i class="fas fa-arrow-left"></i> Back</a>
            <a href="{{ route('staff.pos.index') }}" class="product-btn primary"><i class="fas fa-cash-register"></i> Open POS</a>
        </div>
    </div>

    <div class="product-detail-layout">
        <div class="product-detail-media">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            @else
                <i class="fas fa-box-open"></i>
            @endif
        </div>
        <div class="product-detail-card">
            <div class="product-actions product-detail-actions">
                <span class="product-badge {{ $product->status }}">{{ ucfirst($product->status) }}</span>
            </div>
            <div class="product-meta product-detail-meta">
                <div><span>Price</span><strong>RM{{ number_format($product->price, 2) }}</strong></div>
                <div><span>Stock</span><strong>{{ $product->stock }}</strong></div>
                <div><span>Category</span><strong>{{ $product->category ?: 'General' }}</strong></div>
                <div><span>Updated</span><strong>{{ $product->updated_at->format('M d, Y') }}</strong></div>
            </div>
            <h3>Description</h3>
            <p class="product-description">{{ $product->description ?: 'No description provided.' }}</p>
        </div>
    </div>
</div>
@endsection
