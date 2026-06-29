@extends('customer.sidebar')


@section('content')
<div class="customer-page appointments-page">
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
            <button class="filter-btn" data-filter="pending_payment">Pending Payment</button>
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
             data-recipient="{{ strtolower($appointment->recipient_display_name) }}"
             data-date="{{ $appointment->appointment_date->format('Y-m-d') }}"
             data-status="{{ $appointment->status }}">
            
            <!-- Appointment Header -->
            <div class="appointment-header">
                <div class="service-info">
                    <div class="service-name">{{ $appointment->service->name }}</div>
                    <div class="appointment-id">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
                <span class="status-badge status-{{ $appointment->status }}">
                    {{ ucwords(str_replace('_', ' ', $appointment->status)) }}
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
                            <i class="fas fa-user"></i> For
                        </span>
                        <span class="detail-value">
                            {{ $appointment->recipient_display_name }}
                            @if($appointment->recipient_age !== null)
                                ({{ $appointment->recipient_age }})
                            @endif
                        </span>
                    </div>
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

                @if($appointment->canRetryPayment())
                <div class="payment-window">
                    <i class="fas fa-hourglass-half"></i>
                    <span>
                        Payment expires at {{ $appointment->paymentDeadline()?->format('h:i A') }}
                        ({{ $appointment->paymentMinutesRemaining() }} min left)
                    </span>
                </div>
                @endif
            </div>

            <!-- Appointment Actions -->
            <div class="appointment-actions">
                <a href="{{ route('customer.appointments.show', $appointment->id) }}" 
                   class="btn btn-outline btn-small">
                    <i class="fas fa-eye"></i> View
                </a>

                @if($appointment->canRetryPayment())
                <a href="{{ route('customer.appointments.pay', $appointment->id) }}"
                   class="btn btn-primary btn-small">
                    <i class="fas fa-credit-card"></i> Pay Now
                </a>
                @endif
                
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
        @php
            $currentPage = $appointments->currentPage();
            $lastPage = $appointments->lastPage();
            $startPage = max(1, $currentPage - 1);
            $endPage = min($lastPage, $currentPage + 1);
        @endphp
        <nav class="pagination-panel" aria-label="Appointments pagination">
            <div class="pagination-summary">
                Showing {{ $appointments->firstItem() }}-{{ $appointments->lastItem() }} of {{ $appointments->total() }} appointments
            </div>

            <div class="pagination-controls">
                @if($appointments->onFirstPage())
                    <span class="pagination-link pagination-arrow is-disabled" aria-disabled="true">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a class="pagination-link pagination-arrow" href="{{ $appointments->previousPageUrl() }}" rel="prev" aria-label="Previous page">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @if($startPage > 1)
                    <a class="pagination-link" href="{{ $appointments->url(1) }}">1</a>
                    @if($startPage > 2)
                        <span class="pagination-ellipsis">...</span>
                    @endif
                @endif

                @for($page = $startPage; $page <= $endPage; $page++)
                    @if($page === $currentPage)
                        <span class="pagination-link is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination-link" href="{{ $appointments->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                @if($endPage < $lastPage)
                    @if($endPage < $lastPage - 1)
                        <span class="pagination-ellipsis">...</span>
                    @endif
                    <a class="pagination-link" href="{{ $appointments->url($lastPage) }}">{{ $lastPage }}</a>
                @endif

                @if($appointments->hasMorePages())
                    <a class="pagination-link pagination-arrow" href="{{ $appointments->nextPageUrl() }}" rel="next" aria-label="Next page">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="pagination-link pagination-arrow is-disabled" aria-disabled="true">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </nav>
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
    :root {
        --primary: #0a0a0a;
        --secondary: #5f5a52;
        --accent: #d4af37;
        --accent-soft: #f8e5a0;
        --surface: rgba(255, 255, 255, 0.78);
        --surface-strong: #ffffff;
        --line: rgba(10, 10, 10, 0.08);
        --line-strong: rgba(10, 10, 10, 0.13);
        --success: #2f855a;
        --warning: #b7791f;
        --danger: #c53030;
        --info: #2b6cb0;
        --shadow: 0 12px 34px rgba(10, 10, 10, 0.07);
        --shadow-hover: 0 18px 42px rgba(10, 10, 10, 0.10);
        --radius: 8px;
        --transition: all 0.22s ease;
    }

    .appointments-page {
        max-width: 1320px;
        margin: 0 auto;
        padding: clamp(18px, 2.4vw, 30px);
        color: var(--primary);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .header-content h1 {
        margin: 0;
        color: var(--primary);
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 700;
        line-height: 1;
        letter-spacing: 0;
    }

    .header-content p {
        margin: 0.55rem 0 0;
        color: rgba(10, 10, 10, 0.58);
        font-size: 0.98rem;
    }

    .filter-controls {
        display: grid;
        gap: 12px;
        margin-bottom: 18px;
        padding: 14px;
        border: 1px solid rgba(255, 255, 255, 0.72);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.72);
        box-shadow: var(--shadow);
        backdrop-filter: blur(18px) saturate(180%);
    }

    .search-container {
        position: relative;
    }

    .search-input {
        width: 100%;
        height: 44px;
        padding: 0 1rem 0 2.8rem;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: rgba(255, 255, 255, 0.84);
        color: var(--primary);
        font-size: 0.95rem;
        font-weight: 500;
        transition: var(--transition);
    }

    .search-input:focus {
        outline: none;
        border-color: rgba(212, 175, 55, 0.55);
        box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.12);
        background: #ffffff;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        color: rgba(10, 10, 10, 0.44);
        transform: translateY(-50%);
    }

    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .filter-btn {
        min-height: 34px;
        padding: 0.45rem 0.85rem;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.74);
        color: rgba(10, 10, 10, 0.64);
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: rgba(248, 229, 160, 0.48);
        color: var(--primary);
        border-color: rgba(212, 175, 55, 0.48);
    }

    .appointments-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 360px), 1fr));
        gap: 14px;
        margin-bottom: 18px;
        min-height: var(--appointments-list-height, 260px);
        align-content: start;
    }

    .appointment-card {
        position: relative;
        display: flex;
        min-width: 0;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.72);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.78);
        box-shadow: 0 10px 28px rgba(10, 10, 10, 0.06);
        backdrop-filter: blur(16px) saturate(170%);
        transition: var(--transition);
    }

    .appointment-card::before {
        content: '';
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        background: var(--accent);
        opacity: 0.72;
    }

    .appointment-card.is-filtered-out {
        display: none;
    }

    .appointment-card:hover {
        border-color: rgba(212, 175, 55, 0.34);
        box-shadow: var(--shadow-hover);
        transform: translateY(-3px);
    }

    .appointment-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 15px 12px 18px;
        border-bottom: 1px solid var(--line);
    }

    .service-info {
        min-width: 0;
    }

    .service-name {
        color: var(--primary);
        font-size: 1.03rem;
        font-weight: 800;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .appointment-id {
        display: inline-flex;
        align-items: center;
        min-height: 24px;
        margin-top: 7px;
        padding: 0.22rem 0.55rem;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.70);
        color: rgba(10, 10, 10, 0.48);
        font-size: 0.72rem;
        font-weight: 700;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 26px;
        flex: 0 0 auto;
        padding: 0.28rem 0.62rem;
        border-radius: 999px;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        line-height: 1;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .status-pending,
    .status-pending_payment {
        background: rgba(237, 137, 54, 0.12);
        color: var(--warning);
        border: 1px solid rgba(237, 137, 54, 0.24);
    }

    .status-confirmed {
        background: rgba(66, 153, 225, 0.12);
        color: var(--info);
        border: 1px solid rgba(66, 153, 225, 0.24);
    }

    .status-completed {
        background: rgba(72, 187, 120, 0.13);
        color: var(--success);
        border: 1px solid rgba(72, 187, 120, 0.24);
    }

    .status-cancelled {
        background: rgba(245, 101, 101, 0.12);
        color: var(--danger);
        border: 1px solid rgba(245, 101, 101, 0.24);
    }

    .appointment-details {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 9px;
        padding: 12px 15px 14px 18px;
    }

    .detail-row {
        display: contents;
    }

    .detail-item {
        min-width: 0;
        padding: 9px 10px;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.58);
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-label {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 5px;
        color: rgba(10, 10, 10, 0.48);
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .detail-label i {
        width: 14px;
        color: var(--accent);
        font-size: 0.8rem;
        text-align: center;
    }

    .detail-value {
        display: block;
        min-width: 0;
        color: var(--primary);
        font-size: 0.91rem;
        font-weight: 700;
        line-height: 1.35;
        overflow-wrap: anywhere;
    }

    .detail-value.price {
        color: #9a741b;
        font-size: 0.96rem;
        font-weight: 850;
    }

    .payment-window {
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 0;
        padding: 9px 10px;
        border: 1px solid rgba(237, 137, 54, 0.24);
        border-radius: 8px;
        background: rgba(237, 137, 54, 0.08);
        color: #7b341e;
        font-size: 0.81rem;
        font-weight: 750;
        line-height: 1.35;
    }

    .payment-window i {
        color: var(--warning);
    }

    .appointment-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        padding: 0 15px 15px 18px;
        margin-top: auto;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        min-height: 40px;
        padding: 0 1rem;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.9rem;
        font-weight: 800;
        line-height: 1;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
    }

    .btn-primary {
        background: var(--accent);
        color: var(--primary);
        box-shadow: 0 10px 22px rgba(212, 175, 55, 0.18);
    }

    .btn-primary:hover {
        background: #c19a2f;
        transform: translateY(-1px);
    }

    .btn-outline {
        border-color: var(--line-strong);
        background: rgba(255, 255, 255, 0.58);
        color: var(--primary);
    }

    .btn-outline:hover {
        border-color: rgba(212, 175, 55, 0.46);
        background: rgba(248, 229, 160, 0.30);
    }

    .btn-danger {
        background: rgba(197, 48, 48, 0.10);
        color: #9b2c2c;
        border-color: rgba(197, 48, 48, 0.18);
    }

    .btn-danger:hover {
        background: rgba(197, 48, 48, 0.16);
    }

    .btn-secondary {
        border-color: var(--line);
        background: rgba(255, 255, 255, 0.76);
        color: rgba(10, 10, 10, 0.70);
    }

    .btn-secondary:hover {
        background: rgba(10, 10, 10, 0.06);
    }

    .btn-small {
        min-height: 34px;
        padding: 0 0.78rem;
        font-size: 0.8rem;
    }

    .empty-state {
        grid-column: 1 / -1;
        padding: 52px 24px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.72);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.74);
        box-shadow: var(--shadow);
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        border-radius: 50%;
        background: rgba(212, 175, 55, 0.13);
        color: var(--accent);
        font-size: 2rem;
    }

    .empty-state h3 {
        margin: 0 0 0.45rem;
        color: var(--primary);
        font-size: 1.3rem;
        font-weight: 800;
    }

    .empty-state p {
        margin: 0 0 1.2rem;
        color: rgba(10, 10, 10, 0.58);
    }

    .pagination-panel {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 18px;
        padding: 12px;
        border: 1px solid rgba(255, 255, 255, 0.72);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.72);
        box-shadow: var(--shadow);
        backdrop-filter: blur(18px) saturate(180%);
        flex-wrap: wrap;
    }

    .pagination-summary {
        color: rgba(10, 10, 10, 0.56);
        font-size: 0.86rem;
        font-weight: 700;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
        flex-wrap: wrap;
    }

    .pagination-link,
    .pagination-ellipsis {
        min-width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        border-radius: 8px;
        font-size: 0.86rem;
        font-weight: 800;
        text-decoration: none;
    }

    .pagination-link {
        border: 1px solid var(--line);
        background: rgba(255, 255, 255, 0.68);
        color: var(--primary);
        transition: var(--transition);
    }

    .pagination-link:hover {
        border-color: rgba(212, 175, 55, 0.48);
        background: rgba(248, 229, 160, 0.34);
    }

    .pagination-link.is-active {
        border-color: var(--accent);
        background: var(--accent);
        color: var(--primary);
        box-shadow: 0 8px 18px rgba(212, 175, 55, 0.22);
    }

    .pagination-link.is-disabled {
        opacity: 0.42;
        pointer-events: none;
    }

    .pagination-arrow {
        min-width: 38px;
    }

    .pagination-ellipsis {
        color: rgba(10, 10, 10, 0.42);
    }

    .modal {
        position: fixed;
        inset: 0;
        z-index: 3000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(10, 10, 10, 0.42);
        backdrop-filter: blur(8px);
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        width: min(100%, 480px);
        max-height: calc(100vh - 32px);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(255, 255, 255, 0.72);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 24px 70px rgba(10, 10, 10, 0.22);
        animation: slideIn 0.24s ease;
    }

    @keyframes slideIn {
        from { transform: translateY(-10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header,
    .modal-footer {
        flex-shrink: 0;
        padding: 1rem 1.15rem;
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--line);
    }

    .modal-header h3 {
        margin: 0;
        color: var(--primary);
        font-size: 1.2rem;
        font-weight: 850;
    }

    .modal-close {
        width: 36px;
        height: 36px;
        border: 0;
        border-radius: 8px;
        background: rgba(10, 10, 10, 0.06);
        color: var(--primary);
        cursor: pointer;
        font-size: 1.4rem;
        line-height: 1;
        transition: var(--transition);
    }

    .modal-close:hover {
        background: rgba(212, 175, 55, 0.18);
    }

    .modal-body {
        min-height: 0;
        overflow: auto;
        padding: 1.4rem;
        text-align: center;
    }

    .warning-icon {
        width: 68px;
        height: 68px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        border-radius: 50%;
        background: rgba(245, 101, 101, 0.12);
        color: var(--danger);
        font-size: 1.85rem;
    }

    .modal-body p {
        margin: 0;
        color: rgba(10, 10, 10, 0.66);
        font-size: 1rem;
        line-height: 1.6;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid var(--line);
    }

    @media (max-width: 768px) {
        .appointments-page {
            padding: 18px;
        }

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .filter-buttons {
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 4px;
        }

        .appointment-details {
            grid-template-columns: 1fr;
        }

        .appointment-actions {
            justify-content: stretch;
        }

        .appointment-actions .btn {
            flex: 1 1 calc(50% - 8px);
        }

        .pagination-panel {
            align-items: stretch;
            flex-direction: column;
        }

        .pagination-summary,
        .pagination-controls {
            justify-content: center;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .appointment-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .status-badge {
            align-self: flex-start;
        }

        .appointment-actions .btn,
        .modal-footer .btn {
            width: 100%;
            flex: 1 1 100%;
        }

        .modal-footer {
            flex-direction: column;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const appointmentsContainer = document.querySelector('.appointments-container');
    const appointmentCards = document.querySelectorAll('.appointment-card');
    let activeFilter = 'all';

    function reserveListHeight() {
        if (!appointmentsContainer) return;

        const listTop = appointmentsContainer.getBoundingClientRect().top;
        const comfortableViewportSpace = Math.max(window.innerHeight - listTop - 32, 260);
        const reservedHeight = Math.min(Math.max(appointmentsContainer.offsetHeight, 260), comfortableViewportSpace);

        appointmentsContainer.style.setProperty(
            '--appointments-list-height',
            `${reservedHeight}px`
        );
    }

    function shouldShowForFilter(card, filter) {
        const status = card.getAttribute('data-status');
        const date = card.getAttribute('data-date');
        const today = new Date().toISOString().split('T')[0];

        switch(filter) {
            case 'all':
                return true;
            case 'upcoming':
                return (status === 'pending_payment' || status === 'confirmed') && date >= today;
            case 'pending_payment':
                return status === 'pending_payment';
            case 'confirmed':
                return status === 'confirmed';
            case 'completed':
                return status === 'completed';
            case 'cancelled':
                return status === 'cancelled';
            default:
                return true;
        }
    }

    function applyAppointmentFilters() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';

        appointmentCards.forEach(card => {
            const service = card.getAttribute('data-service');
            const barber = card.getAttribute('data-barber');
            const recipient = card.getAttribute('data-recipient');
            const matchesSearch = searchTerm === '' || service.includes(searchTerm) || barber.includes(searchTerm) || recipient.includes(searchTerm);
            const matchesFilter = shouldShowForFilter(card, activeFilter);

            card.classList.toggle('is-filtered-out', !(matchesSearch && matchesFilter));
        });
    }

    requestAnimationFrame(reserveListHeight);
    
    searchInput?.addEventListener('input', applyAppointmentFilters);
    
    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            activeFilter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            applyAppointmentFilters();
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
