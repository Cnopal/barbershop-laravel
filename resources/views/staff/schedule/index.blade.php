@extends('staff.sidebar')

@section('page-title', 'My Schedule')

@section('content')
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
        margin-bottom: 26px;
        flex-wrap: wrap;
    }

    .staff-page-header h1 {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        color: var(--primary);
    }

    .schedule-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    @media (max-width: 1200px) {
        .schedule-grid {
            grid-template-columns: 1fr;
        }
    }

    .schedule-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
        padding: 24px;
    }

    .schedule-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 20px;
    }

    .appointment-item {
        padding: 14px;
        background: var(--light-gray);
        border-radius: 8px;
        margin-bottom: 12px;
        border-left: 4px solid var(--accent);
    }

    .appointment-item.confirmed {
        border-left-color: #48bb78;
    }

    .appointment-item.pending {
        border-left-color: #ed8936;
    }

    .appointment-customer {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 5px;
    }

    .appointment-service {
        font-size: 13px;
        color: var(--secondary);
        margin-bottom: 8px;
    }

    .appointment-time {
        font-size: 13px;
        color: var(--dark-gray);
        margin-bottom: 8px;
    }

    .appointment-status {
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .appointment-status.confirmed {
        background: #c6f6d5;
        color: #22543d;
    }

    .appointment-status.pending {
        background: #fed7d7;
        color: #742a2a;
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px 20px;
        color: var(--secondary);
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .empty-state i {
        font-size: 48px;
        color: var(--medium-gray);
        margin-bottom: 15px;
    }

    @media (max-width: 768px) {
        .staff-ui-page {
            padding: 20px;
        }
    }
</style>

<div class="staff-ui-page">
<div class="staff-page-header">
    <h1>My Schedule</h1>
</div>

<div class="schedule-grid">
    @if($appointments->count() > 0)
        @foreach($appointments->groupBy('appointment_date') as $date => $dateAppointments)
            <div class="schedule-card">
                <div class="schedule-title">
                    <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}
                </div>

                @foreach($dateAppointments as $appointment)
                    <div class="appointment-item {{ strtolower($appointment->status) }}">
                        <div class="appointment-customer">{{ $appointment->customer->name }}</div>
                        <div class="appointment-service">{{ $appointment->service->name }}</div>
                        <div class="appointment-time">
                            <i class="fas fa-clock"></i>
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->start_time)->format('h:i A') }} -
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->end_time)->format('h:i A') }}
                        </div>
                        <span class="appointment-status {{ strtolower($appointment->status) }}">{{ ucfirst($appointment->status) }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No Scheduled Appointments</h3>
            <p>You don't have any upcoming appointments scheduled.</p>
        </div>
    @endif
</div>
</div>
@endsection
