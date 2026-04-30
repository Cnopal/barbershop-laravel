<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductOrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductOrder::with(['customer', 'items'])
            ->online()
            ->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'not_received') {
                $query->notReceived();
            } else {
                $query->where('order_status', $request->status);
            }
        }

        if ($request->filled('payment') && $request->payment !== 'all') {
            $query->where('payment_status', $request->payment);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($customerQuery) => $customerQuery->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->paginate(12)->withQueryString();

        $stats = [
            'not_received' => ProductOrder::online()->notReceived()->count(),
            'processing' => ProductOrder::online()->where('order_status', ProductOrder::ORDER_PROCESSING)->count(),
            'ready' => ProductOrder::online()->where('order_status', ProductOrder::ORDER_READY)->count(),
            'received' => ProductOrder::online()->where('order_status', ProductOrder::ORDER_RECEIVED)->count(),
        ];

        return view('product_orders.index', [
            'orders' => $orders,
            'stats' => $stats,
            'statuses' => ProductOrder::trackableStatuses(),
            'layout' => $this->layout(),
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function show(ProductOrder $order)
    {
        abort_if($order->order_type !== 'online', 404);

        $order->load(['customer', 'items.product']);

        return view('product_orders.show', [
            'order' => $order,
            'statuses' => ProductOrder::fulfilmentStatuses(),
            'layout' => $this->layout(),
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function updateStatus(Request $request, ProductOrder $order)
    {
        abort_if($order->order_type !== 'online', 404);

        $validated = $request->validate([
            'order_status' => ['required', Rule::in(array_keys(ProductOrder::fulfilmentStatuses()))],
        ]);

        if (in_array($order->order_status, [ProductOrder::ORDER_RECEIVED, ProductOrder::ORDER_CANCELLED], true)) {
            return back()->with('error', 'This order is already closed and cannot be changed.');
        }

        if ($order->payment_status !== 'paid' && $validated['order_status'] !== ProductOrder::ORDER_CANCELLED) {
            return back()->with('error', 'Only paid orders can move to fulfilment.');
        }

        DB::transaction(function () use ($order, $validated) {
            $order->load('items');

            $updates = [
                'order_status' => $validated['order_status'],
            ];

            if ($validated['order_status'] === ProductOrder::ORDER_RECEIVED) {
                $updates['received_at'] = now();
            }

            if ($validated['order_status'] === ProductOrder::ORDER_CANCELLED) {
                if ($order->stock_reduced_at) {
                    foreach ($order->items as $item) {
                        Product::whereKey($item->product_id)->increment('stock', $item->quantity);
                    }

                    $updates['stock_reduced_at'] = null;
                }

                if ($order->payment_status === 'pending_payment') {
                    $updates['payment_status'] = 'cancelled';
                }
            }

            $order->update($updates);
        });

        return redirect()
            ->route($this->routePrefix() . '.product-orders.show', $order)
            ->with('success', 'Product order status updated.');
    }

    private function routePrefix(): string
    {
        return Auth::user()->role === 'admin' ? 'admin' : 'staff';
    }

    private function layout(): string
    {
        return Auth::user()->role === 'admin' ? 'admin.sidebar' : 'staff.sidebar';
    }
}
