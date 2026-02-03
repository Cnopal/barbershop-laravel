@extends('staff.sidebar')

@section('page-title', 'Dashboard')

@section('content')
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--accent);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
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
        font-size: 28px;
        margin-bottom: 10px;
        color: var(--accent);
    }

    .stat-card.blue .stat-icon {
        color: #4299e1;
    }

    .stat-card.green .stat-icon {
        color: #48bb78;
    }

    .stat-card.orange .stat-icon {
        color: #ed8936;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
        margin: 10px 0;
    }

    .stat-label {
        font-size: 14px;
        color: var(--secondary);
        font-weight: 500;
    }

    .two-column-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 30px;
    }

    @media (max-width: 1200px) {
        .two-column-layout {
            grid-template-columns: 1fr;
        }
    }

    .section-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--accent);
    }

    .appointment-item {
        padding: 15px;
        border-bottom: 1px solid var(--medium-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .appointment-item:last-child {
        border-bottom: none;
    }

    .appointment-info {
        flex: 1;
    }

    .appointment-customer {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 5px;
    }

    .appointment-service {
        font-size: 13px;
        color: var(--secondary);
        margin-bottom: 5px;
    }

    .appointment-time {
        font-size: 13px;
        color: var(--dark-gray);
    }

    .appointment-status {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .appointment-status.pending {
        background: #fed7d7;
        color: #742a2a;
    }

    .appointment-status.confirmed {
        background: #c6f6d5;
        color: #22543d;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--secondary);
    }

    .empty-state i {
        font-size: 48px;
        color: var(--medium-gray);
        margin-bottom: 15px;
    }

    .rating-display {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 15px 0;
    }

    .stars {
        color: #fbbf24;
        font-size: 18px;
    }

    .rating-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
    }

    .rating-count {
        font-size: 13px;
        color: var(--secondary);
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--medium-gray);
    }

    .dashboard-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary);
        margin: 0;
    }

    .profile-link-header {
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
        color: var(--primary);
        transition: all 0.3s ease;
    }

    .profile-link-header:hover {
        opacity: 0.8;
    }

    .profile-link-header .avatar-img {
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
        font-size: 18px;
        flex-shrink: 0;
    }

    .profile-link-header .avatar-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-info-header {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .profile-info-header .user-name {
        font-weight: 600;
        color: var(--primary);
    }

    .profile-info-header .user-role {
        font-size: 14px;
        color: var(--secondary);
    }
</style>

<div class="container">
<div class="dashboard-header">
    <h2>Dashboard</h2>
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
</div>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-value">{{ $todayAppointments }}</div>
        <div class="stat-label">Today's Appointments</div>
    </div>

    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-value">{{ $upcomingAppointments }}</div>
        <div class="stat-label">Upcoming Appointments</div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-check"></i></div>
        <div class="stat-value">{{ $completedAppointments }}</div>
        <div class="stat-label">Completed Appointments</div>
    </div>

    <div class="stat-card orange">
        <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
        <div class="stat-value">RM {{ number_format($totalRevenue, 2) }}</div>
        <div class="stat-label">Total Revenue</div>
    </div>
</div>

<div class="two-column-layout">
    <div class="section-card">
        <div class="section-title">
            <i class="fas fa-clock"></i> Today's Schedule
        </div>

        @if($todaySchedule->count() > 0)
            @foreach($todaySchedule as $appointment)
                <div class="appointment-item">
                    <div class="appointment-info">
                        <div class="appointment-customer">{{ $appointment->customer->name }}</div>
                        <div class="appointment-service">{{ $appointment->service->name }}</div>
                        <div class="appointment-time">
                            <i class="fas fa-clock"></i>
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->start_time)->format('h:i A') }} -
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->end_time)->format('h:i A') }}
                        </div>
                    </div>
                    <span class="appointment-status {{ strtolower($appointment->status) }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No appointments scheduled for today</p>
            </div>
        @endif
    </div>

    <div class="section-card">
        <div class="section-title">
            <i class="fas fa-star"></i> Customer Feedback
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
                <div>
                    <div class="rating-value">{{ number_format($averageRating ?? 0, 1) }}/5</div>
                    <div class="rating-count">{{ $totalFeedbacks }} feedback{{ $totalFeedbacks !== 1 ? 's' : '' }}</div>
                </div>
            </div>

            <a href="{{ route('staff.feedbacks.index') }}" style="display: inline-block; margin-top: 15px; color: var(--accent); text-decoration: none; font-weight: 600;">
                View All Feedbacks <i class="fas fa-arrow-right"></i>
            </a>
        @else
            <div class="empty-state" style="padding: 30px 20px;">
                <i class="fas fa-inbox"></i>
                <p>No feedbacks yet</p>
            </div>
        @endif
    </div>
</div>

<div class="section-card" style="margin-top: 30px;">
    <div class="section-title">
        <i class="fas fa-history"></i> Recent Appointments
    </div>

    @if($recentAppointments->count() > 0)
        @foreach($recentAppointments as $appointment)
            <div class="appointment-item">
                <div class="appointment-info">
                    <div class="appointment-customer">{{ $appointment->customer->name }}</div>
                    <div class="appointment-service">{{ $appointment->service->name }}</div>
                    <div class="appointment-time">
                        <i class="fas fa-calendar"></i>
                        {{ $appointment->appointment_date->format('d M Y') }} at
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->start_time)->format('h:i A') }}
                    </div>
                </div>
                <div style="text-align: right;">
                    <span class="appointment-status {{ strtolower($appointment->status) }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                    <div style="font-size: 14px; font-weight: 600; color: var(--primary); margin-top: 5px;">
                        RM {{ number_format($appointment->price, 2) }}
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>No appointments found</p>
        </div>
    @endif
</div>

@endsection