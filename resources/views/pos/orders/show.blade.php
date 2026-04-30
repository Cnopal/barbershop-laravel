@extends($layout)

@section('content')
<div class="pos-receipt-page">
    <div class="pos-receipt-header">
        <div>
            <h1>{{ $order->order_number }}</h1>
            <p>Cash POS receipt</p>
        </div>
        <div class="pos-receipt-actions">
            <a href="{{ route($routePrefix . '.pos.orders.index') }}" class="pos-receipt-btn"><i class="fas fa-arrow-left"></i> Orders</a>
            <a href="{{ route($routePrefix . '.pos.index') }}" class="pos-receipt-btn primary"><i class="fas fa-cash-register"></i> New Sale</a>
        </div>
    </div>

    <div class="pos-receipt-card">
        <div class="pos-receipt-meta">
            <div><span>Customer</span><strong>{{ $order->customer_name ?: 'Walk-in Customer' }}</strong></div>
            <div><span>Phone</span><strong>{{ $order->customer_phone ?: '-' }}</strong></div>
            <div><span>Handled By</span><strong>{{ $order->staff->name ?? 'Deleted staff' }}</strong></div>
            <div><span>Paid At</span><strong>{{ optional($order->paid_at)->format('M d, Y h:i A') }}</strong></div>
            <div><span>Payment</span><strong>{{ ucfirst($order->payment_method) }}</strong></div>
            <div><span>Payment Status</span><strong>{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</strong></div>
            <div><span>Order Status</span><strong>{{ $order->order_status_label }}</strong></div>
            <div><span>Received At</span><strong>{{ optional($order->received_at)->format('M d, Y h:i A') ?: '-' }}</strong></div>
        </div>

        <table class="pos-receipt-table">
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
</div>

<style>
    .pos-receipt-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .pos-receipt-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }

    .pos-receipt-header h1 {
        margin: 0 0 8px;
        font-size: 32px;
        font-weight: 800;
    }

    .pos-receipt-header p {
        margin: 0;
        color: #718096;
    }

    .pos-receipt-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .pos-receipt-btn {
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
    }

    .pos-receipt-btn.primary {
        background: #d4af37;
        border-color: #d4af37;
    }

    .pos-receipt-card {
        max-width: 980px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .pos-receipt-meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 22px;
    }

    .pos-receipt-meta div {
        background: #f7fafc;
        border-radius: 8px;
        padding: 12px;
    }

    .pos-receipt-meta span {
        display: block;
        color: #718096;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .pos-receipt-table {
        width: 100%;
        border-collapse: collapse;
    }

    .pos-receipt-table th,
    .pos-receipt-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }

    .pos-receipt-table th {
        color: #718096;
        font-size: 13px;
        text-transform: uppercase;
    }

    .pos-receipt-table tfoot td {
        border-bottom: none;
        font-size: 19px;
        font-weight: 900;
    }

    .pos-receipt-table tfoot td:last-child {
        color: #d4af37;
    }

    @media (max-width: 720px) {
        .pos-receipt-page {
            padding: 20px;
        }

        .pos-receipt-meta {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
