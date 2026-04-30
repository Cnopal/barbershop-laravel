@extends('customer.sidebar')

@section('content')
@include('customer.products._styles')

<div class="customer-page shop-page">
    <div class="shop-header">
        <div>
            <h1>{{ $order->order_number }}</h1>
            <p>Product order details and pickup status</p>
        </div>
        <div class="shop-inline-actions">
            <a href="{{ route('customer.product-orders.index') }}" class="shop-btn"><i class="fas fa-arrow-left"></i> Orders</a>
            <a href="{{ route('customer.products.index') }}" class="shop-btn primary"><i class="fas fa-store"></i> Shop</a>
        </div>
    </div>

    @if(session('success'))
        <div class="shop-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="shop-alert error">{{ session('error') }}</div>
    @endif

    <div class="shop-order-card">
        <div class="shop-card-footer summary">
            <div class="shop-order-badges">
                <span class="shop-status {{ $order->payment_status }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                <span class="shop-status order-{{ $order->order_status }}">{{ $order->order_status_label }}</span>
                <p class="shop-order-date">{{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="shop-price">RM{{ number_format($order->total, 2) }}</div>
        </div>

        @php
            $steps = [
                \App\Models\ProductOrder::ORDER_PENDING => 'Ordered',
                \App\Models\ProductOrder::ORDER_PROCESSING => 'Processing',
                \App\Models\ProductOrder::ORDER_READY => 'Ready',
                \App\Models\ProductOrder::ORDER_RECEIVED => 'Received',
            ];
            $currentIndex = array_search($order->order_status, array_keys($steps), true);
        @endphp

        <div class="shop-order-progress">
            @if($order->order_status === \App\Models\ProductOrder::ORDER_CANCELLED)
                <div class="shop-order-state cancelled">
                    <i class="fas fa-ban"></i>
                    <strong>This order has been cancelled.</strong>
                </div>
            @elseif($order->order_status === \App\Models\ProductOrder::ORDER_NEEDS_REVIEW)
                <div class="shop-order-state review">
                    <i class="fas fa-triangle-exclamation"></i>
                    <strong>Your payment was received. Staff will review stock before fulfilment.</strong>
                </div>
            @else
                @foreach($steps as $status => $label)
                    @php
                        $stepIndex = $loop->index;
                        $isActive = $currentIndex !== false && $stepIndex <= $currentIndex;
                        $isCurrent = $order->order_status === $status;
                    @endphp
                    <div class="shop-order-step {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                        <span>{{ $stepIndex + 1 }}</span>
                        <strong>{{ $label }}</strong>
                    </div>
                @endforeach
            @endif
        </div>

        <table class="shop-order-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>RM{{ number_format($item->unit_price, 2) }}</td>
                        <td>RM{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total</td>
                    <td>RM{{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="shop-order-note">
            <strong>Payment method:</strong> {{ ucfirst($order->payment_method) }}
            @if($order->paid_at)
                &middot; <strong>Paid:</strong> {{ $order->paid_at->format('M d, Y h:i A') }}
            @endif
            @if($order->received_at)
                &middot; <strong>Received:</strong> {{ $order->received_at->format('M d, Y h:i A') }}
            @endif
        </div>
    </div>
</div>
@endsection
