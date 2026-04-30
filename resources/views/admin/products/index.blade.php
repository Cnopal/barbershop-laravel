@extends('admin.sidebar')

@section('content')
@include('admin.products._styles')

<div class="product-admin-page">
    <div class="product-page-header">
        <div>
            <h1>Products</h1>
            <p>Manage retail inventory for online sales and in-shop POS purchases.</p>
        </div>
        <div class="product-actions">
            <a href="{{ route('admin.pos.index') }}" class="product-btn">
                <i class="fas fa-cash-register"></i> Open POS
            </a>
            <a href="{{ route('admin.products.create') }}" class="product-btn primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="product-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="product-alert error">{{ session('error') }}</div>
    @endif

    <div class="product-stats">
        <div class="product-stat"><span>Total Products</span><strong>{{ $stats['total'] }}</strong></div>
        <div class="product-stat"><span>Active</span><strong>{{ $stats['active'] }}</strong></div>
        <div class="product-stat"><span>Low Stock</span><strong>{{ $stats['low_stock'] }}</strong></div>
        <div class="product-stat"><span>Inventory Value</span><strong>RM{{ number_format($stats['inventory_value'], 2) }}</strong></div>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="product-toolbar">
        <input name="search" class="product-input" value="{{ request('search') }}" placeholder="Search products or categories">
        <select name="status" class="product-select">
            <option value="all">All status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <select name="category" class="product-select">
            <option value="all">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
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
                        <div><span>Created</span><strong>{{ $product->created_at->format('M d, Y') }}</strong></div>
                    </div>
                    <div class="product-card-actions">
                        <a class="product-btn" href="{{ route('admin.products.show', $product) }}"><i class="fas fa-eye"></i> View</a>
                        <a class="product-btn" href="{{ route('admin.products.edit', $product) }}"><i class="fas fa-edit"></i> Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button class="product-btn danger" type="submit"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="product-empty">
                <i class="fas fa-box-open product-empty-icon"></i>
                <h3>No products found</h3>
                <p>Add your first product to start selling online and through POS.</p>
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="product-pagination">{{ $products->links('pagination::bootstrap-4') }}</div>
    @endif
</div>
@endsection
