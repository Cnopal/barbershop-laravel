@extends('customer.sidebar')

@section('title', 'Profile')

@section('content')
@php
    $profileImage = $user->profile_image
        ? asset($user->profile_image)
        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=d4af37&color=1a1f36&bold=true&size=400';

    $activeAppointments = \App\Models\Appointment::where('customer_id', $user->id)
        ->whereIn('status', ['pending_payment', 'confirmed'])
        ->count();
    $completedAppointments = \App\Models\Appointment::where('customer_id', $user->id)
        ->where('status', 'completed')
        ->count();
    $totalSpent = \App\Models\Appointment::where('customer_id', $user->id)
        ->whereIn('status', ['confirmed', 'completed'])
        ->sum('price');
@endphp

<div class="customer-page profile-page">
    @if(session('success'))
        <div class="profile-alert success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <section class="profile-hero">
        <div class="profile-identity">
            <div class="profile-avatar-wrap">
                <img src="{{ $profileImage }}" alt="{{ $user->name }}" class="profile-avatar">
                <span class="profile-online" aria-hidden="true"></span>
            </div>

            <div class="profile-heading">
                <span class="profile-kicker">Customer Profile</span>
                <h1>{{ $user->name }}</h1>
                <p>{{ $user->email }}</p>
                <div class="profile-meta">
                    <span><i class="fas fa-shield-alt"></i> Active account</span>
                    <span><i class="fas fa-calendar-check"></i> Joined {{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <a href="{{ route('customer.profile.edit') }}" class="profile-btn primary">
                <i class="fas fa-pen"></i>
                Edit Profile
            </a>
            <a href="{{ route('customer.appointments.create') }}" class="profile-btn">
                <i class="fas fa-calendar-plus"></i>
                Book Appointment
            </a>
        </div>
    </section>

    <section class="profile-stat-grid" aria-label="Profile summary">
        <article class="profile-stat">
            <span class="stat-icon calendar"><i class="fas fa-calendar-day"></i></span>
            <div>
                <strong>{{ $activeAppointments }}</strong>
                <span>Active bookings</span>
            </div>
        </article>

        <article class="profile-stat">
            <span class="stat-icon done"><i class="fas fa-check"></i></span>
            <div>
                <strong>{{ $completedAppointments }}</strong>
                <span>Completed visits</span>
            </div>
        </article>

        <article class="profile-stat">
            <span class="stat-icon spend"><i class="fas fa-receipt"></i></span>
            <div>
                <strong>RM{{ number_format($totalSpent, 2) }}</strong>
                <span>Total spent</span>
            </div>
        </article>
    </section>

    <section class="profile-layout">
        <article class="profile-panel">
            <div class="panel-heading">
                <div>
                    <span class="profile-kicker">Details</span>
                    <h2>Contact Information</h2>
                </div>
                <a href="{{ route('customer.profile.edit') }}" class="panel-link">Update</a>
            </div>

            <div class="detail-list">
                <div class="detail-row">
                    <span class="detail-icon"><i class="fas fa-user"></i></span>
                    <div>
                        <span class="detail-label">Full Name</span>
                        <strong>{{ $user->name }}</strong>
                    </div>
                </div>

                <div class="detail-row">
                    <span class="detail-icon"><i class="fas fa-envelope"></i></span>
                    <div>
                        <span class="detail-label">Email Address</span>
                        <strong>{{ $user->email }}</strong>
                    </div>
                </div>

                <div class="detail-row">
                    <span class="detail-icon"><i class="fas fa-phone"></i></span>
                    <div>
                        <span class="detail-label">Phone Number</span>
                        <strong class="{{ $user->phone ? '' : 'is-muted' }}">{{ $user->phone ?: 'Not added yet' }}</strong>
                    </div>
                </div>

                <div class="detail-row">
                    <span class="detail-icon"><i class="fas fa-location-dot"></i></span>
                    <div>
                        <span class="detail-label">Address</span>
                        <strong class="{{ $user->address ? '' : 'is-muted' }}">{{ $user->address ?: 'Not added yet' }}</strong>
                    </div>
                </div>
            </div>
        </article>

        <aside class="profile-panel account-panel">
            <div class="panel-heading">
                <div>
                    <span class="profile-kicker">Account</span>
                    <h2>Overview</h2>
                </div>
            </div>

            <div class="account-list">
                <div>
                    <span>Role</span>
                    <strong>{{ ucfirst($user->role ?? 'Customer') }}</strong>
                </div>
                <div>
                    <span>Last Updated</span>
                    <strong>{{ $user->updated_at->format('M d, Y') }}</strong>
                </div>
                <div>
                    <span>Member Since</span>
                    <strong>{{ $user->created_at->format('M d, Y') }}</strong>
                </div>
            </div>

            <a href="{{ route('customer.appointments.index') }}" class="profile-card-link">
                <span>
                    <i class="fas fa-clock-rotate-left"></i>
                    Appointment history
                </span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </aside>
    </section>
</div>

<style>
    .profile-page {
        --profile-border: #e2e8f0;
        --profile-muted: #718096;
        --profile-soft: #f8fafc;
        --profile-shadow: 0 10px 26px rgba(26, 31, 54, 0.08);
    }

    .profile-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: var(--customer-section-gap);
        padding: 14px 16px;
        border-radius: 8px;
        font-weight: 800;
    }

    .profile-alert.success {
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
        border: 1px solid rgba(72, 187, 120, 0.25);
    }

    .profile-hero {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        align-items: center;
        padding: 28px;
        margin-bottom: var(--customer-section-gap);
        background:
            linear-gradient(135deg, rgba(26, 31, 54, 0.98), rgba(18, 24, 38, 0.92)),
            linear-gradient(135deg, rgba(212, 175, 55, 0.22), transparent);
        border: 1px solid rgba(212, 175, 55, 0.20);
        border-radius: 8px;
        box-shadow: var(--profile-shadow);
        color: #fff;
    }

    .profile-identity {
        display: flex;
        align-items: center;
        gap: 22px;
        min-width: 0;
    }

    .profile-avatar-wrap {
        position: relative;
        width: 112px;
        height: 112px;
        flex-shrink: 0;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.82);
        box-shadow: 0 18px 36px rgba(0, 0, 0, 0.22);
    }

    .profile-online {
        position: absolute;
        right: 8px;
        bottom: 8px;
        width: 18px;
        height: 18px;
        border: 3px solid #fff;
        border-radius: 50%;
        background: #48bb78;
    }

    .profile-kicker {
        display: inline-flex;
        color: #8a6d16;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .profile-hero .profile-kicker {
        color: #f4d875;
    }

    .profile-heading h1 {
        margin: 6px 0 6px;
        color: #fff;
        font-size: 34px;
        font-weight: 900;
    }

    .profile-heading p {
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }

    .profile-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 14px;
    }

    .profile-meta span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.10);
        color: rgba(255, 255, 255, 0.90);
        font-size: 13px;
        font-weight: 800;
    }

    .profile-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .profile-btn,
    .panel-link,
    .profile-card-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .profile-btn {
        min-height: 44px;
        padding: 11px 16px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.24);
        background: rgba(255, 255, 255, 0.10);
        color: #fff;
        font-weight: 900;
        white-space: nowrap;
    }

    .profile-btn.primary {
        background: var(--accent);
        color: var(--primary);
        border-color: var(--accent);
    }

    .profile-btn:hover,
    .panel-link:hover,
    .profile-card-link:hover {
        transform: translateY(-2px);
        text-decoration: none;
    }

    .profile-stat-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: var(--customer-card-gap);
        margin-bottom: var(--customer-section-gap);
    }

    .profile-stat,
    .profile-panel {
        background: #fff;
        border: 1px solid var(--profile-border);
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .profile-stat {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px;
    }

    .stat-icon,
    .detail-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        border-radius: 8px;
    }

    .stat-icon.calendar {
        background: rgba(66, 153, 225, 0.12);
        color: #2b6cb0;
    }

    .stat-icon.done {
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
    }

    .stat-icon.spend {
        background: rgba(212, 175, 55, 0.15);
        color: #8a6d16;
    }

    .profile-stat strong {
        display: block;
        color: var(--primary);
        font-size: 22px;
        font-weight: 900;
    }

    .profile-stat span:last-child,
    .detail-label,
    .account-list span {
        color: var(--profile-muted);
        font-size: 13px;
        font-weight: 800;
    }

    .profile-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.65fr) minmax(320px, 0.8fr);
        gap: var(--customer-card-gap);
        align-items: start;
    }

    .profile-panel {
        padding: 24px;
    }

    .panel-heading {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .panel-heading h2 {
        margin: 4px 0 0;
        color: var(--primary);
        font-size: 24px;
        font-weight: 900;
    }

    .panel-link {
        color: var(--primary);
        border: 1px solid var(--profile-border);
        border-radius: 8px;
        padding: 9px 12px;
        font-weight: 900;
    }

    .detail-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .detail-row {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 16px;
        border: 1px solid var(--profile-border);
        border-radius: 8px;
        background: var(--profile-soft);
        min-width: 0;
    }

    .detail-icon {
        background: rgba(212, 175, 55, 0.14);
        color: #8a6d16;
    }

    .detail-row strong {
        display: block;
        margin-top: 4px;
        color: var(--primary);
        overflow-wrap: anywhere;
    }

    .detail-row strong.is-muted {
        color: var(--profile-muted);
        font-style: italic;
    }

    .account-list {
        display: grid;
        gap: 12px;
        margin-bottom: 18px;
    }

    .account-list div {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--profile-border);
    }

    .account-list div:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .account-list strong {
        color: var(--primary);
        text-align: right;
    }

    .profile-card-link {
        width: 100%;
        justify-content: space-between;
        padding: 14px;
        border-radius: 8px;
        background: var(--primary);
        color: #fff;
        font-weight: 900;
    }

    .profile-card-link span {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .profile-card-link:hover {
        color: #fff;
    }

    @media (max-width: 1024px) {
        .profile-hero,
        .profile-layout {
            grid-template-columns: 1fr;
        }

        .profile-hero {
            align-items: flex-start;
            flex-direction: column;
        }

        .profile-actions {
            justify-content: flex-start;
            width: 100%;
        }
    }

    @media (max-width: 760px) {
        .profile-identity {
            align-items: flex-start;
            flex-direction: column;
        }

        .profile-stat-grid,
        .detail-list {
            grid-template-columns: 1fr;
        }

        .profile-heading h1 {
            font-size: 28px;
        }

        .profile-btn {
            width: 100%;
        }
    }
</style>
@endsection
