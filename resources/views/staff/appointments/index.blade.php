@extends('staff.sidebar')

@section('page-title', 'Appointments')

@section('content')
<div class="staff-ui-page appointments-page">
    <header class="page-header">
        <div>
            <span class="eyebrow">Appointment Management</span>
            <h1>My Appointments</h1>
        </div>
        <a href="{{ route('staff.appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Appointment
        </a>
    </header>

    <section class="summary-grid" aria-label="Appointment summary">
        <div class="summary-card">
            <span>Showing</span>
            <strong>{{ $summary['shown'] }}</strong>
        </div>
        <div class="summary-card">
            <span>Confirmed</span>
            <strong>{{ $summary['confirmed'] }}</strong>
        </div>
        <div class="summary-card">
            <span>Pending Payment</span>
            <strong>{{ $summary['pending_payment'] }}</strong>
        </div>
        <div class="summary-card">
            <span>Completed</span>
            <strong>{{ $summary['completed'] }}</strong>
        </div>
    </section>

    <form method="GET" action="{{ route('staff.appointments.index') }}" class="filter-panel">
        <div class="form-group search-group">
            <label for="search">Search</label>
            <div class="input-icon">
                <i class="fas fa-search"></i>
                <input type="search" id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Customer, email, phone, service" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>All</option>
                <option value="pending_payment" {{ ($filters['status'] ?? '') === 'pending_payment' ? 'selected' : '' }}>Pending payment</option>
                <option value="confirmed" {{ ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ ($filters['status'] ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="form-group">
            <label for="from">From</label>
            <input type="date" id="from" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="to">To</label>
            <input type="date" id="to" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="sort">Sort</label>
            <select id="sort" name="sort" class="form-control">
                <option value="latest" {{ ($filters['sort'] ?? 'latest') === 'latest' ? 'selected' : '' }}>Newest</option>
                <option value="date_asc" {{ ($filters['sort'] ?? '') === 'date_asc' ? 'selected' : '' }}>Earliest date</option>
                <option value="date_desc" {{ ($filters['sort'] ?? '') === 'date_desc' ? 'selected' : '' }}>Latest date</option>
                <option value="customer" {{ ($filters['sort'] ?? '') === 'customer' ? 'selected' : '' }}>Customer A-Z</option>
                <option value="service" {{ ($filters['sort'] ?? '') === 'service' ? 'selected' : '' }}>Service A-Z</option>
                <option value="price_desc" {{ ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' }}>Highest price</option>
            </select>
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Apply
            </button>
            <a href="{{ route('staff.appointments.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    @if($appointments->count() > 0)
        <section class="appointments-container">
            <div class="table-scroll">
                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="actions-head">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td data-label="Customer">
                                    <div class="appointment-customer">{{ $appointment->customer->name }}</div>
                                    <div class="appointment-meta">{{ $appointment->customer->email }}</div>
                                    <div class="appointment-meta">
                                        For: {{ $appointment->recipient_display_name }}
                                        @if($appointment->recipient_age !== null)
                                            ({{ $appointment->recipient_age }})
                                        @endif
                                    </div>
                                </td>
                                <td data-label="Service">{{ $appointment->service->name }}</td>
                                <td data-label="Date & Time">
                                    <div class="appointment-customer">{{ $appointment->appointment_date->format('d M Y') }}</div>
                                    <div class="appointment-meta">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->end_time)->format('h:i A') }}</div>
                                </td>
                                <td data-label="Price">RM{{ number_format($appointment->price, 2) }}</td>
                                <td data-label="Status">
                                    <span class="status-badge status-{{ strtolower($appointment->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                </td>
                                <td data-label="Actions">
                                    <div class="action-buttons">
                                        <a href="{{ route('staff.appointments.show', $appointment) }}" class="btn-icon info" title="View" aria-label="View appointment">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('staff.appointments.destroy', $appointment) }}" method="POST" class="inline-delete-form" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon danger" title="Delete" aria-label="Delete appointment">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <div class="pagination-wrap">
            {{ $appointments->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No Appointments Found</h3>
            <p>No appointment matches the current search or filter.</p>
            <a href="{{ route('staff.appointments.index') }}" class="btn btn-secondary">Clear filters</a>
        </div>
    @endif
</div>

<style>
    .staff-ui-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 22px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .eyebrow {
        display: block;
        color: #718096;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0;
        margin-bottom: 6px;
    }

    .page-header h1 {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary);
        margin: 0;
    }

    .btn {
        min-height: 42px;
        padding: 9px 14px;
        border-radius: 8px;
        font-weight: 900;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        font: inherit;
    }

    .btn-primary {
        background: #d4af37;
        color: #1a1f36;
    }

    .btn-secondary {
        background: #fff;
        color: #1a1f36;
        border: 1px solid #e2e8f0;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .summary-card,
    .filter-panel,
    .appointments-container,
    .empty-state {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .summary-card {
        padding: 18px;
        display: grid;
        gap: 4px;
    }

    .summary-card span {
        color: #718096;
        font-size: 13px;
        font-weight: 800;
    }

    .summary-card strong {
        color: #1a1f36;
        font-size: 26px;
    }

    .filter-panel {
        display: grid;
        grid-template-columns: minmax(240px, 1.5fr) repeat(4, minmax(140px, 1fr)) auto;
        gap: 12px;
        align-items: end;
        padding: 18px;
        margin-bottom: 22px;
    }

    .form-group {
        display: grid;
        gap: 6px;
    }

    .form-group label {
        color: #718096;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .form-control {
        width: 100%;
        min-height: 42px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 9px 12px;
        font: inherit;
        color: #1a1f36;
        background: #fff;
    }

    .input-icon {
        position: relative;
    }

    .input-icon i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #718096;
    }

    .input-icon .form-control {
        padding-left: 36px;
    }

    .filter-actions {
        display: flex;
        gap: 8px;
    }

    .appointments-container {
        overflow: hidden;
    }

    .table-scroll {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .appointments-table {
        width: 100%;
        min-width: 820px;
        border-collapse: collapse;
    }

    .appointments-table thead {
        background: #f8fafc;
    }

    .appointments-table th,
    .appointments-table td {
        padding: 16px 18px;
        border-bottom: 1px solid #e2e8f0;
        color: #1a1f36;
        text-align: left;
        vertical-align: middle;
    }

    .appointments-table th {
        color: #718096;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0;
    }

    .appointments-table tbody tr:hover {
        background: #f8fafc;
    }

    .appointment-customer {
        font-weight: 900;
        color: #1a1f36;
        margin-bottom: 4px;
    }

    .appointment-meta {
        font-size: 13px;
        color: #718096;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        white-space: nowrap;
    }

    .status-pending_payment {
        background: #feebc8;
        color: #7b341e;
    }

    .status-confirmed {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-completed {
        background: #bee3f8;
        color: #2c5282;
    }

    .status-cancelled {
        background: #fed7d7;
        color: #742a2a;
    }

    .actions-head {
        text-align: right !important;
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .btn-icon {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        text-decoration: none;
        font: inherit;
    }

    .btn-icon.info {
        background: #ebf8ff;
        color: #2c5282;
    }

    .btn-icon.danger {
        background: #fed7d7;
        color: #742a2a;
    }

    .inline-delete-form {
        display: inline;
        margin: 0;
    }

    .empty-state {
        padding: 40px 20px;
        text-align: center;
        color: #718096;
    }

    .empty-state i {
        font-size: 42px;
        color: #d4af37;
        margin-bottom: 14px;
    }

    .empty-state h3 {
        color: #1a1f36;
        margin-bottom: 6px;
    }

    .empty-state p {
        margin-bottom: 16px;
    }

    .pagination-wrap {
        margin-top: 22px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 1240px) {
        .filter-panel {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .search-group,
        .filter-actions {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 760px) {
        .staff-ui-page {
            padding: 20px;
        }

        .page-header,
        .filter-actions {
            display: grid;
        }

        .btn,
        .btn-primary,
        .btn-secondary {
            width: 100%;
        }

        .summary-grid,
        .filter-panel {
            grid-template-columns: 1fr;
        }

        .search-group,
        .filter-actions {
            grid-column: auto;
        }

        .appointments-table {
            min-width: 0;
        }

        .appointments-table thead {
            display: none;
        }

        .appointments-table,
        .appointments-table tbody,
        .appointments-table tr,
        .appointments-table td {
            display: block;
            width: 100%;
        }

        .appointments-table tr {
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .appointments-table td {
            display: grid;
            grid-template-columns: minmax(112px, 38%) 1fr;
            gap: 12px;
            padding: 8px 0;
            border-bottom: none;
        }

        .appointments-table td::before {
            content: attr(data-label);
            color: #718096;
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .action-buttons {
            justify-content: flex-start;
        }
    }

    @media (max-width: 430px) {
        .page-header h1 {
            font-size: 26px;
        }

        .appointments-table td {
            grid-template-columns: 1fr;
            gap: 4px;
        }
    }
</style>
@endsection
