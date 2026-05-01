@extends('customer.sidebar')

@section('title', 'Dashboard')

@section('content')
<div class="customer-page dashboard-page">
    <header class="dashboard-header">
        <div>
            <span class="eyebrow">Customer Dashboard</span>
            <h1>Welcome back, {{ Auth::user()->name }}</h1>
        </div>
        <a href="{{ route('customer.appointments.create') }}" class="primary-action">
            <i class="fas fa-calendar-plus"></i>
            <span>Book Appointment</span>
        </a>
    </header>

    <section class="stats-grid" aria-label="Account summary">
        <article class="stat-card">
            <div class="stat-icon appointments"><i class="fas fa-calendar-check"></i></div>
            <div>
                <span>Upcoming</span>
                <strong>{{ $stats['upcoming'] }}</strong>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-icon completed"><i class="fas fa-check"></i></div>
            <div>
                <span>Completed</span>
                <strong>{{ $stats['completed'] }}</strong>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-icon cancelled"><i class="fas fa-times"></i></div>
            <div>
                <span>Cancelled</span>
                <strong>{{ $stats['cancelled'] }}</strong>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-icon spent"><i class="fas fa-wallet"></i></div>
            <div>
                <span>Total Spent</span>
                <strong>RM{{ number_format($stats['total_spent'], 2) }}</strong>
            </div>
        </article>
    </section>

    <section class="dashboard-grid">
        <article class="panel appointments-panel">
            <div class="panel-header">
                <div>
                    <h2>Upcoming Appointments</h2>
                    <p>{{ $upcomingAppointments->count() }} active booking{{ $upcomingAppointments->count() === 1 ? '' : 's' }}</p>
                </div>
                <a href="{{ route('customer.appointments.index') }}" class="panel-link">
                    View All
                </a>
            </div>

            @if($upcomingAppointments->count())
                <div class="appointments-table-wrap">
                    <table class="appointments-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Service</th>
                                <th>Barber</th>
                                <th>Status</th>
                                <th class="action-column">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingAppointments as $appointment)
                                <tr>
                                    <td data-label="Date">{{ $appointment->appointment_date->format('d M Y') }}</td>
                                    <td data-label="Time">{{ date('h:i A', strtotime($appointment->start_time)) }}</td>
                                    <td data-label="Service">{{ $appointment->service->name ?? '-' }}</td>
                                    <td data-label="Barber">{{ $appointment->barber->name ?? '-' }}</td>
                                    <td data-label="Status">
                                        <span class="status-badge {{ $appointment->status }}">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                    </td>
                                    <td data-label="Action" class="action-column">
                                        <a href="{{ route('customer.appointments.show', $appointment) }}" class="icon-action" aria-label="View appointment">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar"></i>
                    <h3>No upcoming appointments</h3>
                    <a href="{{ route('customer.appointments.create') }}" class="panel-link">Book now</a>
                </div>
            @endif
        </article>

        <aside class="side-stack">
            <article class="panel queue-panel">
                <div class="panel-header compact">
                    <h2>Walk-in Queue</h2>
                    <a href="{{ route('customer.walk-ins.index') }}" class="panel-link">View</a>
                </div>

                @if($activeWalkIn)
                    <div class="queue-card">
                        <span>Your Number</span>
                        <strong>{{ $activeWalkIn->queue_code }}</strong>
                        <div class="queue-meta">
                            <span><i class="fas fa-clock"></i> {{ $activeWalkIn->formatted_wait }}</span>
                            <span><i class="fas fa-scissors"></i> {{ $activeWalkIn->service->name ?? 'Walk-in service' }}</span>
                        </div>
                    </div>
                @else
                    <p class="muted">No active walk-in queue.</p>
                @endif
            </article>

            <article class="panel quick-actions-panel">
                <h2>Quick Actions</h2>
                <div class="quick-actions">
                    <a href="{{ route('customer.appointments.create') }}" class="quick-action">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Book Appointment</span>
                    </a>
                    <a href="{{ route('customer.services.index') }}" class="quick-action">
                        <i class="fas fa-cut"></i>
                        <span>View Services</span>
                    </a>
                    <a href="{{ route('customer.ai-hair.index') }}" class="quick-action">
                        <i class="fas fa-magic"></i>
                        <span>AI Hair Recommendation</span>
                    </a>
                    <a href="{{ route('customer.products.index') }}" class="quick-action">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Products</span>
                    </a>
                </div>
            </article>
        </aside>
    </section>
</div>

<style>
    .dashboard-page {
        display: grid;
        gap: 22px;
    }

    .dashboard-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .eyebrow {
        display: block;
        color: #718096;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .dashboard-header h1 {
        margin: 0;
        color: #1a1f36;
        font-size: 34px;
    }

    .primary-action,
    .panel-link,
    .quick-action,
    .icon-action {
        text-decoration: none;
        font-weight: 800;
    }

    .primary-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 44px;
        padding: 10px 16px;
        border-radius: 8px;
        color: #1a1f36;
        background: #d4af37;
        white-space: nowrap;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .stat-card,
    .panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.08);
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        min-height: 112px;
        padding: 18px;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .stat-icon.appointments {
        background: #ebf8ff;
        color: #2c5282;
    }

    .stat-icon.completed {
        background: #c6f6d5;
        color: #22543d;
    }

    .stat-icon.cancelled {
        background: #fed7d7;
        color: #742a2a;
    }

    .stat-icon.spent {
        background: #fefcbf;
        color: #744210;
    }

    .stat-card span {
        display: block;
        color: #718096;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .stat-card strong {
        display: block;
        color: #1a1f36;
        font-size: 26px;
        line-height: 1.15;
        overflow-wrap: anywhere;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, 360px);
        gap: 18px;
        align-items: start;
    }

    .panel {
        overflow: hidden;
    }

    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        padding: 18px 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .panel-header.compact {
        align-items: center;
    }

    .panel h2 {
        margin: 0;
        color: #1a1f36;
        font-size: 20px;
    }

    .panel-header p {
        margin: 6px 0 0;
        color: #718096;
        font-size: 14px;
    }

    .panel-link {
        color: #d4af37;
        white-space: nowrap;
    }

    .appointments-table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .appointments-table {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
    }

    .appointments-table th,
    .appointments-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        color: #1a1f36;
        text-align: left;
        vertical-align: middle;
    }

    .appointments-table th {
        background: #f8fafc;
        color: #718096;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0;
    }

    .action-column {
        text-align: right !important;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .status-badge.confirmed {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-badge.pending_payment {
        background: #feebc8;
        color: #7b341e;
    }

    .status-badge.completed {
        background: #bee3f8;
        color: #2a4365;
    }

    .status-badge.cancelled {
        background: #fed7d7;
        color: #742a2a;
    }

    .icon-action {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f8fafc;
        color: #1a1f36;
        border: 1px solid #e2e8f0;
    }

    .side-stack {
        display: grid;
        gap: 18px;
    }

    .queue-panel,
    .quick-actions-panel {
        padding-bottom: 18px;
    }

    .queue-card {
        margin: 18px;
        padding: 18px;
        border-radius: 8px;
        background: #1a1f36;
        color: #fff;
    }

    .queue-card span {
        color: rgba(255, 255, 255, 0.72);
        font-weight: 800;
        font-size: 13px;
    }

    .queue-card strong {
        display: block;
        margin: 6px 0 14px;
        color: #d4af37;
        font-size: 30px;
        line-height: 1;
    }

    .queue-meta {
        display: grid;
        gap: 8px;
    }

    .queue-meta span {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .muted {
        margin: 18px;
        color: #718096;
    }

    .quick-actions-panel {
        padding: 18px;
    }

    .quick-actions-panel h2 {
        margin-bottom: 14px;
    }

    .quick-actions {
        display: grid;
        gap: 10px;
    }

    .quick-action {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 46px;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #1a1f36;
        background: #fff;
    }

    .quick-action i {
        width: 20px;
        color: #d4af37;
        text-align: center;
    }

    .empty-state {
        display: grid;
        justify-items: center;
        gap: 10px;
        padding: 34px 20px;
        text-align: center;
        color: #718096;
    }

    .empty-state i {
        color: #d4af37;
        font-size: 28px;
    }

    .empty-state h3 {
        color: #1a1f36;
        margin: 0;
    }

    @media (max-width: 1180px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .side-stack {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 760px) {
        .dashboard-page {
            gap: 18px;
        }

        .dashboard-header {
            display: grid;
        }

        .dashboard-header h1 {
            font-size: 26px;
        }

        .primary-action {
            width: 100%;
        }

        .stats-grid,
        .side-stack {
            grid-template-columns: 1fr;
        }

        .stat-card {
            min-height: 96px;
            padding: 16px;
        }

        .panel-header {
            padding: 16px;
            flex-direction: column;
        }

        .panel-header.compact {
            flex-direction: row;
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
            grid-template-columns: minmax(100px, 38%) 1fr;
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

        .action-column {
            text-align: left !important;
        }
    }

    @media (max-width: 430px) {
        .dashboard-header h1 {
            font-size: 24px;
        }

        .stat-card strong {
            font-size: 23px;
        }

        .appointments-table td {
            grid-template-columns: 1fr;
            gap: 4px;
        }
    }
</style>
@endsection
