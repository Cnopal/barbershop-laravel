<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::active()
            ->orderBy('name')
            ->get();

        $recentOrders = ProductOrder::with('staff')
            ->where('order_type', 'pos')
            ->latest()
            ->take(6)
            ->get();

        return view('pos.index', [
            'products' => $products,
            'recentOrders' => $recentOrders,
            'layout' => $this->layout(),
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'cash_received' => 'nullable|numeric|min:0',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'required|integer|exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
        ]);

        $requestedProducts = [];
        foreach ($validated['product_ids'] as $index => $productId) {
            $quantity = (int) ($validated['quantities'][$index] ?? 0);
            if ($quantity < 1) {
                continue;
            }

            $requestedProducts[$productId] = ($requestedProducts[$productId] ?? 0) + $quantity;
        }

        if (empty($requestedProducts)) {
            throw ValidationException::withMessages([
                'product_ids' => 'Please add at least one product to the POS cart.',
            ]);
        }

        $order = DB::transaction(function () use ($request, $requestedProducts) {
            $products = Product::whereIn('id', array_keys($requestedProducts))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            foreach ($requestedProducts as $productId => $quantity) {
                $product = $products->get($productId);

                if (!$product || $product->status !== 'active') {
                    throw ValidationException::withMessages([
                        'product_ids' => 'One of the selected products is no longer available.',
                    ]);
                }

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'product_ids' => "{$product->name} only has {$product->stock} item(s) in stock.",
                    ]);
                }

                $total += $product->price * $quantity;
            }

            if ($request->filled('cash_received') && (float) $request->cash_received < $total) {
                throw ValidationException::withMessages([
                    'cash_received' => 'Cash received cannot be less than the order total.',
                ]);
            }

            $order = ProductOrder::create([
                'order_number' => ProductOrder::generateOrderNumber('POS'),
                'staff_id' => Auth::id(),
                'customer_name' => $request->customer_name ?: 'Walk-in Customer',
                'customer_phone' => $request->customer_phone,
                'order_type' => 'pos',
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'order_status' => ProductOrder::ORDER_RECEIVED,
                'total' => $total,
                'paid_at' => now(),
                'stock_reduced_at' => now(),
                'received_at' => now(),
            ]);

            foreach ($requestedProducts as $productId => $quantity) {
                $product = $products->get($productId);
                $subtotal = $product->price * $quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $quantity);
            }

            return $order;
        });

        return redirect()
            ->route($this->routePrefix() . '.pos.orders.show', $order)
            ->with('success', 'Cash sale completed successfully.');
    }

    public function orders()
    {
        $orders = ProductOrder::with(['staff', 'items'])
            ->where('order_type', 'pos')
            ->latest()
            ->paginate(15);

        return view('pos.orders.index', [
            'orders' => $orders,
            'layout' => $this->layout(),
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function show(ProductOrder $order)
    {
        abort_if($order->order_type !== 'pos', 404);

        $order->load(['staff', 'items.product']);

        return view('pos.orders.show', [
            'order' => $order,
            'layout' => $this->layout(),
            'routePrefix' => $this->routePrefix(),
        ]);
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
