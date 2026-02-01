@extends('admin.sidebar')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-left">
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary btn-small">
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
                        {{ ucfirst($appointment->status) }}
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

                        <!-- Barber Information -->
                        <div class="info-section">
                            <h4><i class="fas fa-user-tie"></i> Barber Details</h4>
                            <div class="info-item">
                                <span class="info-label">Barber:</span>
                                <span class="info-value">{{ $appointment->barber->name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Position:</span>
                                <span class="info-value">{{ $appointment->barber->position }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status:</span>
                                <span class="info-value status-indicator status-{{ $appointment->barber->status }}">
                                    {{ ucfirst($appointment->barber->status) }}
                                </span>
                            </div>
                            @if($appointment->barber->bio)
                                <div class="info-item">
                                    <span class="info-label">Bio:</span>
                                    <span class="info-value">{{ $appointment->barber->bio }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Time Information -->
                        <div class="info-section">
                            <h4><i class="fas fa-clock"></i> Time Details</h4>
                            <div class="info-item">
                                <span class="info-label">Date:</span>
                                <span
                                    class="info-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y (l)') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Time:</span>
                                <span
                                    class="info-value">{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                    - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Duration:</span>
                                <span
                                    class="info-value">{{ \Carbon\Carbon::parse($appointment->start_time)->diffInMinutes(\Carbon\Carbon::parse($appointment->end_time)) }}
                                    minutes</span>
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

            <!-- Sidebar Actions & Timeline -->
            <div class="#">
                <!-- Actions Card -->
                <div class="actions-card">
                    <div class="card-header">
                        <h3><i class="fas fa-cogs"></i> Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="action-buttons">
                            <!-- Edit Button -->
                            

                            <!-- Status Update Buttons -->
                            @if($appointment->status == 'pending_payment')
                                <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST"
                                    class="action-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <input type="hidden" name="customer_id" value="{{ $appointment->customer_id }}">
                                    <input type="hidden" name="barber_id" value="{{ $appointment->barber_id }}">
                                    <input type="hidden" name="service_id" value="{{ $appointment->service_id }}">
                                    <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date }}">
                                    <input type="hidden" name="start_time"
                                        value="{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}">
                                    <input type="hidden" name="notes" value="{{ $appointment->notes ?? '' }}">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-check-circle"></i> Confirm Appointment
                                    </button>
                                </form>
                            @endif

                            @if($appointment->status == 'confirmed')
                                <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST"
                                    class="action-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <input type="hidden" name="customer_id" value="{{ $appointment->customer_id }}">
                                    <input type="hidden" name="barber_id" value="{{ $appointment->barber_id }}">
                                    <input type="hidden" name="service_id" value="{{ $appointment->service_id }}">
                                    <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date }}">
                                    <input type="hidden" name="start_time"
                                        value="{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}">
                                    <input type="hidden" name="notes" value="{{ $appointment->notes ?? '' }}">
                                    <button type="submit" class="btn btn-info btn-block">
                                        <i class="fas fa-check-double"></i> Mark as Completed
                                    </button>
                                </form>
                            @endif

                            <!-- Cancel Button -->
                            @if(in_array($appointment->status, ['pending', 'confirmed']))
                                <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST"
                                    class="action-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <input type="hidden" name="customer_id" value="{{ $appointment->customer_id }}">
                                    <input type="hidden" name="barber_id" value="{{ $appointment->barber_id }}">
                                    <input type="hidden" name="service_id" value="{{ $appointment->service_id }}">
                                    <input type="hidden" name="appointment_date" value="{{ $appointment->appointment_date }}">
                                    <input type="hidden" name="start_time"
                                        value="{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}">
                                    <input type="hidden" name="notes" value="{{ $appointment->notes ?? '' }}">
                                    <button type="submit" class="btn btn-warning btn-block"
                                        onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                        <i class="fas fa-times-circle"></i> Cancel Appointment
                                    </button>
                                </form>
                            @endif

                            <!-- Delete Button -->
                            <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST"
                                class="action-form"
                                onsubmit="return confirm('Are you sure you want to delete this appointment? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash"></i> Delete Appointment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="timeline-card">
                    <div class="card-header">
                        <h3><i class="fas fa-history"></i> Status History</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <!-- Created -->
                            <div class="timeline-item status-{{ strtolower($appointment->status) }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-date">
                                        {{ \Carbon\Carbon::parse($appointment->created_at)
        ->timezone('Asia/Kuala_Lumpur')
        ->format('d/m/Y h:i A') }}
                                    </div>


                                    <div class="timeline-title">Appointment Created</div>
                                    <div class="timeline-description">Appointment was created
                                    </div>
                                </div>
                            </div>

                            <!-- Last Updated -->
                            @if($appointment->updated_at != $appointment->created_at)
                                                <div class="timeline-item">
                                                    <div class="timeline-marker">
                                                        <i class="fas fa-edit"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <div class="timeline-date">
                                                            {{ \Carbon\Carbon::parse($appointment->updated_at)
                                ->timezone('Asia/Kuala_Lumpur')
                                ->format('d/m/Y h:i A') }}
                                                        </div>


                                                        <div class="timeline-title">Last Updated</div>
                                                        <div class="timeline-description">Appointment details were updated to
                                                            {{ $appointment->status }}</div>
                                                    </div>
                                                </div>
                            @endif
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

    <!-- Toast notification container -->
    <div id="toastContainer" style="position: fixed; bottom: 30px; right: 30px; z-index: 1100;"></div>

    <style>
        /* CSS Variables - Only include what's needed for this page */
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

        /* Page Header - Enhanced */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 2px solid var(--accent-light);
        }

        .header-left,
        .header-center,
        .header-right {
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
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), #f7d794);
            border-radius: 2px;
        }

        /* ID Badge */
        .id-badge {
            background: linear-gradient(135deg, var(--accent-color) 0%, #e6c158 100%);
            color: var(--primary-color);
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 1px;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
        }

        /* Button Styles */
        .btn {
            padding: 12px 24px;
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 14px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color) 0%, #e6c158 100%);
            color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
        }

        .btn-secondary {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--medium-gray);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .btn-secondary:hover {
            background: var(--light-gray);
            border-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #38a169 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info-color) 0%, #3182ce 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(66, 153, 225, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #dd6b20 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(237, 137, 54, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #e53e3e 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
        }

        .btn-small {
            padding: 8px 20px;
            font-size: 13px;
            border-radius: 6px;
        }

        .btn-block {
            width: 100%;
            margin-bottom: 12px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Cards */
        .details-card,
        .actions-card,
        .timeline-card,
        .info-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border: 1px solid var(--medium-gray);
            margin-bottom: 30px;
            transition: var(--transition);
        }

        .details-card:hover,
        .actions-card:hover,
        .timeline-card:hover,
        .info-card:hover {
            box-shadow: var(--hover-shadow);
            border-color: var(--accent-color);
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
            font-weight: 600;
        }

        .card-header i {
            color: var(--accent-color);
            font-size: 18px;
            background: var(--accent-light);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-body {
            padding: 30px;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .status-pending {
            background: linear-gradient(135deg, var(--warning-color) 0%, #ed8936 100%);
            color: white;
        }

        .status-confirmed {
            background: linear-gradient(135deg, var(--info-color) 0%, #4299e1 100%);
            color: white;
        }

        .status-completed {
            background: linear-gradient(135deg, var(--completed-color) 0%, #6b46c1 100%);
            color: white;
        }

        .status-cancelled {
            background: linear-gradient(135deg, var(--cancelled-color) 0%, #718096 100%);
            color: white;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-section {
            background: var(--light-gray);
            border-radius: var(--border-radius-sm);
            padding: 24px;
            border: 1px solid var(--medium-gray);
        }

        .info-section h4 {
            margin-bottom: 20px;
            color: var(--primary-color);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--accent-light);
        }

        .info-section h4 i {
            color: var(--accent-color);
            font-size: 16px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 14px;
            min-width: 120px;
        }

        .info-value {
            flex: 1;
            text-align: right;
            color: var(--primary-color);
            font-weight: 500;
            font-size: 14px;
        }

        .info-value.price {
            color: var(--accent-color);
            font-weight: 700;
            font-size: 16px;
        }

        .status-indicator {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        /* Notes Section */
        .notes-section {
            background: linear-gradient(135deg, var(--accent-light) 0%, #f8f3e6 100%);
            border-radius: var(--border-radius-sm);
            padding: 24px;
            border: 1px solid var(--accent-color);
            position: relative;
        }

        .notes-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-color);
        }

        .notes-section h4 {
            margin-bottom: 16px;
            color: var(--primary-color);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notes-section h4 i {
            color: var(--accent-color);
        }

        .notes-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--medium-gray);
            line-height: 1.8;
            color: var(--secondary-color);
            white-space: pre-wrap;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .action-form {
            margin: 0;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 24px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--medium-gray);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 24px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -24px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .timeline-marker i {
            color: var(--accent-color);
            font-size: 8px;
        }

        .timeline-item.status-pending .timeline-marker {
            border-color: var(--warning-color);
        }

        .timeline-item.status-confirmed .timeline-marker {
            border-color: var(--info-color);
        }

        .timeline-item.status-completed .timeline-marker {
            border-color: var(--completed-color);
        }

        .timeline-item.status-cancelled .timeline-marker {
            border-color: var(--cancelled-color);
        }

        .timeline-content {
            background: var(--light-gray);
            padding: 16px;
            border-radius: 8px;
            border: 1px solid var(--medium-gray);
        }

        .timeline-date {
            font-size: 12px;
            color: var(--dark-gray);
            margin-bottom: 4px;
        }

        .timeline-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 4px;
            font-size: 14px;
        }

        .timeline-description {
            font-size: 13px;
            color: var(--secondary-color);
        }

        /* Quick Info */
        .quick-info {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .quick-info .info-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 0;
            margin: 0;
            border: none;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent-light) 0%, #f8f3e6 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-icon i {
            color: var(--accent-color);
            font-size: 18px;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 12px;
            color: var(--dark-gray);
            margin-bottom: 2px;
            min-width: auto;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-color);
            text-align: left;
        }

        /* Toast Notifications */
        .toast {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2d3748 100%);
            color: white;
            padding: 20px 28px;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 16px;
            animation: toastSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            max-width: 450px;
            border-left: 4px solid var(--accent-color);
            backdrop-filter: blur(10px);
            margin-bottom: 15px;
        }

        @keyframes toastSlideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes toastSlideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
                padding-bottom: 15px;
            }

            .page-title {
                font-size: 26px;
                text-align: center;
            }

            .page-title::after {
                width: 40px;
                height: 3px;
            }

            .card-body {
                padding: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .info-section {
                padding: 20px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 13px;
            }

            .id-badge {
                font-size: 14px;
                padding: 6px 16px;
            }
        }

        @media (max-width: 576px) {
            .content-grid {
                gap: 20px;
            }

            .page-title {
                font-size: 22px;
            }

            .card-header h3 {
                font-size: 16px;
            }

            .card-header i {
                width: 28px;
                height: 28px;
                font-size: 14px;
            }

            .info-label,
            .info-value {
                font-size: 13px;
            }

            .timeline-content {
                padding: 12px;
            }
        }

        /* Loading States */
        .loading {
            opacity: 0.7;
            pointer-events: none;
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            border: 2px solid var(--accent-color);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toast notification function
            function showToast(message, type = 'success') {
                const toastContainer = document.getElementById('toastContainer');

                // Remove existing toast
                const existingToast = document.querySelector('.toast');
                if (existingToast) {
                    existingToast.style.animation = 'toastSlideOut 0.3s ease';
                    setTimeout(() => existingToast.remove(), 300);
                }

                const icon = type === 'success' ? 'fa-check-circle' :
                    type === 'error' ? 'fa-exclamation-circle' :
                        type === 'warning' ? 'fa-exclamation-triangle' :
                            'fa-info-circle';

                const iconColor = type === 'success' ? 'var(--accent-color)' :
                    type === 'error' ? 'var(--danger-color)' :
                        type === 'warning' ? 'var(--warning-color)' :
                            'var(--info-color)';

                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.innerHTML = `
                    <i class="fas ${icon}" style="color: ${iconColor};"></i>
                    <span>${message}</span>
                `;

                toastContainer.appendChild(toast);

                // Auto-remove after 5 seconds
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.style.animation = 'toastSlideOut 0.3s ease';
                        setTimeout(() => toast.remove(), 300);
                    }
                }, 5000);
            }

            // Show success message if redirected from edit/status change
            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif

            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif

            @if(session('warning'))
                showToast("{{ session('warning') }}", 'warning');
            @endif

            // Add loading states to form submissions
            const forms = document.querySelectorAll('.action-form');
            forms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                        submitBtn.disabled = true;

                        // Re-enable button after 10 seconds if something goes wrong
                        setTimeout(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 10000);
                    }
                });
            });

            // Update "Days Until" in real-time
            function updateDaysUntil() {
                const daysUntilEl = document.getElementById('daysUntil');
                if (!daysUntilEl) return;

                const appointmentDate = new Date('{{ $appointment->appointment_date }}');
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const diffTime = appointmentDate.getTime() - today.getTime();
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                let text = '';
                if (diffDays < 0) {
                    const daysAgo = Math.abs(diffDays);
                    text = `${daysAgo} day${daysAgo !== 1 ? 's' : ''} ago`;
                    daysUntilEl.style.color = 'var(--danger-color)';
                } else if (diffDays === 0) {
                    text = 'Today';
                    daysUntilEl.style.color = 'var(--accent-color)';
                } else {
                    text = `${diffDays} day${diffDays !== 1 ? 's' : ''} from now`;
                    daysUntilEl.style.color = 'var(--success-color)';
                }

                daysUntilEl.textContent = text;
            }

            // Update days until on page load
            updateDaysUntil();

            // Update every minute (for "Today" status updates)
            setInterval(updateDaysUntil, 60000);
        });
    </script>
@endsection