@extends('admin.sidebar')

@section('content')
@include('admin.products._styles')

<div class="product-admin-page">
    <div class="product-page-header">
        <div>
            <h1>{{ $product->name }}</h1>
            <p>{{ $product->category ?: 'General product' }}</p>
        </div>
        <div class="product-actions">
            <a href="{{ route('admin.products.index') }}" class="product-btn"><i class="fas fa-arrow-left"></i> Back</a>
            <a href="{{ route('admin.products.edit', $product) }}" class="product-btn primary"><i class="fas fa-edit"></i> Edit</a>
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="product-btn danger"><i class="fas fa-trash"></i> Delete</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="product-alert success">{{ session('success') }}</div>
    @endif

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
                <div><span>Sold Records</span><strong>{{ $product->order_items_count }}</strong></div>
            </div>
            <h3>Description</h3>
            <p class="product-description">{{ $product->description ?: 'No description provided.' }}</p>
        </div>
    </div>

    <div class="product-detail-card product-section">
        <h3>Recent Product Sales</h3>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Type</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentItems as $item)
                    <tr>
                        <td>{{ $item->order->order_number ?? 'Deleted order' }}</td>
                        <td>{{ ucfirst($item->order->order_type ?? '-') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>RM{{ number_format($item->subtotal, 2) }}</td>
                        <td>{{ $item->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="product-table-empty">No sales yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
