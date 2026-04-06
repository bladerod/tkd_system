let branchDataTable = null;
let originalData = null;

// Validation rules for branch fields
const BranchValidationRules = {
    name: {
        required: true,
        minLength: 2,
        maxLength: 100,
        pattern: /^[A-Za-z0-9\s\-\.\&]+$/,
        message: 'Branch name must be 2-100 characters and can only contain letters, numbers, spaces, hyphens, dots, and ampersands'
    },
    code: {
        required: true,
        minLength: 2,
        maxLength: 20,
        pattern: /^[A-Za-z0-9\-]+$/,
        message: 'Branch code must be 2-20 characters and can only contain letters, numbers, and hyphens'
    },
    address: {
        required: false,
        maxLength: 255,
        message: 'Address cannot exceed 255 characters'
    },
    city: {
        required: false,
        minLength: 2,
        maxLength: 100,
        pattern: /^[A-Za-z\s\-\.]+$/,
        message: 'City name must be 2-100 characters and can only contain letters, spaces, hyphens, and dots'
    },
    province: {
        required: false,
        minLength: 2,
        maxLength: 100,
        pattern: /^[A-Za-z\s\-\.]+$/,
        message: 'Province name must be 2-100 characters and can only contain letters, spaces, hyphens, and dots'
    },
    mobile: {
        required: false,
        pattern: /^(09|\+639)\d{9}$/,
        message: 'Enter a valid Philippine mobile number (09XXXXXXXXX or +639XXXXXXXXX)'
    },
    email: {
        required: false,
        pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
        message: 'Please enter a valid email address'
    }
};

// Delete confirmation function
window.confirmDelete = function(branchId, branchName) {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete "${branchName}". This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/branch/delete/${branchId}`;
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Alpine.js component with validation
window.branchModal = function(checkUrl) {
    return {
        showModal: false,
        isEdit: false,
        isSubmitting: false,
        form: {
            id: null,
            name: '',
            code: '',
            address: '',
            city: '',
            province: '',
            mobile: '',
            email: '',
            status: 'active'
        },
        errors: {},
        validationErrors: {},
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',

        // Open add modal
        openAdd() {
            this.isEdit = false;
            this.isSubmitting = false;
            this.errors = {};
            this.validationErrors = {};
            this.form = {
                id: null,
                name: '',
                code: '',
                address: '',
                city: '',
                province: '',
                mobile: '',
                email: '',
                status: 'active'
            };
            this.showModal = true;
        },

        // Open edit modal
        openEdit(data) {
            this.isEdit = true;
            this.isSubmitting = false;
            this.errors = {};
            this.validationErrors = {};
            this.form = { ...data };
            this.showModal = true;
        },

        // Close modal
        closeModal() {
            this.showModal = false;
            this.isSubmitting = false;
            this.errors = {};
            this.validationErrors = {};
        },
        
        // Validate a single field
        validateField(field) {
            const value = this.form[field]?.trim() || '';
            const rules = BranchValidationRules[field];
            
            if (!rules) return true;
            
            // Clear previous validation error
            delete this.validationErrors[field];
            
            // Required validation
            if (rules.required && !value) {
                this.validationErrors[field] = `${this.getFieldLabel(field)} is required`;
                return false;
            }
            
            // Skip further validation if field is empty and not required
            if (!rules.required && !value) {
                return true;
            }
            
            // Min length validation
            if (rules.minLength && value.length < rules.minLength) {
                this.validationErrors[field] = `${this.getFieldLabel(field)} must be at least ${rules.minLength} characters`;
                return false;
            }
            
            // Max length validation
            if (rules.maxLength && value.length > rules.maxLength) {
                this.validationErrors[field] = `${this.getFieldLabel(field)} cannot exceed ${rules.maxLength} characters`;
                return false;
            }
            
            // Pattern validation
            if (rules.pattern && !rules.pattern.test(value)) {
                this.validationErrors[field] = rules.message;
                return false;
            }
            
            return true;
        },
        
        // Validate entire form
        validateForm() {
            const fieldsToValidate = ['name', 'code', 'address', 'city', 'province', 'mobile', 'email'];
            let isValid = true;
            
            fieldsToValidate.forEach(field => {
                if (!this.validateField(field)) {
                    isValid = false;
                }
            });
            
            return isValid;
        },
        
        // Get human-readable field label
        getFieldLabel(field) {
            const labels = {
                name: 'Branch name',
                code: 'Branch code',
                address: 'Address',
                city: 'City',
                province: 'Province',
                mobile: 'Mobile number',
                email: 'Email address'
            };
            return labels[field] || field;
        },
        
        // Format mobile number as user types
        formatMobileNumber() {
            if (!this.form.mobile) return;
            
            let value = this.form.mobile.replace(/[^0-9+]/g, '');
            if (value.startsWith('+')) {
                value = '+' + value.replace(/[^0-9]/g, '');
                if (value.length > 13) value = value.slice(0, 13);
            } else {
                value = value.replace(/[^0-9]/g, '');
                if (value.length > 11) value = value.slice(0, 11);
            }
            this.form.mobile = value;
            this.validateField('mobile');
        },
        
        // Check field uniqueness (duplicate check)
        async checkFieldUniqueness(field) {
            if (!this.form[field] || (this.isEdit && !this.form[field])) return;
            
            // First validate the field format
            const isValid = this.validateField(field);
            if (!isValid) return;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            try {
                const res = await fetch(checkUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        field: field,
                        value: this.form[field],
                        id: this.form.id
                    })
                });
                
                const data = await res.json();
                this.errors[field] = data.exists;
                
                if (data.exists) {
                    // Show duplicate error
                    this.validationErrors[field] = `${this.getFieldLabel(field)} already exists. Please use a different ${field}.`;
                } else {
                    // Clear duplicate error if it exists
                    if (this.validationErrors[field] && this.validationErrors[field].includes('already exists')) {
                        delete this.validationErrors[field];
                    }
                }
            } catch (e) {
                console.error(`Error checking ${field}:`, e);
            }
        },
        
        // Submit form with validation
        async submitForm() {
            // First validate all fields
            const isValid = this.validateForm();
            
            if (!isValid) {
                // Scroll to first error
                const firstErrorField = Object.keys(this.validationErrors)[0];
                if (firstErrorField) {
                    const errorElement = document.querySelector(`[name="${firstErrorField}"]`);
                    if (errorElement) {
                        errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        errorElement.focus();
                    }
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fix the errors in the form before submitting.',
                    confirmButtonColor: '#1C1C1D'
                });
                return;
            }
            
            // Check for duplicate errors
            const hasDuplicateErrors = Object.values(this.errors).some(error => error === true);
            if (hasDuplicateErrors) {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Entry',
                    text: 'Please fix the duplicate field errors before submitting.',
                    confirmButtonColor: '#1C1C1D'
                });
                return;
            }
            
            // Get the form element
            const form = this.$refs.branchForm;
            if (!form) {
                console.error('Form not found');
                return;
            }
            
            // Set submitting state
            this.isSubmitting = true;
            
            try {
                // Create form data
                const formData = new FormData(form);
                
                // Add method override for edit
                if (this.isEdit) {
                    formData.append('_method', 'PUT');
                }
                
                // Submit the form
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    // Success - close modal and show success message
                    this.closeModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: result.message || `Branch ${this.isEdit ? 'updated' : 'added'} successfully.`,
                        confirmButtonColor: '#1C1C1D'
                    }).then(() => {
                        // Reload page to show updated data
                        window.location.reload();
                    });
                } else {
                    // Error from server
                    if (result.errors) {
                        // Handle Laravel validation errors
                        const errorMessages = Object.values(result.errors).flat();
                        throw new Error(errorMessages.join('\n'));
                    } else {
                        throw new Error(result.message || 'Something went wrong');
                    }
                }
            } catch (error) {
                console.error('Form submission error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Failed to submit form. Please try again.',
                    confirmButtonColor: '#3085d6'
                });
            } finally {
                this.isSubmitting = false;
            }
        },
        
        // Check field (uniqueness) - called from input event
        async checkField(field) {
            await this.checkFieldUniqueness(field);
        },
        
        // Handle field input with real-time validation
        handleFieldInput(field) {
            this.validateField(field);
            if (field === 'mobile') {
                this.formatMobileNumber();
            }
        }
    }
}

// Initialize DataTable
function initializeBranchDataTable() {
    const branchTable = document.getElementById('branchTable');
    if (!branchTable) return;
    
    const tbody = branchTable.querySelector('tbody');
    const rows = tbody ? tbody.querySelectorAll('tr') : [];
    const hasData = rows.length > 0 && !rows[0]?.querySelector('td[colspan]');
    
    if (hasData && typeof simpleDatatables !== 'undefined') {
        try {
            // Store original data
            const tableData = [];
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length > 0) {
                    tableData.push({
                        name: cells[0]?.textContent.trim() || '',
                        code: cells[1]?.textContent.trim() || '',
                        city: cells[2]?.textContent.trim() || '',
                        province: cells[3]?.textContent.trim() || '',
                        mobile: cells[4]?.textContent.trim() || '',
                        email: cells[5]?.textContent.trim() || '',
                        status: cells[6]?.textContent.trim() || ''
                    });
                }
            });
            originalData = tableData;
            
            branchDataTable = new simpleDatatables.DataTable(branchTable, {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50, 100],
                searchable: true,
                sortable: true,
                labels: {
                    placeholder: "Search...",
                    perPage: "Entries per page",
                    noRows: "No branches found",
                    info: "Showing {start} to {end} of {rows} entries",
                    noResults: "No results match your search query"
                }
            });
            
            console.log('Branch DataTable initialized successfully');
            
            // Setup custom filters after DataTable is initialized
            setTimeout(() => setupCustomFilters(), 100);
        } catch (error) {
            console.error('Branch DataTable initialization failed:', error);
        }
    }
}

// Setup custom filters
function setupCustomFilters() {
    const cityFilter = document.getElementById('cityFilter');
    const statusFilter = document.getElementById('statusFilter');
    const customSearch = document.getElementById('branchSearch');
    
    if (cityFilter && branchDataTable) {
        cityFilter.addEventListener('change', function() {
            const selectedCity = this.value;
            if (selectedCity) {
                branchDataTable.columns().search(selectedCity, 2).draw();
            } else {
                branchDataTable.columns().search('').draw();
            }
        });
    }
    
    if (statusFilter && branchDataTable) {
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value;
            if (selectedStatus) {
                branchDataTable.columns().search(selectedStatus, 6).draw();
            } else {
                branchDataTable.columns().search('').draw();
            }
        });
    }
    
    if (customSearch && branchDataTable) {
        customSearch.addEventListener('input', function() {
            branchDataTable.search(this.value).draw();
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeBranchDataTable();
});