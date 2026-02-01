@extends('customer.sidebar')

@section('title', 'Dashboard')

@section('content')

    <h1 style="margin-bottom: 1.5rem;">Welcome back, {{ Auth::user()->name }} </h1>

    {{-- Quick Stats --}}
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        {{-- Upcoming Appointments --}}
        <div class="card">
            <h3>Upcoming Appointments</h3>
            <p class="card-number">
                {{ \App\Models\Appointment::where('customer_id', Auth::id())
        ->whereIn('status', ['pending_payment', 'confirmed'])
        ->count() }}
            </p>
        </div>

        {{-- Completed Appointments --}}
        <div class="card">
            <h3>Completed</h3>
            <p class="card-number">
                {{ \App\Models\Appointment::where('customer_id', Auth::id())
        ->where('status', 'completed')
        ->count() }}
            </p>
        </div>

        {{-- Cancelled --}}
        <div class="card">
            <h3>Cancelled</h3>
            <p class="card-number">
                {{ \App\Models\Appointment::where('customer_id', Auth::id())
        ->where('status', 'cancelled')
        ->count() }}
            </p>
        </div>

        {{-- Total Spent --}}
        <div class="card">
            <h3>Total Spent</h3>
            <p class="card-number">
                RM{{ number_format(\App\Models\Appointment::where('customer_id', Auth::id())
        ->sum('price'), 2) }}
            </p>
        </div>
    </div>

    {{-- Upcoming Appointment List --}}
    <div class="card">
        <h2 style="margin-bottom: 1rem;">Your Upcoming Appointments</h2>

        @php
            $appointments = \App\Models\Appointment::with(['service', 'barber'])
                ->where('customer_id', Auth::id())
                ->whereIn('status', ['pending_payment', 'confirmed'])
                ->orderBy('appointment_date')
                ->orderBy('start_time')
                ->limit(5)
                ->get();
        @endphp

        @if($appointments->count())
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="padding: 0.75rem; text-align: left;">Date</th>
                        <th style="padding: 0.75rem; text-align: left;">Time</th>
                        <th style="padding: 0.75rem; text-align: left;">Service</th>
                        <th style="padding: 0.75rem; text-align: left;">Barber</th>
                        <th style="padding: 0.75rem; text-align: left;">Status</th>
                         <th style="padding: 0.75rem; text-align: left;">Action</th>
                        <th style="padding: 0.75rem;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 0.75rem;">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                            </td>
                            <td style="padding: 0.75rem;">
                                {{ date('h:i A', strtotime($appointment->start_time)) }}
                            </td>
                            <td style="padding: 0.75rem;">
                                {{ $appointment->service->name ?? '-' }}
                            </td>
                            <td style="padding: 0.75rem;">
                                {{ $appointment->barber->name ?? '-' }}
                            </td>
                            <td style="padding: 0.75rem;">
                                <span class="status-badge {{ $appointment->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </td>
                            <td style="padding: 0.75rem;">
                                
                                <a href="{{ route('customer.appointments.show', $appointment->id) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No upcoming appointments.</p>
        @endif
    </div>

    {{-- Quick Actions --}}
    <div style="margin-top: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('customer.appointments.create') }}" class="action-btn">
            <i class="fas fa-calendar-plus"></i> Book Appointment
        </a>

        <a href="{{ route('customer.services.index') }}" class="action-btn secondary">
            <i class="fas fa-cut"></i> View Services
        </a>

        <a href="{{ route('customer.ai-hair.index') }}" class="action-btn accent">
            <i class="fas fa-magic"></i> AI Hair Recommendation
        </a>
    </div>

    {{-- Inline CSS --}}
    <style>
        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
        }

        .card h3,
        .card h2 {
            font-weight: 600;
            color: #1a1f36;
            margin-bottom: 0.5rem;
        }

        .card-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1a1f36;
            margin-top: 0.5rem;
        }

        .status-badge {
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
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

        .action-btn {
            padding: 0.75rem 1.25rem;
            background: #1a1f36;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .action-btn.secondary {
            background: #4a5568;
        }

        .action-btn.accent {
            background: #d4af37;
            color: #1a1f36;
        }

        .action-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        table th,
        table td {
            font-size: 0.95rem;
            color: #1a1f36;
        }

        table th {
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
    </style>

@endsection