// Modal functions
window.openAddDiscountModal = function() {
    document.getElementById('addDiscountModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

window.closeAddDiscountModal = function() {
    document.getElementById('addDiscountModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('addDiscountForm').reset();
    
    // Reset any error messages
    document.querySelectorAll('.error-message').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    document.querySelectorAll('input, select').forEach(el => {
        el.classList.remove('border-red-500', 'border-green-500');
    });
}

window.openEditDiscountModal = function(discountId) {
    console.log('Opening edit modal for discount ID:', discountId);
    
    fetch(`/discounts/${discountId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(discount => {
            console.log('Discount data received:', discount);
            
            // Populate form fields
            document.getElementById('edit_name').value = discount.name || '';
            document.getElementById('edit_type').value = discount.type || '';
            document.getElementById('edit_value').value = discount.value || '';
            document.getElementById('edit_applicable_to').value = discount.applicable_to || '';
            document.getElementById('edit_valid_from').value = discount.valid_from || '';
            document.getElementById('edit_valid_to').value = discount.valid_to || '';
            document.getElementById('edit_status').value = discount.status || '1';
            
            // Set form action URL
            const editForm = document.getElementById('editDiscountForm');
            editForm.action = `/discounts/${discount.id}`;
            
            console.log('Form action set to:', editForm.action);
            
            // Show modal
            document.getElementById('editDiscountModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error loading discount:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load discount data. Please try again.',
                confirmButtonColor: '#3085d6'
            });
        });
};

window.closeEditDiscountModal = function() {
    document.getElementById('editDiscountModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editDiscountForm').reset();
    
    // Reset any error messages
    document.querySelectorAll('.error-message').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    document.querySelectorAll('input, select').forEach(el => {
        el.classList.remove('border-red-500', 'border-green-500');
    });
}

// Handle table clicks using event delegation
function handleTableClick(e) {
    // Handle edit button clicks
    const editButton = e.target.closest('.edit-discount-btn');
    if (editButton) {
        e.preventDefault();
        const discountId = editButton.getAttribute('data-discount-id');
        console.log('Edit button clicked via delegation, discount ID:', discountId);
        if (discountId) {
            openEditDiscountModal(discountId);
        }
        return;
    }
    
    // Handle delete button clicks
    const deleteButton = e.target.closest('.delete-discount-btn');
    if (deleteButton) {
        e.preventDefault();
        const discountId = deleteButton.getAttribute('data-discount-id');
        const discountName = deleteButton.getAttribute('data-discount-name') || 'this discount';
        
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete "${discountName}". This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/discounts/${discountId}`;
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = window.csrfToken || document.querySelector('meta[name="csrf-token"]').content;
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        });
    }
}

// Initialize DataTable
function initializeDataTable(table) {
    try {
        const dataTable = new simpleDatatables.DataTable(table, {
            perPage: 10,
            perPageSelect: [5, 10, 25, 50, 100],
            searchable: true,
            sortable: true,
            labels: {
                placeholder: "Search discounts...",
                perPage: "Entries per page",
                noRows: "No discounts found",
                info: "Showing {start} to {end} of {rows} discounts",
                noResults: "No results match your search query"
            }
        });
        
        // Re-attach listeners after DataTable redraws using event delegation
        if (dataTable && typeof dataTable.on === 'function') {
            dataTable.on('datatable.draw', function() {
                console.log('DataTable redrawn, listeners maintained via delegation');
            });
        }
        
        return dataTable;
    } catch (error) {
        console.error('DataTable initialization failed:', error);
        return null;
    }
}

// Form handling with validation
function initializeFormHandling() {
    // Add form validation
    const addForm = document.getElementById('addDiscountForm');
    if (addForm) {
        const newAddForm = addForm.cloneNode(true);
        addForm.parentNode.replaceChild(newAddForm, addForm);
        
        // Add validation to date fields
        const validFrom = newAddForm.querySelector('#add_valid_from');
        const validTo = newAddForm.querySelector('#add_valid_to');
        
        if (validFrom && validTo) {
            validFrom.addEventListener('change', function() {
                if (validTo.value && this.value > validTo.value) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date Range',
                        text: 'Valid From date cannot be later than Valid To date',
                        confirmButtonColor: '#3085d6'
                    });
                    this.value = '';
                }
            });
            
            validTo.addEventListener('change', function() {
                if (validFrom.value && this.value < validFrom.value) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date Range',
                        text: 'Valid To date cannot be earlier than Valid From date',
                        confirmButtonColor: '#3085d6'
                    });
                    this.value = '';
                }
            });
        }
        
        newAddForm.addEventListener('submit', function(e) {
            console.log('Add form submitted');
            
            // Validate date range
            const fromDate = document.getElementById('add_valid_from').value;
            const toDate = document.getElementById('add_valid_to').value;
            
            if (fromDate && toDate && fromDate > toDate) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date Range',
                    text: 'Valid From date must be before or equal to Valid To date',
                    confirmButtonColor: '#3085d6'
                });
                return false;
            }
            
            const submitBtn = document.getElementById('submitAddBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Adding...';
                submitBtn.disabled = true;
            }
            return true;
        });
    }
    
    // Edit form validation
    const editForm = document.getElementById('editDiscountForm');
    if (editForm) {
        const newEditForm = editForm.cloneNode(true);
        editForm.parentNode.replaceChild(newEditForm, editForm);
        
        // Add validation to date fields
        const validFrom = newEditForm.querySelector('#edit_valid_from');
        const validTo = newEditForm.querySelector('#edit_valid_to');
        
        if (validFrom && validTo) {
            validFrom.addEventListener('change', function() {
                if (validTo.value && this.value > validTo.value) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date Range',
                        text: 'Valid From date cannot be later than Valid To date',
                        confirmButtonColor: '#3085d6'
                    });
                    this.value = '';
                }
            });
            
            validTo.addEventListener('change', function() {
                if (validFrom.value && this.value < validFrom.value) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Date Range',
                        text: 'Valid To date cannot be earlier than Valid From date',
                        confirmButtonColor: '#3085d6'
                    });
                    this.value = '';
                }
            });
        }
        
        newEditForm.addEventListener('submit', function(e) {
            console.log('Edit form submitted');
            console.log('Form action:', this.action);
            
            // Validate date range
            const fromDate = document.getElementById('edit_valid_from').value;
            const toDate = document.getElementById('edit_valid_to').value;
            
            if (fromDate && toDate && fromDate > toDate) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date Range',
                    text: 'Valid From date must be before or equal to Valid To date',
                    confirmButtonColor: '#3085d6'
                });
                return false;
            }
            
            const submitBtn = document.getElementById('submitEditBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Updating...';
                submitBtn.disabled = true;
            }
            return true;
        });
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById('addDiscountModal');
    const editModal = document.getElementById('editDiscountModal');
    
    if (event.target === addModal) closeAddDiscountModal();
    if (event.target === editModal) closeEditDiscountModal();
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key !== 'Escape') return;
    
    const addModal = document.getElementById('addDiscountModal');
    const editModal = document.getElementById('editDiscountModal');
    
    if (addModal && !addModal.classList.contains('hidden')) closeAddDiscountModal();
    if (editModal && !editModal.classList.contains('hidden')) closeEditDiscountModal();
});

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing discounts page...');
    
    // Initialize form handling
    initializeFormHandling();
    
    // Attach button listeners using event delegation
    const table = document.getElementById('discountTable');
    if (table) {
        const tbody = table.querySelector('tbody');
        if (tbody) {
            tbody.removeEventListener('click', handleTableClick);
            tbody.addEventListener('click', handleTableClick);
        }
    }
    
    // Initialize DataTable if table exists and has data
    const discountTable = document.getElementById('discountTable');
    if (discountTable) {
        const tbody = discountTable.querySelector('tbody');
        const rows = tbody ? tbody.querySelectorAll('tr') : [];
        const hasData = rows.length > 0 && !rows[0].querySelector('td[colspan]');
        
        if (hasData) {
            setTimeout(() => {
                initializeDataTable(discountTable);
            }, 100);
        }
    }
});