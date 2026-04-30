
@extends('staff.sidebar')

@section('page-title', 'Create Appointment')

@section('content')
<style>
    .staff-ui-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .form-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
        border: 1px solid var(--medium-gray);
    }

    .form-card {
        margin-bottom: 22px;
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }

    .form-card-header {
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
        padding: 24px;
        border-bottom: 1px solid var(--medium-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
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
        padding: 24px;
    }

    .form-group {
        margin-bottom: 22px;
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

    .btn-create-customer {
        background: var(--accent);
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }

    .btn-create-customer:hover {
        background: #c89b2e;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .btn-group {
        display: flex;
        gap: 12px;
        margin-top: 26px;
        justify-content: flex-end;
        padding: 0 24px 24px;
    }

    .btn {
        padding: 11px 16px;
        border-radius: 8px;
        border: none;
        font-size: 15px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: var(--accent);
        color: white;
    }

    .btn-primary:hover {
        background: #c89b2e;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: var(--light-gray);
        color: var(--primary);
    }

    .btn-secondary:hover {
        background: var(--medium-gray);
    }

    .invalid-feedback {
        color: var(--danger);
        font-size: 13px;
        margin-top: 8px;
        display: block;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        padding: 40px;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        animation: slideUp 0.3s ease;
    }

    .modal-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--medium-gray);
    }

    .modal-header h2 {
        margin: 0;
        font-size: 24px;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-header i {
        color: var(--accent);
        font-size: 28px;
    }

    .modal-body {
        margin-bottom: 30px;
    }

    .modal-footer {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .modal-footer .btn {
        padding: 12px 24px;
        font-size: 14px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .success-message {
        display: none;
        background: #d4edda;
        color: #155724;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
    }

    .error-message {
        display: none;
        background: #f8d7da;
        color: #721c24;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        border: 1px solid #f5c6cb;
    }

    .appointment-fields-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .customer-search-input {
        margin-bottom: 10px;
    }

    .recipient-options {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .recipient-option {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 14px;
        border: 2px solid var(--medium-gray);
        border-radius: 8px;
        background: var(--light-gray);
        font-weight: 600;
        cursor: pointer;
    }

    .recipient-option:has(input:checked) {
        border-color: var(--accent);
        background: rgba(212, 175, 55, 0.14);
    }

    .recipient-fields {
        display: none;
    }

    .recipient-fields.active {
        display: block;
    }

    .recipient-field-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 16px;
    }

    .price-preview {
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 52px;
        padding: 14px 16px;
        background: var(--light-gray);
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
    }

    .price-preview span {
        color: var(--dark-gray);
        font-size: 14px;
        font-weight: 600;
    }

    .price-preview strong {
        color: var(--accent);
        font-size: 18px;
    }

    .slot-help-text {
        display: block;
        margin-bottom: 20px;
        color: var(--accent);
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .staff-ui-page {
            padding: 20px;
        }

        .appointment-fields-grid {
            grid-template-columns: 1fr;
        }

        .recipient-field-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="staff-ui-page">
    <div class="form-container">
        <form action="{{ route('staff.appointments.store') }}" method="POST">
            @csrf

            <!-- Customer Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3><i class="fas fa-user-circle"></i> Customer Information</h3>
                    <button type="button" class="btn-create-customer" onclick="openCustomerModal()">
                        <i class="fas fa-user-plus"></i> New Customer
                    </button>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="customer_id" class="required">Select Customer</label>
                        <input type="search"
                               id="customerSearch"
                               class="form-control customer-search-input"
                               placeholder="Search by name, email, or phone">
                        <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required>
                            <option value="">-- Choose Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                        data-search="{{ strtolower($customer->name . ' ' . $customer->email . ' ' . ($customer->phone ?? '')) }}"
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }}){{ $customer->phone ? ' - ' . $customer->phone : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required">Appointment For</label>
                        <div class="recipient-options">
                            <label class="recipient-option">
                                <input type="radio" name="booking_for" value="self" {{ old('booking_for', 'self') !== 'other' ? 'checked' : '' }}>
                                <span>Selected customer</span>
                            </label>
                            <label class="recipient-option">
                                <input type="radio" name="booking_for" value="other" {{ old('booking_for') === 'other' ? 'checked' : '' }}>
                                <span>Someone else</span>
                            </label>
                        </div>
                        @error('booking_for')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="recipient-fields" id="recipientFields">
                        <div class="recipient-field-grid">
                            <div class="form-group">
                                <label for="recipient_name" class="required">Recipient Name</label>
                                <input type="text"
                                       name="recipient_name"
                                       id="recipient_name"
                                       class="form-control @error('recipient_name') is-invalid @enderror"
                                       value="{{ old('recipient_name') }}"
                                       placeholder="Example: Adam">
                                @error('recipient_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="recipient_age" class="required">Age</label>
                                <input type="number"
                                       name="recipient_age"
                                       id="recipient_age"
                                       class="form-control @error('recipient_age') is-invalid @enderror"
                                       value="{{ old('recipient_age') }}"
                                       min="0"
                                       max="120"
                                       placeholder="10">
                                @error('recipient_age')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <small class="form-text">Children below 12 years old are charged RM15.</small>
                    </div>
                </div>
            </div>

            <!-- Service Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3><i class="fas fa-scissors"></i> Service Details</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="service_id" class="required">Select Service</label>
                        <select name="service_id" id="service_id" class="form-control @error('service_id') is-invalid @enderror" required onchange="updateServiceInfo()">
                            <option value="">-- Choose Service --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-duration="{{ $service->duration }}" data-price="{{ $service->price }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - RM{{ $service->price }} ({{ $service->duration }}min)
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="price-preview" id="pricePreview">
                        <span>Estimated price</span>
                        <strong>RM0.00</strong>
                    </div>
                </div>
            </div>

            <!-- Appointment Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3><i class="fas fa-calendar-alt"></i> Appointment Schedule</h3>
                </div>
                <div class="form-card-body">
                    <small class="form-text slot-help-text">
                        <i class="fas fa-info-circle"></i> Available time slots will be automatically loaded based on your schedule
                    </small>
                    <div class="appointment-fields-grid">
                        <div class="form-group">
                            <label for="appointment_date" class="required">Date</label>
                            <input type="date" name="appointment_date" id="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" value="{{ old('appointment_date') }}" required onchange="updateAvailableSlots()">
                            @error('appointment_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="start_time" class="required">Time</label>
                            <select name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" required>
                                <option value="">-- Select Date and Service First --</option>
                            </select>
                            @error('start_time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="4" placeholder="Add any special notes...">{{ old('notes') }}</textarea>
                        <small class="form-text">Optional - Add special instructions or notes for this appointment</small>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="btn-group">
                <a href="{{ route('staff.appointments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Create Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Customer Creation Modal -->
<div id="customerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-user-plus"></i> Create New Customer</h2>
        </div>
        <div class="modal-body">
            <div class="success-message" id="successMessage"></div>
            <div class="error-message" id="errorMessage"></div>
            
            <form id="customerForm">
                @csrf
                <div class="form-group">
                    <label for="customer_name" class="required">Full Name</label>
                    <input type="text" name="customer_name" id="customer_name" class="form-control" required placeholder="Enter customer name">
                    <span class="invalid-feedback" id="nameError"></span>
                </div>

                <div class="form-group">
                    <label for="customer_email" class="required">Email</label>
                    <input type="email" name="customer_email" id="customer_email" class="form-control" required placeholder="Enter customer email">
                    <span class="invalid-feedback" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="customer_phone" class="required">Phone Number</label>
                    <input type="tel" name="customer_phone" id="customer_phone" class="form-control" required placeholder="Enter phone number">
                    <span class="invalid-feedback" id="phoneError"></span>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeCustomerModal()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" class="btn btn-primary" onclick="submitCustomerForm()">
                <i class="fas fa-check"></i> Create Customer
            </button>
        </div>
    </div>
</div>

<script>
    const childPrice = 15;
    const bookingForInputs = document.querySelectorAll('input[name="booking_for"]');
    const recipientFields = document.getElementById('recipientFields');
    const recipientNameInput = document.getElementById('recipient_name');
    const recipientAgeInput = document.getElementById('recipient_age');
    const pricePreview = document.getElementById('pricePreview');
    const customerSearch = document.getElementById('customerSearch');
    const customerSelect = document.getElementById('customer_id');

    function isBookingForOther() {
        return document.querySelector('input[name="booking_for"]:checked')?.value === 'other';
    }

    function updateRecipientFields() {
        const other = isBookingForOther();
        recipientFields?.classList.toggle('active', other);

        if (recipientNameInput) {
            recipientNameInput.required = other;
        }

        if (recipientAgeInput) {
            recipientAgeInput.required = other;
        }

        updatePricePreview();
    }

    function updatePricePreview() {
        const serviceSelect = document.getElementById('service_id');
        const selectedOption = serviceSelect?.options[serviceSelect.selectedIndex];
        const servicePrice = selectedOption?.dataset.price ? parseFloat(selectedOption.dataset.price) : 0;
        const age = parseInt(recipientAgeInput?.value || '', 10);
        const finalPrice = isBookingForOther() && !Number.isNaN(age) && age < 12 ? childPrice : servicePrice;

        if (pricePreview) {
            pricePreview.querySelector('strong').textContent = `RM${Number(finalPrice || 0).toFixed(2)}`;
        }
    }

    function filterCustomerOptions() {
        if (!customerSearch || !customerSelect) {
            return;
        }

        const query = customerSearch.value.trim().toLowerCase();
        const selectedOption = customerSelect.options[customerSelect.selectedIndex];
        let selectedStillVisible = !selectedOption?.value;

        Array.from(customerSelect.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            const matches = !query || (option.dataset.search || '').includes(query);
            option.hidden = !matches;

            if (option.selected && matches) {
                selectedStillVisible = true;
            }
        });

        if (!selectedStillVisible) {
            customerSelect.value = '';
        }
    }

    function openCustomerModal() {
        document.getElementById('customerModal').classList.add('show');
    }

    function closeCustomerModal() {
        document.getElementById('customerModal').classList.remove('show');
        document.getElementById('customerForm').reset();
        document.getElementById('successMessage').style.display = 'none';
        document.getElementById('errorMessage').style.display = 'none';
    }

    function submitCustomerForm() {
        const formData = new FormData(document.getElementById('customerForm'));

        fetch("{{ route('staff.appointments.create-customer') }}", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const successMsg = document.getElementById('successMessage');
                successMsg.textContent = data.message;
                successMsg.style.display = 'block';

                // Add customer to dropdown
                const select = document.getElementById('customer_id');
                const option = document.createElement('option');
                option.value = data.customer.id;
                option.textContent = `${data.customer.name} (${data.customer.email})${data.customer.phone ? ' - ' + data.customer.phone : ''}`;
                option.dataset.search = `${data.customer.name} ${data.customer.email} ${data.customer.phone || ''}`.toLowerCase();
                option.selected = true;
                select.appendChild(option);

                if (customerSearch) {
                    customerSearch.value = data.customer.name;
                    filterCustomerOptions();
                }

                // Close modal after 2 seconds
                setTimeout(() => {
                    closeCustomerModal();
                }, 2000);
            } else {
                const errorMsg = document.getElementById('errorMessage');
                errorMsg.textContent = data.message || 'Failed to create customer';
                errorMsg.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMsg = document.getElementById('errorMessage');
            errorMsg.textContent = 'An error occurred. Please try again.';
            errorMsg.style.display = 'block';
        });
    }

    function updateServiceInfo() {
        updatePricePreview();
        updateAvailableSlots();
    }

    function updateAvailableSlots() {
        const dateInput = document.getElementById('appointment_date');
        const serviceSelect = document.getElementById('service_id');
        const timeSelect = document.getElementById('start_time');

        if (!dateInput.value || !serviceSelect.value) {
            timeSelect.innerHTML = '<option value="">-- Select Date and Service First --</option>';
            timeSelect.disabled = true;
            return;
        }

        // Fetch available slots from the server
        fetch(`{{ route('staff.appointments.slots') }}?date=${dateInput.value}&service_id=${serviceSelect.value}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                timeSelect.innerHTML = `<option value="">-- ${data.error} --</option>`;
                timeSelect.disabled = true;
                return;
            }

            // Clear previous options
            timeSelect.innerHTML = '<option value="">-- Select Time --</option>';

            if (data.available_slots.length === 0) {
                timeSelect.innerHTML = '<option value="">-- No Available Slots --</option>';
                timeSelect.disabled = true;
            } else {
                data.available_slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.start;
                    option.textContent = slot.display;
                    timeSelect.appendChild(option);
                });
                timeSelect.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            timeSelect.innerHTML = '<option value="">-- Error Loading Slots --</option>';
            timeSelect.disabled = true;
        });
    }

    bookingForInputs.forEach(input => {
        input.addEventListener('change', updateRecipientFields);
    });

    customerSearch?.addEventListener('input', filterCustomerOptions);
    recipientNameInput?.addEventListener('input', updatePricePreview);
    recipientAgeInput?.addEventListener('input', updatePricePreview);
    document.getElementById('service_id')?.addEventListener('change', updatePricePreview);

    document.querySelector('.staff-ui-page form')?.addEventListener('submit', function(e) {
        if (!isBookingForOther()) {
            return;
        }

        if (!recipientNameInput?.value.trim()) {
            e.preventDefault();
            recipientNameInput?.focus();
            alert('Please enter the recipient name');
            return;
        }

        const age = parseInt(recipientAgeInput?.value || '', 10);
        if (Number.isNaN(age) || age < 0 || age > 120) {
            e.preventDefault();
            recipientAgeInput?.focus();
            alert('Please enter a valid recipient age');
        }
    });

    updateRecipientFields();
    filterCustomerOptions();

    // Close modal when clicking outside
    document.getElementById('customerModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCustomerModal();
        }
    });
</script>
@endsection
