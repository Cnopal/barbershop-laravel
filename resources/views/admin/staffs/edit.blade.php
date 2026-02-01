@extends('admin.sidebar')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary btn-small">
                <i class="fas fa-arrow-left"></i> Back to Barbers
            </a>
        </div>
        <div class="header-center">
            <h1 class="page-title">Edit Barber</h1>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.staffs.show', $staff->id) }}" class="btn btn-secondary">
                <i class="fas fa-eye"></i> View Profile
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <form action="{{ route('admin.staffs.update', $staff->id) }}" method="POST" enctype="multipart/form-data" id="editBarberForm">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <!-- Profile & Status Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-user-circle"></i> Profile & Status</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Profile Image -->
                        <div class="form-group">
                            <label for="profile_image" class="optional">Profile Photo</label>
                            <div class="profile-image-upload">
                                <div class="current-image">
                                    @if($staff->profile_image)
                                        <img src="{{ Storage::url($staff->profile_image) }}" alt="{{ $staff->name }}" id="currentProfileImage">
                                        <button type="button" class="remove-image" id="removeImage">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @else
                                        <div class="image-placeholder" id="imagePlaceholder">
                                            <i class="fas fa-user-circle"></i>
                                            <span>No image</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="upload-controls">
                                    <input type="file" id="profile_image" name="profile_image" 
                                           class="file-upload @error('profile_image') is-invalid @enderror"
                                           accept="image/*">
                                    <label for="profile_image" class="btn btn-secondary btn-small">
                                        <i class="fas fa-upload"></i> Change Photo
                                    </label>
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Max 2MB, JPG, PNG, GIF</small>
                                </div>
                                <input type="hidden" name="remove_profile_image" id="removeProfileImage" value="0">
                            </div>
                        </div>

                        <!-- Status Field -->
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" 
                                    class="form-control @error('status') is-invalid @enderror"
                                    required>
                                <option value="active" {{ old('status', $staff->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $staff->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role Field -->
                        <div class="form-group">
                            <label for="role">Role *</label>
                            <select id="role" name="role" 
                                    class="form-control @error('role') is-invalid @enderror"
                                    required>
                                <option value="staff" {{ old('role', $staff->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="senior" {{ old('role', $staff->role) == 'senior' ? 'selected' : '' }}>Senior</option>
                                <option value="manager" {{ old('role', $staff->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Position Field -->
                        <div class="form-group">
                            <label for="position">Position *</label>
                            <select id="position" name="position" 
                                    class="form-control @error('position') is-invalid @enderror"
                                    required>
                                <option value="">Select Position</option>
                                <option value="Junior Barber" {{ old('position', $staff->position) == 'Junior Barber' ? 'selected' : '' }}>Junior Barber</option>
                                <option value="Barber" {{ old('position', $staff->position) == 'Barber' ? 'selected' : '' }}>Barber</option>
                                <option value="Senior Barber" {{ old('position', $staff->position) == 'Senior Barber' ? 'selected' : '' }}>Senior Barber</option>
                                <option value="Head Barber" {{ old('position', $staff->position) == 'Head Barber' ? 'selected' : '' }}>Head Barber</option>
                                <option value="Master Barber" {{ old('position', $staff->position) == 'Master Barber' ? 'selected' : '' }}>Master Barber</option>
                                <option value="Other" {{ old('position', $staff->position) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Custom Position Input (shown when "Other" is selected) -->
                        <div class="form-group" id="customPositionGroup" style="display: {{ old('position', $staff->position) == 'Other' ? 'block' : 'none' }};">
                            <label for="custom_position">Custom Position</label>
                            <input type="text" id="custom_position" name="custom_position" 
                                   class="form-control @error('custom_position') is-invalid @enderror"
                                   value="{{ old('custom_position', $staff->position == 'Other' ? $staff->custom_position : '') }}"
                                   placeholder="Enter custom position">
                            @error('custom_position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Personal Information Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-user"></i> Personal Information</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $staff->name) }}" 
                                   placeholder="Enter barber's full name"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" 
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $staff->email) }}" 
                                   placeholder="barber@example.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" 
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $staff->phone) }}" 
                                   placeholder="012-3456789"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address Field -->
                        <div class="form-group">
                            <label for="address">Address *</label>
                            <textarea id="address" name="address" 
                                      class="form-control @error('address') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Enter full address"
                                      required>{{ old('address', $staff->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bio & Additional Info -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3><i class="fas fa-info-circle"></i> Biography & Additional Information</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group">
                        <label for="bio" class="optional">Biography</label>
                        <textarea id="bio" name="bio" 
                                  class="form-control @error('bio') is-invalid @enderror"
                                  rows="4"
                                  placeholder="Tell us about the barber's experience, specialties, etc...">{{ old('bio', $staff->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">This will be displayed on the barber's public profile.</small>
                    </div>
                </div>
            </div>

            <!-- Password Update (Optional) -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3><i class="fas fa-lock"></i> Update Password (Optional)</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="optional">New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password" name="password" 
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Leave blank to keep current password">
                                <button type="button" class="toggle-password" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="password-strength" id="passwordStrength" style="display: none;">
                                <div class="strength-bar"></div>
                                <span class="strength-text">Password strength: None</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="optional">Confirm New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       placeholder="Confirm new password">
                                <button type="button" class="toggle-password" id="togglePasswordConfirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <small class="form-text text-muted">Only fill these fields if you want to change the password. Password must be at least 8 characters.</small>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.staffs.show', $staff->id) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Barber
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
    
    /* Profile Image Upload */
    .profile-image-upload {
        display: flex;
        align-items: flex-start;
        gap: 25px;
    }
    
    @media (max-width: 768px) {
        .profile-image-upload {
            flex-direction: column;
            align-items: center;
        }
    }
    
    .current-image {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--accent-color);
        background-color: var(--accent-color);
        flex-shrink: 0;
    }
    
    .current-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        background-color: rgba(212, 175, 55, 0.1);
    }
    
    .image-placeholder i {
        font-size: 48px;
        margin-bottom: 10px;
    }
    
    .image-placeholder span {
        font-size: 14px;
        font-weight: 500;
    }
    
    .remove-image {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--danger-color);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        opacity: 0.9;
    }
    
    .remove-image:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    
    .upload-controls {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .file-upload {
        display: none;
    }
    
    .upload-controls .btn {
        align-self: flex-start;
    }
    
    /* Password Input */
    .password-input-wrapper {
        position: relative;
    }
    
    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--dark-gray);
        cursor: pointer;
        padding: 4px;
        transition: var(--transition);
    }
    
    .toggle-password:hover {
        color: var(--primary-color);
    }
    
    .password-strength {
        margin-top: 8px;
    }
    
    .strength-bar {
        height: 4px;
        background-color: var(--medium-gray);
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 4px;
    }
    
    .strength-bar::before {
        content: '';
        display: block;
        height: 100%;
        width: 0%;
        background-color: var(--danger-color);
        transition: var(--transition);
    }
    
    .strength-bar.weak::before {
        width: 25%;
        background-color: var(--danger-color);
    }
    
    .strength-bar.fair::before {
        width: 50%;
        background-color: var(--warning-color);
    }
    
    .strength-bar.good::before {
        width: 75%;
        background-color: #4299e1;
    }
    
    .strength-bar.strong::before {
        width: 100%;
        background-color: var(--success-color);
    }
    
    .strength-text {
        font-size: 12px;
        color: var(--dark-gray);
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
    
    /* Form Text */
    .form-text {
        display: block;
        margin-top: 4px;
        font-size: 13px;
        color: var(--dark-gray);
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
        
        .current-image {
            width: 120px;
            height: 120px;
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
    // Profile image handling
    const profileImageInput = document.getElementById('profile_image');
    const currentProfileImage = document.getElementById('currentProfileImage');
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const removeImageBtn = document.getElementById('removeImage');
    const removeProfileImageInput = document.getElementById('removeProfileImage');
    
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    showToast('Image size must be less than 2MB', 'error');
                    this.value = '';
                    return;
                }
                
                // Check file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    showToast('Please upload a valid image (JPG, PNG, GIF)', 'error');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (currentProfileImage) {
                        currentProfileImage.src = e.target.result;
                    } else {
                        // Create new image element
                        const img = document.createElement('img');
                        img.id = 'currentProfileImage';
                        img.src = e.target.result;
                        img.alt = 'Profile Image';
                        
                        if (imagePlaceholder) {
                            imagePlaceholder.parentNode.replaceChild(img, imagePlaceholder);
                        }
                        
                        // Add remove button if not present
                        if (!removeImageBtn) {
                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'remove-image';
                            removeBtn.id = 'removeImage';
                            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                            removeBtn.addEventListener('click', removeProfileImage);
                            img.parentNode.appendChild(removeBtn);
                        }
                    }
                    
                    // Reset remove flag
                    removeProfileImageInput.value = '0';
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Remove profile image
    function removeProfileImage() {
        if (currentProfileImage) {
            currentProfileImage.remove();
        }
        
        // Show placeholder
        if (imagePlaceholder) {
            const parent = document.querySelector('.current-image');
            parent.innerHTML = `
                <div class="image-placeholder" id="imagePlaceholder">
                    <i class="fas fa-user-circle"></i>
                    <span>No image</span>
                </div>
            `;
        }
        
        // Clear file input
        if (profileImageInput) {
            profileImageInput.value = '';
        }
        
        // Set remove flag
        removeProfileImageInput.value = '1';
        
        // Re-attach event listeners
        const newRemoveBtn = document.createElement('button');
        newRemoveBtn.type = 'button';
        newRemoveBtn.className = 'remove-image';
        newRemoveBtn.innerHTML = '<i class="fas fa-times"></i>';
        newRemoveBtn.addEventListener('click', removeProfileImage);
        document.querySelector('.current-image').appendChild(newRemoveBtn);
    }
    
    // Attach remove event if button exists
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', removeProfileImage);
    }
    
    // Custom position handling
    const positionSelect = document.getElementById('position');
    const customPositionGroup = document.getElementById('customPositionGroup');
    const customPositionInput = document.getElementById('custom_position');
    
    if (positionSelect && customPositionGroup) {
        positionSelect.addEventListener('change', function() {
            if (this.value === 'Other') {
                customPositionGroup.style.display = 'block';
                if (customPositionInput) {
                    customPositionInput.required = true;
                }
            } else {
                customPositionGroup.style.display = 'none';
                if (customPositionInput) {
                    customPositionInput.required = false;
                    customPositionInput.value = '';
                }
            }
        });
    }
    
    // Password strength checker
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthBar = document.querySelector('.strength-bar');
    const strengthText = document.querySelector('.strength-text');
    
    if (passwordInput && passwordStrength && strengthBar && strengthText) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            
            if (password.length > 0) {
                passwordStrength.style.display = 'block';
                let strength = 0;
                let text = 'None';
                
                // Check password strength
                if (password.length >= 8) strength += 25;
                if (/[A-Z]/.test(password)) strength += 25;
                if (/[0-9]/.test(password)) strength += 25;
                if (/[^A-Za-z0-9]/.test(password)) strength += 25;
                
                // Update UI
                strengthBar.className = 'strength-bar';
                if (strength >= 100) {
                    strengthBar.classList.add('strong');
                    text = 'Strong';
                } else if (strength >= 75) {
                    strengthBar.classList.add('good');
                    text = 'Good';
                } else if (strength >= 50) {
                    strengthBar.classList.add('fair');
                    text = 'Fair';
                } else if (strength >= 25) {
                    strengthBar.classList.add('weak');
                    text = 'Weak';
                }
                
                strengthText.textContent = `Password strength: ${text}`;
            } else {
                passwordStrength.style.display = 'none';
            }
        });
    }
    
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    }
    
    if (togglePasswordConfirm) {
        togglePasswordConfirm.addEventListener('click', function() {
            const passwordField = document.getElementById('password_confirmation');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    }
    
    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = value;
                } else if (value.length <= 6) {
                    value = value.replace(/(\d{3})(\d{0,3})/, '$1-$2');
                } else {
                    value = value.replace(/(\d{3})(\d{3})(\d{0,4})/, '$1-$2$3');
                }
            }
            
            e.target.value = value;
        });
    }
    
    // Form validation
    const form = document.getElementById('editBarberForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Check if passwords match (only if password field is filled)
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password && password !== confirmPassword) {
                e.preventDefault();
                showToast('Passwords do not match', 'error');
                return false;
            }
            
            // Check custom position requirement
            if (positionSelect && positionSelect.value === 'Other') {
                if (!customPositionInput.value.trim()) {
                    e.preventDefault();
                    showToast('Please enter a custom position', 'error');
                    customPositionInput.focus();
                    return false;
                }
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
    
    // Toast notification function
    function showToast(message, type = 'success') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast');
        if (existingToast) existingToast.remove();
        
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const iconColor = type === 'success' ? 'var(--success-color)' : 'var(--danger-color)';
        
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `
            <i class="fas ${icon}" style="color: ${iconColor};"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    }
    
    // Show validation errors if any
    @if($errors->any())
        showToast('Please fix the errors in the form', 'error');
    @endif
    
    // Show success message if redirected from other page
    @if(session('success'))
        showToast('{{ session('success') }}');
    @endif
});
</script>
@endsection