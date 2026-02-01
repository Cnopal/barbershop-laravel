@extends('staff.sidebar')

@section('page-title', 'Appointments')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .btn {
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent) 0%, #e6c158 100%);
        color: var(--primary);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
    }

    .appointments-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .appointments-table thead {
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
    }

    .appointments-table th {
        padding: 20px;
        text-align: left;
        font-weight: 600;
        color: var(--primary);
        border-bottom: 2px solid var(--medium-gray);
    }

    .appointments-table td {
        padding: 18px 20px;
        border-bottom: 1px solid var(--medium-gray);
    }

    .appointments-table tbody tr:hover {
        background: var(--light-gray);
    }

    .appointment-customer {
        font-weight: 600;
        color: var(--primary);
    }

    .appointment-date {
        color: var(--secondary);
        font-size: 14px;
    }

    .appointment-time {
        color: var(--dark-gray);
        font-size: 14px;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .status-pending {
        background: #fed7d7;
        color: #742a2a;
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
        background: #f5e6e8;
        color: #6b2c2c;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 13px;
        border-radius: 6px;
    }

    .btn-info {
        background: #bee3f8;
        color: #2c5282;
    }

    .btn-info:hover {
        background: #90cdf4;
    }

    .btn-edit {
        background: #fef5e7;
        color: #7d6608;
    }

    .btn-edit:hover {
        background: #fce8b2;
    }

    .btn-danger {
        background: #fed7d7;
        color: #742a2a;
    }

    .btn-danger:hover {
        background: #fc8181;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--secondary);
    }

    .empty-state i {
        font-size: 64px;
        color: var(--medium-gray);
        margin-bottom: 20px;
    }
</style>

<div class="page-header">
    <h1 style="margin: 0; font-size: 28px;">My Appointments</h1>
    <a href="{{ route('staff.appointments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Create Appointment
    </a>
</div>

@if($appointments->count() > 0)
    <table class="appointments-table">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Service</th>
                <th>Date & Time</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td>
                        <div class="appointment-customer">{{ $appointment->customer->name }}</div>
                        <div class="appointment-date">{{ $appointment->customer->email }}</div>
                    </td>
                    <td>{{ $appointment->service->name }}</td>
                    <td>
                        <div class="appointment-date">{{ $appointment->appointment_date->format('d M Y') }}</div>
                        <div class="appointment-time">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->end_time)->format('h:i A') }}</div>
                    </td>
                    <td>RM {{ number_format($appointment->price, 2) }}</td>
                    <td><span class="status-badge status-{{ strtolower($appointment->status) }}">{{ ucfirst($appointment->status) }}</span></td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <!-- <a href="{{ route('staff.appointments.edit', $appointment->id) }}" class="btn btn-sm btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a> -->
                            <form action="{{ route('staff.appointments.destroy', $appointment->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" style="border: none;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $appointments->links() }}
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3>No Appointments Found</h3>
        <p>You haven't created any appointments yet.</p>
        <a href="{{ route('staff.appointments.create') }}" class="btn btn-primary" style="margin-top: 20px;">
            <i class="fas fa-plus"></i> Create Your First Appointment
        </a>
    </div>
@endif
@endsection