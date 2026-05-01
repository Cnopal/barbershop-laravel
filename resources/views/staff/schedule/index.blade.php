@extends('staff.sidebar')

@section('page-title', 'My Schedule')

@section('content')
<div class="staff-ui-page schedule-page">
    <header class="staff-page-header">
        <div>
            <span class="eyebrow">Work Calendar</span>
            <h1>My Schedule</h1>
        </div>
        <a href="{{ route('staff.appointments.create') }}" class="primary-btn">
            <i class="fas fa-plus"></i> New Appointment
        </a>
    </header>

    <section class="summary-grid" aria-label="Schedule summary">
        <div class="summary-card">
            <span>Total</span>
            <strong>{{ $summary['total'] }}</strong>
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
            <span>Today</span>
            <strong>{{ $summary['today'] }}</strong>
        </div>
    </section>

    <form method="GET" action="{{ route('staff.schedule') }}" class="filter-panel">
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
                <option value="confirmed" {{ ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="pending_payment" {{ ($filters['status'] ?? '') === 'pending_payment' ? 'selected' : '' }}>Pending payment</option>
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
                <option value="date_asc" {{ ($filters['sort'] ?? 'date_asc') === 'date_asc' ? 'selected' : '' }}>Earliest first</option>
                <option value="date_desc" {{ ($filters['sort'] ?? '') === 'date_desc' ? 'selected' : '' }}>Latest first</option>
                <option value="customer" {{ ($filters['sort'] ?? '') === 'customer' ? 'selected' : '' }}>Customer A-Z</option>
                <option value="service" {{ ($filters['sort'] ?? '') === 'service' ? 'selected' : '' }}>Service A-Z</option>
                <option value="status" {{ ($filters['sort'] ?? '') === 'status' ? 'selected' : '' }}>Status</option>
            </select>
        </div>
        <div class="filter-actions">
            <button type="submit" class="primary-btn">
                <i class="fas fa-filter"></i> Apply
            </button>
            <a href="{{ route('staff.schedule') }}" class="secondary-btn">Reset</a>
        </div>
    </form>

    <section class="schedule-grid">
        @forelse($appointments->groupBy(fn ($appointment) => $appointment->appointment_date->format('Y-m-d')) as $date => $dateAppointments)
            <article class="schedule-card">
                <div class="schedule-title">
                    <div>
                        <i class="fas fa-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</span>
                    </div>
                    <strong>{{ $dateAppointments->count() }}</strong>
                </div>

                <div class="appointment-list">
                    @foreach($dateAppointments as $appointment)
                        <a href="{{ route('staff.appointments.show', $appointment) }}" class="schedule-item {{ strtolower($appointment->status) }}">
                            <div class="time-block">
                                <strong>{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->start_time)->format('h:i A') }}</strong>
                                <span>{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->end_time)->format('h:i A') }}</span>
                            </div>
                            <div class="appointment-main">
                                <div class="appointment-customer">{{ $appointment->customer->name }}</div>
                                <div class="appointment-service">{{ $appointment->service->name }}</div>
                                <div class="appointment-meta">
                                    <span>{{ $appointment->recipient_display_name }}</span>
                                    <span>RM{{ number_format($appointment->price, 2) }}</span>
                                </div>
                            </div>
                            <span class="appointment-status {{ strtolower($appointment->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </article>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Scheduled Appointments</h3>
                <p>No bookings match the current search or filter.</p>
                <a href="{{ route('staff.schedule') }}" class="secondary-btn">Clear filters</a>
            </div>
        @endforelse
    </section>
</div>

<style>
    .staff-ui-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .staff-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .eyebrow {
        display: block;
        margin-bottom: 6px;
        color: #718096;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0;
    }

    .staff-page-header h1 {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        color: var(--primary);
    }

    .primary-btn,
    .secondary-btn {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 8px;
        border: none;
        font: inherit;
        font-weight: 900;
        text-decoration: none;
        cursor: pointer;
    }

    .primary-btn {
        background: #d4af37;
        color: #1a1f36;
    }

    .secondary-btn {
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
    .schedule-card,
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
        font-size: 26px;
        color: #1a1f36;
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

    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .schedule-card {
        overflow: hidden;
    }

    .schedule-title {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        padding: 18px;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 900;
    }

    .schedule-title div {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .schedule-title i,
    .schedule-title strong {
        color: #d4af37;
    }

    .appointment-list {
        display: grid;
    }

    .schedule-item {
        display: grid;
        grid-template-columns: 96px minmax(0, 1fr) auto;
        gap: 14px;
        align-items: center;
        padding: 16px 18px;
        border-bottom: 1px solid #e2e8f0;
        border-left: 4px solid #ed8936;
        color: #1a1f36;
        text-decoration: none;
    }

    .schedule-item:last-child {
        border-bottom: none;
    }

    .schedule-item.confirmed {
        border-left-color: #48bb78;
    }

    .time-block strong,
    .time-block span {
        display: block;
    }

    .time-block span,
    .appointment-service,
    .appointment-meta {
        color: #718096;
        font-size: 13px;
    }

    .appointment-customer {
        font-weight: 900;
    }

    .appointment-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    .appointment-status {
        min-height: 28px;
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        white-space: nowrap;
    }

    .appointment-status.confirmed {
        background: #c6f6d5;
        color: #22543d;
    }

    .appointment-status.pending_payment {
        background: #feebc8;
        color: #7b341e;
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px 20px;
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

    @media (max-width: 1240px) {
        .filter-panel {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .search-group,
        .filter-actions {
            grid-column: 1 / -1;
        }

        .schedule-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .staff-ui-page {
            padding: 20px;
        }

        .staff-page-header,
        .filter-actions {
            display: grid;
        }

        .primary-btn,
        .secondary-btn {
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

        .schedule-item {
            grid-template-columns: 1fr;
            align-items: start;
        }
    }
</style>
@endsection
