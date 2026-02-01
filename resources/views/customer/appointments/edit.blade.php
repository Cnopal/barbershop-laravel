@extends('customer.sidebar')

@section('title', 'Edit Appointment')

@section('content')
<div class="edit-appointment-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1>Edit Appointment</h1>
        <p>Make changes to your scheduled appointment</p>
    </div>

    <!-- Edit Form -->
    <div class="edit-form-container">
        <form action="{{ route('customer.appointments.update', $appointment->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <!-- Current Appointment Details -->
                <div class="current-appointment">
                    <h3>Current Appointment Details</h3>
                    <div class="current-details">
                        <div class="detail-item">
                            <span class="label">Service:</span>
                            <span class="value">{{ $appointment->service->name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Barber:</span>
                            <span class="value">{{ $appointment->barber->name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Date:</span>
                            <span class="value">{{ $appointment->appointment_date->format('M d, Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Time:</span>
                            <span class="value">
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - 
                                {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Status:</span>
                            <span class="value status-badge status-{{ $appointment->status }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Edit Options -->
                <div class="edit-options">
                    <h3>What would you like to change?</h3>
                    
                    <!-- Date & Time Change -->
                    <div class="option-section">
                        <h4><i class="fas fa-calendar-alt"></i> Change Date & Time</h4>
                        
                        <div class="form-group">
                            <label for="appointment_date">New Date</label>
                            <input type="date" 
                                   name="appointment_date" 
                                   id="appointment_date" 
                                   class="form-control"
                                   value="{{ $appointment->appointment_date->format('Y-m-d') }}"
                                   min="{{ date('Y-m-d') }}"
                                   max="{{ date('Y-m-d', strtotime('+30 days')) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="start_time">New Time</label>
                            <select name="start_time" id="start_time" class="form-control">
                                <option value="">Select Time</option>
                                @php
                                    $times = [
                                        '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                                        '12:00', '12:30', '14:00', '14:30', '15:00', '15:30',
                                        '16:00', '16:30', '17:00', '17:30', '18:00'
                                    ];
                                @endphp
                                @foreach($times as $time)
                                <option value="{{ $time }}" 
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') == $time ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($time)->format('h:i A') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Barber Change -->
                    <div class="option-section">
                        <h4><i class="fas fa-user-tie"></i> Change Barber</h4>
                        
                        <div class="barber-options">
                            @foreach($barbers as $barber)
                            <label class="barber-option">
                                <input type="radio" 
                                       name="barber_id" 
                                       value="{{ $barber->id }}"
                                       {{ $appointment->barber_id == $barber->id ? 'checked' : '' }}>
                                <div class="barber-card">
                                    <div class="barber-avatar">
                                        {{ strtoupper(substr($barber->name, 0, 2)) }}
                                    </div>
                                    <div class="barber-info">
                                        <h5>{{ $barber->name }}</h5>
                                        <p class="position">{{ $barber->position ?? 'Senior Barber' }}</p>
                                        <div class="availability">
                                            <i class="fas fa-check-circle"></i> Available
                                        </div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Notes Update -->
                    <div class="option-section">
                        <h4><i class="fas fa-sticky-note"></i> Update Notes</h4>
                        <div class="form-group">
                            <textarea name="notes" 
                                      id="notes" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Update your notes...">{{ $appointment->notes }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('customer.appointments.show', $appointment->id) }}" 
                   class="btn btn-outline">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .edit-appointment-page {
        max-width: 1000px;
        margin: 0 auto;
    }

    .page-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: var(--secondary);
    }

    .edit-form-container {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 2rem;
        border: 1px solid var(--medium-gray);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .current-appointment h3,
    .edit-options h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--medium-gray);
    }

    .current-details {
        background: var(--light-gray);
        border-radius: var(--radius);
        padding: 1.5rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .detail-item:last-child {
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
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
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

    .option-section {
        margin-bottom: 2rem;
    }

    .option-section:last-child {
        margin-bottom: 0;
    }

    .option-section h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .option-section h4 i {
        color: var(--accent);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--primary);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--medium-gray);
        border-radius: var(--radius);
        font-size: 1rem;
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    select.form-control {
        cursor: pointer;
    }

    .barber-options {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
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
        padding: 1rem;
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
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.25rem;
        font-weight: bold;
        flex-shrink: 0;
    }

    .barber-info {
        flex: 1;
    }

    .barber-info h5 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .position {
        color: var(--accent);
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .availability {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        color: var(--success);
    }

    .availability i {
        font-size: 0.75rem;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding-top: 2rem;
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

    @media (max-width: 480px) {
        .edit-form-container {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .barber-options {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Barber selection
    document.querySelectorAll('.barber-option input').forEach(input => {
        input.addEventListener('change', function() {
            document.querySelectorAll('.barber-card').forEach(card => {
                card.style.borderColor = 'var(--medium-gray)';
                card.style.background = 'var(--light-gray)';
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = 'none';
            });
            
            if (this.checked) {
                const card = this.closest('.barber-option').querySelector('.barber-card');
                card.style.borderColor = 'var(--accent)';
                card.style.background = 'rgba(212, 175, 55, 0.05)';
                card.style.transform = 'translateY(-2px)';
                card.style.boxShadow = 'var(--shadow)';
            }
        });
    });
    
    // Date validation
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('start_time');
    
    if (dateInput && timeSelect) {
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                alert('Please select a future date');
                this.value = today.toISOString().split('T')[0];
            }
        });
        
        // Time slot availability check (simulated)
        timeSelect.addEventListener('change', function() {
            if (this.value) {
                // In a real app, this would be an AJAX call to check availability
                console.log('Checking availability for:', dateInput.value, this.value);
            }
        });
    }
    
    // Form submission validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (!dateInput.value || !timeSelect.value) {
            e.preventDefault();
            alert('Please select both date and time');
            return;
        }
        
        // Check if any changes were made
        const originalDate = '{{ $appointment->appointment_date->format("Y-m-d") }}';
        const originalTime = '{{ \Carbon\Carbon::parse($appointment->start_time)->format("H:i") }}';
        const originalBarber = {{ $appointment->barber_id }};
        
        if (dateInput.value === originalDate && 
            timeSelect.value === originalTime) {
            // Check if barber was changed
            const selectedBarber = document.querySelector('input[name="barber_id"]:checked').value;
            if (selectedBarber == originalBarber) {
                // Check if notes were changed
                const notesTextarea = document.getElementById('notes');
                const originalNotes = '{{ $appointment->notes }}';
                if (notesTextarea.value.trim() === originalNotes.trim()) {
                    e.preventDefault();
                    alert('No changes were made to the appointment');
                    return;
                }
            }
        }
        
        // Show loading indicator
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;
    });
});
</script>
@endsection