@extends('admin.sidebar')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Services Management</h1>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Service
        </a>
    </div>

    <!-- Control Bar -->
    <div class="control-bar">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Search services...">
        </div>
        
        <div class="filter-controls">
            <select class="filter-select" id="statusFilter">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            
            <select class="filter-select" id="sortBy">
                <option value="name">Sort by Name</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
                <option value="duration">Sort by Duration</option>
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
            </select>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="services-grid" id="servicesGrid">
        @forelse ($services as $service)
            @php
                $durationText = $service->duration . ' min';
                if ($service->duration >= 60) {
                    $hours = floor($service->duration / 60);
                    $minutes = $service->duration % 60;
                    $durationText = $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'm' : '');
                }
            @endphp
            
            <div class="service-card" 
                 data-name="{{ strtolower($service->name) }}"
                 data-price="{{ $service->price }}"
                 data-duration="{{ $service->duration }}"
                 data-created="{{ $service->created_at->timestamp }}"
                 data-status="{{ $service->status }}">

                <div class="service-header">
                    <div class="service-icon">
                        @php
                            // Determine icon based on service name
                            $icon = 'fas fa-cut';
                            $nameLower = strtolower($service->name);
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
                    
                    <div class="service-info">
                        <h3>{{ $service->name }}</h3>
                        
                        <span class="service-status 
                            {{ $service->status === 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ ucfirst($service->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="service-details">
                    <div class="detail-row">
                        <span class="detail-label">Price:</span>
                        <span class="detail-value price-tag">RM{{ number_format($service->price, 2) }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Duration:</span>
                        <span class="detail-value">{{ $durationText }}</span>
                    </div>
                    
                    @if($service->description)
                    <div class="detail-row full-width">
                        <span class="detail-label">Description:</span>
                        <span class="detail-value description-text" title="{{ $service->description }}">
                            {{ Str::limit($service->description, 60) }}
                        </span>
                    </div>
                    @endif
                    
                    <div class="detail-row">
                        <span class="detail-label">Created:</span>
                        <span class="detail-value">{{ $service->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                
                <div class="service-actions">
                    <a href="{{ route('admin.services.show', $service->id) }}"
                       class="btn btn-secondary btn-small view-btn">
                        <i class="fas fa-eye"></i> View
                    </a>
                    
                    <a href="{{ route('admin.services.edit', $service->id) }}"
                       class="btn btn-secondary btn-small">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <form action="{{ route('admin.services.destroy', $service->id) }}"
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
                <i class="fas fa-cut empty-icon"></i>
                <h3>No services found</h3>
                <p>Please add new services to get started</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($services->hasPages())
    <div class="pagination-container">
        {{ $services->links('pagination::bootstrap-4') }}
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
                <h3>Delete Service</h3>
                <p id="deleteMessage">Are you sure you want to delete this service? This action cannot be undone.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelDelete">Cancel</button>
            <button class="btn btn-danger" id="confirmDelete">Delete Service</button>
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
    
    /* Services Grid */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .service-card {
        background-color: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }
    
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .service-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .service-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        margin-right: 20px;
        background-color: rgba(212, 175, 55, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-color);
        font-size: 24px;
        flex-shrink: 0;
    }
    
    .service-info h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--primary-color);
    }
    
    .service-status {
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
    
    .service-details {
        flex: 1;
        margin-bottom: 20px;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--medium-gray);
    }
    
    .detail-row.full-width {
        flex-direction: column;
        align-items: flex-start;
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
        color: var(--primary-color);
    }
    
    .detail-row.full-width .detail-value {
        text-align: left;
        margin-top: 5px;
        font-weight: normal;
        color: var(--secondary-color);
        line-height: 1.4;
    }
    
    .description-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: help;
    }
    
    .price-tag {
        color: var(--accent-color);
        font-weight: 700;
        font-size: 16px;
    }
    
    .service-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    
    .service-actions .btn {
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
        padding: 20px;
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
        
        .services-grid {
            grid-template-columns: 1fr;
        }
        
        .service-header {
            flex-direction: column;
            text-align: center;
        }
        
        .service-icon {
            margin-right: 0;
            margin-bottom: 15px;
        }
        
        .service-actions {
            flex-direction: column;
        }
    }
    
    @media (max-width: 480px) {
        .btn {
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .service-card {
            padding: 20px;
        }
        
        .service-icon {
            width: 50px;
            height: 50px;
            font-size: 20px;
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
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const sortBy = document.getElementById('sortBy');
    const serviceCards = document.querySelectorAll('.service-card');
    const servicesGrid = document.getElementById('servicesGrid');
    const deleteModal = document.getElementById('deleteModal');
    const deleteMessage = document.getElementById('deleteMessage');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');
    
    let currentDeleteForm = null;
    
    // Filter and sort services
    function filterAndSortServices() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusValue = statusFilter.value;
        const sortValue = sortBy.value;
        
        let filteredCards = Array.from(serviceCards);
        
        // Filter services
        filteredCards = filteredCards.filter(card => {
            const name = card.getAttribute('data-name');
            const status = card.getAttribute('data-status');
            
            let shouldShow = true;
            
            // Search filter
            if (searchTerm) {
                shouldShow = name.includes(searchTerm);
            }
            
            // Status filter
            if (shouldShow && statusValue !== 'all') {
                shouldShow = status === statusValue;
            }
            
            card.style.display = shouldShow ? 'flex' : 'none';
            return shouldShow;
        });
        
        // Sort services
        filteredCards.sort((a, b) => {
            switch(sortValue) {
                case 'name':
                    const nameA = a.querySelector('h3').textContent.toLowerCase();
                    const nameB = b.querySelector('h3').textContent.toLowerCase();
                    return nameA.localeCompare(nameB);
                    
                case 'price_low':
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceA - priceB;
                    
                case 'price_high':
                    const priceHighA = parseFloat(a.getAttribute('data-price'));
                    const priceHighB = parseFloat(b.getAttribute('data-price'));
                    return priceHighB - priceHighA;
                    
                case 'duration':
                    const durationA = parseInt(a.getAttribute('data-duration'));
                    const durationB = parseInt(b.getAttribute('data-duration'));
                    return durationA - durationB;
                    
                case 'newest':
                    const createdA = parseInt(a.getAttribute('data-created'));
                    const createdB = parseInt(b.getAttribute('data-created'));
                    return createdB - createdA;
                    
                case 'oldest':
                    const createdOldA = parseInt(a.getAttribute('data-created'));
                    const createdOldB = parseInt(b.getAttribute('data-created'));
                    return createdOldA - createdOldB;
                    
                default:
                    return 0;
            }
        });
        
        // Re-order DOM elements
        filteredCards.forEach(card => {
            servicesGrid.appendChild(card);
        });
        
        // Check if all cards are hidden
        const visibleCards = filteredCards.filter(card => card.style.display !== 'none');
        
        const emptyState = document.querySelector('.empty-state');
        if (emptyState) {
            emptyState.style.display = visibleCards.length === 0 ? 'block' : 'none';
        }
    }
    
    // Event listeners for filtering and sorting
    if (searchInput) searchInput.addEventListener('input', filterAndSortServices);
    if (statusFilter) statusFilter.addEventListener('change', filterAndSortServices);
    if (sortBy) sortBy.addEventListener('change', filterAndSortServices);
    
    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentDeleteForm = this.closest('.delete-form');
            const serviceName = this.closest('.service-card').querySelector('h3').textContent;
            
            deleteMessage.textContent = `Are you sure you want to delete "${serviceName}"? This action cannot be undone.`;
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
    
    // Show description tooltip on hover
    document.querySelectorAll('.description-text').forEach(element => {
        element.addEventListener('mouseenter', function(e) {
            const title = this.getAttribute('title');
            if (title) {
                // Create and show tooltip
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                tooltip.textContent = title;
                tooltip.style.position = 'absolute';
                tooltip.style.background = 'var(--primary-color)';
                tooltip.style.color = 'white';
                tooltip.style.padding = '8px 12px';
                tooltip.style.borderRadius = '4px';
                tooltip.style.fontSize = '12px';
                tooltip.style.zIndex = '1000';
                tooltip.style.maxWidth = '300px';
                tooltip.style.whiteSpace = 'normal';
                tooltip.style.wordWrap = 'break-word';
                
                document.body.appendChild(tooltip);
                
                const rect = this.getBoundingClientRect();
                tooltip.style.left = (rect.left + window.scrollX) + 'px';
                tooltip.style.top = (rect.bottom + window.scrollY + 5) + 'px';
                
                this._tooltip = tooltip;
            }
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
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