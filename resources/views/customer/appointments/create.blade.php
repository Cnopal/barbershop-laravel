@extends('customer.sidebar')

@section('title', 'Book Appointment')

@section('content')
<div class="booking-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1>Book New Appointment</h1>
        <p>Fill in the details below to schedule your appointment</p>
    </div>

    <!-- Display success/error messages -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <h4><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h4>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Progress Indicator -->
    <div class="progress-indicator">
        <div class="progress-step active" data-step="1">
            <div class="step-circle">1</div>
            <div class="step-label">Service</div>
        </div>
        <div class="progress-step" data-step="2">
            <div class="step-circle">2</div>
            <div class="step-label">Barber</div>
        </div>
        <div class="progress-step" data-step="3">
            <div class="step-circle">3</div>
            <div class="step-label">Date & Time</div>
        </div>
        <div class="progress-step" data-step="4">
            <div class="step-circle">4</div>
            <div class="step-label">Confirm</div>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="booking-form-container">
        <form action="{{ route('customer.appointments.store') }}" method="POST" id="bookingForm">
            @csrf
            
            <!-- Hidden time input -->
            <input type="hidden" name="start_time" id="hiddenStartTime">
            
            <div class="form-grid">
                <!-- Step 1: Select Service -->
                <div class="form-step active" id="step1">
                    <div class="step-header">
                        <div class="step-number">1</div>
                        <h2>Select Service</h2>
                    </div>
                    
                    <div class="services-grid">
                        @foreach($services as $service)
                        <label class="service-option" for="service{{ $service->id }}">
                            <input type="radio" 
                                   name="service_id" 
                                   id="service{{ $service->id }}" 
                                   value="{{ $service->id }}"
                                   data-price="{{ $service->price }}"
                                   data-duration="{{ $service->duration }}"
                                   {{ old('service_id') == $service->id ? 'checked' : '' }}
                                   required>
                            <div class="service-card">
                                <div class="service-icon">
                                    @php
                                        $icon = 'fas fa-cut';
                                        $nameLower = strtolower($service->name);
                                        if (str_contains($nameLower, 'shave') || str_contains($nameLower, 'beard')) {
                                            $icon = 'fas fa-razor';
                                        } elseif (str_contains($nameLower, 'style') || str_contains($nameLower, 'styling')) {
                                            $icon = 'fas fa-spray-can';
                                        } elseif (str_contains($nameLower, 'trim')) {
                                            $icon = 'fas fa-scissors';
                                        } elseif (str_contains($nameLower, 'wash') || str_contains($nameLower, 'shampoo')) {
                                            $icon = 'fas fa-shower';
                                        } elseif (str_contains($nameLower, 'color') || str_contains($nameLower, 'dye')) {
                                            $icon = 'fas fa-palette';
                                        } elseif (str_contains($nameLower, 'massage')) {
                                            $icon = 'fas fa-spa';
                                        }
                                    @endphp
                                    <i class="{{ $icon }}"></i>
                                </div>
                                <div class="service-info">
                                    <h4>{{ $service->name }}</h4>
                                    <p>{{ $service->description ? Str::limit($service->description, 60) : 'Professional service' }}</p>
                                </div>
                                <div class="service-details">
                                    <span class="duration">{{ $service->duration }} min</span>
                                    <span class="price">RM{{ number_format($service->price, 2) }}</span>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn btn-primary next-step" data-next="step2">
                            Next: Select Barber <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Select Barber -->
                <div class="form-step" id="step2">
                    <div class="step-header">
                        <div class="step-number">2</div>
                        <h2>Select Barber</h2>
                    </div>
                    
                    <div class="barbers-grid">
                        @foreach($barbers as $barber)
                        <label class="barber-option" for="barber{{ $barber->id }}">
                            <input type="radio" 
                                   name="barber_id" 
                                   id="barber{{ $barber->id }}" 
                                   value="{{ $barber->id }}"
                                   {{ old('barber_id') == $barber->id ? 'checked' : '' }}
                                   required>
                            <div class="barber-card">
                                <div class="barber-avatar">
                                    @if($barber->profile_image)
                                        <img src="{{ asset('storage/' . $barber->profile_image) }}" alt="{{ $barber->name }}">
                                    @else
                                        {{ strtoupper(substr($barber->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div class="barber-info">
                                    <h4>{{ $barber->name }}</h4>
                                    <p class="position">{{ $barber->position ?? 'Professional Barber' }}</p>
                                    <div class="barber-stats">
                                        <span class="stat">
                                            <i class="fas fa-star"></i> 4.8
                                        </span>
                                        <span class="stat">
                                            <i class="fas fa-clock"></i> 5+ yrs
                                        </span>
                                    </div>
                                </div>
                                <div class="barber-status">
                                    <span class="status-badge status-active">Available</span>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn btn-outline prev-step" data-prev="step1">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn btn-primary next-step" data-next="step3">
                            Next: Select Date & Time <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Select Date & Time -->
                <div class="form-step" id="step3">
                    <div class="step-header">
                        <div class="step-number">3</div>
                        <h2>Select Date & Time</h2>
                    </div>
                    
                    <div class="datetime-selection">
                        <div class="date-selection">
                            <h4>Select Date</h4>
                            <input type="date" 
                                   name="appointment_date" 
                                   id="appointment_date" 
                                   class="form-control"
                                   min="{{ date('Y-m-d') }}"
                                   max="{{ date('Y-m-d', strtotime('+60 days')) }}"
                                   value="{{ old('appointment_date', date('Y-m-d')) }}"
                                   required>
                            <small class="text-muted" id="dateMessage"></small>
                        </div>
                        
                        <div class="time-selection">
                            <h4>Select Time</h4>
                            <div id="timeSlotLoading" class="slot-loading">
                                <div class="loading-spinner"></div>
                                <p>Loading available time slots...</p>
                            </div>
                            <div class="time-slots-container" id="timeSlotsContainer">
                                <div class="time-slots" id="timeSlots">
                                    <div class="no-selection">
                                        <i class="fas fa-calendar-alt"></i>
                                        <p>Please select a date to see available time slots</p>
                                    </div>
                                </div>
                                <div class="slot-info" id="slotInfo">
                                    <p id="availableCount"></p>
                                    <p id="timeMessage" class="text-muted"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn btn-outline prev-step" data-prev="step2">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn btn-primary next-step" data-next="step4" id="nextStep3" disabled>
                            Next: Review & Book <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Review & Book -->
                <div class="form-step" id="step4">
                    <div class="step-header">
                        <div class="step-number">4</div>
                        <h2>Review & Book</h2>
                    </div>
                    
                    <div class="review-summary">
                        <div class="summary-card">
                            <h3><i class="fas fa-clipboard-check"></i> Appointment Summary</h3>
                            
                            <div class="summary-item">
                                <span class="label">Service:</span>
                                <span class="value" id="summaryService">-</span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="label">Barber:</span>
                                <span class="value" id="summaryBarber">-</span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="label">Date:</span>
                                <span class="value" id="summaryDate">-</span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="label">Time:</span>
                                <span class="value" id="summaryTime">-</span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="label">Duration:</span>
                                <span class="value" id="summaryDuration">-</span>
                            </div>
                            
                            <div class="summary-item total">
                                <span class="label">Total Price:</span>
                                <span class="value price" id="summaryPrice">-</span>
                            </div>
                        </div>
                        
                        <div class="additional-notes">
                            <h4><i class="fas fa-sticky-note"></i> Additional Notes (Optional)</h4>
                            <textarea name="notes" 
                                      id="notes" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Any special requests, hairstyle, alergies, or notes for your barber...">{{ old('notes') }}</textarea>
                            <small class="text-muted">Maximum 500 characters</small>
                        </div>
                    </div>
                    
                    <div class="booking-terms">
                        <div class="terms-checkbox">
                            <input type="checkbox" id="agreeTerms" required>
                            <label for="agreeTerms">
                                I understand that:
                                <ul>
                                    <li>Cannot update appointment details after booking</li>
                                    <li>No-shows may result in a cancellation fee</li>
                                    <li>Once booked, appointments cannot be canceled or rescheduled</li>
                                    <li>No refunds will be issued for missed or appointments cancellation</li>
                                    <!-- <li></li> -->
                                </ul>
                            </label>
                        </div>
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn btn-outline prev-step" data-prev="step3">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-calendar-check"></i> Confirm Booking
                        </button>
                    </div>
                </div>
            </div>
        </form>
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
    .booking-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }

    .page-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: var(--secondary);
        font-size: 1.125rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Alerts */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: var(--radius);
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert i {
        font-size: 1.25rem;
        margin-top: 0.125rem;
    }

    .alert-success {
        background: rgba(72, 187, 120, 0.1);
        border: 1px solid rgba(72, 187, 120, 0.2);
        color: var(--success);
    }

    .alert-danger {
        background: rgba(245, 101, 101, 0.1);
        border: 1px solid rgba(245, 101, 101, 0.2);
        color: var(--danger);
    }

    .alert-danger h4 {
        margin-top: 0;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 1.25rem;
    }

    .alert-danger li {
        margin-bottom: 0.25rem;
    }

    /* Progress Indicator */
    .progress-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3rem;
        position: relative;
        padding: 0 1rem;
    }

    .progress-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 50px;
        right: 50px;
        height: 2px;
        background: var(--medium-gray);
        z-index: 0;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
        flex: 1;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--light-gray);
        border: 2px solid var(--medium-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--secondary);
        transition: var(--transition);
        margin-bottom: 0.5rem;
    }

    .progress-step.active .step-circle {
        background: var(--accent);
        border-color: var(--accent);
        color: var(--primary);
        transform: scale(1.1);
    }

    .step-label {
        font-size: 0.875rem;
        color: var(--secondary);
        text-align: center;
        font-weight: 500;
    }

    .progress-step.active .step-label {
        color: var(--primary);
        font-weight: 600;
    }

    /* Booking Form Container */
    .booking-form-container {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        border: 1px solid var(--medium-gray);
    }

    .form-step {
        display: none;
        padding: 2rem;
    }

    .form-step.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .step-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--medium-gray);
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--accent);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .step-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        margin: 0;
    }

    /* Services Grid */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .service-option {
        display: block;
        cursor: pointer;
    }

    .service-option input {
        display: none;
    }

    .service-card {
        background: var(--light-gray);
        border: 2px solid var(--medium-gray);
        border-radius: var(--radius);
        padding: 1.5rem;
        transition: var(--transition);
        height: 100%;
    }

    .service-option input:checked + .service-card {
        border-color: var(--accent);
        background: rgba(212, 175, 55, 0.05);
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .service-card:hover {
        border-color: var(--accent);
        transform: translateY(-2px);
    }

    .service-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .service-info h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .service-info p {
        color: var(--secondary);
        font-size: 0.875rem;
        line-height: 1.4;
        margin-bottom: 1rem;
        min-height: 40px;
    }

    .service-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .duration {
        color: var(--secondary);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .duration::before {
        content: '⏱️';
    }

    .price {
        color: var(--accent);
        font-weight: 600;
        font-size: 1.125rem;
    }

    /* Barbers Grid */
    .barbers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .barber-option {
        display: block;
        cursor: pointer;
    }

    .barber-option input {
        display: none;
    }

    .barber-card {
        background: var(--light-gray);
        border: 2px solid var(--medium-gray);
        border-radius: var(--radius);
        padding: 1.5rem;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .barber-option input:checked + .barber-card {
        border-color: var(--accent);
        background: rgba(212, 175, 55, 0.05);
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .barber-card:hover {
        border-color: var(--accent);
        transform: translateY(-2px);
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
        overflow: hidden;
    }

    .barber-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .barber-info {
        flex: 1;
    }

    .barber-info h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .position {
        color: var(--accent);
        font-weight: 500;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .barber-stats {
        display: flex;
        gap: 1rem;
    }

    .stat {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.875rem;
        color: var(--secondary);
    }

    .stat i {
        color: var(--accent);
    }

    .barber-status {
        flex-shrink: 0;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-active {
        background: rgba(72, 187, 120, 0.1);
        color: var(--success);
        border: 1px solid rgba(72, 187, 120, 0.2);
    }

    /* Date & Time Selection */
    .datetime-selection {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .datetime-selection {
            grid-template-columns: 1fr;
        }
    }

    .date-selection h4,
    .time-selection h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1px solid var(--medium-gray);
        border-radius: var(--radius);
        font-size: 1rem;
        transition: var(--transition);
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .text-muted {
        color: var(--secondary);
        font-size: 0.875rem;
        display: block;
        margin-top: 0.25rem;
    }

    /* Time Slots Loading */
    .slot-loading {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        text-align: center;
    }

    .slot-loading.active {
        display: flex;
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid var(--light-gray);
        border-top-color: var(--accent);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 1rem;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .slot-loading p {
        color: var(--secondary);
    }

    /* Time Slots Container */
    .time-slots-container {
        display: none;
    }

    .time-slots-container.active {
        display: block;
    }

    .time-slots {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        min-height: 200px;
    }

    .no-selection {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        text-align: center;
        color: var(--secondary);
    }

    .no-selection i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .time-slot {
        padding: 0.75rem 0.5rem;
        border: 1px solid var(--medium-gray);
        border-radius: var(--radius);
        background: var(--light-gray);
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.875rem;
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }

    .time-slot:hover:not(.unavailable):not(.past) {
        border-color: var(--accent);
        background: rgba(212, 175, 55, 0.05);
        transform: translateY(-1px);
    }

    .time-slot.selected {
        background: var(--accent) !important;
        color: var(--primary) !important;
        border-color: var(--accent) !important;
        font-weight: 600;
        transform: translateY(-1px);
        box-shadow: var(--shadow);
    }

    .time-slot.unavailable {
        background: var(--light-gray);
        color: var(--secondary);
        cursor: not-allowed;
        opacity: 0.6;
        position: relative;
    }

    .time-slot.unavailable::after {
        content: '✗';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.5rem;
        opacity: 0.3;
    }

    .time-slot.past {
        background: #f8f9fa;
        color: #adb5bd;
        cursor: not-allowed;
    }

    .time-slot.past::before {
        content: '⏰';
        margin-right: 0.25rem;
    }

    /* Slot Info */
    .slot-info {
        padding: 1rem;
        border-radius: var(--radius);
        background: var(--light-gray);
        border: 1px solid var(--medium-gray);
    }

    .slot-info p {
        margin: 0.25rem 0;
    }

    #availableCount {
        color: var(--success);
        font-weight: 600;
    }

    #timeMessage {
        font-size: 0.875rem;
    }

    /* Review Summary */
    .review-summary {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .review-summary {
            grid-template-columns: 1fr;
        }
    }

    .summary-card {
        background: var(--light-gray);
        border-radius: var(--radius);
        padding: 1.5rem;
        border: 1px solid var(--medium-gray);
    }

    .summary-card h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--medium-gray);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .summary-item.total {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid var(--accent);
        border-bottom: none;
    }

    .summary-item:last-child {
        margin-bottom: 0;
        border-bottom: none;
    }

    .label {
        color: var(--secondary);
        font-weight: 500;
    }

    .value {
        color: var(--primary);
        font-weight: 600;
        text-align: right;
        max-width: 60%;
        word-break: break-word;
    }

    .value.price {
        color: var(--accent);
        font-size: 1.25rem;
    }

    .additional-notes h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Booking Terms */
    .booking-terms {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: var(--light-gray);
        border-radius: var(--radius);
        border: 1px solid var(--medium-gray);
    }

    .terms-checkbox {
        display: flex;
        gap: 1rem;
    }

    .terms-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-top: 0.25rem;
        accent-color: var(--accent);
    }

    .terms-checkbox label {
        flex: 1;
        color: var(--secondary);
        line-height: 1.6;
    }

    .terms-checkbox ul {
        margin: 0.5rem 0 0 1.5rem;
        padding: 0;
    }

    .terms-checkbox li {
        margin-bottom: 0.25rem;
    }

    /* Step Actions */
    .step-actions {
        display: flex;
        justify-content: space-between;
        padding-top: 1.5rem;
        border-top: 1px solid var(--medium-gray);
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
        white-space: nowrap;
    }

    .btn-primary {
        background: var(--accent);
        color: var(--primary);
    }

    .btn-primary:hover:not(:disabled) {
        background: #c19a2f;
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
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

    /* Responsive */
    @media (max-width: 768px) {
        .progress-indicator {
            display: none;
        }
        
        .step-actions {
            flex-direction: column;
            gap: 1rem;
        }
        
        .step-actions .btn {
            width: 100%;
        }
        
        .services-grid,
        .barbers-grid {
            grid-template-columns: 1fr;
        }
        
        .time-slots {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }
    }

    @media (max-width: 480px) {
        .booking-page {
            padding: 0.5rem;
        }
        
        .form-step {
            padding: 1.5rem;
        }
        
        .page-header h1 {
            font-size: 2rem;
        }
        
        .step-header h2 {
            font-size: 1.25rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables to store selections
    let selectedService = null;
    let selectedBarber = null;
    let selectedDate = null;
    let selectedTime = null;
    let selectedTimeDisplay = null;
    
    // Get DOM elements
    const dateInput = document.getElementById('appointment_date');
    const dateMessage = document.getElementById('dateMessage');
    const timeSlotsContainer = document.getElementById('timeSlotsContainer');
    const timeSlots = document.getElementById('timeSlots');
    const slotInfo = document.getElementById('slotInfo');
    const availableCount = document.getElementById('availableCount');
    const timeMessage = document.getElementById('timeMessage');
    const timeSlotLoading = document.getElementById('timeSlotLoading');
    const nextStep3 = document.getElementById('nextStep3');
    
    // Step navigation
    const steps = document.querySelectorAll('.form-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    let currentStep = 0;
    
    // Initialize form with today's date
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
        selectedDate = today;
    }
    
    // Restore selections from old input if validation failed
    @if(old('service_id'))
    selectedService = "{{ old('service_id') }}";
    document.querySelector(`input[name="service_id"][value="{{ old('service_id') }}"]`).checked = true;
    @endif
    
    @if(old('barber_id'))
    selectedBarber = "{{ old('barber_id') }}";
    document.querySelector(`input[name="barber_id"][value="{{ old('barber_id') }}"]`).checked = true;
    @endif
    
    @if(old('appointment_date'))
    selectedDate = "{{ old('appointment_date') }}";
    dateInput.value = "{{ old('appointment_date') }}";
    @endif
    
    @if(old('start_time'))
    selectedTime = "{{ old('start_time') }}";
    @endif
    
    // If we have all selections, load time slots
    if (selectedService && selectedBarber && selectedDate) {
        setTimeout(() => loadAvailableSlots(), 500);
    }
    
    // Service selection
    document.querySelectorAll('input[name="service_id"]').forEach(input => {
        input.addEventListener('change', function() {
            selectedService = this.value;
            console.log('Service selected:', selectedService);
            
            // If barber and date are already selected, load slots
            if (selectedBarber && selectedDate) {
                loadAvailableSlots();
            }
        });
    });
    
    // Barber selection
    document.querySelectorAll('input[name="barber_id"]').forEach(input => {
        input.addEventListener('change', function() {
            selectedBarber = this.value;
            console.log('Barber selected:', selectedBarber);
            
            // If service and date are already selected, load slots
            if (selectedService && selectedDate) {
                loadAvailableSlots();
            }
        });
    });
    
    // Date selection
    dateInput.addEventListener('change', function() {
        selectedDate = this.value;
        console.log('Date selected:', selectedDate);
        
        // Clear time selection
        selectedTime = null;
        selectedTimeDisplay = null;
        document.getElementById('hiddenStartTime').value = '';
        updateStepButtons();
        
        // Validate date
        const selected = new Date(selectedDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selected < today) {
            dateMessage.textContent = '⚠️ Selected date has already passed';
            dateMessage.style.color = 'var(--danger)';
            showNoSlots('Cannot select a past date');
            return;
        } else {
            dateMessage.textContent = '';
        }
        
        // If service and barber are already selected, load slots
        if (selectedService && selectedBarber) {
            loadAvailableSlots();
        } else {
            showNoSlots('Please select a service and barber first');
        }
    });
    
    // Load available time slots
    function loadAvailableSlots() {
        console.log('Loading slots for:', { selectedService, selectedBarber, selectedDate });
        
        if (!selectedService || !selectedBarber || !selectedDate) {
            showNoSlots('Please select service, barber and date');
            return;
        }
        
        // Show loading
        timeSlotLoading.classList.add('active');
        timeSlotsContainer.classList.remove('active');
        
        // Make AJAX request
        fetch(`/customer/appointments/slots/available?date=${selectedDate}&barber_id=${selectedBarber}&service_id=${selectedService}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('API Response:', data);
                
                // Hide loading
                timeSlotLoading.classList.remove('active');
                timeSlotsContainer.classList.add('active');
                
                // Check for error
                if (data.error) {
                    showNoSlots(data.error);
                    return;
                }
                
                // No available slots
                if (!data.available_slots || data.available_slots.length === 0) {
                    showNoSlots('No available time slots for this date. Please select another date or try a different barber.');
                    return;
                }
                
                // Display available slots
                renderTimeSlots(data.available_slots);
                
                // Show slot count
                const count = data.available_slots.length;
                availableCount.textContent = `${count} available time slot${count !== 1 ? 's' : ''}`;
                
                // Check current time
                const now = new Date();
                const isToday = new Date(selectedDate).toDateString() === now.toDateString();
                
                if (isToday) {
                    timeMessage.textContent = 'Times are displayed in your local timezone';
                } else {
                    timeMessage.textContent = '';
                }
                
            })
            .catch(error => {
                console.error('Error loading time slots:', error);
                timeSlotLoading.classList.remove('active');
                showNoSlots('Error loading time slots. Please try again.');
            });
    }
    
    // Render time slots
    function renderTimeSlots(slots) {
        let html = '';
        
        slots.forEach(slot => {
            const isPast = slot.past || false;
            const isSelected = selectedTime === slot.start;
            
            html += `
                <div class="time-slot ${isPast ? 'past' : ''} ${isSelected ? 'selected' : ''}" 
                     data-time="${slot.start}"
                     data-display="${slot.display}"
                     title="${isPast ? 'This time has already passed' : 'Click to select'}">
                    ${slot.display}
                </div>
            `;
        });
        
        timeSlots.innerHTML = html;
        
        // If we have a previously selected time, select it
        if (selectedTime) {
            const slotElement = document.querySelector(`.time-slot[data-time="${selectedTime}"]`);
            if (slotElement && !slotElement.classList.contains('past')) {
                slotElement.classList.add('selected');
                updateStepButtons();
            } else {
                // Selected time is no longer available
                selectedTime = null;
                selectedTimeDisplay = null;
                document.getElementById('hiddenStartTime').value = '';
                updateStepButtons();
            }
        }
    }
    
    // Show no slots message
    function showNoSlots(message) {
        timeSlots.innerHTML = `
            <div class="no-selection">
                <i class="fas fa-calendar-times"></i>
                <p>${message}</p>
            </div>
        `;
        timeSlotsContainer.classList.add('active');
        slotInfo.style.display = 'none';
        availableCount.textContent = '';
        timeMessage.textContent = '';
    }
    
    // Time slot selection
    document.addEventListener('click', function(e) {
        const timeSlot = e.target.closest('.time-slot');
        if (!timeSlot) return;
        
        if (timeSlot.classList.contains('past') || timeSlot.classList.contains('unavailable')) {
            // Show tooltip for why it's not selectable
            const title = timeSlot.getAttribute('title');
            if (title) {
                alert(title);
            }
            return;
        }
        
        // Clear previous selection
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.classList.remove('selected');
        });
        
        // Set new selection
        timeSlot.classList.add('selected');
        selectedTime = timeSlot.dataset.time;
        selectedTimeDisplay = timeSlot.dataset.display;
        document.getElementById('hiddenStartTime').value = selectedTime;
        
        console.log('Time selected:', selectedTime);
        
        // Update next button
        updateStepButtons();
    });
    
    // Update step buttons state
    function updateStepButtons() {
        if (nextStep3) {
            nextStep3.disabled = !selectedTime;
        }
        
        // Update progress steps
        updateProgressSteps();
    }
    
    // Update progress steps
    function updateProgressSteps() {
        progressSteps.forEach((step, index) => {
            if (index <= currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
    }
    
    // Show specific step
    function showStep(stepIndex) {
        // Validate before leaving current step
        if (currentStep === 2 && stepIndex === 3) {
            if (!validateTimeSelection()) {
                return;
            }
        }
        
        steps.forEach((step, index) => {
            step.classList.toggle('active', index === stepIndex);
        });
        
        currentStep = stepIndex;
        updateProgressSteps();
        
        // Scroll to top of form
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // Validate time selection
    function validateTimeSelection() {
        if (!selectedDate) {
            alert('Please select a date');
            return false;
        }
        
        if (!selectedTime) {
            alert('Please select a time slot');
            return false;
        }
        
        // Check if selected time has passed (for today)
        const today = new Date().toISOString().split('T')[0];
        if (selectedDate === today) {
            const now = new Date();
            const selectedDateTime = new Date(`${selectedDate}T${selectedTime}`);
            
            if (selectedDateTime < now) {
                alert('⚠️ The selected time has already passed.\n\nPlease choose a future time slot.');
                
                // Clear the invalid selection
                selectedTime = null;
                selectedTimeDisplay = null;
                document.getElementById('hiddenStartTime').value = '';
                document.querySelector('.time-slot.selected')?.classList.remove('selected');
                updateStepButtons();
                loadAvailableSlots();
                
                return false;
            }
        }
        
        return true;
    }
    
    // Next step buttons
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            const nextStepId = this.getAttribute('data-next');
            const nextStepIndex = Array.from(steps).findIndex(step => step.id === nextStepId);
            
            if (validateStep(currentStep)) {
                if (nextStepId === 'step4') {
                    updateSummary();
                }
                showStep(nextStepIndex);
            }
        });
    });
    
    // Previous step buttons
    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function() {
            const prevStepId = this.getAttribute('data-prev');
            const prevStepIndex = Array.from(steps).findIndex(step => step.id === prevStepId);
            showStep(prevStepIndex);
        });
    });
    
    // Step validation
    function validateStep(stepIndex) {
        const step = steps[stepIndex];
        
        switch(stepIndex) {
            case 0: // Service
                const serviceSelected = step.querySelector('input[name="service_id"]:checked');
                if (!serviceSelected) {
                    alert('Please select a service');
                    return false;
                }
                return true;
                
            case 1: // Barber
                const barberSelected = step.querySelector('input[name="barber_id"]:checked');
                if (!barberSelected) {
                    alert('Please select a barber');
                    return false;
                }
                return true;
                
            case 2: // Date & Time
                return validateTimeSelection();
        }
        
        return true;
    }
    
    // Update summary
    function updateSummary() {
        // Get selected service
        const serviceInput = document.querySelector('input[name="service_id"]:checked');
        if (serviceInput) {
            const serviceCard = serviceInput.closest('.service-option').querySelector('.service-card');
            document.getElementById('summaryService').textContent = 
                serviceCard.querySelector('h4').textContent;
            document.getElementById('summaryPrice').textContent = 
                serviceCard.querySelector('.price').textContent;
            document.getElementById('summaryDuration').textContent = 
                serviceCard.querySelector('.duration').textContent;
        }
        
        // Get selected barber
        const barberInput = document.querySelector('input[name="barber_id"]:checked');
        if (barberInput) {
            const barberCard = barberInput.closest('.barber-option').querySelector('.barber-card');
            document.getElementById('summaryBarber').textContent = 
                barberCard.querySelector('h4').textContent;
        }
        
        // Get selected date
        if (selectedDate) {
            const date = new Date(selectedDate);
            document.getElementById('summaryDate').textContent = 
                date.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
        }
        
        // Get selected time
        if (selectedTimeDisplay) {
            document.getElementById('summaryTime').textContent = selectedTimeDisplay;
        } else if (selectedTime) {
            const time = selectedTime.split(':');
            const hour = parseInt(time[0]);
            const minute = time[1];
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour % 12 || 12;
            document.getElementById('summaryTime').textContent = 
                `${displayHour}:${minute} ${ampm}`;
        }
    }
    
    // Form submission
    const bookingForm = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    
    bookingForm.addEventListener('submit', function(e) {
        // Prevent double submission
        if (submitBtn.disabled) {
            e.preventDefault();
            return;
        }
        
        // Validate all steps
        if (!validateStep(0) || !validateStep(1) || !validateStep(2)) {
            e.preventDefault();
            alert('Please complete all required fields');
            return;
        }
        
        // Validate terms agreement
        const agreeTerms = document.getElementById('agreeTerms');
        if (!agreeTerms.checked) {
            e.preventDefault();
            alert('Please agree to the booking terms and conditions');
            agreeTerms.focus();
            return;
        }
        
        // Check if time is still selected
        if (!selectedTime) {
            e.preventDefault();
            alert('Please select a time slot');
            showStep(2); // Go back to time selection
            return;
        }
        
        // Check if time has passed (final check)
        const today = new Date().toISOString().split('T')[0];
        if (selectedDate === today) {
            const now = new Date();
            const selectedDateTime = new Date(`${selectedDate}T${selectedTime}`);
            
            if (selectedDateTime < now) {
                e.preventDefault();
                alert('⚠️ The selected time has already passed. Please choose a different time slot.');
                
                // Clear selection and reload
                selectedTime = null;
                selectedTimeDisplay = null;
                document.getElementById('hiddenStartTime').value = '';
                showStep(2);
                loadAvailableSlots();
                return;
            }
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Allow form submission
        console.log('Form submitted with:', {
            service: selectedService,
            barber: selectedBarber,
            date: selectedDate,
            time: selectedTime
        });
    });
    
    // Update progress steps initially
    updateProgressSteps();
});
</script>
@endsection