@extends('admin.sidebar')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Appointments</h1>
            <div class="header-actions">
                <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Appointment
                </a>
            </div>
        </div>

        <!-- Calendar View Toggle -->
        <div class="view-toggle">
            <button class="btn btn-secondary active" id="listViewBtn">
                <i class="fas fa-list"></i> List View
            </button>
            <button class="btn btn-secondary" id="calendarViewBtn">
                <i class="fas fa-calendar"></i> Calendar View
            </button>
        </div>

        <!-- Control Bar -->
        <div class="control-bar">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Search appointments...">
            </div>

            <div class="filter-controls">
                <select class="filter-select" id="statusFilter">
                    <option value="all">All Status</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <select class="filter-select" id="barberFilter">
                    <option value="all">All Barbers</option>
                    @foreach($barbers as $barber)
                        <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                    @endforeach
                </select>

                <input type="date" class="filter-select" id="dateFilter" placeholder="Select Date">
            </div>
        </div>

        <!-- Appointments List View -->
        <div class="appointments-container" id="listView">
            <div class="appointments-table">
                <div class="table-header">
                    <div class="header-cell">Customer</div>
                    <div class="header-cell">Barber</div>
                    <div class="header-cell">Service</div>
                    <div class="header-cell">Date & Time</div>
                    <div class="header-cell">Price</div>
                    <div class="header-cell">Status</div>
                    <div class="header-cell">Actions</div>
                </div>

                <div class="table-body" id="appointmentsTableBody">
                    @forelse($appointments as $appointment)
                        @php
                            $statusClass = '';
                            switch ($appointment->status) {
                                case 'confirmed':
                                    $statusClass = 'status-confirmed';
                                    break;
                                case 'completed':
                                    $statusClass = 'status-completed';
                                    break;
                                case 'cancelled':
                                    $statusClass = 'status-cancelled';
                                    break;
                                default:
                                    $statusClass = 'status-pending';
                            }
                        @endphp

                        <div class="table-row" data-id="{{ $appointment->id }}"
                            data-customer="{{ strtolower($appointment->customer->name) }}"
                            data-barber="{{ strtolower($appointment->barber->name) }}"
                            data-service="{{ strtolower($appointment->service->name) }}"
                            data-date="{{ $appointment->appointment_date->format('Y-m-d') }}"
                            data-status="{{ $appointment->status }}" data-barber-id="{{ $appointment->barber_id }}">
                            <div class="table-cell">
                                <div class="customer-info">
                                    <div class="customer-name">{{ $appointment->customer->name }}</div>
                                    <div class="customer-email">{{ $appointment->customer->email }}</div>
                                </div>
                            </div>
                            <div class="table-cell">
                                <div class="barber-info">
                                    <div class="barber-name">{{ $appointment->barber->name }}</div>
                                    <div class="barber-position">{{ $appointment->barber->position }}</div>
                                </div>
                            </div>
                            <div class="table-cell">
                                <div class="service-info">
                                    <div class="service-name">{{ $appointment->service->name }}</div>
                                    <div class="service-duration">{{ $appointment->service->duration }} min</div>
                                </div>
                            </div>
                            <div class="table-cell">
                                <div class="datetime-info">
                                    <div class="appointment-date">
                                        {{ $appointment->appointment_date->format('M d, Y') }}
                                    </div>
                                    <div class="appointment-time">
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                                    </div>
                                </div>
                            </div>
                            <div class="table-cell">
                                <div class="price-info">
                                    <span class="price">RM{{ number_format($appointment->price, 2) }}</span>
                                </div>
                            </div>
                            <div class="table-cell">
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                            <div class="table-cell">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                                        class="btn-action view-btn" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <!-- <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                                        class="btn-action edit-btn" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a> -->
                                    <button type="button" class="btn-action delete-btn" data-id="{{ $appointment->id }}"
                                        data-name="{{ $appointment->customer->name }}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-calendar-alt empty-icon"></i>
                            <h3>No appointments found</h3>
                            <p>Create your first appointment to get started</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($appointments->hasPages())
                <div class="pagination-container">
                    {{ $appointments->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>

        <!-- Calendar View (Initially Hidden) -->
        <div class="calendar-container" id="calendarView" style="display: none;">
            <div id="appointmentCalendar"></div>
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
                    <h3>Delete Appointment</h3>
                    <p id="deleteMessage">Are you sure you want to delete this appointment? This action cannot be undone.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelDelete">Cancel</button>
                <button class="btn btn-danger" id="confirmDelete">Delete Appointment</button>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal" id="statusModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Update Appointment Status</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="statusSelect">New Status</label>
                    <select id="statusSelect" class="form-control">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="statusNotes">Notes (Optional)</label>
                    <textarea id="statusNotes" class="form-control" rows="3" placeholder="Add any notes..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelStatus">Cancel</button>
                <button class="btn btn-primary" id="confirmStatus">Update Status</button>
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
            --info-color: #4299e1;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
        }

        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: var(--primary-color);
            background-color: #f9fafb;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }

        /* Button Base Styles */
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
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
        }

        .btn-secondary {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--medium-gray);
        }

        .btn-secondary:hover {
            background-color: var(--light-gray);
            border-color: var(--dark-gray);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #e53e3e;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.2);
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 14px;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1.2;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        /* View Toggle */
        .view-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            background: var(--light-gray);
            padding: 8px;
            border-radius: 8px;
            width: fit-content;
        }

        .view-toggle .btn {
            padding: 10px 20px;
            border: none;
            background: transparent;
            color: var(--secondary-color);
        }

        .view-toggle .btn:hover {
            background-color: rgba(212, 175, 55, 0.1);
        }

        .view-toggle .btn.active {
            background-color: var(--accent-color);
            color: var(--primary-color);
            box-shadow: var(--card-shadow);
        }

        .view-toggle .btn.active:hover {
            background-color: #c19a2f;
        }

        /* Control Bar */
        .control-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
        }

        .search-container {
            position: relative;
            width: 300px;
            flex: 1;
            min-width: 250px;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px 12px 45px;
            border-radius: 8px;
            border: 1px solid var(--medium-gray);
            background-color: white;
            font-size: 15px;
            transition: var(--transition);
            color: var(--primary-color);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .search-input::placeholder {
            color: var(--dark-gray);
            opacity: 0.7;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark-gray);
            font-size: 14px;
        }

        .filter-controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
            flex: 1;
        }

        .filter-select {
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid var(--medium-gray);
            background-color: white;
            font-size: 15px;
            color: var(--primary-color);
            cursor: pointer;
            min-width: 150px;
            transition: var(--transition);
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .filter-select:hover {
            border-color: var(--dark-gray);
        }

        /* Appointments Table */
        .appointments-table {
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 25px;
            border: 1px solid var(--medium-gray);
        }

        .table-header {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr 1.5fr 0.8fr 0.8fr 0.8fr;
            background-color: var(--light-gray);
            padding: 20px;
            border-bottom: 1px solid var(--medium-gray);
            font-weight: 600;
            color: var(--primary-color);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-cell {
            padding: 0 10px;
            display: flex;
            align-items: center;
        }

        .table-body {
            max-height: 600px;
            overflow-y: auto;
        }

        .table-body::-webkit-scrollbar {
            width: 8px;
        }

        .table-body::-webkit-scrollbar-track {
            background: var(--light-gray);
        }

        .table-body::-webkit-scrollbar-thumb {
            background: var(--medium-gray);
            border-radius: 4px;
        }

        .table-body::-webkit-scrollbar-thumb:hover {
            background: var(--dark-gray);
        }

        .table-row {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr 1.5fr 0.8fr 0.8fr 0.8fr;
            padding: 20px;
            border-bottom: 1px solid var(--medium-gray);
            transition: var(--transition);
            align-items: center;
            background-color: white;
        }

        .table-row:hover {
            background-color: var(--light-gray);
            transform: translateX(2px);
        }

        .table-row:last-child {
            border-bottom: none;
        }

        .table-cell {
            padding: 0 10px;
        }

        .customer-info,
        .barber-info,
        .service-info,
        .datetime-info,
        .price-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .customer-name,
        .barber-name,
        .service-name,
        .appointment-date,
        .price {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 15px;
        }

        .customer-email,
        .barber-position,
        .service-duration,
        .appointment-time {
            font-size: 13px;
            color: var(--dark-gray);
            line-height: 1.4;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            min-width: 90px;
            transition: var(--transition);
            cursor: pointer;
            user-select: none;
        }

        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-pending {
            background-color: rgba(237, 137, 54, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(237, 137, 54, 0.2);
        }

        .status-confirmed {
            background-color: rgba(66, 153, 225, 0.1);
            color: var(--info-color);
            border: 1px solid rgba(66, 153, 225, 0.2);
        }

        .status-completed {
            background-color: rgba(72, 187, 120, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(72, 187, 120, 0.2);
        }

        .status-cancelled {
            background-color: rgba(245, 101, 101, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(245, 101, 101, 0.2);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-start;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            border: 1px solid var(--medium-gray);
            background-color: white;
            color: var(--primary-color);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .view-btn:hover {
            background-color: var(--info-color);
            color: white;
            border-color: var(--info-color);
        }

        .edit-btn:hover {
            background-color: var(--accent-color);
            color: var(--primary-color);
            border-color: var(--accent-color);
        }

        .delete-btn:hover {
            background-color: var(--danger-color);
            color: white;
            border-color: var(--danger-color);
        }

        /* Calendar View */
        .calendar-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            padding: 25px;
            min-height: 600px;
            border: 1px solid var(--medium-gray);
        }

        #appointmentCalendar {
            height: 600px;
            font-family: inherit;
        }

        /* FullCalendar Custom Styles */
        .fc {
            font-family: inherit;
        }

        .fc-toolbar {
            flex-wrap: wrap;
            gap: 10px;
        }

        .fc-toolbar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .fc-button {
            background-color: white;
            border: 1px solid var(--medium-gray);
            color: var(--primary-color);
            font-weight: 600;
            transition: var(--transition);
        }

        .fc-button:hover {
            background-color: var(--light-gray);
            border-color: var(--dark-gray);
        }

        .fc-button-primary:not(:disabled).fc-button-active {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--primary-color);
        }

        .fc-event {
            cursor: pointer;
            border: none !important;
            padding: 4px 8px !important;
            font-size: 12px;
            border-radius: 4px;
            transition: var(--transition);
        }

        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .fc-event-title {
            font-weight: 600;
            white-space: normal;
        }

        .fc-daygrid-event-dot {
            display: none;
        }

        /* Empty State */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: var(--dark-gray);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
            color: var(--accent-color);
        }

        .empty-state h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }

        .empty-state p {
            font-size: 15px;
            opacity: 0.8;
            max-width: 400px;
            margin: 0 auto;
        }

        /* Pagination */
        .pagination-container {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            list-style: none;
            gap: 5px;
            padding: 0;
            margin: 0;
        }

        .pagination li {
            margin: 0;
        }

        .pagination li a,
        .pagination li span {
            display: inline-block;
            padding: 8px 12px;
            background-color: white;
            border: 1px solid var(--medium-gray);
            border-radius: 6px;
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
            font-size: 14px;
            min-width: 40px;
            text-align: center;
        }

        .pagination li.active span {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--primary-color);
            font-weight: 600;
        }

        .pagination li a:hover {
            background-color: var(--light-gray);
            border-color: var(--dark-gray);
            transform: translateY(-2px);
        }

        .pagination li.disabled span {
            background-color: var(--light-gray);
            color: var(--dark-gray);
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* Modal Styles */
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
            animation: fadeIn 0.3s ease;
        }

        .modal.active {
            display: flex;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
            padding: 25px 25px 15px;
            border-bottom: 1px solid var(--medium-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            line-height: 1.3;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--dark-gray);
            cursor: pointer;
            transition: var(--transition);
            line-height: 1;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .modal-close:hover {
            color: var(--primary-color);
            background-color: var(--light-gray);
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
            line-height: 1.3;
        }

        .delete-message p {
            color: var(--dark-gray);
            line-height: 1.6;
            font-size: 15px;
        }

        /* Form Controls in Modal */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid var(--medium-gray);
            font-size: 15px;
            transition: var(--transition);
            background-color: white;
            color: var(--primary-color);
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
            line-height: 1.5;
        }

        /* Toast notification styles */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: var(--primary-color);
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
            max-width: 400px;
        }

        .toast i {
            color: var(--success-color);
            font-size: 18px;
            flex-shrink: 0;
        }

        .toast span {
            line-height: 1.4;
            flex: 1;
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {

            .table-header,
            .table-row {
                grid-template-columns: 1fr 1fr 1fr 1.5fr;
            }

            .header-cell:nth-child(5),
            .header-cell:nth-child(6),
            .header-cell:nth-child(7),
            .table-cell:nth-child(5),
            .table-cell:nth-child(6),
            .table-cell:nth-child(7) {
                display: none;
            }

            .appointments-table {
                overflow-x: auto;
            }
        }

        @media (max-width: 1024px) {
            .container {
                padding: 20px;
            }

            .control-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-container {
                width: 100%;
                min-width: 100%;
            }

            .filter-controls {
                width: 100%;
                justify-content: flex-start;
            }

            .filter-select {
                flex: 1;
                min-width: 0;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            .page-title {
                font-size: 24px;
            }

            .header-actions {
                width: 100%;
                justify-content: center;
            }

            .view-toggle {
                width: 100%;
                justify-content: center;
            }

            .view-toggle .btn {
                flex: 1;
                justify-content: center;
            }

            .table-header,
            .table-row {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 15px;
            }

            .header-cell {
                display: none;
            }

            .table-cell {
                padding: 0;
                display: flex;
                flex-direction: column;
                gap: 5px;
                border-bottom: 1px solid var(--light-gray);
                padding-bottom: 15px;
            }

            .table-cell:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .action-buttons {
                justify-content: center;
                padding-top: 10px;
            }

            .status-badge {
                align-self: flex-start;
            }

            .modal-content {
                width: 95%;
                margin: 10px;
            }

            .modal-footer {
                flex-direction: column;
            }

            .modal-footer .btn {
                width: 100%;
            }

            .fc-toolbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .fc-toolbar-chunk {
                width: 100%;
            }

            .fc-toolbar-chunk:first-child {
                order: 2;
            }

            .fc-toolbar-chunk:nth-child(2) {
                order: 1;
                text-align: center;
            }

            .fc-toolbar-chunk:last-child {
                order: 3;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }

            .btn {
                padding: 10px 16px;
                font-size: 14px;
            }

            .form-card-header h3 {
                font-size: 16px;
            }

            .filter-controls {
                flex-direction: column;
            }

            .filter-select {
                width: 100%;
            }

            .table-row {
                padding: 12px;
            }

            .customer-name,
            .barber-name,
            .service-name,
            .appointment-date,
            .price {
                font-size: 14px;
            }

            .customer-email,
            .barber-position,
            .service-duration,
            .appointment-time {
                font-size: 12px;
            }

            .status-badge {
                font-size: 11px;
                padding: 4px 10px;
            }

            .btn-action {
                width: 32px;
                height: 32px;
                font-size: 13px;
            }

            .pagination li a,
            .pagination li span {
                padding: 6px 10px;
                font-size: 13px;
                min-width: 36px;
            }
        }

        /* Print Styles */
        @media print {

            .view-toggle,
            .control-bar,
            .action-buttons,
            .modal,
            .btn-action {
                display: none !important;
            }

            .container {
                padding: 0;
                max-width: 100%;
            }

            .appointments-table {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .table-row {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .table-row:hover {
                background-color: white !important;
                transform: none !important;
            }
        }
    </style>

    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    // View Toggle
    const listViewBtn = document.getElementById('listViewBtn');
    const calendarViewBtn = document.getElementById('calendarViewBtn');
    const listView = document.getElementById('listView');
    const calendarView = document.getElementById('calendarView');
    let calendar = null;

    listViewBtn.addEventListener('click', function () {
        listViewBtn.classList.add('active');
        calendarViewBtn.classList.remove('active');
        listView.style.display = 'block';
        calendarView.style.display = 'none';
    });

    calendarViewBtn.addEventListener('click', function () {
        calendarViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        listView.style.display = 'none';
        calendarView.style.display = 'block';

        if (!calendar) {
            initializeCalendar();
        }
    });

    function initializeCalendar() {
        const calendarEl = document.getElementById('appointmentCalendar');
        
        // Configure Malaysia time zone (UTC+8)
        calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'Asia/Kuala_Lumpur', // Malaysia time zone
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: @json($calendarEvents),
            eventTimeFormat: { // AM/PM format
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short',
                hour12: true
            },
            slotLabelFormat: { // AM/PM format for time slots
                hour: 'numeric',
                minute: '2-digit',
                omitZeroMinute: false,
                meridiem: 'short',
                hour12: true
            },
            slotLabelInterval: '01:00:00',
            slotDuration: '01:00:00',
            slotMinTime: '08:00:00',
            slotMaxTime: '20:00:00',
            allDaySlot: false,
            eventClick: function (info) {
                const event = info.event;
                window.location.href = `/admin/appointments/${event.id}`;
            },
            eventDidMount: function (info) {
                // Add tooltip with Malaysia time format
                const start = info.event.start;
                const end = info.event.end;
                
                // Format times in Malaysia time with AM/PM
                const startTime = start.toLocaleTimeString('en-US', {
                    timeZone: 'Asia/Kuala_Lumpur',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
                
                const endTime = end.toLocaleTimeString('en-US', {
                    timeZone: 'Asia/Kuala_Lumpur',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });

                info.el.title = `
Customer: ${info.event.extendedProps.customer}
Barber: ${info.event.extendedProps.barber}
Service: ${info.event.extendedProps.service}
Time: ${startTime} - ${endTime}
Status: ${info.event.extendedProps.status}
Price: ${info.event.extendedProps.price}
`;
            },
            // Additional locale settings for Malaysia
            locale: 'en', // Use English locale
            buttonText: {
                today: 'Today',
                month: 'Month',
                week: 'Week',
                day: 'Day'
            },
            dayHeaderFormat: { // Format day headers
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                omitCommas: false
            },
            titleFormat: { // Format calendar title
                year: 'numeric',
                month: 'long'
            }
        });
        calendar.render();
    }

    // Filtering functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const barberFilter = document.getElementById('barberFilter');
    const dateFilter = document.getElementById('dateFilter');
    const appointmentsRows = document.querySelectorAll('.table-row');

    function filterAppointments() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusValue = statusFilter.value;
        const barberValue = barberFilter.value;
        const dateValue = dateFilter.value;

        appointmentsRows.forEach(row => {
            if (row.classList.contains('empty-state')) return;

            const customer = row.getAttribute('data-customer');
            const barber = row.getAttribute('data-barber');
            const service = row.getAttribute('data-service');
            const status = row.getAttribute('data-status');
            const date = row.getAttribute('data-date');
            const barberId = row.getAttribute('data-barber-id');

            let shouldShow = true;

            // Search filter
            if (searchTerm) {
                shouldShow = customer.includes(searchTerm) ||
                    barber.includes(searchTerm) ||
                    service.includes(searchTerm);
            }

            // Status filter
            if (shouldShow && statusValue !== 'all') {
                shouldShow = status === statusValue;
            }

            // Barber filter
            if (shouldShow && barberValue !== 'all') {
                shouldShow = barberId === barberValue;
            }

            // Date filter
            if (shouldShow && dateValue) {
                shouldShow = date === dateValue;
            }

            row.style.display = shouldShow ? 'grid' : 'none';
        });
    }

    // Event listeners for filtering
    if (searchInput) searchInput.addEventListener('input', filterAppointments);
    if (statusFilter) statusFilter.addEventListener('change', filterAppointments);
    if (barberFilter) barberFilter.addEventListener('change', filterAppointments);
    if (dateFilter) dateFilter.addEventListener('change', filterAppointments);

    // Delete confirmation
    const deleteModal = document.getElementById('deleteModal');
    const deleteMessage = document.getElementById('deleteMessage');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');

    let currentDeleteId = null;
    let deleteForm = null;

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            currentDeleteId = this.getAttribute('data-id');
            const appointmentName = this.getAttribute('data-name');

            deleteMessage.textContent = `Are you sure you want to delete the appointment for "${appointmentName}"? This action cannot be undone.`;
            deleteModal.classList.add('active');

            // Create a form for deletion
            deleteForm = document.createElement('form');
            deleteForm.method = 'POST';
            deleteForm.action = `/admin/appointments/${currentDeleteId}`;
            deleteForm.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(deleteForm);
        });
    });

    // Cancel delete
    cancelDelete.addEventListener('click', function () {
        deleteModal.classList.remove('active');
        if (deleteForm) {
            deleteForm.remove();
            deleteForm = null;
        }
        currentDeleteId = null;
    });

    // Confirm delete
    confirmDelete.addEventListener('click', function () {
        if (deleteForm) {
            deleteForm.submit();
        }
        deleteModal.classList.remove('active');
    });

    // Close modal when clicking outside
    window.addEventListener('click', function (e) {
        if (e.target === deleteModal) {
            deleteModal.classList.remove('active');
            if (deleteForm) {
                deleteForm.remove();
                deleteForm = null;
            }
            currentDeleteId = null;
        }
    });

    // Status update modal
    const statusModal = document.getElementById('statusModal');
    const cancelStatus = document.getElementById('cancelStatus');
    const confirmStatus = document.getElementById('confirmStatus');
    const statusSelect = document.getElementById('statusSelect');
    const statusNotes = document.getElementById('statusNotes');

    let currentAppointmentId = null;

    document.querySelectorAll('.status-badge').forEach(badge => {
        badge.addEventListener('click', function () {
            const row = this.closest('.table-row');
            currentAppointmentId = row.getAttribute('data-id');
            const currentStatus = row.getAttribute('data-status');

            statusSelect.value = currentStatus;
            statusNotes.value = '';
            statusModal.classList.add('active');
        });
    });

    // Cancel status update
    cancelStatus.addEventListener('click', function () {
        statusModal.classList.remove('active');
        currentAppointmentId = null;
    });

    // Confirm status update
    confirmStatus.addEventListener('click', function () {
        if (currentAppointmentId) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');
            formData.append('status', statusSelect.value);
            formData.append('notes', statusNotes.value);

            fetch(`/admin/appointments/${currentAppointmentId}/status`, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        statusModal.classList.remove('active');
        currentAppointmentId = null;
    });

    // Keyboard support for modals
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (deleteModal.classList.contains('active')) {
                deleteModal.classList.remove('active');
                if (deleteForm) {
                    deleteForm.remove();
                    deleteForm = null;
                }
            }
            if (statusModal.classList.contains('active')) {
                statusModal.classList.remove('active');
                currentAppointmentId = null;
            }
        }
    });

    // Initialize date picker for filtering
    if (dateFilter) {
        flatpickr(dateFilter, {
            dateFormat: "Y-m-d",
            allowInput: true
        });
    }
});
</script>
@endsection