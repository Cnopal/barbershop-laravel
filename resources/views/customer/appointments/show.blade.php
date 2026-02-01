@extends('customer.sidebar')


@section('content')
    <div class="appointment-details-page">
        <!-- Back Navigation -->
        <div class="back-nav">
            <a href="{{ route('customer.appointments.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Appointments
            </a>
        </div>

        <!-- Appointment Header -->
        <div class="appointment-header">
            <div class="header-content">
                <h1>Appointment Details</h1>
                <div class="appointment-id">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>

            <div class="header-actions">
                <span class="status-badge status-{{ $appointment->status }}">
                    {{ ucfirst($appointment->status) }}
                </span>

                @if(in_array($appointment->status, ['pending', 'confirmed']))
                    <div class="action-buttons">
                        <button class="btn btn-danger btn-small" id="cancelBtn">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Appointment Details Grid -->
        <div class="details-grid">
            <!-- Service Information -->
            <div class="detail-card">
                <div class="card-header">
                    <i class="fas fa-cut card-icon"></i>
                    <h3>Service Information</h3>
                </div>
                <div class="card-body">
                    <div class="detail-item">
                        <span class="detail-label">Service</span>
                        <span class="detail-value">{{ $appointment->service->name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Description</span>
                        <span class="detail-value">{{ $appointment->service->description ?? 'No description' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Price</span>
                        <span class="detail-value price">RM{{ number_format($appointment->price, 2) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value">
                            @php
                                $duration = $appointment->service->duration;
                                if ($duration >= 60) {
                                    $hours = floor($duration / 60);
                                    $minutes = $duration % 60;
                                    echo $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'm' : '');
                                } else {
                                    echo $duration . ' minutes';
                                }
                            @endphp
                        </span>
                    </div>
                </div>
            </div>

            <!-- Barber Information -->
            <div class="detail-card">
                <div class="card-header">
                    <i class="fas fa-user-tie card-icon"></i>
                    <h3>Barber Information</h3>
                </div>
                <div class="card-body">
                    <div class="barber-profile">
                        <div class="barber-avatar">
                            {{ strtoupper(substr($appointment->barber->name, 0, 2)) }}
                        </div>
                        <div class="barber-info">
                            <h4>{{ $appointment->barber->name }}</h4>
                            <p class="barber-position">{{ $appointment->barber->position ?? 'Senior Barber' }}</p>
                            @if($appointment->barber->phone)
                                <p class="barber-contact">
                                    <i class="fas fa-phone"></i> {{ $appointment->barber->phone }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="barber-stats">
                        <div class="stat">
                            <span class="stat-value">4.8</span>
                            <span class="stat-label">Rating</span>
                        </div>
                        <div class="stat">
                            <span class="stat-value">5+</span>
                            <span class="stat-label">Years Exp</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Schedule -->
            <div class="detail-card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt card-icon"></i>
                    <h3>Appointment Schedule</h3>
                </div>
                <div class="card-body">
                    <div class="schedule-item">
                        <div class="schedule-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="schedule-info">
                            <span class="schedule-label">Date</span>
                            <span class="schedule-value">{{ $appointment->appointment_date->format('l, F d, Y') }}</span>
                        </div>
                    </div>

                    <div class="schedule-item">
                        <div class="schedule-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="schedule-info">
                            <span class="schedule-label">Time</span>
                            <span class="schedule-value">
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} -
                                {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                            </span>
                        </div>
                    </div>

                    <div class="schedule-item">
                        <div class="schedule-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="schedule-info">
                            <span class="schedule-label">Time Until</span>
                            <span class="schedule-value" id="countdown">
                                {{ $appointment->appointment_date->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="detail-card">
                <div class="card-header">
                    <i class="fas fa-user card-icon"></i>
                    <h3>Your Information</h3>
                </div>
                <div class="card-body">
                    <div class="detail-item">
                        <span class="detail-label">Name</span>
                        <span class="detail-value">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">{{ Auth::user()->email }}</span>
                    </div>
                    @if(Auth::user()->phone)
                        <div class="detail-item">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value">{{ Auth::user()->phone }}</span>
                        </div>
                    @endif
                    <div class="detail-item">
                        <span class="detail-label">Booking Date</span>
                        <span class="detail-value">{{ $appointment->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        @if($appointment->notes)
            <div class="notes-section">
                <div class="section-header">
                    <h3><i class="fas fa-sticky-note"></i> Additional Notes</h3>
                </div>
                <div class="notes-content">
                    {{ $appointment->notes }}
                </div>
            </div>
        @endif

        <!-- Status Timeline -->
        <div class="timeline-section">
            <div class="section-header">
                <h3><i class="fas fa-history"></i> Appointment Status</h3>
            </div>
            <div class="timeline">
                <div class="timeline-item active">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>Appointment Created</h4>
                        <p>{{ $appointment->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                @if($appointment->status === 'confirmed')
                    <div class="timeline-item active">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Appointment Confirmed</h4>
                            <p>{{ $appointment->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                @endif

                @if($appointment->status === 'completed')
                    <div class="timeline-item active">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Service Completed</h4>
                            <p>Estimated: {{ $appointment->appointment_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                @endif

                @if($appointment->status === 'cancelled')
                    <div class="timeline-item cancelled">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Appointment Cancelled</h4>
                            <p>{{ $appointment->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                @else
                    <div class="timeline-item future">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Appointment Date</h4>
                            <p>{{ $appointment->appointment_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('customer.appointments.create') }}" class="btn btn-outline">
                <i class="fas fa-calendar-plus"></i> Book Another
            </a>
            <a href="{{ route('customer.appointments.index') }}" class="btn btn-primary">
                <i class="fas fa-list"></i> View All Appointments
            </a>
        </div>
    </div>

    <!-- Cancel Confirmation Modal -->
    <div class="modal" id="cancelModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Cancel Appointment</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p>Are you sure you want to cancel your appointment for "{{ $appointment->service->name }}" with
                    {{ $appointment->barber->name }}?
                </p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelCancel">No, Keep It</button>
                <form method="POST" action="{{ route('customer.appointments.cancel', $appointment->id) }}"
                    style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* CSS Variables */
        :root {
            --primary: #1a1f36;
            --secondary: #4a5568;
            --accent: #d4af37;
            --light: #f8f9fa;
            --dark: #121826;
            --light-gray: #f1f5f9;
            --medium-gray: #e2e8f0;
            --success: #48bb78;
            --warning: #ed8936;
            --danger: #f56565;
            --info: #4299e1;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --transition: all 0.3s ease;
        }

        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            line-height: 1.5;
            color: var(--primary);
            background-color: var(--light);
        }

        .appointment-details-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Back Navigation */
        .back-nav {
            margin-bottom: 2rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
        }

        .back-link:hover {
            color: var(--accent);
            background-color: var(--light-gray);
        }

        /* Appointment Header */
        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 1.5rem;
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--medium-gray);
        }

        .header-content h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .appointment-id {
            font-size: 0.875rem;
            color: var(--secondary);
            background: var(--light-gray);
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            display: inline-block;
        }

        .header-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 1rem;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.375rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: rgba(237, 137, 54, 0.1);
            color: var(--warning);
            border: 1px solid rgba(237, 137, 54, 0.2);
        }

        .status-confirmed {
            background: rgba(66, 153, 225, 0.1);
            color: var(--info);
            border: 1px solid rgba(66, 153, 225, 0.2);
        }

        .status-completed {
            background: rgba(72, 187, 120, 0.1);
            color: var(--success);
            border: 1px solid rgba(72, 187, 120, 0.2);
        }

        .status-cancelled {
            background: rgba(245, 101, 101, 0.1);
            color: var(--danger);
            border: 1px solid rgba(245, 101, 101, 0.2);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.75rem;
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: var(--radius);
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-size: 0.9375rem;
            font-family: inherit;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--accent);
            color: var(--primary);
        }

        .btn-primary:hover {
            background: #c19a2f;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #e53e3e;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-secondary {
            background: var(--light-gray);
            color: var(--secondary);
            border: 1px solid var(--medium-gray);
        }

        .btn-secondary:hover {
            background: var(--medium-gray);
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Detail Cards */
        .detail-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--medium-gray);
            transition: var(--transition);
            height: 100%;
        }

        .detail-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: var(--light-gray);
            border-bottom: 1px solid var(--medium-gray);
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--accent);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .card-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Detail Items */
        .detail-item {
            margin-bottom: 1rem;
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            display: block;
            font-size: 0.875rem;
            color: var(--secondary);
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .detail-value {
            font-weight: 500;
            color: var(--primary);
            display: block;
            line-height: 1.4;
        }

        .detail-value.price {
            color: var(--accent);
            font-size: 1.25rem;
            font-weight: 600;
        }

        /* Barber Profile */
        .barber-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .barber-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: bold;
            flex-shrink: 0;
        }

        .barber-info h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--primary);
        }

        .barber-position {
            color: var(--accent);
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .barber-contact {
            color: var(--secondary);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .barber-stats {
            display: flex;
            gap: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--medium-gray);
        }

        .stat {
            text-align: center;
            flex: 1;
        }

        .stat-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Schedule Items */
        .schedule-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .schedule-item:last-child {
            margin-bottom: 0;
        }

        .schedule-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--light-gray);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            flex-shrink: 0;
        }

        .schedule-label {
            display: block;
            font-size: 0.875rem;
            color: var(--secondary);
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .schedule-value {
            font-weight: 600;
            color: var(--primary);
            display: block;
        }

        /* Notes Section */
        .notes-section,
        .timeline-section {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            border: 1px solid var(--medium-gray);
        }

        .section-header {
            padding: 1.5rem;
            background: var(--light-gray);
            border-bottom: 1px solid var(--medium-gray);
        }

        .section-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .notes-content {
            padding: 1.5rem;
            color: var(--secondary);
            line-height: 1.6;
            white-space: pre-line;
        }

        /* Timeline */
        .timeline {
            padding: 1.5rem;
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 2rem;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: -2rem;
            width: 2px;
            background: var(--medium-gray);
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-dot {
            position: absolute;
            left: -5px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--medium-gray);
            z-index: 1;
        }

        .timeline-item.active .timeline-dot {
            background: var(--accent);
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.2);
        }

        .timeline-item.cancelled .timeline-dot {
            background: var(--danger);
            box-shadow: 0 0 0 4px rgba(245, 101, 101, 0.2);
        }

        .timeline-item.future .timeline-dot {
            background: var(--secondary);
        }

        .timeline-content h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }

        .timeline-content p {
            font-size: 0.875rem;
            color: var(--secondary);
            margin: 0;
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--medium-gray);
        }

        .text-muted {
            color: var(--secondary);
            opacity: 0.7;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: var(--radius);
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid var(--medium-gray);
        }

        .modal-header h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--secondary);
            cursor: pointer;
            transition: var(--transition);
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .modal-close:hover {
            background: var(--light-gray);
            color: var(--primary);
        }

        .modal-body {
            padding: 1.5rem;
            text-align: center;
        }

        .warning-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(245, 101, 101, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--danger);
            font-size: 2.25rem;
        }

        .modal-body p {
            color: var(--secondary);
            line-height: 1.6;
            margin-bottom: 0.5rem;
            font-size: 1.125rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem 1.5rem;
            border-top: 1px solid var(--medium-gray);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        /* Form Styles */
        form {
            display: inline;
            margin: 0;
        }

        form button[type="submit"] {
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .appointment-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
                align-items: stretch;
            }

            .action-buttons {
                flex-direction: column;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                flex-direction: column;
            }

            .modal-footer {
                flex-direction: column;
            }

            .modal-footer .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .barber-profile {
                flex-direction: column;
                text-align: center;
            }

            .barber-stats {
                justify-content: center;
            }

            .schedule-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .schedule-icon {
                align-self: flex-start;
            }
        }

        /* Countdown Animation */
        #countdown {
            font-weight: 600;
            color: var(--accent);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }

        /* Loading State */
        .btn.loading {
            position: relative;
            color: transparent;
        }

        .btn.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
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
            // Cancel appointment
            const cancelBtn = document.getElementById('cancelBtn');
            const cancelModal = document.getElementById('cancelModal');
            const modalClose = document.querySelector('.modal-close');
            const cancelCancel = document.getElementById('cancelCancel');

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function () {
                    cancelModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            }

            function closeModal() {
                cancelModal.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (modalClose) modalClose.addEventListener('click', closeModal);
            if (cancelCancel) cancelCancel.addEventListener('click', closeModal);

            window.addEventListener('click', function (e) {
                if (e.target === cancelModal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && cancelModal.classList.contains('active')) {
                    closeModal();
                }
            });

            // Countdown timer
            const countdownElement = document.getElementById('countdown');
            if (countdownElement) {
                function updateCountdown() {
                    const appointmentDate = new Date('{{ $appointment->appointment_date->format("Y-m-d") }} {{ $appointment->start_time }}');
                    const now = new Date();
                    const diff = appointmentDate - now;

                    if (diff > 0) {
                        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                        if (days > 0) {
                            countdownElement.textContent = `${days}d ${hours}h ${minutes}m`;
                        } else if (hours > 0) {
                            countdownElement.textContent = `${hours}h ${minutes}m`;
                        } else {
                            countdownElement.textContent = `${minutes}m`;
                        }
                    } else {
                        countdownElement.textContent = 'Appointment time has passed';
                    }
                }

                updateCountdown();
                setInterval(updateCountdown, 60000); // Update every minute
            }
        });
    </script>
@endsection