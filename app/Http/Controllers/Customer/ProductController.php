<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::available();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Product::available()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('customer.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_if($product->status !== 'active', 404);

        $relatedProducts = Product::available()
            ->where('id', '!=', $product->id)
            ->when($product->category, fn ($query) => $query->where('category', $product->category))
            ->take(3)
            ->get();

        return view('customer.products.show', compact('product', 'relatedProducts'));
    }

    public function checkout(Request $request, Product $product)
    {
        abort_if($product->status !== 'active', 404);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . max(1, $product->stock),
        ]);

        if ($product->stock < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available for this product.');
        }

        if (!env('STRIPE_SECRET')) {
            return back()->with('error', 'Stripe payment is not configured yet.');
        }

        $total = $product->price * $validated['quantity'];

        $order = ProductOrder::create([
            'order_number' => ProductOrder::generateOrderNumber('ONL'),
            'customer_id' => Auth::id(),
            'customer_name' => Auth::user()->name,
            'order_type' => 'online',
            'payment_method' => 'stripe',
            'payment_status' => 'pending_payment',
            'order_status' => ProductOrder::ORDER_PENDING,
            'total' => $total,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => $product->price,
            'quantity' => $validated['quantity'],
            'subtotal' => $total,
        ]);

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $productData = ['name' => $product->name];
            if ($product->image_url) {
                $productData['images'] = [$product->image_url];
            }

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'myr',
                            'product_data' => $productData,
                            'unit_amount' => (int) round($product->price * 100),
                        ],
                        'quantity' => $validated['quantity'],
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('customer.product-orders.payment.success', $order) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('customer.product-orders.payment.cancel', $order),
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ]);
        } catch (\Throwable $exception) {
            $order->update([
                'payment_status' => 'cancelled',
                'order_status' => ProductOrder::ORDER_CANCELLED,
            ]);

            return redirect()
                ->route('customer.products.show', $product)
                ->with('error', 'Unable to start Stripe checkout. Please try again.');
        }

        $order->update(['stripe_session_id' => $session->id]);

        return redirect($session->url);
    }

    public function orders(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', 'in:newest,oldest'],
            'status' => ['nullable', 'in:all,' . implode(',', array_keys(ProductOrder::trackableStatuses()))],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        if (!empty($filters['date_from']) && !empty($filters['date_to']) && strtotime($filters['date_to']) < strtotime($filters['date_from'])) {
            return back()
                ->withErrors(['date_to' => 'The end date must be after or equal to the start date.'])
                ->withInput();
        }

        $query = ProductOrder::with('items')
            ->where('customer_id', Auth::id())
            ->where('order_type', 'online');

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $statusSearch = str_replace([' ', '-'], '_', strtolower($search));

            $query->where(function ($q) use ($search, $statusSearch) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('payment_status', 'like', "%{$search}%")
                    ->orWhere('order_status', 'like', "%{$search}%")
                    ->orWhere('payment_status', 'like', "%{$statusSearch}%")
                    ->orWhere('order_status', 'like', "%{$statusSearch}%")
                    ->orWhereHas('items', function ($itemQuery) use ($search) {
                        $itemQuery->where('product_name', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('order_status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $sort = $filters['sort'] ?? 'newest';
        $sort === 'oldest'
            ? $query->orderBy('created_at')
            : $query->latest('created_at');

        $orders = $query->paginate(10)->withQueryString();
        $orderStatuses = ProductOrder::trackableStatuses();

        return view('customer.product_orders.index', compact('orders', 'orderStatuses'));
    }

    public function orderShow(ProductOrder $order)
    {
        abort_if($order->customer_id !== Auth::id() || $order->order_type !== 'online', 403);

        $order->load('items.product');

        return view('customer.product_orders.show', compact('order'));
    }

    public function paymentSuccess(Request $request, ProductOrder $order)
    {
        abort_if($order->customer_id !== Auth::id() || $order->order_type !== 'online', 403);

        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('success', 'Payment already completed.');
        }

        if ($order->stripe_session_id && $request->filled('session_id') && $order->stripe_session_id !== $request->session_id) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Payment session did not match this order.');
        }

        try {
            DB::transaction(function () use ($order) {
                $order->load('items');

                foreach ($order->items as $item) {
                    $product = Product::whereKey($item->product_id)->lockForUpdate()->first();

                    if (!$product || $product->status !== 'active' || $product->stock < $item->quantity) {
                        throw new RuntimeException('Product stock is no longer available.');
                    }

                    $product->decrement('stock', $item->quantity);
                }

                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => ProductOrder::ORDER_PROCESSING,
                    'paid_at' => now(),
                    'stock_reduced_at' => now(),
                ]);
            });
        } catch (RuntimeException $exception) {
            $order->update([
                'payment_status' => 'paid',
                'order_status' => ProductOrder::ORDER_NEEDS_REVIEW,
                'paid_at' => $order->paid_at ?: now(),
            ]);

            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Payment received, but stock needs admin review before fulfilment.');
        }

        return redirect()
            ->route('customer.product-orders.show', $order)
            ->with('success', 'Payment successful! Your product order is confirmed.');
    }

    public function paymentCancel(ProductOrder $order)
    {
        abort_if($order->customer_id !== Auth::id() || $order->order_type !== 'online', 403);

        if ($order->payment_status === 'pending_payment') {
            $order->update([
                'payment_status' => 'cancelled',
                'order_status' => ProductOrder::ORDER_CANCELLED,
            ]);
        }

        return redirect()
            ->route('customer.product-orders.show', $order)
            ->with('error', 'Payment cancelled.');
    }
}
