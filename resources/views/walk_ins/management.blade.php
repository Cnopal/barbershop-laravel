@php
    $waitingCount = $queues->where('status', \App\Models\WalkInQueue::STATUS_WAITING)->count();
    $servingCount = $queues->where('status', \App\Models\WalkInQueue::STATUS_SERVING)->count();
    $doneCount = $queues->whereIn('status', [\App\Models\WalkInQueue::STATUS_COMPLETED, \App\Models\WalkInQueue::STATUS_SKIPPED])->count();
    $num = 1;
@endphp

<div class="walkin-page">
    <div class="page-header">
        <div>
            <h1>Walk-in Queue</h1>
            <p>Manage daily walk-in customers and queue status</p>
        </div>
        <a href="{{ route('walk-ins.display') }}" class="btn btn-secondary" target="_blank">
            <i class="fas fa-tv"></i> Display Screen
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <span>Waiting</span>
            <strong>{{ $waitingCount }}</strong>
        </div>
        <div class="stat-card">
            <span>Serving</span>
            <strong>{{ $servingCount }}</strong>
        </div>
        <div class="stat-card">
            <span>Done Today</span>
            <strong>{{ $doneCount }}</strong>
        </div>
    </div>

    <div class="content-grid">
        <section class="panel">
            <div class="panel-header">
                <h2><i class="fas fa-user-plus"></i> Add Walk-in</h2>
            </div>
            <form method="POST" action="{{ route($routePrefix . '.walk-ins.store') }}" class="walkin-form">
                @csrf

                <div class="form-group">
                    <label>Customer Type</label>
                    <div class="segmented-control">
                        <label>
                            <input type="radio" name="customer_type" value="guest" {{ old('customer_type', 'guest') !== 'registered' ? 'checked' : '' }}>
                            <span>Guest</span>
                        </label>
                        <label>
                            <input type="radio" name="customer_type" value="registered" {{ old('customer_type') === 'registered' ? 'checked' : '' }}>
                            <span>Registered</span>
                        </label>
                    </div>
                </div>

                <div class="registered-fields" id="registeredFields">
                    <div class="form-group">
                        <label for="customerSearch">Search Customer</label>
                        <input type="search" id="customerSearch" class="form-control" placeholder="Name, email, or phone">
                    </div>
                    <div class="form-group">
                        <label for="customer_id">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control">
                            <option value="">Select customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                        data-search="{{ strtolower($customer->name . ' ' . $customer->email . ' ' . ($customer->phone ?? '')) }}"
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }}){{ $customer->phone ? ' - ' . $customer->phone : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="guest-fields" id="guestFields">
                    <div class="form-group">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name') }}" placeholder="Walk-in customer name">
                    </div>
                    <div class="form-group">
                        <label for="customer_phone">Phone</label>
                        <input type="tel" name="customer_phone" id="customer_phone" class="form-control" value="{{ old('customer_phone') }}" placeholder="Optional phone number">
                    </div>
                </div>

                <div class="form-group">
                    <label for="recipient_age">Customer Age</label>
                    <input type="number" name="recipient_age" id="recipient_age" class="form-control" value="{{ old('recipient_age') }}" min="0" max="120" placeholder="Optional, used for child rate">
                    <small class="field-hint">Below {{ \App\Models\Appointment::CHILD_RATE_AGE_LIMIT }} years old is RM{{ number_format(\App\Models\Appointment::CHILD_RATE_PRICE, 2) }}.</small>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="barber_id">Assign Barber</label>
                        <select name="barber_id" id="barber_id" class="form-control">
                            <option value="">Any available barber</option>
                            @foreach($barbers as $barber)
                                <option value="{{ $barber->id }}" {{ old('barber_id', auth()->id()) == $barber->id ? 'selected' : '' }}>
                                    {{ $num++ . ". " }}{{ $barber->name }}{{ $barber->position ? ' - ' . $barber->position : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service_id">Service</label>
                        <select name="service_id" id="service_id" class="form-control" required>
                            <option value="">Select service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} ({{ $service->duration }} min, RM{{ number_format($service->price, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="price-preview">
                    <span>Queue Price</span>
                    <strong id="pricePreviewValue">Select service</strong>
                    <small id="pricePreviewNote">Price is saved when the walk-in is added.</small>
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Optional notes">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add to Queue
                </button>
            </form>
        </section>

        <section class="panel queue-panel">
            <div class="panel-header queue-toolbar">
                <div>
                    <h2><i class="fas fa-list-ol"></i> Queue List</h2>
                    <p>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
                </div>
                <form method="GET" action="{{ route($routePrefix . '.walk-ins.index') }}" class="date-filter">
                    <input type="date" name="date" value="{{ $date }}" class="form-control">
                    <button class="btn btn-secondary" type="submit">
                        <i class="fas fa-filter"></i>
                    </button>
                </form>
            </div>

            <div class="queue-list">
                @forelse($queues as $queue)
                    <article class="queue-row status-{{ $queue->status }}">
                        <div class="queue-number">
                            <strong>{{ $queue->queue_code }}</strong>
                            <span>#{{ str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <div class="queue-main">
                            <div class="queue-title">
                                <h3>{{ $queue->display_customer_name }}</h3>
                                <span class="status-pill status-{{ $queue->status }}">{{ $queue->status_label }}</span>
                            </div>
                            <div class="queue-meta">
                                <span><i class="fas fa-phone"></i> {{ $queue->customer_phone ?: 'No phone' }}</span>
                                <span><i class="fas fa-user-tie"></i> {{ $queue->barber->name ?? 'Any barber' }}</span>
                                <span><i class="fas fa-scissors"></i> {{ $queue->service->name ?? 'Walk-in service' }}</span>
                                <span><i class="fas fa-clock"></i> {{ $queue->formatted_wait }}</span>
                                @if($queue->recipient_age !== null)
                                    <span><i class="fas fa-child"></i> {{ $queue->recipient_age }} years old</span>
                                @endif
                                <span><i class="fas fa-money-bill-wave"></i> RM{{ number_format($queue->price, 2) }}{{ $queue->hasChildRate() ? ' child rate' : '' }}</span>
                            </div>
                            @if($queue->notes)
                                <p class="queue-notes">{{ $queue->notes }}</p>
                            @endif
                        </div>

                        <div class="queue-actions">
                            @if($queue->status !== \App\Models\WalkInQueue::STATUS_SERVING)
                                <form method="POST" action="{{ route($routePrefix . '.walk-ins.status', $queue) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="serving">
                                    <button class="icon-btn success" title="Start serving">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                            @endif

                            @if($queue->status !== \App\Models\WalkInQueue::STATUS_COMPLETED)
                                <form method="POST" action="{{ route($routePrefix . '.walk-ins.status', $queue) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button class="icon-btn info" title="Complete">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif

                            @if($queue->status !== \App\Models\WalkInQueue::STATUS_SKIPPED)
                                <form method="POST" action="{{ route($routePrefix . '.walk-ins.status', $queue) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="skipped">
                                    <button class="icon-btn warning" title="Skip">
                                        <i class="fas fa-forward"></i>
                                    </button>
                                </form>
                            @endif

                            @if($queue->status !== \App\Models\WalkInQueue::STATUS_WAITING)
                                <form method="POST" action="{{ route($routePrefix . '.walk-ins.status', $queue) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="waiting">
                                    <button class="icon-btn" title="Return to waiting">
                                        <i class="fas fa-rotate-left"></i>
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route($routePrefix . '.walk-ins.destroy', $queue) }}" onsubmit="return confirm('Remove this queue entry?')">
                                @csrf
                                @method('DELETE')
                                <button class="icon-btn danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-list-ol"></i>
                        <h3>No walk-in queue yet</h3>
                        <p>Add the first walk-in customer for today.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>

<style>
    .walkin-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .page-header,
    .queue-toolbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }

    .page-header h1 {
        font-size: 32px;
        font-weight: 800;
        margin: 0 0 6px;
    }

    .page-header p,
    .panel-header p {
        margin: 0;
        color: #718096;
    }

    .alert {
        display: flex;
        gap: 10px;
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
    }

    .alert-danger {
        background: #fed7d7;
        color: #742a2a;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 26px;
    }

    .stat-card,
    .panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .stat-card {
        padding: 20px;
    }

    .stat-card span {
        display: block;
        color: #718096;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .stat-card strong {
        font-size: 32px;
        color: #d4af37;
    }

    .content-grid {
        display: grid;
        grid-template-columns: minmax(320px, 430px) 1fr;
        gap: 20px;
        align-items: start;
    }

    .panel-header {
        padding: 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .panel-header h2 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 20px;
        margin: 0;
    }

    .panel-header i {
        color: #d4af37;
    }

    .walkin-form {
        padding: 24px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        min-height: 44px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 12px;
        color: #1a1f36;
        font: inherit;
        background: #fff;
    }

    textarea.form-control {
        min-height: 86px;
        resize: vertical;
    }

    .field-hint {
        display: block;
        margin-top: 6px;
        color: #718096;
        font-size: 12px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .segmented-control {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .segmented-control label {
        margin: 0;
        cursor: pointer;
    }

    .segmented-control input {
        position: absolute;
        opacity: 0;
    }

    .segmented-control span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        font-weight: 800;
    }

    .segmented-control input:checked + span {
        background: rgba(212, 175, 55, 0.16);
        border-color: #d4af37;
    }

    .registered-fields {
        display: none;
    }

    .registered-fields.active {
        display: block;
    }

    .guest-fields.hidden {
        display: none;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        font: inherit;
    }

    .btn-primary {
        width: 100%;
        color: #1a1f36;
        background: #d4af37;
    }

    .btn-secondary {
        color: #1a1f36;
        background: #fff;
        border: 1px solid #e2e8f0;
    }

    .price-preview {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        flex-wrap: wrap;
    }

    .price-preview span,
    .price-preview small {
        color: #718096;
        font-weight: 700;
    }

    .price-preview strong {
        color: #1a1f36;
        font-size: 20px;
    }

    .price-preview small {
        flex-basis: 100%;
        font-size: 12px;
    }

    .queue-panel {
        min-width: 0;
    }

    .date-filter {
        display: flex;
        gap: 10px;
    }

    .date-filter .form-control {
        min-width: 170px;
    }

    .queue-list {
        display: grid;
        gap: 12px;
        padding: 20px;
    }

    .queue-row {
        display: grid;
        grid-template-columns: 130px 1fr auto;
        gap: 16px;
        align-items: center;
        padding: 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
    }

    .queue-row.status-serving {
        border-color: #4299e1;
        background: #ebf8ff;
    }

    .queue-number strong,
    .queue-number span {
        display: block;
    }

    .queue-number strong {
        color: #d4af37;
        font-size: 22px;
    }

    .queue-number span {
        color: #718096;
        font-weight: 700;
    }

    .queue-title {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 8px;
    }

    .queue-title h3 {
        font-size: 18px;
        margin: 0;
    }

    .queue-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        color: #4a5568;
        font-size: 14px;
    }

    .queue-meta i {
        color: #d4af37;
    }

    .queue-notes {
        margin: 10px 0 0;
        color: #718096;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        min-height: 26px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .status-waiting {
        background: #fefcbf;
        color: #744210;
    }

    .status-serving {
        background: #bee3f8;
        color: #2c5282;
    }

    .status-completed {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-skipped {
        background: #fed7d7;
        color: #742a2a;
    }

    .queue-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 8px;
    }

    .icon-btn {
        width: 38px;
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        color: #1a1f36;
        cursor: pointer;
    }

    .icon-btn.success {
        color: #22543d;
        background: #c6f6d5;
    }

    .icon-btn.info {
        color: #2c5282;
        background: #bee3f8;
    }

    .icon-btn.warning {
        color: #744210;
        background: #fefcbf;
    }

    .icon-btn.danger {
        color: #742a2a;
        background: #fed7d7;
    }

    .empty-state {
        text-align: center;
        padding: 48px 20px;
        color: #718096;
    }

    .empty-state i {
        color: #d4af37;
        font-size: 42px;
        margin-bottom: 14px;
    }

    @media (max-width: 1100px) {
        .content-grid,
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .queue-row {
            grid-template-columns: 1fr;
        }

        .queue-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 640px) {
        .walkin-page {
            padding: 20px;
        }

        .form-grid,
        .segmented-control {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerTypeInputs = document.querySelectorAll('input[name="customer_type"]');
        const registeredFields = document.getElementById('registeredFields');
        const guestFields = document.getElementById('guestFields');
        const customerSelect = document.getElementById('customer_id');
        const customerSearch = document.getElementById('customerSearch');
        const customerName = document.getElementById('customer_name');
        const recipientAge = document.getElementById('recipient_age');
        const serviceSelect = document.getElementById('service_id');
        const pricePreviewValue = document.getElementById('pricePreviewValue');
        const pricePreviewNote = document.getElementById('pricePreviewNote');
        const childRateAgeLimit = {{ \App\Models\Appointment::CHILD_RATE_AGE_LIMIT }};
        const childRatePrice = {{ \App\Models\Appointment::CHILD_RATE_PRICE }};

        function usingRegisteredCustomer() {
            return document.querySelector('input[name="customer_type"]:checked')?.value === 'registered';
        }

        function updateCustomerFields() {
            const registered = usingRegisteredCustomer();
            registeredFields?.classList.toggle('active', registered);
            guestFields?.classList.toggle('hidden', registered);

            if (customerSelect) {
                customerSelect.required = registered;
            }

            if (customerName) {
                customerName.required = !registered;
            }
        }

        function filterCustomers() {
            if (!customerSearch || !customerSelect) {
                return;
            }

            const query = customerSearch.value.trim().toLowerCase();
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            let selectedStillVisible = !selectedOption?.value;

            Array.from(customerSelect.options).forEach(option => {
                if (!option.value) {
                    option.hidden = false;
                    return;
                }

                const matches = !query || (option.dataset.search || '').includes(query);
                option.hidden = !matches;

                if (option.selected && matches) {
                    selectedStillVisible = true;
                }
            });

            if (!selectedStillVisible) {
                customerSelect.value = '';
            }
        }

        function updatePricePreview() {
            if (!serviceSelect || !pricePreviewValue || !pricePreviewNote) {
                return;
            }

            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const servicePrice = Number(selectedOption?.dataset.price || 0);
            const ageValue = recipientAge?.value;
            const age = ageValue === '' || ageValue === undefined ? null : Number(ageValue);

            if (!selectedOption?.value || servicePrice <= 0) {
                pricePreviewValue.textContent = 'Select service';
                pricePreviewNote.textContent = 'Choose a service to calculate the queue price.';
                return;
            }

            if (age !== null && age < childRateAgeLimit) {
                pricePreviewValue.textContent = `RM${childRatePrice.toFixed(2)}`;
                pricePreviewNote.textContent = `Child rate applied because age is below ${childRateAgeLimit}.`;
                return;
            }

            pricePreviewValue.textContent = `RM${servicePrice.toFixed(2)}`;
            pricePreviewNote.textContent = 'Normal service price will be saved for this queue.';
        }

        customerTypeInputs.forEach(input => input.addEventListener('change', updateCustomerFields));
        customerSearch?.addEventListener('input', filterCustomers);
        recipientAge?.addEventListener('input', updatePricePreview);
        serviceSelect?.addEventListener('change', updatePricePreview);

        updateCustomerFields();
        filterCustomers();
        updatePricePreview();
    });
</script>
