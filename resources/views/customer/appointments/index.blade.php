@extends('customer.sidebar')


@section('content')
<div class="appointments-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1>My Appointments</h1>
            <p>Manage your scheduled appointments and booking history</p>
        </div>
        <a href="{{ route('customer.appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Appointment
        </a>
    </div>

    <!-- Filter Controls -->
    <div class="filter-controls">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Search appointments...">
        </div>
        
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="upcoming">Upcoming</button>
            <button class="filter-btn" data-filter="pending">Pending</button>
            <button class="filter-btn" data-filter="confirmed">Confirmed</button>
            <button class="filter-btn" data-filter="completed">Completed</button>
            <button class="filter-btn" data-filter="cancelled">Cancelled</button>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="appointments-container">
        @forelse($appointments as $appointment)
        <div class="appointment-card" 
             data-id="{{ $appointment->id }}"
             data-service="{{ strtolower($appointment->service->name) }}"
             data-barber="{{ strtolower($appointment->barber->name) }}"
             data-date="{{ $appointment->appointment_date->format('Y-m-d') }}"
             data-status="{{ $appointment->status }}">
            
            <!-- Appointment Header -->
            <div class="appointment-header">
                <div class="service-info">
                    <div class="service-name">{{ $appointment->service->name }}</div>
                    <div class="appointment-id">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
                <span class="status-badge status-{{ $appointment->status }}">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>

            <!-- Appointment Details -->
            <div class="appointment-details">
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-user-tie"></i> Barber
                        </span>
                        <span class="detail-value">{{ $appointment->barber->name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-tag"></i> Price
                        </span>
                        <span class="detail-value price">RM{{ number_format($appointment->price, 2) }}</span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-calendar"></i> Date
                        </span>
                        <span class="detail-value">{{ $appointment->appointment_date->format('M d, Y') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-clock"></i> Time
                        </span>
                        <span class="detail-value">
                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - 
                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                        </span>
                    </div>
                </div>

                @if($appointment->notes)
                <div class="detail-row">
                    <div class="detail-item full-width">
                        <span class="detail-label">
                            <i class="fas fa-sticky-note"></i> Notes
                        </span>
                        <span class="detail-value">{{ Str::limit($appointment->notes, 100) }}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Appointment Actions -->
            <div class="appointment-actions">
                <a href="{{ route('customer.appointments.show', $appointment->id) }}" 
                   class="btn btn-outline btn-small">
                    <i class="fas fa-eye"></i> View
                </a>
                
                @if(in_array($appointment->status, ['pending_payment', 'confirmed']))
               
                
                <button class="btn btn-danger btn-small cancel-btn" 
                        data-id="{{ $appointment->id }}"
                        data-service="{{ $appointment->service->name }}">
                    <i class="fas fa-times"></i> Cancel
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3>No Appointments Found</h3>
            <p>You haven't booked any appointments yet</p>
            <a href="{{ route('customer.appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Book Your First Appointment
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($appointments->hasPages())
    <div class="pagination">
        {{ $appointments->links() }}
    </div>
    @endif
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
            <p id="cancelMessage">Are you sure you want to cancel this appointment?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelCancel">No, Keep It</button>
            <button class="btn btn-danger" id="confirmCancel">Yes, Cancel</button>
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

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-content h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .header-content p {
        color: var(--secondary);
    }

    /* Filter Controls */
    .filter-controls {
        background: white;
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
    }

    .search-container {
        position: relative;
        margin-bottom: 1rem;
    }

    .search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 1px solid var(--medium-gray);
        border-radius: var(--radius);
        font-size: 1rem;
        transition: var(--transition);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary);
    }

    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .filter-btn {
        padding: 0.5rem 1rem;
        border: 1px solid var(--medium-gray);
        background: white;
        color: var(--secondary);
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--accent);
        color: var(--primary);
        border-color: var(--accent);
    }

    /* Appointments Container */
    .appointments-container {
        display: grid;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Appointment Card */
    .appointment-card {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        border: 1px solid var(--medium-gray);
    }

    .appointment-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent);
    }

    .appointment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background: var(--light-gray);
        border-bottom: 1px solid var(--medium-gray);
    }

    .service-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .service-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary);
    }

    .appointment-id {
        font-size: 0.875rem;
        color: var(--secondary);
        background: white;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        border: 1px solid var(--medium-gray);
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

    /* Appointment Details */
    .appointment-details {
        padding: 1.5rem;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .detail-label i {
        color: var(--accent);
    }

    .detail-value {
        font-weight: 500;
        color: var(--primary);
        display: block;
    }

    .detail-value.price {
        color: var(--accent);
        font-size: 1.125rem;
        font-weight: 600;
    }

    /* Appointment Actions */
    .appointment-actions {
        padding: 1rem 1.5rem 1.5rem;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    /* Buttons */
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

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--secondary);
        grid-column: 1 / -1;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
        color: var(--accent);
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: var(--primary);
    }

    .empty-state p {
        margin-bottom: 1.5rem;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    .pagination ul {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
    }

    .pagination li {
        margin: 0;
    }

    .pagination a,
    .pagination span {
        display: inline-block;
        padding: 0.5rem 1rem;
        border: 1px solid var(--medium-gray);
        border-radius: var(--radius);
        color: var(--primary);
        text-decoration: none;
        transition: var(--transition);
    }

    .pagination .active span {
        background: var(--accent);
        color: var(--primary);
        border-color: var(--accent);
        font-weight: 600;
    }

    .pagination a:hover {
        background: var(--light-gray);
        border-color: var(--accent);
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
        margin-bottom: 0;
        font-size: 1.125rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem 1.5rem;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .appointment-actions {
            flex-direction: column;
        }

        .appointment-actions .btn {
            width: 100%;
        }

        .filter-buttons {
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .filter-buttons::-webkit-scrollbar {
            height: 4px;
        }

        .filter-buttons::-webkit-scrollbar-track {
            background: var(--light-gray);
        }

        .filter-buttons::-webkit-scrollbar-thumb {
            background: var(--medium-gray);
            border-radius: 2px;
        }
    }

    @media (max-width: 480px) {
        .appointment-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .service-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .modal-footer {
            flex-direction: column;
        }

        .modal-footer .btn {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const appointmentCards = document.querySelectorAll('.appointment-card');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        appointmentCards.forEach(card => {
            const service = card.getAttribute('data-service');
            const barber = card.getAttribute('data-barber');
            
            if (searchTerm === '' || 
                service.includes(searchTerm) || 
                barber.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
    
    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter cards
            appointmentCards.forEach(card => {
                const status = card.getAttribute('data-status');
                const date = card.getAttribute('data-date');
                const today = new Date().toISOString().split('T')[0];
                
                let shouldShow = false;
                
                switch(filter) {
                    case 'all':
                        shouldShow = true;
                        break;
                    case 'upcoming':
                        shouldShow = (status === 'pending' || status === 'confirmed') && date >= today;
                        break;
                    case 'pending':
                        shouldShow = status === 'pending';
                        break;
                    case 'confirmed':
                        shouldShow = status === 'confirmed';
                        break;
                    case 'completed':
                        shouldShow = status === 'completed';
                        break;
                    case 'cancelled':
                        shouldShow = status === 'cancelled';
                        break;
                }
                
                card.style.display = shouldShow ? 'block' : 'none';
            });
        });
    });
    
    // Cancel appointment modal
    const cancelModal = document.getElementById('cancelModal');
    const modalClose = document.querySelector('.modal-close');
    const cancelCancel = document.getElementById('cancelCancel');
    const confirmCancel = document.getElementById('confirmCancel');
    const cancelMessage = document.getElementById('cancelMessage');
    
    let appointmentIdToCancel = null;
    
    // Set up cancel buttons
    document.querySelectorAll('.cancel-btn').forEach(button => {
        button.addEventListener('click', function() {
            appointmentIdToCancel = this.getAttribute('data-id');
            const serviceName = this.getAttribute('data-service');
            
            cancelMessage.textContent = `Are you sure you want to cancel your appointment for "${serviceName}"?`;
            cancelModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });
    
    // Close modal function
    function closeModal() {
        cancelModal.classList.remove('active');
        document.body.style.overflow = '';
        appointmentIdToCancel = null;
    }
    
    // Modal close handlers
    modalClose.addEventListener('click', closeModal);
    cancelCancel.addEventListener('click', closeModal);
    
    // Confirm cancel
    confirmCancel.addEventListener('click', function() {
        if (appointmentIdToCancel) {
            // Create cancel form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/customer/appointments/${appointmentIdToCancel}/cancel`;
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PATCH';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
        
        closeModal();
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === cancelModal) {
            closeModal();
        }
    });
    
    // Keyboard support
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && cancelModal.classList.contains('active')) {
            closeModal();
        }
    });
    
    // Card hover animations
    appointmentCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection