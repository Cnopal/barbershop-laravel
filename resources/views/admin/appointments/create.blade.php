@extends('admin.sidebar')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary btn-small">
                <i class="fas fa-arrow-left"></i> Back to Appointments
            </a>
        </div>
        <div class="header-center">
            <h1 class="page-title">Create New Appointment</h1>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <form action="{{ route('admin.appointments.store') }}" method="POST" id="createAppointmentForm">
            @csrf
            
            <div class="form-row">
                <!-- Customer & Service Selection -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-user"></i> Customer & Service</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Customer Selection -->
                        <div class="form-group">
                            <label for="customer_id">Customer *</label>
                            <select id="customer_id" name="customer_id" 
                                    class="form-control @error('customer_id') is-invalid @enderror"
                                    required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Service Selection -->
                        <div class="form-group">
                            <label for="service_id">Service *</label>
                            <select id="service_id" name="service_id" 
                                    class="form-control @error('service_id') is-invalid @enderror"
                                    required>
                                <option value="">Select Service</option>
                                @foreach($services as $service)
                                    @if($service->status == 'active')
                                        <option value="{{ $service->id }}" 
                                                data-price="{{ $service->price }}"
                                                data-duration="{{ $service->duration }}"
                                                {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} - RM{{ number_format($service->price, 2) }} ({{ $service->duration }} min)
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Barber Selection -->
                        <div class="form-group">
                            <label for="barber_id">Barber *</label>
                            <select id="barber_id" name="barber_id" 
                                    class="form-control @error('barber_id') is-invalid @enderror"
                                    required>
                                <option value="">Select Barber</option>
                                @foreach($barbers as $barber)
                                    @if($barber->status == 'active')
                                        <option value="{{ $barber->id }}" 
                                                {{ old('barber_id') == $barber->id ? 'selected' : '' }}>
                                            {{ $barber->name }} ({{ $barber->position }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('barber_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Date & Time Selection -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-clock"></i> Date & Time</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Appointment Date -->
                        <div class="form-group">
                            <label for="appointment_date">Date *</label>
                            <input type="date" id="appointment_date" name="appointment_date" 
                                   class="form-control @error('appointment_date') is-invalid @enderror"
                                   value="{{ old('appointment_date', date('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Time Selection -->
                        <div class="form-group">
                            <label for="start_time">Start Time *</label>
                            <select id="start_time" name="start_time" 
                                    class="form-control @error('start_time') is-invalid @enderror"
                                    required>
                                <option value="">Select Start Time</option>
                                <!-- Times will be populated by JavaScript -->
                            </select>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- End Time (Auto-calculated) -->
                        <div class="form-group">
                            <label for="end_time">End Time</label>
                            <input type="text" id="end_time" name="end_time" 
                                   class="form-control" readonly>
                            <small class="form-text text-muted">Calculated based on service duration</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price & Status -->
            <div class="form-row">
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-dollar-sign"></i> Price & Status</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group">
                            <label for="price">Price (RM)</label>
                            <input type="number" id="price" name="price" 
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price') }}" 
                                   step="0.01"
                                   min="0"
                                   readonly>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Auto-calculated from service price</small>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" 
                                    class="form-control @error('status') is-invalid @enderror"
                                    required>
                                <option value="pending_payment" {{ old('status') == 'pending_payment' ? 'selected' : '' }}>Pending payment</option>
                                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label for="notes" class="optional">Notes</label>
                            <textarea id="notes" name="notes" 
                                      class="form-control @error('notes') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Add any special instructions or notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Appointment Summary -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-clipboard-check"></i> Appointment Summary</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="appointment-summary">
                            <div class="summary-item">
                                <span class="summary-label">Customer:</span>
                                <span class="summary-value" id="summaryCustomer">-</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Service:</span>
                                <span class="summary-value" id="summaryService">-</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Duration:</span>
                                <span class="summary-value" id="summaryDuration">-</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Barber:</span>
                                <span class="summary-value" id="summaryBarber">-</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Date:</span>
                                <span class="summary-value" id="summaryDate">-</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Time:</span>
                                <span class="summary-value" id="summaryTime">-</span>
                            </div>
                            <div class="summary-item total">
                                <span class="summary-label">Total Price:</span>
                                <span class="summary-value" id="summaryPrice">RM0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i> Create Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toast notification container (hidden by default) -->
<div id="toastContainer" style="position: fixed; bottom: 30px; right: 30px; z-index: 1100;"></div>

<style>
    /* CSS Variables */
    :root {
        --primary-color: #1a1f36;
        --secondary-color: #4a5568;
        --accent-color: #d4af37;
        --accent-light: #f8f3e6;
        --light-gray: #f8fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #718096;
        --success-color: #48bb78;
        --warning-color: #ed8936;
        --danger-color: #f56565;
        --info-color: #4299e1;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --hover-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        --border-radius: 12px;
        --border-radius-sm: 8px;
    }

    /* Global Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Custom Scrollbar Styling */
    ::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    ::-webkit-scrollbar-track {
        background: var(--light-gray);
        border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--medium-gray);
        border-radius: 5px;
        border: 2px solid var(--light-gray);
        transition: var(--transition);
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--dark-gray);
    }

    ::-webkit-scrollbar-corner {
        background: var(--light-gray);
    }

    /* For Firefox */
    * {
        scrollbar-width: thin;
        scrollbar-color: var(--medium-gray) var(--light-gray);
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
        color: var(--primary-color);
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: 100vh;
    }

    /* Container */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Page Header - Enhanced */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px 0;
        border-bottom: 2px solid var(--accent-light);
    }

    .header-left, .header-center {
        display: flex;
        align-items: center;
    }

    .header-center {
        flex: 1;
        justify-content: center;
    }

    .page-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary-color);
        margin: 0;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-color), #f7d794);
        border-radius: 2px;
    }

    /* Button Styles - Enhanced */
    .btn {
        padding: 12px 28px;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 15px;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        letter-spacing: 0.3px;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent-color) 0%, #e6c158 100%);
        color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
    }

    .btn-secondary {
        background: white;
        color: var(--primary-color);
        border: 2px solid var(--medium-gray);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .btn-secondary:hover {
        background: var(--light-gray);
        border-color: var(--accent-color);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .btn-small {
        padding: 8px 20px;
        font-size: 14px;
        border-radius: 6px;
    }

    /* Form Container - Enhanced */
    .form-container {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        overflow: hidden;
        border: 1px solid var(--medium-gray);
        margin-top: 20px;
        position: relative;
        animation: slideUp 0.4s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-color), #f7d794);
    }

    /* Form Cards - Enhanced */
    .form-card {
        margin-bottom: 30px;
        border: 1px solid var(--medium-gray);
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        background: white;
        transition: var(--transition);
    }

    .form-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        border-color: var(--accent-color);
    }

    .form-card-header {
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
        padding: 24px;
        border-bottom: 1px solid var(--medium-gray);
        position: relative;
    }

    .form-card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-color);
    }

    .form-card-header h3 {
        margin: 0;
        font-size: 18px;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
    }

    .form-card-header i {
        color: var(--accent-color);
        font-size: 20px;
        background: var(--accent-light);
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-card-body {
        padding: 30px;
    }

    /* Appointment Summary Styles */
    .appointment-summary {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--light-gray);
    }

    .summary-item.total {
        padding-top: 16px;
        border-top: 2px solid var(--accent-color);
        border-bottom: none;
    }

    .summary-label {
        font-weight: 600;
        color: var(--secondary-color);
        font-size: 14px;
    }

    .summary-value {
        font-weight: 500;
        color: var(--primary-color);
        font-size: 14px;
        text-align: right;
        max-width: 60%;
    }

    .summary-item.total .summary-label {
        font-size: 16px;
        color: var(--primary-color);
    }

    .summary-item.total .summary-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--accent-color);
    }

    /* Form Grid */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    @media (max-width: 992px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    /* Form Groups - Enhanced */
    .form-group {
        margin-bottom: 28px;
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--primary-color);
        font-size: 14px;
        letter-spacing: 0.3px;
    }

    .form-group label:after {
        content: " *";
        color: var(--danger-color);
    }

    .form-group label.optional:after {
        content: "";
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        border-radius: var(--border-radius-sm);
        border: 2px solid var(--medium-gray);
        font-size: 15px;
        transition: var(--transition);
        background: white;
        color: var(--primary-color);
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
        transform: translateY(-1px);
    }

    .form-control.is-invalid {
        border-color: var(--danger-color);
        background: linear-gradient(to right, rgba(245, 101, 101, 0.05), transparent);
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(245, 101, 101, 0.1);
    }

    .form-control:disabled {
        background-color: var(--light-gray);
        cursor: not-allowed;
        opacity: 0.7;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
        line-height: 1.6;
        padding: 16px;
    }

    /* Form Text */
    .form-text {
        display: block;
        margin-top: 6px;
        font-size: 13px;
        color: var(--dark-gray);
        line-height: 1.5;
        padding-left: 4px;
    }

    /* Form Actions - Enhanced */
    .form-actions {
        padding: 30px;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
        position: relative;
    }

    .form-actions::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--accent-color), transparent);
    }

    /* Invalid Feedback - Enhanced */
    .invalid-feedback {
        display: block;
        margin-top: 6px;
        font-size: 13px;
        color: var(--danger-color);
        padding-left: 4px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .invalid-feedback::before {
        content: '!';
        width: 16px;
        height: 16px;
        background: var(--danger-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
    }

    /* Toast notification styles - Enhanced */
    .toast {
        background: linear-gradient(135deg, var(--primary-color) 0%, #2d3748 100%);
        color: white;
        padding: 20px 28px;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 16px;
        animation: toastSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        max-width: 450px;
        border-left: 4px solid var(--accent-color);
        backdrop-filter: blur(10px);
        margin-bottom: 15px;
    }

    @keyframes toastSlideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes toastSlideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .toast i {
        color: var(--accent-color);
        font-size: 22px;
        flex-shrink: 0;
        background: rgba(255, 255, 255, 0.1);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast span {
        line-height: 1.5;
        flex: 1;
        font-weight: 500;
    }

    .toast.error {
        border-left-color: var(--danger-color);
    }

    .toast.error i {
        color: var(--danger-color);
    }

    .toast-close {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.7);
        cursor: pointer;
        padding: 4px;
        margin-left: 12px;
        transition: var(--transition);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast-close:hover {
        color: white;
        background: rgba(255, 255, 255, 0.1);
    }

    .toast-close i {
        font-size: 14px;
        background: none;
        width: auto;
        height: auto;
    }

    /* Loading animation for submit button */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    /* Focus states for accessibility */
    .btn:focus,
    .form-control:focus {
        outline: 2px solid var(--accent-color);
        outline-offset: 2px;
    }

    /* Disabled state styling */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn:disabled:hover::before {
        width: 0;
        height: 0;
    }

    /* Selection color */
    ::selection {
        background-color: var(--accent-color);
        color: var(--primary-color);
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
            padding-bottom: 15px;
        }

        .page-title {
            font-size: 26px;
            text-align: center;
        }

        .page-title::after {
            width: 40px;
            height: 3px;
        }

        .form-card-body {
            padding: 24px;
        }

        .form-actions {
            flex-direction: column;
            padding: 24px;
            gap: 15px;
        }

        .form-actions .btn {
            width: 100%;
        }

        .form-control {
            padding: 12px 14px;
        }

        .btn {
            padding: 12px 20px;
        }

        .toast {
            max-width: calc(100% - 40px);
            right: 20px;
            bottom: 20px;
        }
    }

    @media (max-width: 576px) {
        .form-group label {
            font-size: 13px;
        }

        .page-title {
            font-size: 24px;
        }

        .form-card-header h3 {
            font-size: 16px;
        }

        .form-card-header i {
            width: 32px;
            height: 32px;
            font-size: 16px;
        }

        .summary-label,
        .summary-value {
            font-size: 13px;
        }

        .summary-item.total .summary-label {
            font-size: 14px;
        }

        .summary-item.total .summary-value {
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .btn {
            padding: 10px 16px;
            font-size: 14px;
        }

        .btn-small {
            padding: 6px 16px;
        }

        .form-row {
            gap: 20px;
        }
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const customerSelect = document.getElementById('customer_id');
    const serviceSelect = document.getElementById('service_id');
    const barberSelect = document.getElementById('barber_id');
    const dateInput = document.getElementById('appointment_date');
    const startTimeSelect = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const priceInput = document.getElementById('price');
    const statusSelect = document.getElementById('status');
    const notesInput = document.getElementById('notes');
    
    // Summary elements
    const summaryCustomer = document.getElementById('summaryCustomer');
    const summaryService = document.getElementById('summaryService');
    const summaryDuration = document.getElementById('summaryDuration');
    const summaryBarber = document.getElementById('summaryBarber');
    const summaryDate = document.getElementById('summaryDate');
    const summaryTime = document.getElementById('summaryTime');
    const summaryPrice = document.getElementById('summaryPrice');
    
    // Time slots configuration
    const businessHours = {
        start: 9,  // 9 AM
        end: 18,   // 6 PM
        interval: 30 // minutes
    };
    
    let selectedService = null;
    let selectedBarber = null;
    let selectedDate = dateInput.value;
    let selectedStartTime = '';
    let selectedCustomer = null;
    
    // Initialize date picker with more options
    const datePicker = flatpickr(dateInput, {
        dateFormat: "Y-m-d",
        minDate: "today",
        maxDate: new Date().fp_incr(90), // 3 months in advance
        disableMobile: true,
        onChange: function(selectedDates, dateStr) {
            selectedDate = dateStr;
            updateTimeSlots();
            updateSummary();
        },
        onOpen: function() {
            // Add custom styling to date picker
            const calendarContainer = document.querySelector('.flatpickr-calendar');
            if (calendarContainer) {
                calendarContainer.style.borderRadius = '12px';
                calendarContainer.style.boxShadow = '0 10px 40px rgba(0,0,0,0.15)';
            }
        }
    });
    
    // Update summary when form changes
    function updateSummary() {
        // Customer
        const customerOption = customerSelect.options[customerSelect.selectedIndex];
        selectedCustomer = customerOption.value ? {
            id: customerOption.value,
            name: customerOption.text.split('(')[0].trim(),
            email: customerOption.text.match(/\(([^)]+)\)/)?.[1] || ''
        } : null;
        
        summaryCustomer.textContent = selectedCustomer ? selectedCustomer.name : '-';
        
        // Service
        const serviceOption = serviceSelect.options[serviceSelect.selectedIndex];
        summaryService.textContent = serviceOption.value ? serviceOption.text.split('-')[0].trim() : '-';
        
        // Duration
        if (selectedService) {
            summaryDuration.textContent = selectedService.duration + ' min';
        } else {
            summaryDuration.textContent = '-';
        }
        
        // Barber
        const barberOption = barberSelect.options[barberSelect.selectedIndex];
        summaryBarber.textContent = barberOption.value ? barberOption.text.split('(')[0].trim() : '-';
        
        // Date
        if (selectedDate) {
            const date = new Date(selectedDate);
            const options = { 
                weekday: 'short', 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            };
            summaryDate.textContent = date.toLocaleDateString('en-US', options);
        } else {
            summaryDate.textContent = '-';
        }
        
        // Time
        if (selectedStartTime) {
            const startTime = formatTime(selectedStartTime);
            if (selectedService) {
                const endTime = calculateEndTime(selectedStartTime, selectedService.duration);
                summaryTime.textContent = `${startTime} - ${formatTime(endTime)}`;
                endTimeInput.value = formatTime(endTime);
            } else {
                summaryTime.textContent = startTime;
            }
        } else {
            summaryTime.textContent = '-';
            endTimeInput.value = '';
        }
        
        // Price
        if (selectedService) {
            const price = parseFloat(selectedService.price).toFixed(2);
            summaryPrice.textContent = 'RM' + price;
            priceInput.value = price;
        } else {
            summaryPrice.textContent = 'RM0.00';
            priceInput.value = '';
        }
    }
    
    // Service selection change
    serviceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            selectedService = {
                id: selectedOption.value,
                price: selectedOption.getAttribute('data-price'),
                duration: parseInt(selectedOption.getAttribute('data-duration')),
                name: selectedOption.text.split('-')[0].trim()
            };
            
            // Update time slots based on new service duration
            updateTimeSlots();
        } else {
            selectedService = null;
        }
        
        updateSummary();
    });
    
    // Barber selection change
    barberSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        selectedBarber = selectedOption.value ? {
            id: selectedOption.value,
            name: selectedOption.text.split('(')[0].trim(),
            position: selectedOption.text.match(/\(([^)]+)\)/)?.[1] || ''
        } : null;
        
        updateTimeSlots();
        updateSummary();
    });
    
    // Customer selection change
    customerSelect.addEventListener('change', updateSummary);
    
    // Status selection change
    statusSelect.addEventListener('change', updateSummary);
    
    // Notes input change
    if (notesInput) {
        notesInput.addEventListener('input', function() {
            // Add character counter
            const charCount = this.value.length;
            const maxLength = 500;
            if (charCount > maxLength * 0.8) {
                // Show warning if approaching limit
                if (!this.nextElementSibling?.classList.contains('char-count')) {
                    const charCountEl = document.createElement('div');
                    charCountEl.className = 'char-count form-text';
                    charCountEl.style.fontSize = '12px';
                    charCountEl.style.color = charCount > maxLength ? 'var(--danger-color)' : 'var(--dark-gray)';
                    this.parentNode.appendChild(charCountEl);
                }
                if (this.nextElementSibling?.classList.contains('char-count')) {
                    this.nextElementSibling.textContent = `${charCount}/${maxLength} characters`;
                    this.nextElementSibling.style.color = charCount > maxLength ? 'var(--danger-color)' : 'var(--warning-color)';
                }
            } else if (this.nextElementSibling?.classList.contains('char-count')) {
                this.nextElementSibling.remove();
            }
        });
    }
    
    // Start time selection change
    startTimeSelect.addEventListener('change', function() {
        selectedStartTime = this.value;
        
        if (selectedStartTime && selectedService) {
            const endTime = calculateEndTime(selectedStartTime, selectedService.duration);
            endTimeInput.value = formatTime(endTime);
        } else {
            endTimeInput.value = '';
        }
        
        updateSummary();
    });
    
    // Helper functions
    function calculateEndTime(startTime, duration) {
        const [hours, minutes] = startTime.split(':').map(Number);
        const startDate = new Date();
        startDate.setHours(hours, minutes, 0, 0);
        
        const endDate = new Date(startDate.getTime() + duration * 60000);
        return `${endDate.getHours().toString().padStart(2, '0')}:${endDate.getMinutes().toString().padStart(2, '0')}`;
    }
    
    function formatTime(time) {
        if (!time) return '';
        const [hours, minutes] = time.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const formattedHour = hour % 12 || 12;
        return `${formattedHour}:${minutes} ${ampm}`;
    }
    
    function addMinutesToTime(time, minutes) {
        const [hours, mins] = time.split(':').map(Number);
        const date = new Date();
        date.setHours(hours, mins, 0);
        date.setMinutes(date.getMinutes() + minutes);
        const newHours = String(date.getHours()).padStart(2, '0');
        const newMins = String(date.getMinutes()).padStart(2, '0');
        return `${newHours}:${newMins}`;
    }
    
    
    // Update time slots based on selections
// Update time slots based on selections
async function updateTimeSlots() {
    // Get current values
    const barberId = barberSelect.value;
    const date = dateInput.value;
    const serviceId = serviceSelect.value;
    
    // Validate all required fields
    if (!barberId || !date || !serviceId) {
        startTimeSelect.innerHTML = '<option value="">Please select barber, date, and service first</option>';
        startTimeSelect.disabled = true;
        return;
    }

    // Show loading state
    startTimeSelect.disabled = true;
    startTimeSelect.innerHTML = '<option value="">Loading available slots...</option>';
    
    // Add loading class to form
    startTimeSelect.classList.add('loading');

    try {
        // Use the correct route - notice it's now '/admin/appointments/available-slots' 
        // (without the extra /admin prefix since we're already in the admin group)
        const url = `{{ route('admin.appointments.available-slots') }}?barber_id=${encodeURIComponent(barberId)}&date=${encodeURIComponent(date)}&service_id=${encodeURIComponent(serviceId)}`;
        
        console.log('Fetching URL:', url); // Debug log
        
        // Fetch available slots from the server with proper headers
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        console.log('Response status:', response.status); // Debug log
        
        if (!response.ok) {
            throw new Error(`Server error: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        console.log('Received data:', data); // Debug log

        // Check for error response
        if (data.error) {
            throw new Error(data.error);
        }

        // Check if it's the new format with available_slots object
        let slots = [];
        if (data.available_slots) {
            slots = data.available_slots;
        } else if (Array.isArray(data)) {
            // Old format - just an array of times
            const duration = selectedService ? selectedService.duration : 30;
            slots = data.map(time => ({
                start: time,
                display: `${formatTime(time)} - ${formatTime(addMinutesToTime(time, duration))}`
            }));
        } else {
            throw new Error('Invalid response format from server');
        }

        if (!slots || slots.length === 0) {
            startTimeSelect.innerHTML = '<option value="">No available slots for this date</option>';
            startTimeSelect.disabled = true;
            return;
        }

        // Build options for select dropdown
        let options = '<option value="">Select Start Time</option>';
        slots.forEach(slot => {
            // Handle both old format (string) and new format (object)
            const timeValue = slot.start || slot;
            const timeDisplay = slot.display || formatTime(timeValue);
            
            if (!slot.past) {
                options += `<option value="${timeValue}">${timeDisplay}</option>`;
            }
        });

        startTimeSelect.innerHTML = options;
        startTimeSelect.disabled = false;
        
        // If there was a previously selected time, try to reselect it
        if (selectedStartTime) {
            const optionExists = Array.from(startTimeSelect.options).some(option => 
                option.value === selectedStartTime
            );
            
            if (optionExists) {
                startTimeSelect.value = selectedStartTime;
                if (selectedService) {
                    const endTime = calculateEndTime(selectedStartTime, selectedService.duration);
                    endTimeInput.value = formatTime(endTime);
                }
            } else {
                selectedStartTime = '';
                startTimeSelect.value = '';
                endTimeInput.value = '';
            }
        }

    } catch (error) {
        console.error('Error fetching available slots:', error);
        startTimeSelect.innerHTML = '<option value="">Error loading time slots</option>';
        startTimeSelect.disabled = true;
        showToast(`Error: ${error.message}`, 'error');
    } finally {
        startTimeSelect.classList.remove('loading');
    }
}

    
    // Form validation with enhanced feedback
    const form = document.getElementById('createAppointmentForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate required fields
            const requiredFields = [
                { element: customerSelect, name: 'customer' },
                { element: serviceSelect, name: 'service' },
                { element: barberSelect, name: 'barber' },
                { element: dateInput, name: 'date' },
                { element: startTimeSelect, name: 'start time' }
            ];
            
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(field => {
                if (!field.element.value) {
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = field.element;
                    
                    // Add visual feedback
                    field.element.classList.add('is-invalid');
                    
                    // Create error message if it doesn't exist
                    if (!field.element.nextElementSibling?.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = `Please select a ${field.name}`;
                        field.element.parentNode.appendChild(errorDiv);
                    }
                } else {
                    field.element.classList.remove('is-invalid');
                    // Remove error message if it exists
                    const errorDiv = field.element.parentNode.querySelector('.invalid-feedback');
                    if (errorDiv) errorDiv.remove();
                }
            });
            
            if (!isValid) {
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
                showToast('Please fill in all required fields', 'error');
                return;
            }
            
            // Submit the form
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating appointment...';
            submitBtn.disabled = true;
            
            // Submit the form after a brief delay
            setTimeout(() => {
                form.submit();
            }, 500);
        });
    }
    
    // Real-time validation for date (ensure it's not in the past)
    // Real-time validation for date (ensure it's not in the past)
dateInput.addEventListener('change', function() {
    const selectedDateValue = new Date(this.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDateValue < today) {
        this.classList.add('is-invalid');

        if (!this.nextElementSibling?.classList.contains('invalid-feedback')) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = 'Please select a future date';
            this.parentNode.appendChild(errorDiv);
        }

        // Reset to today
        this.value = today.toISOString().split('T')[0];
        selectedDate = this.value;
        updateTimeSlots();
    } else {
        this.classList.remove('is-invalid');
        const errorDiv = this.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) errorDiv.remove();
        selectedDate = this.value;
        updateTimeSlots();
        updateSummary();
    }
});

    
    // Toast notification function
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toastContainer');
        
        // Remove existing toast
        const existingToast = document.querySelector('.toast');
        if (existingToast) {
            existingToast.style.animation = 'toastSlideOut 0.3s ease';
            setTimeout(() => existingToast.remove(), 300);
        }
        
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const iconColor = type === 'success' ? 'var(--accent-color)' : 'var(--danger-color)';
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <i class="fas ${icon}" style="color: ${iconColor};"></i>
            <span>${message}</span>
            <button class="toast-close">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Add close button functionality
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            toast.style.animation = 'toastSlideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        });
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'toastSlideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }
    
    // Initialize form state
    updateSummary();
    updateTimeSlots();
    
    // Auto-focus on first field if no errors
    @if(!$errors->any())
        setTimeout(() => {
            customerSelect.focus();
        }, 300);
    @endif
    
    // Show validation errors if any
    @if($errors->any())
        setTimeout(() => {
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.focus();
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // Show toast with error count
            const errorCount = document.querySelectorAll('.is-invalid').length;
            if (errorCount > 0) {
                showToast(`Please fix ${errorCount} error${errorCount > 1 ? 's' : ''} in the form`, 'error');
            }
        }, 100);
    @endif
});
</script>
@endsection