@extends('staff.sidebar')

@section('content')
@include('admin.products._styles')

<div class="product-admin-page">
    <div class="product-page-header">
        <div>
            <h1>Products</h1>
            <p>View current retail inventory and stock levels for customer sales.</p>
        </div>
        <div class="product-actions">
            <a href="{{ route('staff.pos.index') }}" class="product-btn primary">
                <i class="fas fa-cash-register"></i> Open POS
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('staff.products.index') }}" class="product-toolbar compact">
        <input name="search" class="product-input" value="{{ request('search') }}" placeholder="Search products or categories">
        <select name="status" class="product-select">
            <option value="all">All status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button class="product-btn primary" type="submit"><i class="fas fa-search"></i> Filter</button>
    </form>

    <div class="product-grid">
        @forelse($products as $product)
            <div class="product-card">
                <div class="product-card-media">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    @else
                        <i class="fas fa-box-open"></i>
                    @endif
                </div>
                <div class="product-card-body">
                    <div class="product-card-title">
                        <h3>{{ $product->name }}</h3>
                        <span class="product-badge {{ $product->status }}">{{ ucfirst($product->status) }}</span>
                    </div>
                    <div class="product-meta">
                        <div><span>Price</span><strong>RM{{ number_format($product->price, 2) }}</strong></div>
                        <div><span>Stock</span><strong>{{ $product->stock }}</strong></div>
                        <div><span>Category</span><strong>{{ $product->category ?: 'General' }}</strong></div>
                        <div><span>Updated</span><strong>{{ $product->updated_at->format('M d') }}</strong></div>
                    </div>
                    <div class="product-card-actions single">
                        <a class="product-btn" href="{{ route('staff.products.show', $product) }}"><i class="fas fa-eye"></i> View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="product-empty">
                <i class="fas fa-box-open product-empty-icon"></i>
                <h3>No products found</h3>
                <p>Products added by admin will appear here.</p>
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="product-pagination">{{ $products->links('pagination::bootstrap-4') }}</div>
    @endif
</div>
@endsection
