@extends('admin.sidebar')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary btn-small">
                <i class="fas fa-arrow-left"></i> Back to Services
            </a>
        </div>
        <div class="header-center">
            <h1 class="page-title">Service Details</h1>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Service
            </a>
        </div>
    </div>

    <!-- Service Details Container -->
    <div class="service-show-container">
        <!-- Service Header -->
        <div class="service-header-card">
            <div class="service-icon-large">
                @php
                    // Determine icon based on service name
                    $nameLower = strtolower($service->name);
                    $icon = 'fas fa-cut';
                    if (str_contains($nameLower, 'shave') || str_contains($nameLower, 'beard')) {
                        $icon = 'fas fa-razor';
                    } elseif (str_contains($nameLower, 'color') || str_contains($nameLower, 'dye')) {
                        $icon = 'fas fa-paint-brush';
                    } elseif (str_contains($nameLower, 'wash') || str_contains($nameLower, 'shampoo')) {
                        $icon = 'fas fa-shower';
                    } elseif (str_contains($nameLower, 'style') || str_contains($nameLower, 'styling')) {
                        $icon = 'fas fa-spray-can';
                    } elseif (str_contains($nameLower, 'trim')) {
                        $icon = 'fas fa-scissors';
                    }
                @endphp
                <i class="{{ $icon }}"></i>
            </div>
            
            <div class="service-header-info">
                <h2 class="service-title">{{ $service->name }}</h2>
                
                <div class="service-meta">
                    <span class="service-status {{ $service->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        <i class="fas fa-circle"></i>
                        {{ ucfirst($service->status) }}
                    </span>
                    
                    <span class="service-date">
                        <i class="fas fa-calendar"></i>
                        Created: {{ $service->created_at->format('M d, Y') }}
                    </span>
                    
                    <span class="service-updated">
                        <i class="fas fa-history"></i>
                        Updated: {{ $service->updated_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Service Details Grid -->
        <div class="details-grid">
            <!-- Price & Duration Card -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <h3><i class="fas fa-money-bill-wave"></i> Pricing & Duration</h3>
                </div>
                <div class="detail-card-body">
                    <div class="stat-row">
                        <div class="stat-item">
                            <div class="stat-icon price">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Price</div>
                                <div class="stat-value">RM{{ number_format($service->price, 2) }}</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon duration">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Duration</div>
                                <div class="stat-value">
                                    @php
                                        $durationText = $service->duration . ' min';
                                        if ($service->duration >= 60) {
                                            $hours = floor($service->duration / 60);
                                            $minutes = $service->duration % 60;
                                            $durationText = $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'm' : '');
                                        }
                                    @endphp
                                    {{ $durationText }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Statistics Card -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <h3><i class="fas fa-chart-bar"></i> Service Statistics</h3>
                </div>
                <div class="detail-card-body">
                    <div class="stat-row">
                        <div class="stat-item">
                            <div class="stat-icon appointments">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Total Appointments</div>
                                <div class="stat-value">{{ $service->appointments_count ?? 0 }}</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon revenue">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Estimated Revenue</div>
                                <div class="stat-value">
                                    @php
                                        $revenue = ($service->appointments_count ?? 0) * $service->price;
                                    @endphp
                                    RM{{ number_format($revenue, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Card -->
        <div class="detail-card full-width">
            <div class="detail-card-header">
                <h3><i class="fas fa-file-alt"></i> Service Description</h3>
            </div>
            <div class="detail-card-body">
                @if($service->description)
                    <div class="description-content">
                        {{ $service->description }}
                    </div>
                @else
                    <div class="no-description">
                        <i class="fas fa-info-circle"></i>
                        <p>No description provided for this service.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Appointments (Optional) -->
        @if(isset($recentAppointments) && $recentAppointments->count() > 0)
        <div class="detail-card full-width">
            <div class="detail-card-header">
                <h3><i class="fas fa-history"></i> Recent Appointments</h3>
                <a href="{{ route('admin.appointments.index', ['service_id' => $service->id]) }}" class="btn btn-small btn-secondary">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="detail-card-body">
                <div class="appointments-list">
                    @foreach($recentAppointments as $appointment)
                    <div class="appointment-item">
                        <div class="appointment-client">
                            <i class="fas fa-user"></i>
                            <span>{{ $appointment->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="appointment-date">
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                            @if($appointment->appointment_time)
                                at {{ $appointment->appointment_time }}
                            @endif
                        </div>
                        <div class="appointment-status {{ $appointment->status }}">
                            {{ ucfirst($appointment->status) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="button" class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Print Details
            </button>
            
            <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Service
            </a>
            
            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger delete-btn">
                    <i class="fas fa-trash"></i> Delete Service
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
                <h3>Delete Service</h3>
                <p id="deleteMessage">Are you sure you want to delete "{{ $service->name }}"? This action cannot be undone.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelDelete">Cancel</button>
            <button class="btn btn-danger" id="confirmDelete">Delete Service</button>
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
    
    /* Service Show Container */
    .service-show-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }
    
    /* Service Header Card */
    .service-header-card {
        padding: 40px;
        border-bottom: 1px solid var(--medium-gray);
        background: linear-gradient(135deg, var(--light-gray) 0%, white 100%);
        display: flex;
        align-items: center;
        gap: 30px;
    }
    
    .service-icon-large {
        width: 100px;
        height: 100px;
        border-radius: 20px;
        background-color: rgba(212, 175, 55, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-color);
        font-size: 40px;
        flex-shrink: 0;
    }
    
    .service-header-info {
        flex: 1;
    }
    
    .service-title {
        font-size: 36px;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0 0 15px 0;
    }
    
    .service-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .service-status {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-active {
        background-color: rgba(72, 187, 120, 0.1);
        color: var(--success-color);
    }
    
    .status-inactive {
        background-color: rgba(245, 101, 101, 0.1);
        color: var(--danger-color);
    }
    
    .service-date,
    .service-updated {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--dark-gray);
        font-size: 14px;
    }
    
    .service-date i,
    .service-updated i {
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
    
    /* Statistics */
    .stat-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    @media (max-width: 768px) {
        .stat-row {
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
    
    .stat-icon.price {
        background-color: rgba(212, 175, 55, 0.1);
        color: var(--accent-color);
    }
    
    .stat-icon.duration {
        background-color: rgba(66, 153, 225, 0.1);
        color: #4299e1;
    }
    
    .stat-icon.appointments {
        background-color: rgba(72, 187, 120, 0.1);
        color: var(--success-color);
    }
    
    .stat-icon.revenue {
        background-color: rgba(159, 122, 234, 0.1);
        color: #9f7aea;
    }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--dark-gray);
        margin-bottom: 4px;
    }
    
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary-color);
        line-height: 1;
    }
    
    /* Description Content */
    .description-content {
        line-height: 1.6;
        color: var(--primary-color);
        font-size: 16px;
        white-space: pre-line;
    }
    
    .no-description {
        text-align: center;
        padding: 40px 20px;
        color: var(--dark-gray);
    }
    
    .no-description i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }
    
    .no-description p {
        margin: 0;
        font-size: 16px;
    }
    
    /* Appointments List */
    .appointments-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .appointment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background-color: var(--light-gray);
        border-radius: 8px;
        transition: var(--transition);
    }
    
    .appointment-item:hover {
        background-color: var(--medium-gray);
    }
    
    .appointment-client {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        color: var(--primary-color);
    }
    
    .appointment-client i {
        color: var(--accent-color);
    }
    
    .appointment-date {
        color: var(--dark-gray);
        font-size: 14px;
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
        }
        
        .service-header-card {
            flex-direction: column;
            text-align: center;
            padding: 30px;
        }
        
        .service-icon-large {
            width: 80px;
            height: 80px;
            font-size: 32px;
        }
        
        .service-title {
            font-size: 28px;
        }
        
        .service-meta {
            justify-content: center;
        }
        
        .details-grid {
            padding: 20px;
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
        
        .appointment-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
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