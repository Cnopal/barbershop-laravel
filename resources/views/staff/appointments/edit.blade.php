@extends('staff.sidebar')

@section('page-title', 'Edit Appointment')

@section('content')
<style>
    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid var(--medium-gray);
        margin-top: 20px;
        max-width: 600px;
    }

    .form-card {
        margin-bottom: 30px;
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }

    .form-card-header {
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
        padding: 24px;
        border-bottom: 1px solid var(--medium-gray);
    }

    .form-card-header h3 {
        margin: 0;
        font-size: 18px;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-card-header i {
        color: var(--accent);
        font-size: 20px;
    }

    .form-card-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 28px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--primary);
        font-size: 14px;
    }

    .form-group label.required::after {
        content: " *";
        color: var(--danger);
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        border-radius: 8px;
        border: 2px solid var(--medium-gray);
        font-size: 15px;
        transition: all 0.3s ease;
        background: white;
        color: var(--primary);
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .form-text {
        display: block;
        margin-top: 6px;
        font-size: 13px;
        color: var(--dark-gray);
    }

    .info-section {
        background: #f8f9fa;
        border-left: 4px solid var(--accent);
        padding: 20px;
        border-radius: 6px;
        margin-bottom: 30px;
    }

    .info-section h4 {
        margin: 0 0 15px 0;
        color: var(--primary);
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .info-row span:first-child {
        color: var(--dark-gray);
        font-weight: 500;
    }

    .info-row span:last-child {
        color: var(--primary);
        font-weight: 600;
    }

    .form-actions {
        padding: 30px;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
    }

    .btn {
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent) 0%, #e6c158 100%);
        color: var(--primary);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
    }

    .btn-secondary {
        background: white;
        color: var(--primary);
        border: 2px solid var(--medium-gray);
    }

    .btn-secondary:hover {
        background: var(--light-gray);
        border-color: var(--accent);
    }

    .locked-message {
        background: linear-gradient(135deg, #fee 0%, #fdd 100%);
        border-left: 4px solid var(--danger);
        padding: 20px;
        border-radius: 6px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .locked-message i {
        font-size: 24px;
        color: var(--danger);
        flex-shrink: 0;
    }

    .locked-message div {
        flex: 1;
    }

    .locked-message h4 {
        margin: 0 0 5px 0;
        color: var(--danger);
        font-size: 15px;
        font-weight: 600;
    }

    .locked-message p {
        margin: 0;
        color: var(--primary);
        font-size: 13px;
    }

    .form-disabled {
        opacity: 0.6;
        pointer-events: none;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h1 style="margin: 0; font-size: 28px;">Update Appointment Status</h1>
    <a href="{{ route('staff.appointments.show', $appointment->id) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="form-container">
    @if(in_array($appointment->status, ['completed', 'cancelled']))
        <!-- Locked Message -->
        <div class="locked-message">
            <i class="fas fa-lock"></i>
            <div>
                <h4>Appointment Locked</h4>
                <p>This appointment has been marked as {{ $appointment->status }} and cannot be edited anymore.</p>
            </div>
        </div>
    @endif

    <!-- Appointment Details Info -->
    <div class="info-section">
        <h4><i class="fas fa-info-circle"></i> Appointment Details</h4>
        <div class="info-row">
            <span>Customer:</span>
            <span>{{ $appointment->customer->name }}</span>
        </div>
        <div class="info-row">
            <span>Service:</span>
            <span>{{ $appointment->service->name }}</span>
        </div>
        <div class="info-row">
            <span>Date:</span>
            <span>{{ $appointment->appointment_date->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span>Time:</span>
            <span>{{ date('h:i A', strtotime($appointment->start_time)) }} - {{ date('h:i A', strtotime($appointment->end_time)) }}</span>
        </div>
        <div class="info-row">
            <span>Price:</span>
            <span>RM{{ number_format($appointment->price, 2) }}</span>
        </div>
    </div>

    <form action="{{ route('staff.appointments.update', $appointment->id) }}" method="POST" @if(in_array($appointment->status, ['completed', 'cancelled'])) class="form-disabled" @endif>
        @csrf
        @method('PUT')

        <!-- Status Card -->
        <div class="form-card">
            <div class="form-card-header">
                <h3><i class="fas fa-tasks"></i> Update Status</h3>
            </div>
            <div class="form-card-body">
                <div class="form-group">
                    <label for="status" class="required">Status</label>
                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="">-- Select Status --</option>
                        <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>
                            <i class="fas fa-clock"></i> Pending
                        </option>
                        <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>
                            <i class="fas fa-check"></i> Confirmed
                        </option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>
                            <i class="fas fa-check-double"></i> Completed
                        </option>
                        <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>
                            <i class="fas fa-times"></i> Cancelled
                        </option>
                    </select>
                    @error('status')
                        <div style="color: var(--danger); margin-top: 6px; font-size: 13px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Notes Card -->
        <div class="form-card">
            <div class="form-card-header">
                <h3><i class="fas fa-sticky-note"></i> Notes</h3>
            </div>
            <div class="form-card-body">
                <div class="form-group">
                    <label for="notes">Special Instructions (Optional)</label>
                    <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Add any special instructions or notes...">{{ old('notes', $appointment->notes) }}</textarea>
                    <small class="form-text">Maximum 500 characters</small>
                    @error('notes')
                        <div style="color: var(--danger); margin-top: 6px; font-size: 13px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="reset" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Status
            </button>
        </div>
    </form>
</div>
@endsection
