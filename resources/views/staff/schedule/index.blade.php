@extends('staff.sidebar')

@section('page-title', 'My Schedule')

@section('content')
<style>
    .schedule-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    @media (max-width: 1200px) {
        .schedule-grid {
            grid-template-columns: 1fr;
        }
    }

    .schedule-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }

    .schedule-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 20px;
    }

    .appointment-item {
        padding: 15px;
        background: var(--light-gray);
        border-radius: 8px;
        margin-bottom: 15px;
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
        text-align: center;
        padding: 40px 20px;
        color: var(--secondary);
    }

    .empty-state i {
        font-size: 48px;
        color: var(--medium-gray);
        margin-bottom: 15px;
    }
</style>

<h1 style="margin: 0 0 30px 0; font-size: 28px;">My Schedule</h1>

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
        <div class="empty-state" style="grid-column: 1 / -1;">
            <i class="fas fa-inbox"></i>
            <h3>No Scheduled Appointments</h3>
            <p>You don't have any upcoming appointments scheduled.</p>
        </div>
    @endif
</div>
@endsection