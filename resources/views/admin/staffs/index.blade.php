@extends('admin.sidebar')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Barbers Management</h1>
        <a href="{{ route('admin.staffs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Barber
        </a>
    </div>

    <!-- Control Bar -->
    <div class="control-bar">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Search barbers...">
        </div>
        
        <div class="filter-controls">
            <select class="filter-select" id="statusFilter">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            
            <select class="filter-select" id="positionFilter">
                <option value="all">All Positions</option>
                @php
                    $positions = $staffs->pluck('position')->unique()->filter()->sort()->values();
                @endphp
                @foreach($positions as $position)
                    @if($position)
                        <option value="{{ strtolower($position) }}">{{ $position }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <!-- Barbers Grid -->
    <div class="barbers-grid" id="barbersGrid">
        @forelse ($staffs as $staff)
            @php
                $initials = strtoupper(substr($staff->name, 0, 2));
                $phone = $staff->phone ?? '-';
                $address = $staff->address ?? '-';
                $position = $staff->position ?? 'Staff';
            @endphp
            
            <div class="barber-card" 
                 data-name="{{ strtolower($staff->name) }}"
                 data-email="{{ strtolower($staff->email) }}"
                 data-position="{{ strtolower($position) }}"
                 data-status="{{ $staff->status }}">

                <!-- Header -->
                <div class="barber-header">
                    <div class="barber-avatar">
                        {{ $initials }}
                    </div>

                    <div class="barber-info">
                        <h3>{{ $staff->name }}</h3>
                        <div class="barber-position">{{ $position }}</div>

                        <span class="barber-status 
                            {{ $staff->status === 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ ucfirst($staff->status) }}
                        </span>
                    </div>
                </div>

                <!-- Details -->
                <div class="barber-details">
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $staff->email }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value">{{ $phone }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Address:</span>
                        <span class="detail-value">{{ $address }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Role:</span>
                        <span class="detail-value">{{ ucfirst($staff->role) }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="barber-actions">
                    <a href="{{ route('admin.staffs.show', $staff->id) }}"
                       class="btn btn-secondary btn-small view-btn">
                        <i class="fas fa-eye"></i> View
                    </a>
                    
                    <a href="{{ route('admin.staffs.edit', $staff->id) }}"
                       class="btn btn-secondary btn-small">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <form action="{{ route('admin.staffs.destroy', $staff->id) }}"
                          method="POST"
                          class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-small delete-btn">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-user-tie empty-icon"></i>
                <h3>No staff found</h3>
                <p>Please add new barber</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($staffs->hasPages())
    <div class="pagination-container">
        {{ $staffs->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-body">
            <div class="delete-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="delete-message">
                <h3>Delete Barber</h3>
                <p id="deleteMessage">Are you sure you want to delete this barber? This action cannot be undone.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelDelete">Cancel</button>
            <button class="btn btn-danger" id="confirmDelete">Delete Barber</button>
        </div>
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
        max-width: 1400px;
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
    
    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-color);
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
    
    .btn-danger {
        background-color: var(--danger-color);
        color: white;
    }
    
    .btn-danger:hover {
        background-color: #e53e3e;
    }
    
    .btn-small {
        padding: 8px 16px;
        font-size: 14px;
    }
    
    /* Control Bar */
    .control-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .search-container {
        position: relative;
        width: 300px;
    }
    
    .search-input {
        width: 100%;
        padding: 12px 16px 12px 45px;
        border-radius: 8px;
        border: 1px solid var(--medium-gray);
        background-color: white;
        font-size: 15px;
        transition: var(--transition);
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }
    
    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--dark-gray);
    }
    
    .filter-controls {
        display: flex;
        gap: 10px;
    }
    
    .filter-select {
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid var(--medium-gray);
        background-color: white;
        font-size: 15px;
        color: var(--primary-color);
        cursor: pointer;
        min-width: 150px;
    }
    
    /* Barbers Grid */
    .barbers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .barber-card {
        background-color: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }
    
    .barber-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .barber-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .barber-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 20px;
        border: 3px solid var(--accent-color);
        background-color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 28px;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    .barber-info h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--primary-color);
    }
    
    .barber-position {
        color: var(--accent-color);
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 8px;
    }
    
    .barber-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 13px;
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
    
    .barber-details {
        flex: 1;
        margin-bottom: 20px;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--medium-gray);
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        color: var(--dark-gray);
        font-weight: 500;
        font-size: 14px;
    }
    
    .detail-value {
        font-weight: 600;
        font-size: 14px;
        text-align: right;
        max-width: 180px;
        word-break: break-word;
        color: var(--primary-color);
    }
    
    .barber-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    
    .barber-actions .btn {
        flex: 1;
    }
    
    /* Delete form styling */
    .delete-form {
        margin: 0;
        padding: 0;
        display: inline;
    }
    
    /* Empty State */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: var(--dark-gray);
    }
    
    .empty-icon {
        font-size: 48px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    /* Pagination */
    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        list-style: none;
        gap: 8px;
        padding: 0;
        margin: 0;
    }
    
    .pagination li {
        margin: 0;
    }
    
    .pagination li a,
    .pagination li span {
        display: inline-block;
        padding: 8px 16px;
        background-color: white;
        border: 1px solid var(--medium-gray);
        border-radius: 6px;
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition);
    }
    
    .pagination li.active span {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .pagination li a:hover {
        background-color: var(--light-gray);
        transform: translateY(-2px);
    }
    
    /* Style for disabled pagination items */
    .pagination li.disabled span {
        background-color: var(--medium-gray);
        color: var(--dark-gray);
        cursor: not-allowed;
    }
    
    /* Modal styling */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1050;
        align-items: center;
        justify-content: center;
    }
    
    .modal.active {
        display: flex;
    }
    
    .modal-content {
        background-color: white;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        padding: 25px 25px 15px;
        border-bottom: 1px solid var(--medium-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--dark-gray);
        cursor: pointer;
        transition: var(--transition);
    }
    
    .modal-close:hover {
        color: var(--primary-color);
    }
    
    .modal-body {
        padding: 25px;
    }
    
    .modal-footer {
        padding: 15px 25px 25px;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }
    
    /* Delete Confirmation Modal */
    .delete-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: rgba(245, 101, 101, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: var(--danger-color);
        font-size: 36px;
    }
    
    .delete-message {
        text-align: center;
        margin-bottom: 25px;
    }
    
    .delete-message h3 {
        font-size: 22px;
        margin-bottom: 10px;
        color: var(--primary-color);
    }
    
    .delete-message p {
        color: var(--dark-gray);
        line-height: 1.5;
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
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .control-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-container {
            width: 100%;
        }
        
        .filter-controls {
            flex-direction: column;
        }
        
        .filter-select {
            width: 100%;
        }
        
        .barbers-grid {
            grid-template-columns: 1fr;
        }
        
        .barber-header {
            flex-direction: column;
            text-align: center;
        }
        
        .barber-avatar {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .barber-actions {
            flex-direction: column;
        }
        
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
    
    @media (max-width: 480px) {
        .page-title {
            font-size: 24px;
        }
        
        .btn {
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .barber-card {
            padding: 20px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const positionFilter = document.getElementById('positionFilter');
    const barberCards = document.querySelectorAll('.barber-card');
    const deleteModal = document.getElementById('deleteModal');
    const deleteMessage = document.getElementById('deleteMessage');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');
    
    let currentDeleteForm = null;
    
    // Filter barbers
    function filterBarbers() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusValue = statusFilter.value;
        const positionValue = positionFilter.value.toLowerCase();
        
        barberCards.forEach(card => {
            const name = card.getAttribute('data-name');
            const email = card.getAttribute('data-email');
            const position = card.getAttribute('data-position');
            const status = card.getAttribute('data-status');
            
            let shouldShow = true;
            
            // Search filter
            if (searchTerm) {
                shouldShow = name.includes(searchTerm) || 
                           email.includes(searchTerm) || 
                           position.includes(searchTerm);
            }
            
            // Status filter
            if (shouldShow && statusValue !== 'all') {
                shouldShow = status === statusValue;
            }
            
            // Position filter
            if (shouldShow && positionValue !== 'all') {
                shouldShow = position === positionValue;
            }
            
            card.style.display = shouldShow ? 'flex' : 'none';
        });
        
        // Check if all cards are hidden
        const visibleCards = Array.from(barberCards).filter(card => 
            card.style.display !== 'none'
        );
        
        const emptyState = document.querySelector('.empty-state');
        if (emptyState) {
            emptyState.style.display = visibleCards.length === 0 ? 'block' : 'none';
        }
    }
    
    // Event listeners for filtering
    if (searchInput) searchInput.addEventListener('input', filterBarbers);
    if (statusFilter) statusFilter.addEventListener('change', filterBarbers);
    if (positionFilter) positionFilter.addEventListener('change', filterBarbers);
    
    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentDeleteForm = this.closest('.delete-form');
            const barberName = this.closest('.barber-card').querySelector('h3').textContent;
            
            deleteMessage.textContent = `Are you sure you want to delete "${barberName}"? This action cannot be undone.`;
            deleteModal.classList.add('active');
        });
    });
    
    // Cancel delete
    cancelDelete.addEventListener('click', function() {
        deleteModal.classList.remove('active');
        currentDeleteForm = null;
    });
    
    // Confirm delete
    confirmDelete.addEventListener('click', function() {
        if (currentDeleteForm) {
            currentDeleteForm.submit();
        }
        deleteModal.classList.remove('active');
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.classList.remove('active');
            currentDeleteForm = null;
        }
    });
    
    // Keyboard support for modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && deleteModal.classList.contains('active')) {
            deleteModal.classList.remove('active');
            currentDeleteForm = null;
        }
    });
    
    // Show success message if present in session
    @if(session('success'))
        showToast('{{ session('success') }}');
    @endif
    
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
    
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
});
</script>
@endsection