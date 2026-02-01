@extends('staff.sidebar')

@section('page-title', 'Appointments')

@section('content')
<style>
    :root {
        --primary: #1a1f36;
        --secondary: #4a5568;
        --accent: #d4af37;
        --light-gray: #f7fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #718096;
        --success: #48bb78;
        --warning: #ed8936;
        --danger: #f56565;
        --info: #4299e1;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --transition: all 0.3s ease;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        gap: 1rem;
    }

    .page-header h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary);
        margin: 0;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.9375rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
        color: var(--primary);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
    }

    .appointments-container {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .appointments-table {
        width: 100%;
        border-collapse: collapse;
    }

    .appointments-table thead {
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
    }

    .appointments-table th {
        padding: 1.25rem;
        text-align: left;
        font-weight: 600;
        color: var(--primary);
        border-bottom: 2px solid var(--medium-gray);
        font-size: 0.875rem;
    }

    .appointments-table td {
        padding: 1.125rem 1.25rem;
        border-bottom: 1px solid var(--medium-gray);
        color: var(--primary);
    }

    .appointments-table tbody tr:hover {
        background-color: var(--light-gray);
    }

    .appointment-customer {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .appointment-meta {
        font-size: 0.875rem;
        color: var(--secondary);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: rgba(237, 137, 54, 0.1);
        color: #c05621;
    }

    .status-confirmed {
        background: rgba(72, 187, 120, 0.1);
        color: #22543d;
    }

    .status-completed {
        background: rgba(66, 153, 225, 0.1);
        color: #2c5282;
    }

    .status-cancelled {
        background: rgba(245, 101, 101, 0.1);
        color: #742a2a;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }

    .btn-info {
        background: rgba(66, 153, 225, 0.1);
        color: #2c5282;
    }

    .btn-info:hover {
        background: rgba(66, 153, 225, 0.2);
    }

    .btn-edit {
        background: rgba(237, 137, 54, 0.1);
        color: #c05621;
    }

    .btn-edit:hover {
        background: rgba(237, 137, 54, 0.2);
    }

    .btn-danger {
        background: rgba(245, 101, 101, 0.1);
        color: #742a2a;
    }

    .btn-danger:hover {
        background: rgba(245, 101, 101, 0.2);
    }

    .empty-state {
        background: white;
        border-radius: 12px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: var(--card-shadow);
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--accent);
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--secondary);
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .appointments-table {
            font-size: 0.875rem;
        }

        .appointments-table th,
        .appointments-table td {
            padding: 0.75rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-sm {
            width: 100%;
        }
    }
</style>

<div class="page-header">
    <h2>My Appointments</h2>
    <a href="{{ route('staff.appointments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Appointment
    </a>
</div>

@if($appointments->count() > 0)
    <div class="appointments-container">
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
                            <div class="appointment-meta">{{ $appointment->customer->email }}</div>
                        </td>
                        <td>{{ $appointment->service->name }}</td>
                        <td>
                            <div class="appointment-customer">{{ $appointment->appointment_date->format('d M Y') }}</div>
                            <div class="appointment-meta">{{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $appointment->end_time)->format('h:i A') }}</div>
                        </td>
                        <td>RM {{ number_format($appointment->price, 2) }}</td>
                        <td><span class="status-badge status-{{ strtolower($appointment->status) }}">{{ ucfirst($appointment->status) }}</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('staff.appointments.destroy', $appointment->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $appointments->links() }}
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3>No Appointments Found</h3>
        <p>You haven't created any appointments yet.</p>
        <a href="{{ route('staff.appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Your First Appointment
        </a>
    </div>
@endif
@endsection