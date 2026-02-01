@extends('admin.sidebar')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-small">
                <i class="fas fa-arrow-left"></i> Back to Customers
            </a>
        </div>
        <div class="header-center">
            <h1 class="page-title">Customer Profile</h1>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Customer
            </a>
        </div>
    </div>

    <!-- Customer Profile Container -->
    <div class="customer-profile-container">
        <!-- Profile Header -->
        <div class="profile-header-card">
            <div class="profile-avatar-large">
                {{ strtoupper(substr($customer->name, 0, 2)) }}
            </div>
            
            <div class="profile-header-info">
                <h2 class="profile-name">{{ $customer->name }}</h2>
                <div class="profile-meta">
                    <span class="profile-email">
                        <i class="fas fa-envelope"></i>
                        {{ $customer->email }}
                    </span>
                    
                    <span class="profile-member-since">
                        <i class="fas fa-calendar-alt"></i>
                        Member since {{ $customer->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Customer Details Grid -->
        <div class="details-grid">
            <!-- Contact Information Card -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <h3><i class="fas fa-address-book"></i> Contact Information</h3>
                </div>
                <div class="detail-card-body">
                    <div class="contact-details">
                        @if($customer->phone)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-content">
                                <div class="contact-label">Phone Number</div>
                                <div class="contact-value">{{ $customer->phone }}</div>
                            </div>
                        </div>
                        @endif
                        
                        @if($customer->address)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-content">
                                <div class="contact-label">Address</div>
                                <div class="contact-value">{{ $customer->address }}</div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-content">
                                <div class="contact-label">Email Address</div>
                                <div class="contact-value">{{ $customer->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <h3><i class="fas fa-chart-bar"></i> Customer Statistics</h3>
                </div>
                <div class="detail-card-body">
                    <div class="statistics-grid">
                        <div class="stat-item">
                            <div class="stat-icon total">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $totalAppointments }}</div>
                                <div class="stat-label">Total Appointments</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon completed">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $completedAppointments }}</div>
                                <div class="stat-label">Completed</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon pending">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $upcomingAppointments }}</div>
                                <div class="stat-label">Upcoming</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon revenue">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">RM{{ number_format($totalSpent, 2) }}</div>
                                <div class="stat-label">Total Spent</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Appointments Card -->
        <div class="detail-card full-width">
            <div class="detail-card-header">
                <h3><i class="fas fa-history"></i> Appointment History ({{ $totalAppointments }})</h3>
            </div>
            <div class="detail-card-body">
                @if($appointments->count() > 0)
                    <div class="appointments-table-container">
                        <table class="appointments-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Service</th>
                                    <th>Barber</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                        <td>{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                                        <td>{{ $appointment->service->name ?? '-' }}</td>
                                        <td>{{ $appointment->barber->name ?? '-' }}</td>
                                        <td>RM{{ number_format($appointment->price, 2) }}</td>
                                        <td>
                                            <span class="status-badge {{ $appointment->status }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>No appointments yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="button" class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Print Profile
            </button>
            
            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Customer
            </a>
            
            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger delete-btn">
                    <i class="fas fa-trash"></i> Delete Customer
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
                <h3>Delete Customer</h3>
                <p id="deleteMessage">Are you sure you want to delete {{ $customer->name }}? This action cannot be undone and will delete all associated appointments.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelDelete">Cancel</button>
            <button class="btn btn-danger" id="confirmDelete">Delete Customer</button>
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
    

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 20px;
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
    
    /* Customer Profile Container */
    .customer-profile-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }
    
    /* Profile Header */
    .profile-header-card {
        padding: 40px;
        border-bottom: 1px solid var(--medium-gray);
        background: linear-gradient(135deg, var(--light-gray) 0%, white 100%);
        display: flex;
        align-items: center;
        gap: 30px;
    }
    
    .profile-avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 36px;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    .profile-header-info {
        flex: 1;
    }
    
    .profile-name {
        font-size: 36px;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0 0 15px 0;
    }
    
    .profile-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .profile-email,
    .profile-member-since {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--dark-gray);
        font-size: 16px;
    }
    
    .profile-email i,
    .profile-member-since i {
        color: var(--accent-color);
    }
    
    /* Details Grid */
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        padding: 40px;
    }
    
    @media (max-width: 992px) {
        .details-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Detail Cards */
    .detail-card {
        background-color: white;
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
        overflow: hidden;
    }
    
    .detail-card.full-width {
        grid-column: 1 / -1;
    }
    
    .detail-card-header {
        background-color: var(--light-gray);
        padding: 20px;
        border-bottom: 1px solid var(--medium-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .detail-card-header h3 {
        margin: 0;
        font-size: 18px;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .detail-card-header i {
        color: var(--accent-color);
    }
    
    .detail-card-body {
        padding: 25px;
    }
    
    /* Contact Details */
    .contact-details {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }
    
    .contact-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background-color: rgba(212, 175, 55, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-color);
        flex-shrink: 0;
    }
    
    .contact-content {
        flex: 1;
    }
    
    .contact-label {
        font-size: 12px;
        color: var(--dark-gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    
    .contact-value {
        font-size: 16px;
        color: var(--primary-color);
        font-weight: 500;
    }
    
    /* Statistics Grid */
    .statistics-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    @media (max-width: 768px) {
        .statistics-grid {
            grid-template-columns: 1fr;
        }
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
    
    .stat-icon.total {
        background-color: rgba(212, 175, 55, 0.1);
        color: var(--accent-color);
    }
    
    .stat-icon.completed {
        background-color: rgba(72, 187, 120, 0.1);
        color: var(--success-color);
    }
    
    .stat-icon.pending {
        background-color: rgba(66, 153, 225, 0.1);
        color: #4299e1;
    }
    
    .stat-icon.revenue {
        background-color: rgba(159, 122, 234, 0.1);
        color: #9f7aea;
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
    
    /* Appointments List */
    .appointments-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .appointment-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        background-color: var(--light-gray);
        border-radius: 8px;
        transition: var(--transition);
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .appointment-item:hover {
        background-color: var(--medium-gray);
    }
    
    .appointment-date {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 120px;
    }
    
    .appointment-date .date {
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .appointment-date .time {
        font-size: 13px;
        color: var(--dark-gray);
    }
    
    .appointment-service,
    .appointment-barber {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--secondary-color);
        min-width: 150px;
    }
    
    .appointment-service i,
    .appointment-barber i {
        color: var(--accent-color);
    }
    
    .appointment-price {
        font-weight: 600;
        color: var(--accent-color);
        min-width: 80px;
    }
    
    .appointment-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        min-width: 100px;
        text-align: center;
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
    
    /* Modal Styling */
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
    
    /* Delete Confirmation Modal */
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
    
    /* Toast notification styles (if needed) */
    .toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: var(--primary-color);
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }
    
    .toast i {
        color: var(--success-color);
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
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
        }
        
        .page-title {
            font-size: 24px;
        }
        
        .profile-header-card {
            flex-direction: column;
            text-align: center;
            padding: 30px;
        }
        
        .profile-avatar-large {
            width: 80px;
            height: 80px;
            font-size: 28px;
        }
        
        .profile-name {
            font-size: 28px;
        }
        
        .details-grid {
            padding: 20px;
        }
        
        .appointment-item {
            flex-direction: column;
            align-items: flex-start;
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
        
        .detail-card-header h3 {
            font-size: 16px;
        }
        
        .profile-meta {
            flex-direction: column;
            gap: 10px;
        }
        
        .statistics-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Print Styles */
    @media print {
        .page-header,
        .action-buttons,
        .detail-card-header .btn,
        .delete-modal {
            display: none !important;
        }
        
        .customer-profile-container {
            box-shadow: none;
            border: 1px solid #ddd;
        }
        
        .profile-header-card {
            background: none;
            border-bottom: 2px solid #ddd;
        }
        
        .detail-card {
            break-inside: avoid;
            page-break-inside: avoid;
        }
        
        .container {
            padding: 0;
            max-width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    const deleteBtn = document.querySelector('.delete-btn');
    const deleteModal = document.getElementById('deleteModal');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');
    
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            deleteModal.classList.add('active');
        });
    }
    
    if (cancelDelete) {
        cancelDelete.addEventListener('click', function() {
            deleteModal.classList.remove('active');
        });
    }
    
    if (confirmDelete) {
        confirmDelete.addEventListener('click', function() {
            const form = deleteBtn.closest('.delete-form');
            if (form) {
                form.submit();
            }
            deleteModal.classList.remove('active');
        });
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.classList.remove('active');
        }
    });
    
    // Keyboard support for modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && deleteModal.classList.contains('active')) {
            deleteModal.classList.remove('active');
        }
    });
});
</script>
@endsection