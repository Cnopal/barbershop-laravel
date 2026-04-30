@extends($layout)

@section('content')
<div class="order-track-page">
    <div class="order-track-header">
        <div>
            <h1>{{ $order->order_number }}</h1>
            <p>Online product order tracking</p>
        </div>
        <div class="order-track-actions">
            <a href="{{ route($routePrefix . '.product-orders.index') }}" class="order-track-btn">
                <i class="fas fa-arrow-left"></i> Orders
            </a>
            <a href="{{ route($routePrefix . '.products.index') }}" class="order-track-btn primary">
                <i class="fas fa-box-open"></i> Products
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="order-track-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="order-track-alert error">{{ session('error') }}</div>
    @endif

    <div class="order-track-layout">
        <div class="order-track-card">
            <div class="order-track-meta">
                <div><span>Customer</span><strong>{{ $order->customer->name ?? $order->customer_name ?? 'Customer' }}</strong></div>
                <div><span>Phone</span><strong>{{ $order->customer_phone ?: ($order->customer->phone ?? '-') }}</strong></div>
                <div><span>Total</span><strong>RM{{ number_format($order->total, 2) }}</strong></div>
                <div><span>Payment</span><strong class="order-pill payment-{{ $order->payment_status }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</strong></div>
                <div><span>Order Status</span><strong class="order-pill order-{{ $order->order_status }}">{{ $order->order_status_label }}</strong></div>
                <div><span>Paid At</span><strong>{{ optional($order->paid_at)->format('M d, Y h:i A') ?: '-' }}</strong></div>
                <div><span>Received At</span><strong>{{ optional($order->received_at)->format('M d, Y h:i A') ?: '-' }}</strong></div>
                <div><span>Created</span><strong>{{ $order->created_at->format('M d, Y h:i A') }}</strong></div>
            </div>

            <div class="order-track-progress">
                @php
                    $steps = [
                        \App\Models\ProductOrder::ORDER_PENDING => 'Ordered',
                        \App\Models\ProductOrder::ORDER_PROCESSING => 'Processing',
                        \App\Models\ProductOrder::ORDER_READY => 'Ready',
                        \App\Models\ProductOrder::ORDER_RECEIVED => 'Received',
                    ];
                    $currentIndex = array_search($order->order_status, array_keys($steps), true);
                @endphp

                @if($order->order_status === \App\Models\ProductOrder::ORDER_CANCELLED)
                    <div class="order-track-state cancelled">
                        <i class="fas fa-ban"></i>
                        <strong>Order cancelled</strong>
                    </div>
                @elseif($order->order_status === \App\Models\ProductOrder::ORDER_NEEDS_REVIEW)
                    <div class="order-track-state review">
                        <i class="fas fa-triangle-exclamation"></i>
                        <strong>Needs admin or staff review before fulfilment.</strong>
                    </div>
                @else
                    @foreach($steps as $status => $label)
                        @php
                            $stepIndex = $loop->index;
                            $isActive = $currentIndex !== false && $stepIndex <= $currentIndex;
                            $isCurrent = $order->order_status === $status;
                        @endphp
                        <div class="order-step {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                            <span>{{ $stepIndex + 1 }}</span>
                            <strong>{{ $label }}</strong>
                        </div>
                    @endforeach
                @endif
            </div>

            <table class="order-track-table compact">
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
        </div>

        <div class="order-track-card side">
            <h2>Update Status</h2>

            @if(in_array($order->order_status, [\App\Models\ProductOrder::ORDER_RECEIVED, \App\Models\ProductOrder::ORDER_CANCELLED], true))
                <p class="order-track-note">This order is closed. No more status changes are available.</p>
            @else
                <form method="POST" action="{{ route($routePrefix . '.product-orders.status', $order) }}" class="order-status-form">
                    @csrf
                    @method('PATCH')

                    <label for="order_status">Next status</label>
                    <select id="order_status" name="order_status" class="order-track-select">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ old('order_status', $order->order_status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('order_status')
                        <span class="order-track-error">{{ $message }}</span>
                    @enderror

                    <button type="submit" class="order-track-btn primary">
                        <i class="fas fa-save"></i> Save Status
                    </button>
                </form>
            @endif

            <div class="order-track-note">
                <strong>Useful statuses</strong>
                <span>Processing means paid but not ready. Ready for pickup means the customer can collect. Received closes the order.</span>
            </div>
        </div>
    </div>
</div>

<style>
    .order-track-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .order-track-header,
    .order-track-actions {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .order-track-header {
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 26px;
    }

    .order-track-header h1 {
        margin: 0 0 8px;
        font-size: 32px;
        font-weight: 800;
    }

    .order-track-header p {
        margin: 0;
        color: #718096;
    }

    .order-track-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 22px;
        align-items: start;
    }

    .order-track-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .order-track-card.side h2 {
        margin: 0 0 18px;
        font-size: 22px;
        font-weight: 800;
    }

    .order-track-meta {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 22px;
    }

    .order-track-meta div {
        background: #f7fafc;
        border-radius: 8px;
        padding: 12px;
    }

    .order-track-meta span {
        display: block;
        color: #718096;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .order-track-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        color: #1a1f36;
        text-decoration: none;
        font-weight: 800;
        cursor: pointer;
    }

    .order-track-btn.primary {
        background: #d4af37;
        border-color: #d4af37;
    }

    .order-track-progress {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 22px;
    }

    .order-step {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 14px;
        color: #718096;
        background: #fff;
    }

    .order-step span {
        display: inline-flex;
        width: 28px;
        height: 28px;
        border-radius: 999px;
        align-items: center;
        justify-content: center;
        background: #edf2f7;
        font-weight: 900;
        margin-bottom: 10px;
    }

    .order-step strong {
        display: block;
    }

    .order-step.active {
        border-color: rgba(72, 187, 120, 0.35);
        color: #1a1f36;
        background: rgba(72, 187, 120, 0.08);
    }

    .order-step.current {
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.12);
    }

    .order-track-state {
        grid-column: 1 / -1;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
    }

    .order-track-state.review {
        background: rgba(159, 122, 234, 0.14);
        color: #6b46c1;
    }

    .order-track-state.cancelled {
        background: rgba(245, 101, 101, 0.12);
        color: #c53030;
    }

    .order-track-table {
        width: 100%;
        border-collapse: collapse;
    }

    .order-track-table th,
    .order-track-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }

    .order-track-table th {
        color: #718096;
        font-size: 13px;
        text-transform: uppercase;
    }

    .order-track-table tfoot td {
        border-bottom: none;
        font-size: 18px;
        font-weight: 900;
    }

    .order-track-table tfoot td:last-child {
        color: #d4af37;
    }

    .order-status-form {
        display: grid;
        gap: 12px;
    }

    .order-status-form label {
        font-weight: 800;
    }

    .order-track-select {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 15px;
        color: #1a1f36;
        background: #fff;
    }

    .order-pill {
        display: inline-flex;
        border-radius: 999px;
        padding: 6px 11px;
        font-size: 12px;
        font-weight: 900;
        background: #edf2f7;
        color: #4a5568;
        white-space: nowrap;
    }

    .payment-paid,
    .order-received {
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
    }

    .payment-pending_payment,
    .order-pending,
    .order-processing {
        background: rgba(237, 137, 54, 0.14);
        color: #c05621;
    }

    .order-ready_for_pickup {
        background: rgba(66, 153, 225, 0.14);
        color: #2b6cb0;
    }

    .order-needs_review {
        background: rgba(159, 122, 234, 0.14);
        color: #6b46c1;
    }

    .payment-cancelled,
    .order-cancelled {
        background: rgba(245, 101, 101, 0.12);
        color: #c53030;
    }

    .order-track-note {
        display: grid;
        gap: 6px;
        margin-top: 18px;
        color: #718096;
        line-height: 1.6;
    }

    .order-track-note strong {
        color: #1a1f36;
    }

    .order-track-error {
        color: #c53030;
        font-size: 13px;
        font-weight: 800;
    }

    .order-track-alert {
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-weight: 700;
    }

    .order-track-alert.success {
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
        border: 1px solid rgba(72, 187, 120, 0.25);
    }

    .order-track-alert.error {
        background: rgba(245, 101, 101, 0.12);
        color: #c53030;
        border: 1px solid rgba(245, 101, 101, 0.25);
    }

    @media (max-width: 1100px) {
        .order-track-layout,
        .order-track-meta,
        .order-track-progress {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .order-track-page {
            padding: 20px;
        }
    }
</style>
@endsection
