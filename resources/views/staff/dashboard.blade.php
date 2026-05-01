@extends('staff.sidebar')

@section('page-title', 'Dashboard')

@section('content')
<div class="staff-dashboard-page">
    <header class="dashboard-header">
        <div>
            <span class="eyebrow">Staff Dashboard</span>
            <h1>Dashboard</h1>
        </div>
        <a href="{{ route('staff.profile.show') }}" class="profile-link-header">
            <div class="avatar-img">
                @if(Auth::user()->profile_image)
                    <img src="{{ asset(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
                @else
                    {{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->name, strrpos(Auth::user()->name, ' ') + 1, 1) }}
                @endif
            </div>
            <div class="profile-info-header">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-role">{{ Auth::user()->position ?? 'Staff Member' }}</span>
            </div>
        </a>
    </header>

    <nav class="quick-actions" aria-label="Staff quick actions">
        <a href="{{ route('staff.appointments.create') }}" class="quick-action">
            <i class="fas fa-calendar-plus"></i>
            <span>New Appointment</span>
        </a>
        <a href="{{ route('staff.walk-ins.index') }}" class="quick-action">
            <i class="fas fa-list-ol"></i>
            <span>Walk-in Queue</span>
        </a>
        <a href="{{ route('staff.schedule') }}" class="quick-action">
            <i class="fas fa-clock"></i>
            <span>Schedule</span>
        </a>
        <a href="{{ route('staff.pos.index') }}" class="quick-action">
            <i class="fas fa-cash-register"></i>
            <span>POS</span>
        </a>
    </nav>

    <section class="dashboard-grid" aria-label="Staff summary">
        <article class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div>
                <strong>{{ $todayAppointments }}</strong>
                <span>Today's Appointments</span>
            </div>
        </article>

        <article class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-list-ol"></i></div>
            <div>
                <strong>{{ $todayWalkIns }}</strong>
                <span>Today's Walk-ins</span>
            </div>
        </article>

        <article class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <strong>{{ $upcomingAppointments }}</strong>
                <span>Upcoming</span>
            </div>
        </article>

        <article class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check"></i></div>
            <div>
                <strong>{{ $completedAppointments + $completedWalkIns }}</strong>
                <span>Completed Services</span>
                <small>{{ $completedAppointments }} appointments, {{ $completedWalkIns }} walk-ins</small>
            </div>
        </article>

        <article class="stat-card orange">
            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div>
                <strong>RM{{ number_format($totalRevenue, 2) }}</strong>
                <span>Total Revenue</span>
                <small>Appt RM{{ number_format($appointmentRevenue, 2) }} &middot; Walk-in RM{{ number_format($walkInRevenue, 2) }} &middot; Product RM{{ number_format($productRevenue, 2) }}</small>
            </div>
        </article>
    </section>

    <section class="dashboard-main-grid">
        <article class="panel">
            <div class="panel-header">
                <div>
                    <h2>Today's Schedule</h2>
                    <p>{{ $todaySchedule->count() }} appointment{{ $todaySchedule->count() === 1 ? '' : 's' }}</p>
                </div>
                <a href="{{ route('staff.schedule') }}" class="panel-link">View Schedule</a>
            </div>

            <div class="item-list">
                @forelse($todaySchedule as $appointment)
                    <a href="{{ route('staff.appointments.show', $appointment) }}" class="activity-item" data-activity-type="appointment" data-activity-text="{{ strtolower($appointment->customer->name . ' ' . $appointment->service->name . ' ' . $appointment->status) }}">
                        <div class="time-chip">
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->start_time)->format('h:i A') }}
                        </div>
                        <div class="activity-main">
                            <strong>{{ $appointment->customer->name }}</strong>
                            <span>{{ $appointment->service->name }} &middot; {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->end_time)->format('h:i A') }}</span>
                        </div>
                        <span class="status-pill {{ strtolower($appointment->status) }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
                    </a>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No appointments scheduled for today.</p>
                    </div>
                @endforelse
            </div>
        </article>

        <article class="panel feedback-panel">
            <div class="panel-header">
                <div>
                    <h2>Customer Feedback</h2>
                    <p>{{ $totalFeedbacks }} feedback{{ $totalFeedbacks === 1 ? '' : 's' }}</p>
                </div>
                <a href="{{ route('staff.feedbacks.index') }}" class="panel-link">View All</a>
            </div>

            @if($totalFeedbacks > 0)
                <div class="rating-display">
                    <div class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($averageRating ?? 0))
                                <i class="fas fa-star"></i>
                            @elseif ($i - 0.5 <= ($averageRating ?? 0))
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <strong>{{ number_format($averageRating ?? 0, 1) }}/5</strong>
                </div>
            @else
                <div class="empty-state compact">
                    <i class="fas fa-inbox"></i>
                    <p>No feedbacks yet.</p>
                </div>
            @endif
        </article>
    </section>

    <section class="panel activity-panel">
        <div class="panel-header">
            <div>
                <h2>Recent Activity</h2>
                <p>Search and filter recent appointments or walk-ins.</p>
            </div>
        </div>

        <div class="activity-toolbar">
            <input type="search" id="activitySearch" class="activity-search" placeholder="Search customer, service, queue, or status">
            <button type="button" class="activity-filter active" data-filter="all">All</button>
            <button type="button" class="activity-filter" data-filter="appointment">Appointments</button>
            <button type="button" class="activity-filter" data-filter="walk-in">Walk-ins</button>
        </div>

        <div class="item-list">
            @foreach($recentWalkIns as $queue)
                <a href="{{ route('staff.walk-ins.index') }}" class="activity-item" data-activity-type="walk-in" data-activity-text="{{ strtolower($queue->display_customer_name . ' ' . ($queue->service->name ?? 'walk-in service') . ' ' . $queue->queue_code . ' ' . $queue->status_label) }}">
                    <div class="time-chip">{{ $queue->queue_code }}</div>
                    <div class="activity-main">
                        <strong>{{ $queue->display_customer_name }}</strong>
                        <span>{{ $queue->service->name ?? 'Walk-in service' }} &middot; {{ $queue->queue_date->format('d M Y') }} &middot; RM{{ number_format($queue->price, 2) }}</span>
                    </div>
                    <span class="status-pill {{ strtolower($queue->status) }}">{{ $queue->status_label }}</span>
                </a>
            @endforeach

            @foreach($recentAppointments as $appointment)
                <a href="{{ route('staff.appointments.show', $appointment) }}" class="activity-item" data-activity-type="appointment" data-activity-text="{{ strtolower($appointment->customer->name . ' ' . $appointment->recipient_display_name . ' ' . $appointment->service->name . ' ' . $appointment->status) }}">
                    <div class="time-chip">{{ $appointment->appointment_date->format('d M') }}</div>
                    <div class="activity-main">
                        <strong>{{ $appointment->customer->name }}</strong>
                        <span>{{ $appointment->service->name }} &middot; {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->start_time)->format('h:i A') }} &middot; RM{{ number_format($appointment->price, 2) }}</span>
                    </div>
                    <span class="status-pill {{ strtolower($appointment->status) }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span>
                </a>
            @endforeach
        </div>

        <div class="empty-state compact" id="activityEmpty" hidden>
            <i class="fas fa-search"></i>
            <p>No recent activity matches your filter.</p>
        </div>
    </section>
</div>

<style>
    .staff-dashboard-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .dashboard-header,
    .panel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .dashboard-header {
        margin-bottom: 22px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--medium-gray);
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

    .dashboard-header h1,
    .panel h2 {
        margin: 0;
        color: var(--primary);
    }

    .dashboard-header h1 {
        font-size: 30px;
    }

    .profile-link-header,
    .quick-action,
    .panel-link,
    .activity-item {
        text-decoration: none;
    }

    .profile-link-header {
        display: flex;
        align-items: center;
        gap: 14px;
        color: var(--primary);
    }

    .avatar-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid var(--accent);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
        color: var(--primary);
        font-weight: bold;
        flex-shrink: 0;
    }

    .avatar-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-info-header {
        display: grid;
        gap: 2px;
    }

    .user-name {
        font-weight: 800;
    }

    .user-role,
    .panel-header p,
    .stat-card span,
    .stat-card small,
    .activity-main span {
        color: var(--secondary);
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .quick-action,
    .stat-card,
    .panel {
        background: white;
        border: 1px solid var(--medium-gray);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.08);
    }

    .quick-action {
        min-height: 54px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--primary);
        font-weight: 900;
    }

    .quick-action i,
    .panel-link {
        color: var(--accent);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .stat-card {
        min-height: 126px;
        padding: 18px;
        display: grid;
        gap: 10px;
        border-left: 4px solid var(--accent);
    }

    .stat-card.blue {
        border-left-color: #4299e1;
    }

    .stat-card.green {
        border-left-color: #48bb78;
    }

    .stat-card.orange {
        border-left-color: #ed8936;
    }

    .stat-icon {
        color: var(--accent);
        font-size: 24px;
    }

    .stat-card strong {
        display: block;
        color: var(--primary);
        font-size: 28px;
        line-height: 1.1;
        overflow-wrap: anywhere;
    }

    .stat-card span,
    .stat-card small {
        display: block;
        font-weight: 700;
    }

    .stat-card small {
        margin-top: 6px;
        font-size: 12px;
    }

    .dashboard-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.6fr);
        gap: 18px;
        margin-bottom: 18px;
    }

    .panel {
        overflow: hidden;
    }

    .panel-header {
        padding: 18px 20px;
        border-bottom: 1px solid var(--medium-gray);
    }

    .panel-header p {
        margin: 5px 0 0;
    }

    .panel-link {
        font-weight: 900;
    }

    .item-list {
        display: grid;
    }

    .activity-item {
        display: grid;
        grid-template-columns: 92px minmax(0, 1fr) auto;
        align-items: center;
        gap: 14px;
        padding: 15px 20px;
        border-bottom: 1px solid var(--medium-gray);
        color: var(--primary);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .time-chip {
        min-height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 8px;
        background: var(--light-gray);
        color: var(--primary);
        font-weight: 900;
        text-align: center;
    }

    .activity-main {
        min-width: 0;
        display: grid;
        gap: 4px;
    }

    .activity-main strong,
    .activity-main span {
        overflow-wrap: anywhere;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        white-space: nowrap;
        background: #feebc8;
        color: #7b341e;
    }

    .status-pill.confirmed,
    .status-pill.completed {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-pill.serving {
        background: #bee3f8;
        color: #2c5282;
    }

    .status-pill.cancelled,
    .status-pill.skipped {
        background: #fed7d7;
        color: #742a2a;
    }

    .rating-display {
        padding: 24px 20px;
        display: grid;
        gap: 10px;
    }

    .stars {
        color: #fbbf24;
        font-size: 20px;
    }

    .rating-display strong {
        font-size: 26px;
    }

    .activity-toolbar {
        display: flex;
        gap: 10px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--medium-gray);
        flex-wrap: wrap;
    }

    .activity-search {
        flex: 1 1 280px;
        min-height: 42px;
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
        padding: 9px 12px;
        font: inherit;
    }

    .activity-filter {
        min-height: 42px;
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
        padding: 9px 12px;
        background: #fff;
        color: var(--primary);
        font: inherit;
        font-weight: 900;
        cursor: pointer;
    }

    .activity-filter.active {
        background: var(--accent);
        border-color: var(--accent);
    }

    .empty-state {
        padding: 34px 20px;
        text-align: center;
        color: var(--secondary);
    }

    .empty-state.compact {
        padding: 26px 20px;
    }

    .empty-state i {
        color: var(--accent);
        font-size: 34px;
        margin-bottom: 10px;
    }

    @media (max-width: 1280px) {
        .dashboard-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .quick-actions {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-main-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .staff-dashboard-page {
            padding: 20px;
        }

        .dashboard-header,
        .profile-link-header {
            align-items: flex-start;
        }

        .dashboard-header {
            display: grid;
        }

        .dashboard-header h1 {
            font-size: 26px;
        }

        .quick-actions,
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .panel-header {
            padding: 16px;
        }

        .activity-toolbar {
            display: grid;
        }

        .activity-search,
        .activity-filter {
            width: 100%;
        }

        .activity-item {
            grid-template-columns: 1fr;
            align-items: start;
            padding: 16px;
        }

        .time-chip {
            justify-content: flex-start;
            width: fit-content;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('activitySearch');
        const filterButtons = document.querySelectorAll('.activity-filter');
        const activityItems = document.querySelectorAll('.activity-panel .activity-item');
        const emptyState = document.getElementById('activityEmpty');
        let activeFilter = 'all';

        function applyActivityFilter() {
            const query = (searchInput?.value || '').trim().toLowerCase();
            let visible = 0;

            activityItems.forEach(item => {
                const matchesType = activeFilter === 'all' || item.dataset.activityType === activeFilter;
                const matchesSearch = !query || (item.dataset.activityText || '').includes(query);
                const show = matchesType && matchesSearch;

                item.hidden = !show;
                if (show) {
                    visible += 1;
                }
            });

            if (emptyState) {
                emptyState.hidden = visible > 0 || activityItems.length === 0;
            }
        }

        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                activeFilter = button.dataset.filter || 'all';
                filterButtons.forEach(item => item.classList.toggle('active', item === button));
                applyActivityFilter();
            });
        });

        searchInput?.addEventListener('input', applyActivityFilter);
        applyActivityFilter();
    });
</script>
@endsection
