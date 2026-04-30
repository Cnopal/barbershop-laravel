@extends($layout)

@section('content')
<div class="pos-orders-page">
    <div class="pos-orders-header">
        <div>
            <h1>POS Orders</h1>
            <p>Cash sales completed by admin and staff.</p>
        </div>
        <a href="{{ route($routePrefix . '.pos.index') }}" class="pos-orders-btn primary">
            <i class="fas fa-cash-register"></i> Open POS
        </a>
    </div>

    @if(session('success'))
        <div class="pos-orders-alert">{{ session('success') }}</div>
    @endif

    <div class="pos-orders-card">
        <table class="pos-orders-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Staff</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->customer_name ?: 'Walk-in Customer' }}</td>
                        <td>{{ $order->staff->name ?? 'Deleted staff' }}</td>
                        <td>{{ $order->items->sum('quantity') }}</td>
                        <td>RM{{ number_format($order->total, 2) }}</td>
                        <td><span class="pos-orders-badge">{{ $order->order_status_label }}</span></td>
                        <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            <a href="{{ route($routePrefix . '.pos.orders.show', $order) }}" class="pos-orders-btn">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="pos-orders-empty">No POS orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="pos-orders-pagination">{{ $orders->links('pagination::bootstrap-4') }}</div>
    @endif
</div>

<style>
    .pos-orders-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .pos-orders-header {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }

    .pos-orders-header h1 {
        margin: 0 0 8px;
        font-size: 32px;
        font-weight: 800;
    }

    .pos-orders-header p {
        margin: 0;
        color: #718096;
    }

    .pos-orders-btn {
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
        white-space: nowrap;
    }

    .pos-orders-btn.primary {
        background: #d4af37;
        border-color: #d4af37;
    }

    .pos-orders-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: auto;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .pos-orders-pagination {
        margin-top: 22px;
    }

    .pos-orders-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .pos-orders-table th,
    .pos-orders-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }

    .pos-orders-table th {
        color: #718096;
        font-size: 13px;
        text-transform: uppercase;
    }

    .pos-orders-table tr:last-child td {
        border-bottom: none;
    }

    .pos-orders-empty {
        text-align: center;
        color: #718096;
        padding: 36px;
    }

    .pos-orders-badge {
        display: inline-flex;
        border-radius: 999px;
        padding: 6px 11px;
        font-size: 12px;
        font-weight: 900;
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
        white-space: nowrap;
    }

    .pos-orders-alert {
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 18px;
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
        border: 1px solid rgba(72, 187, 120, 0.25);
        font-weight: 700;
    }

    @media (max-width: 700px) {
        .pos-orders-page {
            padding: 20px;
        }
    }
</style>
@endsection
