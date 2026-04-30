@extends('customer.sidebar')

@section('title', 'Edit Profile')

@section('content')
@php
    $profileImage = $user->profile_image
        ? asset($user->profile_image)
        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=d4af37&color=1a1f36&bold=true&size=400';
@endphp

<div class="customer-page profile-edit-page">
    <section class="profile-edit-header">
        <div>
            <a href="{{ route('customer.profile.show') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to Profile
            </a>
            <span class="profile-kicker">Account Settings</span>
            <h1>Edit Profile</h1>
            <p>Keep your contact details and account security up to date.</p>
        </div>
    </section>

    @if($errors->any())
        <div class="profile-alert error">
            <i class="fas fa-circle-exclamation"></i>
            <div>
                <strong>Please fix these details</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="profile-alert success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-edit-layout">
        @csrf
        @method('PATCH')

        <aside class="profile-photo-panel">
            <img src="{{ $profileImage }}" alt="{{ $user->name }}" id="previewImage" class="edit-avatar">
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->email }}</p>

            <label class="upload-dropzone" id="uploadArea" for="profile_image">
                <i class="fas fa-cloud-arrow-up"></i>
                <strong>Upload photo</strong>
                <span>JPG, PNG, or GIF up to 2MB</span>
                <input type="file" id="profile_image" name="profile_image" accept="image/*">
            </label>

            @error('profile_image')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </aside>

        <main class="profile-form-panel">
            <section class="form-section">
                <div class="section-heading">
                    <span class="section-icon"><i class="fas fa-user"></i></span>
                    <div>
                        <h2>Personal Information</h2>
                        <p>Your main customer profile details.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name <span>*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span>*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror" placeholder="012-3456789">
                        @error('phone')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="4" class="form-control @error('address') is-invalid @enderror" placeholder="Enter your address">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="form-section">
                <div class="section-heading">
                    <span class="section-icon"><i class="fas fa-lock"></i></span>
                    <div>
                        <h2>Password</h2>
                        <p>Leave both password fields empty to keep your current password.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <div class="password-field">
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                            <button type="button" class="toggle-password" data-target="password" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="password-field">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
                            <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Show password confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <div class="form-actions">
                <a href="{{ route('customer.profile.show') }}" class="profile-btn">Cancel</a>
                <button type="reset" class="profile-btn">Reset</button>
                <button type="submit" class="profile-btn primary">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </div>
        </main>
    </form>
</div>

<style>
    .profile-edit-page {
        --profile-border: #e2e8f0;
        --profile-muted: #718096;
        --profile-soft: #f8fafc;
        --profile-shadow: 0 10px 26px rgba(26, 31, 54, 0.08);
    }

    .profile-edit-header {
        margin-bottom: var(--customer-section-gap);
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        color: var(--secondary);
        text-decoration: none;
        font-weight: 800;
    }

    .back-link:hover {
        color: var(--accent);
        text-decoration: none;
    }

    .profile-kicker {
        display: block;
        color: #8a6d16;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .profile-edit-header h1 {
        margin: 6px 0 8px;
        color: var(--primary);
        font-size: 34px;
        font-weight: 900;
    }

    .profile-edit-header p {
        color: var(--profile-muted);
        margin: 0;
    }

    .profile-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        margin-bottom: var(--customer-section-gap);
        border-radius: 8px;
        font-weight: 800;
    }

    .profile-alert.success {
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
        border: 1px solid rgba(72, 187, 120, 0.25);
    }

    .profile-alert.error {
        background: rgba(245, 101, 101, 0.12);
        color: #c53030;
        border: 1px solid rgba(245, 101, 101, 0.25);
    }

    .profile-alert ul {
        margin: 8px 0 0;
        padding-left: 18px;
        font-weight: 700;
    }

    .profile-edit-layout {
        display: grid;
        grid-template-columns: 340px minmax(0, 1fr);
        gap: var(--customer-card-gap);
        align-items: start;
    }

    .profile-photo-panel,
    .profile-form-panel {
        background: #fff;
        border: 1px solid var(--profile-border);
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .profile-photo-panel {
        position: sticky;
        top: 24px;
        padding: 24px;
        text-align: center;
    }

    .edit-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #fff;
        box-shadow: var(--profile-shadow);
        margin-bottom: 16px;
    }

    .profile-photo-panel h2 {
        margin: 0 0 6px;
        color: var(--primary);
        font-size: 24px;
        font-weight: 900;
    }

    .profile-photo-panel p {
        margin: 0 0 20px;
        color: var(--profile-muted);
        overflow-wrap: anywhere;
    }

    .upload-dropzone {
        display: grid;
        justify-items: center;
        gap: 8px;
        padding: 20px;
        border: 1px dashed #cbd5e0;
        border-radius: 8px;
        background: var(--profile-soft);
        color: var(--primary);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .upload-dropzone:hover,
    .upload-dropzone.drag-over {
        border-color: var(--accent);
        background: rgba(212, 175, 55, 0.12);
    }

    .upload-dropzone i {
        color: #8a6d16;
        font-size: 28px;
    }

    .upload-dropzone span {
        color: var(--profile-muted);
        font-size: 13px;
        font-weight: 700;
    }

    .upload-dropzone input {
        display: none;
    }

    .profile-form-panel {
        padding: 24px;
    }

    .form-section {
        padding-bottom: 24px;
        margin-bottom: 24px;
        border-bottom: 1px solid var(--profile-border);
    }

    .form-section:last-of-type {
        margin-bottom: 0;
    }

    .section-heading {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .section-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 8px;
        background: rgba(212, 175, 55, 0.14);
        color: #8a6d16;
        flex-shrink: 0;
    }

    .section-heading h2 {
        margin: 0 0 4px;
        color: var(--primary);
        font-size: 22px;
        font-weight: 900;
    }

    .section-heading p {
        margin: 0;
        color: var(--profile-muted);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group {
        display: grid;
        gap: 8px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        color: var(--primary);
        font-size: 14px;
        font-weight: 900;
    }

    .form-group label span {
        color: var(--danger);
    }

    .form-control {
        width: 100%;
        min-height: 46px;
        padding: 11px 13px;
        border: 1px solid var(--profile-border);
        border-radius: 8px;
        background: #fff;
        color: var(--primary);
        font: inherit;
        transition: all 0.2s ease;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 110px;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.14);
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .password-field {
        position: relative;
    }

    .password-field .form-control {
        padding-right: 48px;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 8px;
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: var(--profile-muted);
        cursor: pointer;
        transform: translateY(-50%);
    }

    .toggle-password:hover {
        color: var(--primary);
        background: var(--profile-soft);
    }

    .form-error {
        color: var(--danger);
        font-size: 13px;
        font-weight: 800;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 24px;
        border-top: 1px solid var(--profile-border);
    }

    .profile-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 11px 16px;
        border: 1px solid var(--profile-border);
        border-radius: 8px;
        background: #fff;
        color: var(--primary);
        font: inherit;
        font-weight: 900;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .profile-btn.primary {
        background: var(--accent);
        border-color: var(--accent);
    }

    .profile-btn:hover {
        color: var(--primary);
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(26, 31, 54, 0.08);
    }

    @media (max-width: 1024px) {
        .profile-edit-layout {
            grid-template-columns: 1fr;
        }

        .profile-photo-panel {
            position: static;
        }
    }

    @media (max-width: 720px) {
        .profile-edit-header h1 {
            font-size: 28px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .profile-btn {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const uploadArea = document.getElementById('uploadArea');
        const uploadInput = document.getElementById('profile_image');
        const previewImage = document.getElementById('previewImage');
        const originalPreview = previewImage?.src;

        function previewSelectedFile() {
            const file = uploadInput?.files?.[0];
            if (!file || !file.type.startsWith('image/') || !previewImage) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                previewImage.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

        if (uploadArea && uploadInput) {
            uploadInput.addEventListener('change', previewSelectedFile);

            uploadArea.addEventListener('dragover', function (event) {
                event.preventDefault();
                uploadArea.classList.add('drag-over');
            });

            uploadArea.addEventListener('dragleave', function () {
                uploadArea.classList.remove('drag-over');
            });

            uploadArea.addEventListener('drop', function (event) {
                event.preventDefault();
                uploadArea.classList.remove('drag-over');
                uploadInput.files = event.dataTransfer.files;
                previewSelectedFile();
            });
        }

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');

                if (!input || !icon) {
                    return;
                }

                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            });
        });

        document.querySelector('.profile-edit-layout')?.addEventListener('reset', function () {
            if (previewImage && originalPreview) {
                window.setTimeout(() => {
                    previewImage.src = originalPreview;
                }, 0);
            }
        });
    });
</script>
@endsection
