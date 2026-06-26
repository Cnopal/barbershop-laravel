@extends('customer.sidebar')

@section('title', 'Dashboard')

@section('content')
@php
    $firstName = strtok(Auth::user()->name ?? 'there', ' ') ?: 'there';
    $nextAppointment = $upcomingAppointments->first();
@endphp

<div class="customer-page dashboard-page">
    <section class="customer-hero">
        <div class="hero-copy">
            <span class="eyebrow">Men's Club Customer Lounge</span>
            <h1>Welcome back, {{ $firstName }}.</h1>
            <p>Book your next cut, track appointments, join the walk-in queue, and explore grooming essentials from one polished space.</p>
            <div class="hero-actions">
                <a href="{{ route('customer.appointments.create') }}" class="primary-action">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Book Appointment</span>
                </a>
                <a href="{{ route('customer.services.index') }}" class="secondary-action">
                    <i class="fas fa-scissors"></i>
                    <span>Explore Services</span>
                </a>
            </div>
        </div>

        <aside class="next-visit-card">
            <span>Next Visit</span>
            @if($nextAppointment)
                <strong>{{ $nextAppointment->appointment_date->format('d M') }}</strong>
                <p>{{ date('h:i A', strtotime($nextAppointment->start_time)) }} with {{ $nextAppointment->barber->name ?? 'your barber' }}</p>
                <a href="{{ route('customer.appointments.show', $nextAppointment) }}">View booking</a>
            @else
                <strong>No booking</strong>
                <p>Your next appointment will appear here once you reserve a slot.</p>
                <a href="{{ route('customer.appointments.create') }}">Reserve now</a>
            @endif
        </aside>
    </section>

    <section class="stats-grid" aria-label="Account summary">
        <article class="stat-card">
            <span>Upcoming</span>
            <strong>{{ $stats['upcoming'] }}</strong>
            <small>Active bookings</small>
        </article>
        <article class="stat-card">
            <span>Completed</span>
            <strong>{{ $stats['completed'] }}</strong>
            <small>Finished visits</small>
        </article>
        <article class="stat-card">
            <span>Cancelled</span>
            <strong>{{ $stats['cancelled'] }}</strong>
            <small>Past cancellations</small>
        </article>
        <article class="stat-card highlight-card">
            <span>Total Spent</span>
            <strong>RM{{ number_format($stats['total_spent'], 2) }}</strong>
            <small>Appointments, queue, products</small>
        </article>
    </section>

    <section class="dashboard-grid">
        <article class="panel appointments-panel">
            <div class="panel-header">
                <div>
                    <span class="panel-kicker">Schedule</span>
                    <h2>Upcoming Appointments</h2>
                    <p>{{ $upcomingAppointments->count() }} active booking{{ $upcomingAppointments->count() === 1 ? '' : 's' }}</p>
                </div>
                <a href="{{ route('customer.appointments.index') }}" class="panel-link">View All</a>
            </div>

            @if($upcomingAppointments->count())
                <div class="appointment-list">
                    @foreach($upcomingAppointments as $appointment)
                        <a href="{{ route('customer.appointments.show', $appointment) }}" class="appointment-card-row">
                            <div class="date-tile">
                                <span>{{ $appointment->appointment_date->format('M') }}</span>
                                <strong>{{ $appointment->appointment_date->format('d') }}</strong>
                            </div>
                            <div class="appointment-details">
                                <strong>{{ $appointment->service->name ?? '-' }}</strong>
                                <span>{{ date('h:i A', strtotime($appointment->start_time)) }} · {{ $appointment->barber->name ?? 'Barber' }}</span>
                            </div>
                            <span class="status-badge {{ $appointment->status }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar"></i>
                    <h3>No upcoming appointments</h3>
                    <p>Choose a service and barber to reserve your next visit.</p>
                    <a href="{{ route('customer.appointments.create') }}" class="panel-link">Book now</a>
                </div>
            @endif
        </article>

        <aside class="side-stack">
            <article class="panel queue-panel">
                <div class="panel-header compact">
                    <div>
                        <span class="panel-kicker">Live</span>
                        <h2>Walk-in Queue</h2>
                    </div>
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
                <div class="panel-header compact no-border">
                    <div>
                        <span class="panel-kicker">Browse</span>
                        <h2>Quick Actions</h2>
                    </div>
                </div>
                <div class="quick-actions">
                    <a href="{{ route('customer.ai-hair.index') }}" class="quick-action ai-action">
                        <i class="fas fa-magic"></i>
                        <span>AI Hair Recommendation</span>
                    </a>
                    <a href="{{ route('customer.barbers.index') }}" class="quick-action">
                        <i class="fas fa-users"></i>
                        <span>Our Barbers</span>
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

    .customer-hero {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, 360px);
        gap: 22px;
        overflow: hidden;
        min-height: 340px;
        padding: clamp(28px, 5vw, 54px);
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 8px;
        background:
            linear-gradient(135deg, rgba(248, 249, 250, 0.94), rgba(233, 236, 239, 0.88)),
            radial-gradient(circle at top right, rgba(212, 175, 55, 0.30), transparent 24rem);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.10);
    }

    .customer-hero::after {
        content: '';
        position: absolute;
        inset: 0 0 0 auto;
        width: min(42%, 420px);
        background: linear-gradient(135deg, var(--accent), #c19a2f);
        clip-path: polygon(32% 0, 100% 0, 100% 100%, 0 100%);
        opacity: 0.95;
        pointer-events: none;
    }

    .hero-copy,
    .next-visit-card {
        position: relative;
        z-index: 1;
    }

    .eyebrow,
    .panel-kicker {
        display: inline-flex;
        margin-bottom: 8px;
        color: var(--accent);
        font-size: 12px;
        font-weight: 900;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    .customer-hero h1 {
        max-width: 680px;
        margin: 0;
        color: var(--primary);
        font-size: clamp(40px, 6.5vw, 76px);
        line-height: 0.98;
    }

    .customer-hero p {
        max-width: 570px;
        margin: 18px 0 0;
        color: #2a2a2a;
        font-size: 16px;
        line-height: 1.75;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 26px;
    }

    .primary-action,
    .secondary-action,
    .panel-link,
    .quick-action,
    .next-visit-card a,
    .appointment-card-row {
        text-decoration: none;
        font-weight: 800;
    }

    .primary-action,
    .secondary-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 48px;
        padding: 12px 18px;
        border-radius: 8px;
        transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s ease;
        white-space: nowrap;
    }

    .primary-action {
        color: var(--primary);
        background: linear-gradient(135deg, var(--accent), #c19a2f);
        box-shadow: 0 10px 24px rgba(212, 175, 55, 0.24);
    }

    .secondary-action {
        color: var(--primary);
        border: 2px solid var(--primary);
        background: transparent;
    }

    .primary-action:hover,
    .secondary-action:hover {
        transform: translateY(-2px);
    }

    .secondary-action:hover {
        color: #fff;
        background: var(--primary);
    }

    .next-visit-card {
        align-self: stretch;
        display: grid;
        align-content: center;
        gap: 10px;
        min-height: 230px;
        padding: 24px;
        border: 1px solid rgba(255, 255, 255, 0.34);
        border-radius: 8px;
        color: #fff;
        background: rgba(10, 10, 10, 0.82);
        box-shadow: 0 18px 42px rgba(0, 0, 0, 0.18);
    }

    .next-visit-card span {
        color: rgba(255, 255, 255, 0.68);
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .next-visit-card strong {
        color: var(--accent-light);
        font-family: 'Playfair Display', serif;
        font-size: clamp(34px, 4vw, 50px);
        line-height: 1;
    }

    .next-visit-card p {
        margin: 0;
        color: rgba(255, 255, 255, 0.74);
    }

    .next-visit-card a {
        width: fit-content;
        color: #fff;
        border-bottom: 1px solid currentColor;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .stat-card,
    .panel {
        border: 1px solid rgba(0, 0, 0, 0.07);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
    }

    .stat-card {
        display: grid;
        gap: 6px;
        min-height: 126px;
        padding: 20px;
    }

    .stat-card span,
    .stat-card small {
        color: #718096;
        font-size: 13px;
        font-weight: 800;
    }

    .stat-card strong {
        color: var(--primary);
        font-family: 'Playfair Display', serif;
        font-size: 34px;
        line-height: 1;
    }

    .highlight-card {
        background: #0a0a0a;
    }

    .highlight-card span,
    .highlight-card small {
        color: rgba(255, 255, 255, 0.64);
    }

    .highlight-card strong {
        color: var(--accent-light);
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
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        padding: 20px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.07);
    }

    .panel-header.compact {
        align-items: center;
    }

    .panel-header.no-border {
        border-bottom: 0;
        padding-bottom: 10px;
    }

    .panel h2 {
        margin: 0;
        color: var(--primary);
        font-size: 24px;
    }

    .panel-header p {
        margin: 6px 0 0;
        color: #718096;
        font-size: 14px;
    }

    .panel-link {
        color: var(--accent);
        white-space: nowrap;
    }

    .appointment-list {
        display: grid;
    }

    .appointment-card-row {
        display: grid;
        grid-template-columns: 64px minmax(0, 1fr) auto;
        align-items: center;
        gap: 14px;
        padding: 16px 20px;
        color: var(--primary);
        border-bottom: 1px solid rgba(0, 0, 0, 0.07);
        transition: background 0.22s ease;
    }

    .appointment-card-row:last-child {
        border-bottom: 0;
    }

    .appointment-card-row:hover {
        background: rgba(212, 175, 55, 0.08);
    }

    .date-tile {
        display: grid;
        place-items: center;
        min-height: 64px;
        border-radius: 8px;
        background: var(--light-gray);
    }

    .date-tile span {
        color: #718096;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .date-tile strong {
        color: var(--primary);
        font-family: 'Playfair Display', serif;
        font-size: 26px;
        line-height: 1;
    }

    .appointment-details {
        min-width: 0;
        display: grid;
        gap: 4px;
    }

    .appointment-details strong {
        overflow-wrap: anywhere;
    }

    .appointment-details span {
        overflow: hidden;
        color: #718096;
        font-size: 14px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
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

    .side-stack {
        display: grid;
        gap: 18px;
    }

    .queue-card {
        margin: 18px;
        padding: 20px;
        border-radius: 8px;
        color: #fff;
        background: linear-gradient(135deg, #0a0a0a, #2a2a2a);
    }

    .queue-card span {
        color: rgba(255, 255, 255, 0.72);
        font-weight: 800;
        font-size: 13px;
    }

    .queue-card strong {
        display: block;
        margin: 6px 0 14px;
        color: var(--accent-light);
        font-family: 'Playfair Display', serif;
        font-size: 38px;
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
        margin: 0;
        padding: 0 20px 20px;
        color: #718096;
    }

    .quick-actions {
        display: grid;
        gap: 10px;
        padding: 0 20px 20px;
    }

    .quick-action {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 48px;
        padding: 11px 12px;
        border: 1px solid rgba(0, 0, 0, 0.07);
        border-radius: 8px;
        color: var(--primary);
        background: #fff;
        transition: transform 0.22s ease, background 0.22s ease;
    }

    .quick-action:hover {
        transform: translateY(-1px);
        background: rgba(212, 175, 55, 0.08);
    }

    .quick-action i {
        width: 22px;
        color: var(--accent);
        text-align: center;
    }

    .ai-action {
        color: #fff;
        border-color: transparent;
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .ai-action i {
        color: #fff;
    }

    .ai-action:hover {
        background: linear-gradient(135deg, #5a6fd8, #6f4396);
    }

    .empty-state {
        display: grid;
        justify-items: center;
        gap: 10px;
        padding: 42px 20px;
        color: #718096;
        text-align: center;
    }

    .empty-state i {
        color: var(--accent);
        font-size: 28px;
    }

    .empty-state h3 {
        margin: 0;
        color: var(--primary);
        font-size: 22px;
    }

    .empty-state p {
        max-width: 360px;
        margin: 0;
    }

    @media (max-width: 1180px) {
        .customer-hero,
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .customer-hero::after {
            width: 46%;
            opacity: 0.22;
        }

        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .side-stack {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 760px) {
        .dashboard-page {
            gap: 18px;
        }

        .customer-hero {
            padding: 26px;
        }

        .customer-hero h1 {
            font-size: 40px;
        }

        .hero-actions,
        .hero-actions a {
            width: 100%;
        }

        .stats-grid,
        .side-stack {
            grid-template-columns: 1fr;
        }

        .appointment-card-row {
            grid-template-columns: 58px minmax(0, 1fr);
            padding: 16px;
        }

        .appointment-card-row .status-badge {
            grid-column: 2;
            width: fit-content;
        }

        .panel-header {
            padding: 16px;
        }
    }

    @media (max-width: 430px) {
        .customer-hero h1 {
            font-size: 34px;
        }

        .appointment-card-row {
            grid-template-columns: 1fr;
        }

        .appointment-card-row .status-badge {
            grid-column: auto;
        }
    }
</style>
@endsection
