@extends('staff.sidebar')

@section('page-title', 'Appointment Details')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <a href="{{ route('staff.appointments.index') }}" class="btn btn-secondary btn-small">
                <i class="fas fa-arrow-left"></i> Back to Appointments
            </a>
        </div>
        <div class="header-center">
            <h1 class="page-title">Appointment Details</h1>
        </div>
        <div class="header-right">
            <div class="appointment-id">
                <span class="id-badge">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-grid">
        <!-- Appointment Details Card -->
        <div class="details-card">
            <div class="card-header">
                <h3><i class="fas fa-calendar-alt"></i> Appointment Information</h3>
                <div class="status-badge status-{{ $appointment->status }}">
                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                </div>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <!-- Customer Information -->
                    <div class="info-section">
                        <h4><i class="fas fa-user"></i> Customer Details</h4>
                        <div class="info-item">
                            <span class="info-label">Name:</span>
                            <span class="info-value">{{ $appointment->customer->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $appointment->customer->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone:</span>
                            <span class="info-value">{{ $appointment->customer->phone ?? 'Not provided' }}</span>
                        </div>
                        @if($appointment->customer->address)
                            <div class="info-item">
                                <span class="info-label">Address:</span>
                                <span class="info-value">{{ $appointment->customer->address }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Service Information -->
                    <div class="info-section">
                        <h4><i class="fas fa-cut"></i> Service Details</h4>
                        <div class="info-item">
                            <span class="info-label">Service:</span>
                            <span class="info-value">{{ $appointment->service->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Duration:</span>
                            <span class="info-value">{{ $appointment->service->duration }} minutes</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Price:</span>
                            <span class="info-value price">RM{{ number_format($appointment->service->price, 2) }}</span>
                        </div>
                        @if($appointment->service->description)
                            <div class="info-item">
                                <span class="info-label">Description:</span>
                                <span class="info-value">{{ $appointment->service->description }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Time Information -->
                    <div class="info-section">
                        <h4><i class="fas fa-clock"></i> Time Details</h4>
                        <div class="info-item">
                            <span class="info-label">Date:</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y (l)') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Time:</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Duration:</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($appointment->start_time)->diffInMinutes(\Carbon\Carbon::parse($appointment->end_time)) }} minutes</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Created:</span>
                            <span class="info-value">{{ $appointment->created_at->diffForHumans() }}</span>
                        </div>
                        @if($appointment->updated_at != $appointment->created_at)
                            <div class="info-item">
                                <span class="info-label">Last Updated:</span>
                                <span class="info-value">{{ $appointment->updated_at->diffForHumans() }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Payment Information -->
                    <div class="info-section">
                        <h4><i class="fas fa-dollar-sign"></i> Payment Information</h4>
                        <div class="info-item">
                            <span class="info-label">Price:</span>
                            <span class="info-value price">RM{{ number_format($appointment->price, 2) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value">Pending</span>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                @if($appointment->notes)
                    <div class="notes-section">
                        <h4><i class="fas fa-sticky-note"></i> Additional Notes</h4>
                        <div class="notes-content">
                            {{ $appointment->notes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="sidebar-action">
            <!-- Actions Card -->
            <div class="actions-card">
                <div class="card-header">
                    <h3><i class="fas fa-cogs"></i> Actions</h3>
                </div>
                <div class="card-body">
                    <div class="action-buttons">
                        <!-- Status Update Buttons -->
                        @if($appointment->status == 'pending_payment')
                            <form action="{{ route('staff.appointments.update', $appointment->id) }}" method="POST" class="action-form">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <input type="hidden" name="customer_id" value="{{ $appointment->customer_id }}">
                                <input type="hidden" name="service_id" value="{{ $appointment->service_id }}">
                                <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date }}">
                                <input type="hidden" name="start_time" value="{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}">
                                <input type="hidden" name="notes" value="{{ $appointment->notes ?? '' }}">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-check-circle"></i> Confirm Appointment
                                </button>
                            </form>
                        @endif

                        @if($appointment->status == 'confirmed')
                            <form action="{{ route('staff.appointments.update', $appointment->id) }}" method="POST" class="action-form">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <input type="hidden" name="customer_id" value="{{ $appointment->customer_id }}">
                                <input type="hidden" name="service_id" value="{{ $appointment->service_id }}">
                                <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date }}">
                                <input type="hidden" name="start_time" value="{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}">
                                <input type="hidden" name="notes" value="{{ $appointment->notes ?? '' }}">
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="fas fa-check-double"></i> Mark as Completed
                                </button>
                            </form>
                        @endif

                        <!-- Cancel Button -->
                        @if(in_array($appointment->status, ['pending_payment', 'confirmed']))
                            <form action="{{ route('staff.appointments.update', $appointment->id) }}" method="POST" class="action-form" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <input type="hidden" name="customer_id" value="{{ $appointment->customer_id }}">
                                <input type="hidden" name="service_id" value="{{ $appointment->service_id }}">
                                <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date }}">
                                <input type="hidden" name="start_time" value="{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}">
                                <input type="hidden" name="notes" value="{{ $appointment->notes ?? '' }}">
                                <button type="submit" class="btn btn-warning btn-block" onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                    <i class="fas fa-times-circle"></i> Cancel Appointment
                                </button>
                            </form>
                        @endif

                        <!-- Edit Button -->
                        @if(!in_array($appointment->status, ['completed', 'cancelled']))
                            <a href="{{ route('staff.appointments.edit', $appointment->id) }}" class="btn btn-primary btn-block">
                                <i class="fas fa-edit"></i> Edit Appointment
                            </a>
                        @endif

                        <!-- Delete Button -->
                        <form action="{{ route('staff.appointments.destroy', $appointment->id) }}" method="POST" class="action-form" onsubmit="return confirm('Are you sure you want to delete this appointment? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> Delete Appointment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick Info Card -->
            <div class="info-card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Quick Info</h3>
                </div>
                <div class="card-body">
                    <div class="quick-info">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Days Until</div>
                                <div class="info-value" id="daysUntil">
                                    @php
                                        $appointmentDate = \Carbon\Carbon::parse($appointment->appointment_date);
                                        $today = \Carbon\Carbon::today();
                                        $daysUntil = $today->diffInDays($appointmentDate, false);

                                        if ($daysUntil < 0) {
                                            echo abs($daysUntil) . ' day' . (abs($daysUntil) != 1 ? 's' : '') . ' ago';
                                        } elseif ($daysUntil == 0) {
                                            echo 'Today';
                                        } else {
                                            echo $daysUntil . ' day' . ($daysUntil != 1 ? 's' : '') . ' from now';
                                        }
                                    @endphp
                                </div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Time Slot</div>
                                <div class="info-value">
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                                </div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Duration</div>
                                <div class="info-value">{{ $appointment->service->duration }} min</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Total Cost</div>
                                <div class="info-value">RM{{ number_format($appointment->service->price, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS Variables */
    :root {
        --primary-color: #1a1f36;
        --secondary-color: #4a5568;
        --accent-color: #d4af37;
        --accent-light: #f8f3e6;
        --light-gray: #f8fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #718096;
        --success-color: #48bb78;
        --warning-color: #ed8936;
        --danger-color: #f56565;
        --info-color: #4299e1;
        --completed-color: #805ad5;
        --cancelled-color: #a0aec0;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --hover-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        --border-radius: 12px;
        --border-radius-sm: 8px;
    }

    /* Container */
    .container {
        max-width: auto;
        margin: 0 auto;
        padding: 30px;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding: 20px 0;
        border-bottom: 2px solid var(--accent-light);
    }

    .header-left, .header-center, .header-right {
        display: flex;
        align-items: center;
    }

    .header-center {
        flex: 1;
        justify-content: center;
    }

    .page-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary-color);
        margin: 0;
    }

    .appointment-id {
        display: flex;
        align-items: center;
    }

    .id-badge {
        background: linear-gradient(135deg, var(--accent-color), #f7d794);
        color: var(--primary-color);
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 14px;
    }

    /* Buttons */
    .btn {
        padding: 12px 28px;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 15px;
        text-decoration: none;
    }

    .btn-secondary {
        background: white;
        color: var(--primary-color);
        border: 2px solid var(--medium-gray);
    }

    .btn-secondary:hover {
        background: var(--light-gray);
        border-color: var(--accent-color);
    }

    .btn-small {
        padding: 8px 20px;
        font-size: 14px;
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Cards */
    .details-card, .actions-card, .info-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        overflow: hidden;
        border: 1px solid var(--medium-gray);
        margin-bottom: 30px;
    }

    .card-header {
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
        padding: 24px;
        border-bottom: 1px solid var(--medium-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-color);
    }

    .card-header h3 {
        margin: 0;
        font-size: 18px;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header i {
        color: var(--accent-color);
        font-size: 20px;
    }

    .card-body {
        padding: 30px;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-section {
        background: var(--light-gray);
        padding: 20px;
        border-radius: var(--border-radius-sm);
    }

    .info-section h4 {
        margin: 0 0 15px 0;
        font-size: 14px;
        font-weight: 700;
        color: var(--secondary-color);
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-section i {
        color: var(--accent-color);
    }

    .info-item {
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .info-item:last-child {
        margin-bottom: 0;
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: var(--secondary-color);
        font-size: 12px;
        text-transform: uppercase;
        flex: 0 0 40%;
    }

    .info-value {
        font-weight: 500;
        color: var(--primary-color);
        text-align: right;
        flex: 1;
    }

    .info-value.price {
        font-weight: 700;
        color: var(--accent-color);
        font-size: 16px;
    }

    /* Status Badge */
    .status-badge {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.status-pending_payment {
        background: #fed7d7;
        color: #742a2a;
    }

    .status-badge.status-confirmed {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-badge.status-completed {
        background: #bee3f8;
        color: #2c5282;
    }

    .status-badge.status-cancelled {
        background: #f5e6e8;
        color: #6b2c2c;
    }

    /* Notes Section */
    .notes-section {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid var(--light-gray);
    }

    .notes-section h4 {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0 0 15px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .notes-section i {
        color: var(--accent-color);
    }

    .notes-content {
        background: var(--light-gray);
        padding: 20px;
        border-radius: var(--border-radius-sm);
        border-left: 4px solid var(--accent-color);
        color: var(--primary-color);
        line-height: 1.6;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .action-form {
        width: 100%;
    }

    .btn-block {
        width: 100%;
        justify-content: center;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-color), #38a169);
        color: white;
        box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(72, 187, 120, 0.4);
    }

    .btn-info {
        background: linear-gradient(135deg, var(--info-color), #3182ce);
        color: white;
        box-shadow: 0 4px 15px rgba(66, 153, 225, 0.3);
    }

    .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(66, 153, 225, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--warning-color), #dd6b20);
        color: white;
        box-shadow: 0 4px 15px rgba(237, 137, 54, 0.3);
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(237, 137, 54, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color), #e53e3e);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 101, 101, 0.4);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent-color), #e6c158);
        color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
    }

    /* Quick Info */
    .quick-info {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .quick-info .info-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: var(--light-gray);
        border-radius: var(--border-radius-sm);
        border: none;
        margin: 0;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-color), #f7d794);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 20px;
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
    }

    .info-content .info-label {
        margin: 0;
        border: none;
        text-transform: uppercase;
        font-size: 11px;
    }

    .info-content .info-value {
        margin-top: 5px;
        font-size: 18px;
        font-weight: 700;
    }

    .sidebar-action {
        display: flex;
        flex-direction: column;
    }
</style>
@endsection
