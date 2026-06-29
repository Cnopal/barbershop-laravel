<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\ProductOrderConfirmedMail;
use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        ProductOrder::cancelExpiredPendingPayments();

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
        ProductOrder::cancelExpiredPendingPayments();

        abort_if($product->status !== 'active', 404);

        $relatedProducts = Product::available()
            ->where('id', '!=', $product->id)
            ->when($product->category, fn ($query) => $query->where('category', $product->category))
            ->take(3)
            ->get();

        return view('customer.products.show', compact('product', 'relatedProducts'));
    }

    public function checkout(Request $request, ?Product $product = null)
    {
        ProductOrder::cancelExpiredPendingPayments();

        $requestedProducts = $this->checkoutProducts($request, $product);

        if (!env('STRIPE_SECRET')) {
            return back()->withInput()->with('error', 'Stripe payment is not configured yet.');
        }

        $order = DB::transaction(function () use ($requestedProducts) {
            $products = Product::whereIn('id', array_keys($requestedProducts))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            $items = [];

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

                $subtotal = (float) $product->price * $quantity;
                $total += $subtotal;

                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }

            $order = ProductOrder::create([
                'order_number' => ProductOrder::generateOrderNumber('ONL'),
                'customer_id' => Auth::id(),
                'customer_name' => Auth::user()->name,
                'order_type' => 'online',
                'payment_method' => 'stripe',
                'payment_status' => 'pending_payment',
                'order_status' => ProductOrder::ORDER_PENDING,
                'total' => $total,
                'payment_expires_at' => now('Asia/Kuala_Lumpur')->addMinutes(ProductOrder::PAYMENT_RETRY_MINUTES),
            ]);

            foreach ($items as $item) {
                $order->items()->create($item);
            }

            return $order;
        });

        return redirect()->route('customer.product-orders.pay', $order);
    }

    public function pay(ProductOrder $order)
    {
        abort_if($order->customer_id !== Auth::id() || $order->order_type !== 'online', 403);

        $order->loadMissing('items.product');

        if ($order->cancelIfPaymentExpired()) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Payment window expired. Your product order has been cancelled.');
        }

        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('success', 'Payment already completed.');
        }

        if ($order->payment_status !== 'pending_payment' || $order->order_status !== ProductOrder::ORDER_PENDING) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'This product order is not available for payment.');
        }

        $order->ensurePaymentWindow();

        if ($order->isPaymentExpired()) {
            $order->cancelIfPaymentExpired();

            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Payment window expired. Your product order has been cancelled.');
        }

        if ($order->items->isEmpty()) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'This product order has no items to pay for.');
        }

        if (!env('STRIPE_SECRET')) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Stripe payment is not configured yet.');
        }

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $lineItems = $order->items->map(function ($item) {
                $productData = ['name' => $item->product_name];
                $imageUrl = $item->product ? $item->product->image_url : null;

                if ($imageUrl) {
                    $productData['images'] = [$imageUrl];
                }

                return [
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => $productData,
                        'unit_amount' => (int) round($item->unit_price * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            })->values()->all();

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('customer.product-orders.payment.success', $order) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('customer.product-orders.payment.cancel', $order),
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_id' => $order->customer_id,
                ],
            ]);
        } catch (\Throwable $exception) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Unable to start Stripe checkout. Please try again before the payment window expires.');
        }

        $order->update(['stripe_session_id' => $session->id]);

        return redirect($session->url);
    }

    public function orders(Request $request)
    {
        ProductOrder::cancelExpiredPendingPayments();

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
        $orders->getCollection()->each(function (ProductOrder $order) {
            $order->ensurePaymentWindow();
        });

        $orderStatuses = ProductOrder::trackableStatuses();

        return view('customer.product_orders.index', compact('orders', 'orderStatuses'));
    }

    public function orderShow(ProductOrder $order)
    {
        abort_if($order->customer_id !== Auth::id() || $order->order_type !== 'online', 403);

        if ($order->cancelIfPaymentExpired()) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Payment window expired. Your product order has been cancelled.');
        }

        $order->ensurePaymentWindow();
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

        if (!$request->filled('session_id')) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Payment session was missing. Please try again.');
        }

        if ($order->stripe_session_id && $order->stripe_session_id !== $request->session_id) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Payment session did not match this order.');
        }

        if ($order->cancelIfPaymentExpired()) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Payment was not completed within 5 minutes. Your product order has been cancelled.');
        }

        if ($order->payment_status !== 'pending_payment' || $order->order_status !== ProductOrder::ORDER_PENDING) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'This product order is not available for payment.');
        }

        try {
            DB::transaction(function () use ($order) {
                $lockedOrder = ProductOrder::whereKey($order->id)->lockForUpdate()->firstOrFail();
                $lockedOrder->load('items');

                if ($lockedOrder->payment_status === 'paid') {
                    return;
                }

                foreach ($lockedOrder->items as $item) {
                    $product = Product::whereKey($item->product_id)->lockForUpdate()->first();

                    if (!$product || $product->status !== 'active' || $product->stock < $item->quantity) {
                        throw new RuntimeException('Product stock is no longer available.');
                    }

                    $product->decrement('stock', $item->quantity);
                }

                $lockedOrder->update([
                    'payment_status' => 'paid',
                    'order_status' => ProductOrder::ORDER_PROCESSING,
                    'paid_at' => now('Asia/Kuala_Lumpur'),
                    'stock_reduced_at' => now('Asia/Kuala_Lumpur'),
                ]);
            });
        } catch (RuntimeException $exception) {
            $order->refresh();
            $order->update([
                'payment_status' => 'paid',
                'order_status' => ProductOrder::ORDER_NEEDS_REVIEW,
                'paid_at' => $order->paid_at ?: now('Asia/Kuala_Lumpur'),
            ]);

            $this->sendProductOrderConfirmationEmail($order);

            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Payment received, but stock needs admin review before fulfilment.');
        }

        $order->refresh();
        $this->sendProductOrderConfirmationEmail($order);

        return redirect()
            ->route('customer.product-orders.show', $order)
            ->with('success', 'Payment successful! Your product order is confirmed.');
    }

    public function paymentCancel(ProductOrder $order)
    {
        abort_if($order->customer_id !== Auth::id() || $order->order_type !== 'online', 403);

        if ($order->cancelIfPaymentExpired()) {
            return redirect()
                ->route('customer.product-orders.show', $order)
                ->with('error', 'Payment window expired. Your product order has been cancelled.');
        }

        $minutesRemaining = $order->paymentMinutesRemaining();
        $message = $minutesRemaining > 0
            ? "Payment was not completed. You can retry payment within {$minutesRemaining} minute(s)."
            : 'Payment was not completed.';

        return redirect()
            ->route('customer.product-orders.show', $order)
            ->with('error', $message);
    }

    private function checkoutProducts(Request $request, ?Product $product = null): array
    {
        if ($product) {
            abort_if($product->status !== 'active', 404);

            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:' . max(1, $product->stock),
            ]);

            return [$product->id => (int) $validated['quantity']];
        }

        $validated = $request->validate([
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['required', 'integer', 'exists:products,id'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:1'],
        ], [
            'product_ids.required' => 'Please select at least one product to checkout.',
            'product_ids.min' => 'Please select at least one product to checkout.',
        ]);

        $requestedProducts = [];

        foreach ($validated['product_ids'] as $productId) {
            $quantity = (int) ($validated['quantities'][$productId] ?? 0);

            if ($quantity < 1) {
                throw ValidationException::withMessages([
                    'product_ids' => 'Please enter a quantity for each selected product.',
                ]);
            }

            $requestedProducts[$productId] = ($requestedProducts[$productId] ?? 0) + $quantity;
        }

        if (empty($requestedProducts)) {
            throw ValidationException::withMessages([
                'product_ids' => 'Please select at least one product to checkout.',
            ]);
        }

        return $requestedProducts;
    }

    private function sendProductOrderConfirmationEmail(ProductOrder $order): void
    {
        $order->loadMissing(['customer', 'items']);

        if (!$order->customer || !$order->customer->email) {
            return;
        }

        try {
            Mail::to($order->customer->email)
                ->send(new ProductOrderConfirmedMail($order));
        } catch (\Throwable $exception) {
            Log::warning('Product order confirmation email failed.', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_id' => $order->customer_id,
                'customer_email' => $order->customer->email,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
