@extends($layout)

@section('content')
<div class="pos-page">
    <div class="pos-header">
        <div>
            <h1>Point of Sale</h1>
            <p>Cash checkout for walk-in product purchases.</p>
        </div>
        <div class="pos-header-actions">
            <a href="{{ route($routePrefix . '.pos.orders.index') }}" class="pos-btn">
                <i class="fas fa-receipt"></i> POS Orders
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="pos-alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="pos-alert error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="pos-alert error">{{ $errors->first() }}</div>
    @endif

    <div class="pos-layout">
        <section class="pos-products-panel">
            <div class="pos-panel-header">
                <h2>Products</h2>
                <div class="pos-search">
                    <i class="fas fa-search"></i>
                    <input id="productSearch" type="text" placeholder="Search products">
                </div>
            </div>

            <div class="pos-products-grid" id="productsGrid">
                @forelse($products as $product)
                    <button type="button"
                        class="pos-product-card"
                        data-id="{{ $product->id }}"
                        data-name="{{ strtolower($product->name) }}"
                        data-category="{{ strtolower($product->category ?? '') }}"
                        data-stock="{{ $product->stock }}"
                        {{ $product->stock < 1 ? 'disabled' : '' }}>
                        <div class="pos-product-image">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            @else
                                <i class="fas fa-box-open"></i>
                            @endif
                        </div>
                        <div class="pos-product-info">
                            <strong>{{ $product->name }}</strong>
                            <span>{{ $product->category ?: 'General' }}</span>
                            <div>
                                <b>RM{{ number_format($product->price, 2) }}</b>
                                <em>{{ $product->stock }} left</em>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="pos-empty">
                        <i class="fas fa-box-open"></i>
                        <h3>No active products</h3>
                        <p>Admin can add products before POS sales are available.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <aside class="pos-cart-panel">
            <form method="POST" action="{{ route($routePrefix . '.pos.orders.store') }}" id="posForm">
                @csrf
                <div class="pos-panel-header">
                    <h2>Cart</h2>
                    <button type="button" class="pos-icon-btn" id="clearCart" title="Clear cart">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="pos-customer-grid">
                    <input name="customer_name" class="pos-input" value="{{ old('customer_name') }}" placeholder="Walk-in customer">
                    <input name="customer_phone" class="pos-input" value="{{ old('customer_phone') }}" placeholder="Phone optional">
                </div>

                <div class="pos-cart-items" id="cartItems">
                    <div class="pos-cart-empty">No products selected</div>
                </div>

                <div id="cartInputs"></div>

                <div class="pos-summary">
                    <div><span>Items</span><strong id="itemsCount">0</strong></div>
                    <div><span>Total</span><strong id="cartTotal">RM0.00</strong></div>
                    <label>
                        <span>Cash Received</span>
                        <input name="cash_received" id="cashReceived" type="number" min="0" step="0.01" class="pos-input" value="{{ old('cash_received') }}">
                    </label>
                    <div><span>Change</span><strong id="changeDue">RM0.00</strong></div>
                </div>

                <button type="submit" class="pos-btn primary full" id="checkoutBtn" disabled>
                    <i class="fas fa-check"></i> Complete Cash Sale
                </button>
            </form>

            <div class="pos-recent">
                <div class="pos-panel-header">
                    <h2>Recent Sales</h2>
                </div>
                @forelse($recentOrders as $order)
                    <a href="{{ route($routePrefix . '.pos.orders.show', $order) }}" class="pos-recent-row">
                        <div>
                            <strong>{{ $order->order_number }}</strong>
                            <span>{{ $order->customer_name ?: 'Walk-in Customer' }}</span>
                        </div>
                        <b>RM{{ number_format($order->total, 2) }}</b>
                    </a>
                @empty
                    <div class="pos-cart-empty">No POS sales yet</div>
                @endforelse
            </div>
        </aside>
    </div>
</div>

<style>
    .pos-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .pos-header,
    .pos-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .pos-header {
        align-items: flex-start;
        margin-bottom: 26px;
    }

    .pos-header h1,
    .pos-panel-header h2 {
        margin: 0;
        color: #1a1f36;
    }

    .pos-header h1 {
        font-size: 32px;
        font-weight: 800;
    }

    .pos-header p {
        margin: 8px 0 0;
        color: #718096;
    }

    .pos-btn,
    .pos-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 11px 16px;
        background: #fff;
        color: #1a1f36;
        text-decoration: none;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pos-btn:hover,
    .pos-icon-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(26, 31, 54, 0.08);
        color: #1a1f36;
        text-decoration: none;
    }

    .pos-btn.primary {
        background: #d4af37;
        border-color: #d4af37;
    }

    .pos-btn.full {
        width: 100%;
    }

    .pos-btn:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        transform: none;
    }

    .pos-icon-btn {
        width: 42px;
        height: 42px;
        padding: 0;
        color: #e53e3e;
    }

    .pos-alert {
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-weight: 700;
    }

    .pos-alert.success {
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
        border: 1px solid rgba(72, 187, 120, 0.25);
    }

    .pos-alert.error {
        background: rgba(245, 101, 101, 0.12);
        color: #c53030;
        border: 1px solid rgba(245, 101, 101, 0.25);
    }

    .pos-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 420px;
        gap: 22px;
        align-items: start;
    }

    .pos-products-panel,
    .pos-cart-panel,
    .pos-recent {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .pos-cart-panel {
        position: sticky;
        top: 20px;
    }

    .pos-search {
        position: relative;
        min-width: 280px;
    }

    .pos-search i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
    }

    .pos-search input,
    .pos-input {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 11px 13px;
        font-size: 14px;
        color: #1a1f36;
        background: #fff;
    }

    .pos-search input {
        padding-left: 40px;
    }

    .pos-search input:focus,
    .pos-input:focus {
        outline: none;
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.14);
    }

    .pos-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 14px;
    }

    .pos-product-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        padding: 0;
        overflow: hidden;
        text-align: left;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pos-product-card:hover:not(:disabled) {
        border-color: #d4af37;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(26, 31, 54, 0.08);
    }

    .pos-product-card:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    .pos-product-image {
        height: 130px;
        background: #f7fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d4af37;
        font-size: 34px;
    }

    .pos-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pos-product-info {
        padding: 14px;
    }

    .pos-product-info strong,
    .pos-product-info span,
    .pos-product-info b,
    .pos-product-info em {
        display: block;
    }

    .pos-product-info strong {
        font-size: 15px;
        margin-bottom: 5px;
    }

    .pos-product-info span {
        color: #718096;
        font-size: 13px;
        margin-bottom: 12px;
    }

    .pos-product-info div {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
    }

    .pos-product-info b {
        color: #d4af37;
    }

    .pos-product-info em {
        color: #718096;
        font-size: 12px;
        font-style: normal;
    }

    .pos-customer-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 16px;
    }

    .pos-cart-items {
        display: grid;
        gap: 10px;
        margin-bottom: 16px;
        max-height: 340px;
        overflow-y: auto;
    }

    .pos-cart-empty,
    .pos-empty {
        color: #718096;
        text-align: center;
        padding: 26px 10px;
        background: #f7fafc;
        border-radius: 8px;
    }

    .pos-empty {
        grid-column: 1 / -1;
    }

    .pos-empty i {
        font-size: 36px;
        color: #d4af37;
        margin-bottom: 10px;
    }

    .pos-cart-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 110px 34px;
        gap: 10px;
        align-items: center;
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    .pos-cart-row strong,
    .pos-cart-row span {
        display: block;
    }

    .pos-cart-row span {
        color: #718096;
        font-size: 13px;
        margin-top: 4px;
    }

    .pos-qty {
        display: grid;
        grid-template-columns: 30px 1fr 30px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }

    .pos-qty button {
        border: 0;
        background: #f7fafc;
        color: #1a1f36;
        font-weight: 900;
        cursor: pointer;
    }

    .pos-qty input {
        width: 100%;
        border: 0;
        text-align: center;
        padding: 8px 0;
        font-weight: 800;
    }

    .pos-remove {
        border: 0;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: rgba(245, 101, 101, 0.12);
        color: #e53e3e;
        cursor: pointer;
    }

    .pos-summary {
        background: #f7fafc;
        border-radius: 8px;
        padding: 14px;
        margin-bottom: 16px;
        display: grid;
        gap: 12px;
    }

    .pos-summary div,
    .pos-summary label {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        align-items: center;
        color: #718096;
        font-weight: 700;
    }

    .pos-summary strong {
        color: #1a1f36;
        font-size: 18px;
    }

    .pos-summary label {
        align-items: stretch;
        flex-direction: column;
    }

    .pos-recent {
        margin-top: 18px;
    }

    .pos-recent-row {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
        color: #1a1f36;
        text-decoration: none;
    }

    .pos-recent-row:last-child {
        border-bottom: none;
    }

    .pos-recent-row span {
        display: block;
        color: #718096;
        font-size: 13px;
        margin-top: 4px;
    }

    .pos-recent-row b {
        color: #d4af37;
        white-space: nowrap;
    }

    @media (max-width: 1150px) {
        .pos-layout {
            grid-template-columns: 1fr;
        }

        .pos-cart-panel {
            position: static;
        }
    }

    @media (max-width: 700px) {
        .pos-page {
            padding: 20px;
        }

        .pos-panel-header,
        .pos-header,
        .pos-customer-grid {
            grid-template-columns: 1fr;
            flex-direction: column;
            align-items: stretch;
        }

        .pos-search {
            min-width: 0;
            width: 100%;
        }

        .pos-cart-row {
            grid-template-columns: 1fr;
        }
    }
</style>

@php
    $posProducts = $products->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'category' => $product->category ?: 'General',
            'price' => (float) $product->price,
            'stock' => $product->stock,
        ];
    })->values();
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
    const products = @json($posProducts);

    const cart = new Map();
    const productLookup = new Map(products.map(product => [String(product.id), product]));
    const grid = document.getElementById('productsGrid');
    const search = document.getElementById('productSearch');
    const cartItems = document.getElementById('cartItems');
    const cartInputs = document.getElementById('cartInputs');
    const itemsCount = document.getElementById('itemsCount');
    const cartTotal = document.getElementById('cartTotal');
    const cashReceived = document.getElementById('cashReceived');
    const changeDue = document.getElementById('changeDue');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const clearCart = document.getElementById('clearCart');

    function money(value) {
        return 'RM' + value.toFixed(2);
    }

    function totals() {
        let count = 0;
        let total = 0;
        cart.forEach((quantity, id) => {
            const product = productLookup.get(String(id));
            count += quantity;
            total += product.price * quantity;
        });
        return { count, total };
    }

    function renderCart() {
        const currentTotals = totals();
        itemsCount.textContent = currentTotals.count;
        cartTotal.textContent = money(currentTotals.total);
        checkoutBtn.disabled = currentTotals.count === 0;

        const cash = parseFloat(cashReceived.value || '0');
        changeDue.textContent = money(Math.max(0, cash - currentTotals.total));

        cartInputs.innerHTML = '';
        cart.forEach((quantity, id) => {
            cartInputs.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="product_ids[]" value="${id}">
                <input type="hidden" name="quantities[]" value="${quantity}">
            `);
        });

        if (cart.size === 0) {
            cartItems.innerHTML = '<div class="pos-cart-empty">No products selected</div>';
            return;
        }

        cartItems.innerHTML = '';
        cart.forEach((quantity, id) => {
            const product = productLookup.get(String(id));
            cartItems.insertAdjacentHTML('beforeend', `
                <div class="pos-cart-row" data-id="${id}">
                    <div>
                        <strong>${product.name}</strong>
                        <span>${money(product.price)} each</span>
                    </div>
                    <div class="pos-qty">
                        <button type="button" data-action="minus">-</button>
                        <input value="${quantity}" readonly>
                        <button type="button" data-action="plus">+</button>
                    </div>
                    <button type="button" class="pos-remove" title="Remove product"><i class="fas fa-times"></i></button>
                </div>
            `);
        });
    }

    grid?.addEventListener('click', function (event) {
        const card = event.target.closest('.pos-product-card');
        if (!card || card.disabled) return;

        const id = card.dataset.id;
        const product = productLookup.get(String(id));
        const nextQuantity = (cart.get(id) || 0) + 1;

        if (nextQuantity > product.stock) {
            alert(`${product.name} only has ${product.stock} item(s) in stock.`);
            return;
        }

        cart.set(id, nextQuantity);
        renderCart();
    });

    cartItems.addEventListener('click', function (event) {
        const row = event.target.closest('.pos-cart-row');
        if (!row) return;

        const id = row.dataset.id;
        const product = productLookup.get(String(id));

        if (event.target.closest('.pos-remove')) {
            cart.delete(id);
            renderCart();
            return;
        }

        if (event.target.dataset.action === 'plus') {
            const nextQuantity = (cart.get(id) || 0) + 1;
            if (nextQuantity > product.stock) {
                alert(`${product.name} only has ${product.stock} item(s) in stock.`);
                return;
            }
            cart.set(id, nextQuantity);
            renderCart();
        }

        if (event.target.dataset.action === 'minus') {
            const nextQuantity = (cart.get(id) || 0) - 1;
            if (nextQuantity <= 0) {
                cart.delete(id);
            } else {
                cart.set(id, nextQuantity);
            }
            renderCart();
        }
    });

    search?.addEventListener('input', function () {
        const value = this.value.trim().toLowerCase();
        document.querySelectorAll('.pos-product-card').forEach(card => {
            const matches = card.dataset.name.includes(value) || card.dataset.category.includes(value);
            card.style.display = matches ? 'block' : 'none';
        });
    });

    cashReceived?.addEventListener('input', renderCart);
    clearCart?.addEventListener('click', function () {
        cart.clear();
        renderCart();
    });

    renderCart();
});
</script>
@endsection
