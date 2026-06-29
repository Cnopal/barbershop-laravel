@php
    $customer = $order->customer;
    $paidAt = $order->paid_at?->timezone('Asia/Kuala_Lumpur')->format('M d, Y h:i A');
    $isNeedsReview = $order->order_status === \App\Models\ProductOrder::ORDER_NEEDS_REVIEW;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Order Confirmed</title>
</head>
<body style="margin:0; padding:0; background:#f5f1e8; font-family:Arial, Helvetica, sans-serif; color:#1f1f1f;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f1e8; padding:32px 14px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px; background:#ffffff; border-radius:14px; overflow:hidden; border:1px solid #eadfca;">
                    <tr>
                        <td style="padding:28px 28px 20px; background:#0f1115; color:#ffffff;">
                            <div style="font-size:13px; letter-spacing:1.8px; text-transform:uppercase; color:#d4af37; font-weight:700;">Men's Club</div>
                            <h1 style="margin:12px 0 0; font-size:28px; line-height:1.2;">Product order confirmed</h1>
                            <p style="margin:10px 0 0; color:#d8d8d8; line-height:1.6;">
                                Hi {{ $customer->name ?? $order->customer_name ?? 'there' }}, your payment was successful for order {{ $order->order_number }}.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:26px 28px;">
                            @if($isNeedsReview)
                                <div style="margin-bottom:18px; padding:14px 16px; border-radius:10px; background:#fff8e6; border:1px solid #f1d58a; color:#7a5412; line-height:1.6;">
                                    Payment received. Staff will review stock availability before fulfilment.
                                </div>
                            @else
                                <div style="margin-bottom:18px; padding:14px 16px; border-radius:10px; background:#effaf4; border:1px solid #b7e4c7; color:#276749; line-height:1.6;">
                                    Your order is now processing. We will prepare it for pickup.
                                </div>
                            @endif

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #eee7da; color:#777; width:38%;">Order number</td>
                                    <td style="padding:12px 0; border-bottom:1px solid #eee7da; font-weight:700;">{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #eee7da; color:#777;">Order status</td>
                                    <td style="padding:12px 0; border-bottom:1px solid #eee7da; font-weight:700;">{{ $order->order_status_label }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #eee7da; color:#777;">Paid at</td>
                                    <td style="padding:12px 0; border-bottom:1px solid #eee7da; font-weight:700;">{{ $paidAt ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 0; border-bottom:1px solid #eee7da; color:#777;">Total paid</td>
                                    <td style="padding:12px 0; border-bottom:1px solid #eee7da; font-weight:700;">RM {{ number_format((float) $order->total, 2) }}</td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                                <thead>
                                    <tr>
                                        <th align="left" style="padding:10px 0; border-bottom:2px solid #eadfca; color:#555;">Product</th>
                                        <th align="center" style="padding:10px 0; border-bottom:2px solid #eadfca; color:#555;">Qty</th>
                                        <th align="right" style="padding:10px 0; border-bottom:2px solid #eadfca; color:#555;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td style="padding:12px 0; border-bottom:1px solid #eee7da; font-weight:700;">{{ $item->product_name }}</td>
                                            <td align="center" style="padding:12px 0; border-bottom:1px solid #eee7da;">{{ $item->quantity }}</td>
                                            <td align="right" style="padding:12px 0; border-bottom:1px solid #eee7da;">RM {{ number_format((float) $item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div style="margin-top:24px;">
                                <a href="{{ route('customer.product-orders.show', $order) }}" style="display:inline-block; padding:13px 18px; border-radius:8px; background:#d4af37; color:#111111; text-decoration:none; font-weight:700;">View order</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 28px; background:#fbf8ef; color:#777; font-size:13px; line-height:1.6;">
                            This email was sent automatically after a successful product order payment.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>