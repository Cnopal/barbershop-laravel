@extends($layout)

@section('content')
<div class="order-track-page">
    <div class="order-track-header">
        <div>
            <h1>Product Orders</h1>
            <p>Track online product purchases from payment to customer pickup.</p>
        </div>
        <a href="{{ route($routePrefix . '.products.index') }}" class="order-track-btn">
            <i class="fas fa-box-open"></i> Products
        </a>
    </div>

    @if(session('success'))
        <div class="order-track-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="order-track-alert error">{{ session('error') }}</div>
    @endif

    <div class="order-track-stats">
        <div class="order-stat">
            <span>Not Received</span>
            <strong>{{ $stats['not_received'] }}</strong>
        </div>
        <div class="order-stat">
            <span>Processing</span>
            <strong>{{ $stats['processing'] }}</strong>
        </div>
        <div class="order-stat">
            <span>Ready</span>
            <strong>{{ $stats['ready'] }}</strong>
        </div>
        <div class="order-stat">
            <span>Received</span>
            <strong>{{ $stats['received'] }}</strong>
        </div>
    </div>

    <form method="GET" class="order-track-toolbar">
        <input type="text" name="search" value="{{ request('search') }}" class="order-track-input" placeholder="Search order or customer">
        <select name="status" class="order-track-select">
            <option value="all">All order statuses</option>
            <option value="not_received" {{ request('status') === 'not_received' ? 'selected' : '' }}>Not received yet</option>
            @foreach($statuses as $value => $label)
                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="payment" class="order-track-select">
            <option value="all">All payments</option>
            <option value="pending_payment" {{ request('payment') === 'pending_payment' ? 'selected' : '' }}>Pending payment</option>
            <option value="paid" {{ request('payment') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="cancelled" {{ request('payment') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="order-track-btn primary">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>

    <div class="order-track-card">
        <table class="order-track-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Order Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->customer->name ?? $order->customer_name ?? 'Customer' }}</td>
                        <td>{{ $order->items->sum('quantity') }}</td>
                        <td>RM{{ number_format($order->total, 2) }}</td>
                        <td><span class="order-badge payment-{{ $order->payment_status }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span></td>
                        <td><span class="order-badge order-{{ $order->order_status }}">{{ $order->order_status_label }}</span></td>
                        <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            <a href="{{ route($routePrefix . '.product-orders.show', $order) }}" class="order-track-btn">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="order-track-empty">
                            <i class="fas fa-receipt"></i>
                            <strong>No product orders found</strong>
                            <span>Online customer purchases will appear here.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="order-track-pagination">{{ $orders->links('pagination::bootstrap-4') }}</div>
    @endif
</div>

<style>
    .order-track-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .order-track-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }

    .order-track-header h1 {
        margin: 0 0 8px;
        font-size: 32px;
        font-weight: 800;
    }

    .order-track-header p,
    .order-track-empty span {
        margin: 0;
        color: #718096;
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
        white-space: nowrap;
    }

    .order-track-btn.primary {
        background: #d4af37;
        border-color: #d4af37;
    }

    .order-track-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .order-stat {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 18px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .order-stat span {
        display: block;
        color: #718096;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .order-stat strong {
        display: block;
        margin-top: 8px;
        font-size: 26px;
    }

    .order-track-toolbar {
        display: grid;
        grid-template-columns: 1fr 220px 190px auto;
        gap: 12px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 22px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .order-track-input,
    .order-track-select {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 15px;
        color: #1a1f36;
        background: #fff;
    }

    .order-track-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: auto;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .order-track-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .order-track-table th,
    .order-track-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }

    .order-track-table th {
        color: #718096;
        font-size: 13px;
        text-transform: uppercase;
    }

    .order-badge {
        display: inline-flex;
        align-items: center;
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

    .order-track-empty {
        text-align: center;
        color: #718096;
        padding: 42px;
    }

    .order-track-empty i,
    .order-track-empty strong {
        display: block;
        margin-bottom: 8px;
    }

    .order-track-empty i {
        color: #d4af37;
        font-size: 34px;
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

    .order-track-pagination {
        margin-top: 22px;
    }

    @media (max-width: 900px) {
        .order-track-stats,
        .order-track-toolbar {
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
