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
            <h1 class="page-title">Edit Service</h1>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.services.show', $service->id) }}" class="btn btn-secondary">
                <i class="fas fa-eye"></i> View Service
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" id="editServiceForm">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <!-- Basic Information Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name">Service Name *</label>
                            <input type="text" id="name" name="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $service->name) }}" 
                                   placeholder="e.g., Haircut, Beard Trim, etc."
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price Field -->
                        <div class="form-group">
                            <label for="price">Price (RM) *</label>
                            <div class="input-with-icon">
                                <span class="input-icon">RM</span>
                                <input type="number" id="price" name="price" 
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $service->price) }}" 
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Duration Field -->
                        <div class="form-group">
                            <label for="duration">Duration (minutes) *</label>
                            <div class="input-with-icon">
                                <input type="number" id="duration" name="duration" 
                                       class="form-control @error('duration') is-invalid @enderror"
                                       value="{{ old('duration', $service->duration) }}" 
                                       min="5"
                                       max="480"
                                       placeholder="30"
                                       required>
                                <span class="input-icon">min</span>
                            </div>
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Enter duration in minutes (5-480 minutes)</small>
                        </div>
                    </div>
                </div>

                <!-- Status & Description Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-cog"></i> Status & Description</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Status Field -->
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" 
                                    class="form-control @error('status') is-invalid @enderror"
                                    required>
                                <option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $service->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="form-group">
                            <label for="description" class="optional">Description</label>
                            <textarea id="description" name="description" 
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="6"
                                      placeholder="Describe the service in detail...">{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Optional: This will help customers understand the service better.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Preview -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3><i class="fas fa-eye"></i> Service Preview</h3>
                </div>
                <div class="form-card-body">
                    <div class="service-preview">
                        <div class="preview-header">
                            <div class="preview-icon">
                                @php
                                    // Determine icon based on service name
                                    $nameLower = strtolower(old('name', $service->name));
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
                            <div class="preview-info">
                                <h4 id="previewName">{{ old('name', $service->name) ?: 'Service Name' }}</h4>
                                <span class="preview-status {{ old('status', $service->status) == 'active' ? 'status-active' : 'status-inactive' }}">
                                    {{ ucfirst(old('status', $service->status)) ?: 'Active' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="preview-details">
                            <div class="preview-row">
                                <span>Price:</span>
                                <strong id="previewPrice">RM{{ number_format(old('price', $service->price), 2) ?: '0.00' }}</strong>
                            </div>
                            <div class="preview-row">
                                <span>Duration:</span>
                                <span id="previewDuration">{{ old('duration', $service->duration) ?: '30' }} min</span>
                            </div>
                            @if(old('description', $service->description))
                            <div class="preview-row full-width">
                                <span>Description:</span>
                                <p id="previewDescription">{{ old('description', $service->description) }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Service
                </button>
            </div>
        </form>
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
    
    .btn-small {
        padding: 8px 16px;
        font-size: 14px;
    }
    
    /* Form Container */
    .form-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }
    
    /* Form Cards */
    .form-card {
        margin-bottom: 25px;
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
        overflow: hidden;
    }
    
    .form-card-header {
        background-color: var(--light-gray);
        padding: 20px;
        border-bottom: 1px solid var(--medium-gray);
    }
    
    .form-card-header h3 {
        margin: 0;
        font-size: 18px;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-card-header i {
        color: var(--accent-color);
    }
    
    .form-card-body {
        padding: 25px;
    }
    
    /* Form Grid */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }
    
    @media (max-width: 992px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
    
    /* Form Groups */
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--primary-color);
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
        padding: 12px 16px;
        border-radius: 6px;
        border: 1px solid var(--medium-gray);
        font-size: 15px;
        transition: var(--transition);
        background-color: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: var(--danger-color);
    }
    
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(245, 101, 101, 0.1);
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23718096' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
        padding-right: 40px;
    }
    
    /* Input with Icon */
    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .input-with-icon .form-control {
        padding-left: 40px;
        padding-right: 40px;
    }
    
    .input-icon {
        position: absolute;
        padding: 0 12px;
        color: var(--dark-gray);
        font-weight: 500;
        pointer-events: none;
    }
    
    .input-with-icon .input-icon:first-child {
        left: 0;
    }
    
    .input-with-icon .input-icon:last-child {
        right: 0;
    }
    
    /* Form Text */
    .form-text {
        display: block;
        margin-top: 4px;
        font-size: 13px;
        color: var(--dark-gray);
    }
    
    /* Service Preview */
    .service-preview {
        background-color: var(--light-gray);
        border-radius: 8px;
        padding: 20px;
        border: 1px solid var(--medium-gray);
    }
    
    .preview-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        gap: 15px;
    }
    
    .preview-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background-color: rgba(212, 175, 55, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-color);
        font-size: 20px;
        flex-shrink: 0;
    }
    
    .preview-info h4 {
        margin: 0 0 5px 0;
        font-size: 18px;
        color: var(--primary-color);
    }
    
    .preview-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-active {
        background-color: rgba(72, 187, 120, 0.1);
        color: var(--success-color);
    }
    
    .status-inactive {
        background-color: rgba(245, 101, 101, 0.1);
        color: var(--danger-color);
    }
    
    .preview-details {
        border-top: 1px solid var(--medium-gray);
        padding-top: 15px;
    }
    
    .preview-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        color: var(--secondary-color);
    }
    
    .preview-row.full-width {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .preview-row.full-width p {
        margin: 5px 0 0 0;
        color: var(--primary-color);
        line-height: 1.4;
    }
    
    .preview-row strong {
        color: var(--accent-color);
        font-size: 16px;
    }
    
    /* Form Actions */
    .form-actions {
        padding: 25px;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        background-color: var(--light-gray);
    }
    
    /* Invalid Feedback */
    .invalid-feedback {
        display: block;
        margin-top: 4px;
        font-size: 14px;
        color: var(--danger-color);
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
        
        .form-card-body {
            padding: 20px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .form-actions .btn {
            width: 100%;
        }
    }
    
    @media (max-width: 480px) {
        .btn {
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .form-card-header h3 {
            font-size: 16px;
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
    // Form elements
    const nameInput = document.getElementById('name');
    const priceInput = document.getElementById('price');
    const durationInput = document.getElementById('duration');
    const statusSelect = document.getElementById('status');
    const descriptionInput = document.getElementById('description');
    
    // Preview elements
    const previewName = document.getElementById('previewName');
    const previewPrice = document.getElementById('previewPrice');
    const previewDuration = document.getElementById('previewDuration');
    const previewStatus = document.querySelector('.preview-status');
    const previewDescription = document.getElementById('previewDescription');
    
    // Update preview in real-time
    function updatePreview() {
        // Update name
        if (nameInput.value.trim()) {
            previewName.textContent = nameInput.value;
        } else {
            previewName.textContent = 'Service Name';
        }
        
        // Update price
        const price = parseFloat(priceInput.value) || 0;
        previewPrice.textContent = 'RM' + price.toFixed(2);
        
        // Update duration
        const duration = parseInt(durationInput.value) || 0;
        previewDuration.textContent = duration + ' min';
        
        // Update status
        if (statusSelect.value === 'active') {
            previewStatus.className = 'preview-status status-active';
            previewStatus.textContent = 'Active';
        } else {
            previewStatus.className = 'preview-status status-inactive';
            previewStatus.textContent = 'Inactive';
        }
        
        // Update description
        if (descriptionInput.value.trim()) {
            if (!previewDescription) {
                // Create description element if it doesn't exist
                const previewRow = document.createElement('div');
                previewRow.className = 'preview-row full-width';
                previewRow.innerHTML = `
                    <span>Description:</span>
                    <p id="previewDescription">${descriptionInput.value}</p>
                `;
                document.querySelector('.preview-details').appendChild(previewRow);
            } else {
                previewDescription.textContent = descriptionInput.value;
            }
        } else if (previewDescription) {
            // Remove description if empty
            previewDescription.parentElement.remove();
        }
    }
    
    // Add event listeners for real-time preview
    if (nameInput) nameInput.addEventListener('input', updatePreview);
    if (priceInput) priceInput.addEventListener('input', updatePreview);
    if (durationInput) durationInput.addEventListener('input', updatePreview);
    if (statusSelect) statusSelect.addEventListener('change', updatePreview);
    if (descriptionInput) descriptionInput.addEventListener('input', updatePreview);
    
    // Form validation
    const form = document.getElementById('editServiceForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validate price
            const price = parseFloat(priceInput.value);
            if (price < 0) {
                e.preventDefault();
                alert('Price cannot be negative');
                priceInput.focus();
                return false;
            }
            
            // Validate duration
            const duration = parseInt(durationInput.value);
            if (duration < 5 || duration > 480) {
                e.preventDefault();
                alert('Duration must be between 5 and 480 minutes');
                durationInput.focus();
                return false;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            submitBtn.disabled = true;
            
            // Re-enable button if form fails to submit
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
    }
    
    // Format price input
    if (priceInput) {
        priceInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(2);
            }
        });
    }
});
</script>
@endsection