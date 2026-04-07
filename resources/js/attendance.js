// attendance.js

// Modal functions
window.openModal = function() {
    const modal = document.getElementById('manualModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

window.closeModal = function() {
    const modal = document.getElementById('manualModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Reset form
        const form = document.getElementById('manualAttendanceForm');
        if (form) {
            form.reset();
            // Reset checkin time to current
            const checkinTime = document.getElementById('checkin_time');
            if (checkinTime) {
                checkinTime.value = getCurrentDateTime();
            }
        }
        
        // Clear error messages
        document.querySelectorAll('#manualModal .error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        // Remove red borders from inputs
        document.querySelectorAll('#manualModal input, #manualModal select').forEach(field => {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
        });
    }
}

function getCurrentDateTime() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

// Validate individual field
function validateAttendanceField(field) {
    const fieldName = field.name;
    const fieldValue = field.value.trim();
    const errorElement = document.getElementById(`error_${fieldName}`);
    
    let isValid = true;
    let errorMessage = '';
    
    if (field.required && !fieldValue) {
        isValid = false;
        errorMessage = 'This field is required';
    }
    
    if (isValid) {
        field.classList.remove('border-red-500');
        field.classList.add('border-gray-300');
        if (errorElement) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }
    } else {
        field.classList.remove('border-gray-300');
        field.classList.add('border-red-500');
        if (errorElement) {
            errorElement.textContent = errorMessage;
            errorElement.classList.remove('hidden');
        }
    }
    
    return isValid;
}

// Validate entire form
function validateAttendanceForm() {
    const form = document.getElementById('manualAttendanceForm');
    if (!form) return true;
    
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateAttendanceField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

// Handle form submission
function initializeAttendanceForm() {
    const manualForm = document.getElementById('manualAttendanceForm');
    if (manualForm) {
        // Remove existing listener to avoid duplicates
        const newForm = manualForm.cloneNode(true);
        manualForm.parentNode.replaceChild(newForm, manualForm);
        
        newForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateAttendanceForm()) {
                return false;
            }
            
            const submitBtn = document.getElementById('submitAttendanceBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Saving...';
                submitBtn.disabled = true;
            }
            
            this.submit();
        });
    }
}

// Branch to Student Dropdown Filter
function initializeDependentDropdowns() {
    const branchSelect = document.getElementById('branch');
    const studentSelect = document.getElementById('student_id');

    if (branchSelect && studentSelect) {
        // Run this whenever the branch changes
        branchSelect.addEventListener('change', function() {
            const selectedBranchId = this.value;
            
            // Reset the student dropdown to the default option
            studentSelect.value = '';

            // Loop through all options in the student dropdown
            Array.from(studentSelect.options).forEach(option => {
                // Ignore the default "Select Student" option
                if (option.value === '') return;

                // Get the branch ID we attached to the option
                const studentBranchId = option.getAttribute('data-branch');

                // If no branch is selected, OR the branch matches the student's branch -> Show it
                if (!selectedBranchId || studentBranchId === selectedBranchId) {
                    option.style.display = '';
                    option.disabled = false;
                } else {
                    // Otherwise -> Hide it
                    option.style.display = 'none';
                    option.disabled = true;
                }
            });
        });
        
        // Trigger it once on load just in case a branch is pre-selected
        branchSelect.dispatchEvent(new Event('change'));
    }
}



// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key !== 'Escape') return;
    
    const modal = document.getElementById('manualModal');
    if (modal && !modal.classList.contains('hidden')) {
        closeModal();
    }
});

// Close modal when clicking outside
let isMouseDownInsideModal = false;

document.addEventListener('mousedown', function(event) {
    const modalPanel = document.querySelector('#manualModal .bg-white');
    if (modalPanel && modalPanel.contains(event.target)) {
        isMouseDownInsideModal = true;
    } else {
        isMouseDownInsideModal = false;
    }
});

document.addEventListener('mouseup', function(event) {
    const modal = document.getElementById('manualModal');
    const modalPanel = document.querySelector('#manualModal .bg-white');
    
    if (modal && !modal.classList.contains('hidden')) {
        if (modalPanel && !modalPanel.contains(event.target) && !isMouseDownInsideModal) {
            closeModal();
        }
    }
    
    isMouseDownInsideModal = false;
});

// Add real-time validation
function initializeRealTimeValidation() {
    document.querySelectorAll('#manualAttendanceForm input, #manualAttendanceForm select').forEach(field => {
        field.addEventListener('input', function() {
            validateAttendanceField(this);
        });
        
        field.addEventListener('blur', function() {
            validateAttendanceField(this);
        });
    });
}

// Initialize DataTable
function initializeDataTable(table) {
    try {
        if (typeof simpleDatatables === 'undefined') {
            console.warn('simpleDatatables not loaded yet');
            return null;
        }
        
        const DataTable = window.simpleDatatables?.DataTable || window.simpleDatatables;
        
        if (!DataTable) {
            console.warn('DataTable constructor not found');
            return null;
        }
        
        const dataTable = new DataTable(table, {
            perPage: 10,
            perPageSelect: [5, 10, 25, 50, 100],
            searchable: true,
            sortable: true,
            labels: {
                placeholder: "Search...",
                perPage: "Entries per page",
                noRows: "No attendance records found",
                info: "Showing {start} to {end} of {rows} entries",
                noResults: "No results match your search query"
            }
        });
        
        return dataTable;
    } catch (error) {
        console.error('DataTable initialization failed:', error);
        return null;
    }
}

// Show SweetAlert notifications
function showNotification(type, message) {
    if (typeof Swal !== 'undefined') {
        const config = {
            icon: type,
            title: type === 'success' ? 'Success!' : 'Error!',
            text: message,
            confirmButtonColor: '#1C1C1D',
            timer: type === 'success' ? 3000 : undefined
        };
        
        if (type === 'error') {
            delete config.timer;
        }
        
        Swal.fire(config);
    } else {
        console.log(`${type.toUpperCase()}: ${message}`);
    }
}

// Initialize all functionality
function initializeAttendanceModule() {
    console.log('Attendance module initializing...');
    
    // Initialize form handling
    initializeAttendanceForm();
    
    // Initialize real-time validation
    initializeRealTimeValidation();

    initializeDependentDropdowns();
    
    // Initialize DataTable if table exists
    const userTable = document.getElementById('userTable');
    if (userTable) {
        const tbody = userTable.querySelector('tbody');
        const rows = tbody ? tbody.querySelectorAll('tr') : [];
        const hasData = rows.length > 0 && !rows[0]?.querySelector('td[colspan]');
        
        if (hasData) {
            setTimeout(() => {
                initializeDataTable(userTable);
            }, 100);
        }
    }
    
    // Check for session messages and show notifications
    const successMessage = document.querySelector('meta[name="session-success"]')?.getAttribute('content');
    const errorMessage = document.querySelector('meta[name="session-error"]')?.getAttribute('content');
    
    if (successMessage) {
        showNotification('success', successMessage);
    }
    
    if (errorMessage) {
        showNotification('error', errorMessage);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing attendance module...');
    initializeAttendanceModule();
});