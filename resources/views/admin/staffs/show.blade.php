@extends('admin.sidebar')

@section('content')
    <div class="container">
        <!-- Page Header with Back Button -->
        <div class="page-header">
            <div class="header-left">
                <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary btn-small">
                    <i class="fas fa-arrow-left"></i> Back to Barbers
                </a>
            </div>
            <div class="header-center">
                <h1 class="page-title">Barber Details</h1>
            </div>
            <div class="header-right">
                <a href="{{ route('admin.staffs.edit', $staff->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Barber
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="barber-show-container">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar-large">
                        @if($staff->profile_image)
                            <img src="{{ Storage::url($staff->profile_image) }}" alt="{{ $staff->name }}">
                        @else
                            <div class="avatar-initials">{{ strtoupper(substr($staff->name, 0, 2)) }}</div>
                        @endif
                    </div>

                    <div class="profile-info">
                        <h2 class="profile-name">{{ $staff->name }}</h2>
                        <div class="profile-position">{{ $staff->position ?? 'Staff' }}</div>

                        <div class="profile-status">
                            <span class="status-badge {{ $staff->status === 'active' ? 'active' : 'inactive' }}">
                                <i class="fas fa-circle"></i>
                                {{ ucfirst($staff->status) }}
                            </span>

                            <span class="role-badge">
                                <i class="fas fa-user-tag"></i>
                                {{ ucfirst($staff->role) }}
                            </span>
                        </div>

                        <div class="profile-rating">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= ($staff->average_rating ?? 0) ? 'filled' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-text">({{ $staff->total_appointments ?? 0 }} appointments)</span>
                        </div>
                    </div>
                </div>

                <div class="profile-contact">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <span class="contact-label">Email</span>
                            <span class="contact-value">{{ $staff->email }}</span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <span class="contact-label">Phone</span>
                            <span class="contact-value">{{ $staff->phone ?? 'Not provided' }}</span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <span class="contact-label">Address</span>
                            <span class="contact-value">{{ $staff->address ?? 'Not provided' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats and Info Grid -->
            <div class="info-grid">
                <!-- Professional Info Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3><i class="fas fa-briefcase"></i> Professional Information</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="info-row">
                            <span class="info-label">Position</span>
                            <span class="info-value">{{ $staff->position ?? 'Staff' }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Role</span>
                            <span class="info-value badge role-badge">{{ ucfirst($staff->role) }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="status-badge {{ $staff->status === 'active' ? 'active' : 'inactive' }}">
                                    {{ ucfirst($staff->status) }}
                                </span>
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Member Since</span>
                            <span class="info-value">{{ $staff->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Last Updated</span>
                            <span class="info-value">{{ $staff->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3><i class="fas fa-chart-bar"></i> Statistics</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="stat-grid">
                            <div class="stat-item">
                                <div class="stat-icon appointments">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">{{ $staff->total_appointments ?? 0 }}</div>
                                    <div class="stat-label">Total Appointments</div>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-icon completed">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">{{ $staff->completed_appointments ?? 0 }}</div>
                                    <div class="stat-label">Completed</div>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-icon rating">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">{{ number_format($staff->average_rating ?? 0, 1) }}/5</div>
                                    <div class="stat-label">Avg Rating</div>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-icon revenue">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">RM{{ number_format($staff->total_revenue ?? 0, 2) }}</div>
                                    <div class="stat-label">Total Revenue</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="info-grid">
                <!-- Bio Card -->
                <div class="info-card full-width">
                    <div class="info-card-header">
                        <h3><i class="fas fa-info-circle"></i> About</h3>
                    </div>
                    <div class="info-card-body">
                        @if($staff->bio)
                            <div class="bio-content">{{ $staff->bio }}</div>
                        @else
                            <div class="no-bio">No bio provided</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Appointments -->
            <!-- In the Recent Appointments table section -->
            @if(isset($recentAppointments) && $recentAppointments->count() > 0)
                <div class="info-card full-width">
                    <div class="info-card-header">
                        <h3><i class="fas fa-history"></i> Recent Appointments</h3>
                        <a href="{{ route('admin.appointments.index', ['barber_id' => $staff->id]) }}"
                            class="btn btn-small btn-secondary">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="info-card-body">
                        <div class="appointments-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Service</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAppointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->customer->name ?? 'N/A' }}</td>
                                            <!-- Changed from user to customer -->
                                            <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                                            <td>
                                                {{ $appointment->appointment_date->format('M d, Y') }}
                                                @if($appointment->start_time)
                                                    at {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="appointment-status {{ $appointment->status }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td>RM{{ number_format($appointment->price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <!-- ... rest of the code -->
                <div class="info-card full-width">
                    <div class="info-card-header">
                        <h3><i class="fas fa-history"></i> Recent Appointments</h3>
                    </div>
                    <div class="info-card-body">
                        <div class="no-appointments">
                            <i class="fas fa-calendar-times"></i>
                            <p>No appointments found for this barber</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="button" class="btn btn-secondary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print Profile
                </button>

                <a href="{{ route('admin.staffs.edit', $staff->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Barber
                </a>

                <form action="{{ route('admin.staffs.destroy', $staff->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger delete-btn">
                        <i class="fas fa-trash"></i> Delete Barber
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-body">
                <div class="delete-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="delete-message">
                    <h3>Delete Barber</h3>
                    <p id="deleteMessage">Are you sure you want to delete {{ $staff->name }}? This action cannot be undone
                        and will delete all associated appointments.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelDelete">Cancel</button>
                <button class="btn btn-danger" id="confirmDelete">Delete Barber</button>
            </div>
        </div>
    </div>

    <style>
        /* CSS Variables */
        :root {
            --primary-color: #1a1f36;
            --secondary-color: #4a5568;
            --accent-color: #d4af37;
            --light-gray: #f7fafc;
            --medium-gray: #e2e8f0;
            --dark-gray: #718096;
            --success-color: #48bb78;
            --warning-color: #ed8936;
            --danger-color: #f56565;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
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
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        /* Button Styles */
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 15px;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--accent-color);
            color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #c19a2f;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--medium-gray);
        }

        .btn-secondary:hover {
            background-color: var(--light-gray);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #e53e3e;
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 14px;
        }

        /* Main Container */
        .barber-show-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        /* Profile Card */
        .profile-card {
            padding: 40px;
            border-bottom: 1px solid var(--medium-gray);
            background: linear-gradient(135deg, var(--light-gray) 0%, white 100%);
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            gap: 30px;
        }

        .profile-avatar-large {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid var(--accent-color);
            flex-shrink: 0;
            background-color: var(--accent-color);
        }

        .profile-avatar-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-initials {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 48px;
            font-weight: bold;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0 0 10px 0;
        }

        .profile-position {
            font-size: 20px;
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 15px;
        }

        .profile-status {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            align-items: center;
        }

        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-badge.active {
            background-color: rgba(72, 187, 120, 0.1);
            color: var(--success-color);
        }

        .status-badge.inactive {
            background-color: rgba(245, 101, 101, 0.1);
            color: var(--danger-color);
        }

        .role-badge {
            padding: 6px 16px;
            border-radius: 20px;
            background-color: rgba(66, 153, 225, 0.1);
            color: #4299e1;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .profile-rating {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stars {
            display: flex;
            gap: 2px;
        }

        .stars i {
            color: var(--medium-gray);
            font-size: 18px;
        }

        .stars i.filled {
            color: var(--warning-color);
        }

        .rating-text {
            color: var(--dark-gray);
            font-size: 14px;
        }

        /* Contact Info */
        .profile-contact {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid var(--medium-gray);
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .contact-item i {
            color: var(--accent-color);
            font-size: 20px;
            margin-top: 2px;
        }

        .contact-label {
            display: block;
            font-size: 12px;
            color: var(--dark-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .contact-value {
            display: block;
            font-size: 16px;
            color: var(--primary-color);
            font-weight: 500;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            padding: 40px;
        }

        @media (max-width: 992px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Info Cards */
        .info-card {
            background-color: white;
            border: 1px solid var(--medium-gray);
            border-radius: 8px;
            overflow: hidden;
        }

        .info-card.full-width {
            grid-column: 1 / -1;
        }

        .info-card-header {
            background-color: var(--light-gray);
            padding: 20px;
            border-bottom: 1px solid var(--medium-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-card-header h3 {
            margin: 0;
            font-size: 18px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card-header i {
            color: var(--accent-color);
        }

        .info-card-body {
            padding: 25px;
        }

        /* Info Rows */
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--dark-gray);
            font-weight: 500;
        }

        .info-value {
            color: var(--primary-color);
            font-weight: 600;
            text-align: right;
        }

        /* Statistics Grid */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background-color: var(--light-gray);
            border-radius: 8px;
            transition: var(--transition);
        }

        .stat-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-icon.appointments {
            background-color: rgba(72, 187, 120, 0.1);
            color: var(--success-color);
        }

        .stat-icon.completed {
            background-color: rgba(66, 153, 225, 0.1);
            color: #4299e1;
        }

        .stat-icon.rating {
            background-color: rgba(237, 137, 54, 0.1);
            color: var(--warning-color);
        }

        .stat-icon.revenue {
            background-color: rgba(212, 175, 55, 0.1);
            color: var(--accent-color);
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--dark-gray);
        }

        /* Bio Content */
        .bio-content {
            line-height: 1.6;
            color: var(--primary-color);
            font-size: 16px;
            white-space: pre-line;
        }

        .no-bio {
            color: var(--dark-gray);
            font-style: italic;
            text-align: center;
            padding: 20px;
        }

        /* Appointments Table */
        .appointments-table {
            overflow-x: auto;
        }

        .appointments-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .appointments-table th {
            text-align: left;
            padding: 12px 16px;
            background-color: var(--light-gray);
            color: var(--dark-gray);
            font-weight: 600;
            font-size: 14px;
            border-bottom: 1px solid var(--medium-gray);
        }

        .appointments-table td {
            padding: 16px;
            border-bottom: 1px solid var(--light-gray);
            color: var(--primary-color);
        }

        .appointments-table tr:hover {
            background-color: var(--light-gray);
        }

        .appointment-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .appointment-status.completed {
            background-color: rgba(72, 187, 120, 0.1);
            color: var(--success-color);
        }

        .appointment-status.confirmed {
            background-color: rgba(66, 153, 225, 0.1);
            color: #4299e1;
        }

        .appointment-status.pending {
            background-color: rgba(237, 137, 54, 0.1);
            color: var(--warning-color);
        }

        .appointment-status.cancelled {
            background-color: rgba(245, 101, 101, 0.1);
            color: var(--danger-color);
        }

        .no-appointments {
            text-align: center;
            padding: 40px 20px;
            color: var(--dark-gray);
        }

        .no-appointments i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .no-appointments p {
            margin: 0;
            font-size: 16px;
        }

        /* Action Buttons */
        .action-buttons {
            padding: 30px 40px;
            border-top: 1px solid var(--medium-gray);
            display: flex;
            justify-content: center;
            gap: 15px;
            background-color: var(--light-gray);
        }

        .action-buttons .delete-form {
            margin: 0;
        }

        /* Delete Confirmation Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 15px 25px 25px;
            border-top: 1px solid var(--medium-gray);
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .delete-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: rgba(245, 101, 101, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--danger-color);
            font-size: 36px;
        }

        .delete-message {
            text-align: center;
            margin-bottom: 25px;
        }

        .delete-message h3 {
            font-size: 22px;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .delete-message p {
            color: var(--dark-gray);
            line-height: 1.5;
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
            }

            .header-left,
            .header-center,
            .header-right {
                justify-content: flex-start;
            }

            .page-title {
                font-size: 24px;
                text-align: center;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .profile-avatar-large {
                width: 120px;
                height: 120px;
            }

            .profile-name {
                font-size: 28px;
            }

            .profile-contact {
                grid-template-columns: 1fr;
            }

            .info-grid {
                padding: 20px;
            }

            .stat-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
                padding: 20px;
            }

            .action-buttons .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .btn {
                padding: 10px 16px;
                font-size: 14px;
            }

            .profile-card {
                padding: 25px;
            }

            .info-card-body {
                padding: 20px;
            }
        }

        /* Print Styles */
        @media print {

            .page-header .btn,
            .action-buttons,
            .info-card-header .btn {
                display: none !important;
            }

            .barber-show-container {
                box-shadow: none;
            }

            .profile-card {
                background: none;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete confirmation
            const deleteBtn = document.querySelector('.delete-btn');
            const deleteModal = document.getElementById('deleteModal');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            const deleteMessage = document.getElementById('deleteMessage');

            if (deleteBtn) {
                deleteBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    deleteModal.classList.add('active');
                });
            }

            if (cancelDelete) {
                cancelDelete.addEventListener('click', function () {
                    deleteModal.classList.remove('active');
                });
            }

            if (confirmDelete) {
                confirmDelete.addEventListener('click', function () {
                    const form = deleteBtn.closest('.delete-form');
                    if (form) {
                        form.submit();
                    }
                    deleteModal.classList.remove('active');
                });
            }

            // Close modal when clicking outside
            window.addEventListener('click', function (e) {
                if (e.target === deleteModal) {
                    deleteModal.classList.remove('active');
                }
            });

            // Keyboard support for modal
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && deleteModal.classList.contains('active')) {
                    deleteModal.classList.remove('active');
                }
            });

            // Copy email to clipboard
            const emailValue = document.querySelector('.contact-value');
            if (emailValue) {
                emailValue.style.cursor = 'pointer';
                emailValue.title = 'Click to copy';
                emailValue.addEventListener('click', function () {
                    const email = this.textContent;
                    navigator.clipboard.writeText(email).then(function () {
                        showToast('Email copied to clipboard!');
                    });
                });
            }

            // Toast notification function
            function showToast(message, type = 'success') {
                // Remove existing toast
                const existingToast = document.querySelector('.toast');
                if (existingToast) existingToast.remove();

                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                const iconColor = type === 'success' ? 'var(--success-color)' : 'var(--danger-color)';

                const toast = document.createElement('div');
                toast.className = 'toast';
                toast.innerHTML = `
                <i class="fas ${icon}" style="color: ${iconColor};"></i>
                <span>${message}</span>
            `;

                document.body.appendChild(toast);

                // Remove after 3 seconds
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.style.animation = 'slideOut 0.3s ease';
                        setTimeout(() => toast.remove(), 300);
                    }
                }, 3000);
            }
        });
    </script>
@endsection