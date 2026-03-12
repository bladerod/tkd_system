// Modal functions
window.openAddUserModal = function() {
    document.getElementById('addUserModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

window.closeAddUserModal = function() {
    document.getElementById('addUserModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('addUserForm').reset();
    
    // Reset password field type
    const addPassword = document.getElementById('add_password');
    if (addPassword) {
        addPassword.setAttribute('type', 'password');
    }
    
    // Reset eye icons
    document.querySelectorAll('.eye-open').forEach(icon => icon.classList.remove('hidden'));
    document.querySelectorAll('.eye-closed').forEach(icon => icon.classList.add('hidden'));
}

function initializePasswordToggles() {
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.removeEventListener('click', handlePasswordToggle);
        button.addEventListener('click', handlePasswordToggle);
    });
}


const ValidationRules = {
    // Mobile number validation for Philippine numbers
    mobile: {
        pattern: /^(09|\+639)\d{9}$/,
        message: 'Please enter a valid Philippine mobile number (e.g., 09123456789 or +639123456789)',
        format: (value) => {
            // Remove all non-digits
            let cleaned = value.replace(/\D/g, '');
            
            // Auto-add +63 prefix if needed
            if (cleaned.length === 10 && cleaned.startsWith('9')) {
                cleaned = '0' + cleaned;
            }
            if (cleaned.length === 11 && cleaned.startsWith('09')) {
                // Valid format
            } else if (cleaned.length === 12 && cleaned.startsWith('639')) {
                cleaned = '0' + cleaned.substring(2);
            }
            
            return cleaned;
        }
    },
    
    // Email validation
    email: {
        pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
        message: 'Please enter a valid email address'
    },
    
    // Name validation (letters, spaces, hyphens only)
    name: {
        pattern: /^[A-Za-z\s\-]+$/,
        message: 'Only letters, spaces, and hyphens allowed'
    },
    
    // Username validation (alphanumeric + underscore)
    username: {
        pattern: /^[a-zA-Z0-9_]+$/,
        message: 'Only letters, numbers, and underscores allowed'
    },
    
    // Password strength checker
    passwordStrength: (password) => {
        let strength = 0;
        let feedback = [];
        
        if (password.length >= 6) strength += 20;
        else feedback.push('at least 6 characters');
        
        if (/[A-Z]/.test(password)) strength += 20;
        if (/[a-z]/.test(password)) strength += 20;
        if (/[0-9]/.test(password)) strength += 20;
        if (/[^A-Za-z0-9]/.test(password)) strength += 20;
        
        let level = 'Weak';
        let color = 'bg-red-500';
        
        if (strength >= 80) {
            level = 'Strong';
            color = 'bg-green-500';
        } else if (strength >= 60) {
            level = 'Good';
            color = 'bg-yellow-500';
        } else if (strength >= 40) {
            level = 'Fair';
            color = 'bg-orange-500';
        }
        
        return { strength, level, color, feedback };
    }
};


function validateField(field) {
    const fieldName = field.name;
    const fieldValue = field.value.trim();
    const errorElement = document.getElementById(`error_${fieldName}`);
    const fieldGroup = field.closest('.form-group');
    
    if (!errorElement) return true;
    
    let isValid = true;
    let errorMessage = '';
    
    // Required field validation
    if (field.required && !fieldValue) {
        isValid = false;
        errorMessage = 'This field is required';
    } else if (fieldValue) {
        // Field-specific validation
        switch (fieldName) {
            case 'mobile':
                const cleaned = ValidationRules.mobile.format(fieldValue);
                if (fieldValue !== cleaned) {
                    field.value = cleaned; // Auto-format
                }
                if (!ValidationRules.mobile.pattern.test(cleaned)) {
                    isValid = false;
                    errorMessage = ValidationRules.mobile.message;
                } else if (cleaned.length < 11 || cleaned.length > 13) {
                    isValid = false;
                    errorMessage = 'Mobile number must be 11-13 digits';
                }
                break;
                
            case 'email':
                if (!ValidationRules.email.pattern.test(fieldValue)) {
                    isValid = false;
                    errorMessage = ValidationRules.email.message;
                }
                break;
                
            case 'fname':
            case 'lname':
                if (fieldValue.length < 2) {
                    isValid = false;
                    errorMessage = 'Must be at least 2 characters';
                } else if (!ValidationRules.name.pattern.test(fieldValue)) {
                    isValid = false;
                    errorMessage = ValidationRules.name.message;
                }
                break;
                
            case 'username':
                if (fieldValue.length < 3) {
                    isValid = false;
                    errorMessage = 'Must be at least 3 characters';
                } else if (!ValidationRules.username.pattern.test(fieldValue)) {
                    isValid = false;
                    errorMessage = ValidationRules.username.message;
                }
                // Check username availability (optional)
                checkUsernameAvailability(fieldValue).then(available => {
                    if (!available) {
                        showFieldError(field, 'Username already taken');
                    }
                });
                break;
                
            case 'password':
                if (fieldValue.length < 6) {
                    isValid = false;
                    errorMessage = 'Must be at least 6 characters';
                } else {
                    // Check password strength
                    const strength = ValidationRules.passwordStrength(fieldValue);
                    updatePasswordStrength(field, strength);
                }
                break;
        }
    }
    
    // Update UI based on validation
    if (isValid) {
        field.classList.remove('border-red-500');
        field.classList.add('border-green-500');
        errorElement.classList.add('hidden');
        errorElement.textContent = '';
        
        // Hide validation hint if visible
        const hint = fieldGroup.querySelector('.validation-hint');
        if (hint) hint.classList.add('hidden');
    } else {
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

function showFieldError(field, message) {
    const fieldName = field.name;
    const errorElement = document.getElementById(`error_${fieldName}`);
    const fieldGroup = field.closest('.form-group');
    
    field.classList.remove('border-green-500');
    field.classList.add('border-red-500');
    
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
    
    // Show validation hint
    const hint = fieldGroup.querySelector('.validation-hint');
    if (hint) hint.classList.remove('hidden');
}

function updatePasswordStrength(field, strength) {
    const fieldGroup = field.closest('.form-group');
    const strengthContainer = fieldGroup.querySelector('.password-strength');
    const strengthBar = fieldGroup.querySelector('.strength-progress');
    const strengthText = fieldGroup.querySelector('.strength-text');
    
    if (strengthContainer && strengthBar && strengthText) {
        strengthContainer.classList.remove('hidden');
        strengthBar.style.width = strength.strength + '%';
        strengthBar.className = `strength-progress h-1 rounded transition-all duration-300 ${strength.color}`;
        strengthText.textContent = strength.level;
        strengthText.className = `strength-text text-xs ${strength.color.replace('bg-', 'text-')}`;
    }
}

async function checkUsernameAvailability(username) {
    try {
        const response = await fetch(`/check-username?username=${encodeURIComponent(username)}`);
        const data = await response.json();
        return data.available;
    } catch (error) {
        console.error('Error checking username:', error);
        return true; // Assume available on error
    }
}

function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

function initializeValidation() {
    // Remove any existing submit handlers first
    const addForm = document.getElementById('addUserForm');
    const editForm = document.getElementById('editUserForm');
    
    if (addForm) {
        // Remove old listeners by cloning and replacing
        const newAddForm = addForm.cloneNode(true);
        addForm.parentNode.replaceChild(newAddForm, addForm);
        
        // Add new submit handler
        newAddForm.addEventListener('submit', function(e) {
            console.log('Add form submitted');
            
            // Show loading state
            const submitBtn = document.getElementById('submitAddBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Adding...';
                submitBtn.disabled = true;
            }
            
            // Let the form submit normally
            return true;
        });
    }
    
    if (editForm) {
        // Remove old listeners by cloning and replacing
        const newEditForm = editForm.cloneNode(true);
        editForm.parentNode.replaceChild(newEditForm, editForm);
        
        // Add new submit handler
        newEditForm.addEventListener('submit', function(e) {
            console.log('Edit form submitted');
            console.log('Form action:', this.action);
            
            // Show loading state
            const submitBtn = document.getElementById('submitEditBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Updating...';
                submitBtn.disabled = true;
            }
            
            // Let the form submit normally
            return true;
        });
    }
    
    // Add real-time validation listeners (optional, won't block submission)
    document.querySelectorAll('#addUserForm input, #addUserForm select, #editUserForm input, #editUserForm select').forEach(field => {
        field.addEventListener('blur', function() {
            // Optional: visual feedback only
            if (this.value.trim() === '' && this.required) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-green-500');
            }
        });
    });
    
    // Initialize password toggles
    initializePasswordToggles();
}


function handlePasswordToggle(e) {
    e.preventDefault();
    const button = e.currentTarget;
    const targetId = button.getAttribute('data-target');
    const passwordInput = document.getElementById(targetId);
    
    if (!passwordInput) return;
    
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Toggle icons
    const eyeIcon = button.querySelector('.eye-icon');
    const eyeOpen = button.querySelector('.eye-open');
    const eyeClosed = button.querySelector('.eye-closed');
    
    if (eyeOpen && eyeClosed) {
        if (type === 'text') {
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
}

// Edit user functions
window.openEditUserModal = function(userId) {
    console.log('Opening edit modal for user ID:', userId);
    
    fetch(`/users/${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(user => {
            console.log('User data received:', user);
            
            // Populate form fields
            document.getElementById('edit_branch_id').value = user.branch_id || '';
            document.getElementById('edit_role').value = user.role || '';
            document.getElementById('edit_fname').value = user.fname || '';
            document.getElementById('edit_lname').value = user.lname || '';
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_username').value = user.username || '';
            
            // Fix: Use correct ID for mobile field
            const mobileField = document.getElementById('edit_mobile');
            if (mobileField) {
                mobileField.value = user.mobile || '';
            }
            
            // Set form action URL - use user.id
            const editForm = document.getElementById('editUserForm');
            editForm.action = `/users/${user.id}`;
            
            console.log('Form action set to:', editForm.action);
            
            // Show modal
            document.getElementById('editUserModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Reset password field
            const editPassword = document.getElementById('edit_password');
            if (editPassword) {
                editPassword.value = '';
                editPassword.setAttribute('type', 'password');
            }
            
            // Reset eye icons
            document.querySelectorAll('.eye-open').forEach(icon => icon.classList.remove('hidden'));
            document.querySelectorAll('.eye-closed').forEach(icon => icon.classList.add('hidden'));
        })
        .catch(error => {
            console.error('Error loading user:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load user data. Please try again.',
                confirmButtonColor: '#3085d6'
            });
        });
};

window.closeEditUserModal = function() {
    document.getElementById('editUserModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editUserForm').reset();
    
    // Reset password field type
    const editPassword = document.getElementById('edit_password');
    if (editPassword) {
        editPassword.setAttribute('type', 'password');
    }
    
    // Reset eye icons
    document.querySelectorAll('.eye-open').forEach(icon => icon.classList.remove('hidden'));
    document.querySelectorAll('.eye-closed').forEach(icon => icon.classList.add('hidden'));
}

// Close modal when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById('addUserModal');
    const editModal = document.getElementById('editUserModal');
    
    if (event.target === addModal) closeAddUserModal();
    if (event.target === editModal) closeEditUserModal();
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key !== 'Escape') return;
    
    const addModal = document.getElementById('addUserModal');
    const editModal = document.getElementById('editUserModal');
    
    if (addModal && !addModal.classList.contains('hidden')) closeAddUserModal();
    if (editModal && !editModal.classList.contains('hidden')) closeEditUserModal();
});

// Event handlers
function handleEditClick(e) {
    e.preventDefault();
    const button = e.currentTarget;
    const userId = button.getAttribute('data-user-id');
    console.log('Edit button clicked for user ID:', userId);
    
    if (userId) {
        openEditUserModal(userId);
    }
}

function handleDeleteClick(e) {
    e.preventDefault();
    const userId = this.getAttribute('data-user-id');
    const userName = this.getAttribute('data-user-name') || 'this user';
    
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete ${userName}. This action cannot be undone.`,
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
        form.action = `/users/${userId}`;
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

// Attach button listeners
function attachButtonListeners() {
    console.log('Attaching button listeners using event delegation');
    
    // Remove old listeners first
    const table = document.getElementById('userTable');
    if (!table) return;
    
    // Use event delegation on the table body
    const tbody = table.querySelector('tbody');
    if (!tbody) return;
    
    // Remove old listeners
    tbody.removeEventListener('click', handleTableClick);
    
    // Add new listener using delegation
    tbody.addEventListener('click', handleTableClick);
}

function handleTableClick(e) {
    // Handle edit button clicks
    const editButton = e.target.closest('.edit-btn');
    if (editButton) {
        e.preventDefault();
        const userId = editButton.getAttribute('data-user-id');
        console.log('Edit button clicked via delegation, user ID:', userId);
        if (userId) {
            openEditUserModal(userId);
        }
        return;
    }
    
    // Handle delete button clicks
    const deleteButton = e.target.closest('.delete-user-btn');
    if (deleteButton) {
        e.preventDefault();
        const userId = deleteButton.getAttribute('data-user-id');
        const userName = deleteButton.getAttribute('data-user-name') || 'this user';
        
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${userName}. This action cannot be undone.`,
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
            form.action = `/users/${userId}`;
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
                placeholder: "Search...",
                perPage: "Entries per page",
                noRows: "No users found",
                info: "Showing {start} to {end} of {rows} entries",
                noResults: "No results match your search query"
            }
        });
        
        // Re-attach listeners after DataTable redraws using event delegation
        if (dataTable && typeof dataTable.on === 'function') {
            dataTable.on('datatable.draw', function() {
                console.log('DataTable redrawn, listeners maintained via delegation');
                // No need to reattach - delegation handles it
            });
        }
        
        return dataTable;
    } catch (error) {
        console.error('DataTable initialization failed:', error);
        return null;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    // Initialize password toggles
    initializePasswordToggles();
    
    // Initialize form handling
    initializeFormHandling();
    
    // Attach button listeners using event delegation
    attachButtonListeners();
    
    // Initialize DataTable if table exists
    const userTable = document.getElementById('userTable');
    if (userTable) {
        const tbody = userTable.querySelector('tbody');
        const rows = tbody ? tbody.querySelectorAll('tr') : [];
        const hasData = rows.length > 0 && !rows[0].querySelector('td[colspan]');
        
        if (hasData) {
            setTimeout(() => {
                initializeDataTable(userTable);
            }, 100);
        }
    }
});
    

function initializeFormHandling() {
    // Add form
    const addForm = document.getElementById('addUserForm');
    if (addForm) {
        // Remove old listeners by cloning
        const newAddForm = addForm.cloneNode(true);
        addForm.parentNode.replaceChild(newAddForm, addForm);
        
        // Add new submit handler
        newAddForm.addEventListener('submit', function(e) {
            console.log('Add form submitted');
            
            // Show loading state
            const submitBtn = document.getElementById('submitAddBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Adding...';
                submitBtn.disabled = true;
            }
            
            // Let the form submit normally
            return true;
        });
    }
    
    // Edit form
    const editForm = document.getElementById('editUserForm');
    if (editForm) {
        // Remove old listeners by cloning
        const newEditForm = editForm.cloneNode(true);
        editForm.parentNode.replaceChild(newEditForm, editForm);
        
        // Add new submit handler
        newEditForm.addEventListener('submit', function(e) {
            console.log('Edit form submitted');
            console.log('Form action:', this.action);
            
            // Show loading state
            const submitBtn = document.getElementById('submitEditBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Updating...';
                submitBtn.disabled = true;
            }
            
            // Let the form submit normally
            return true;
        });
    }
    
    // Initialize password toggles again for new forms
    initializePasswordToggles();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById('addUserModal');
    const editModal = document.getElementById('editUserModal');
    
    if (event.target === addModal) closeAddUserModal();
    if (event.target === editModal) closeEditUserModal();
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key !== 'Escape') return;
    
    const addModal = document.getElementById('addUserModal');
    const editModal = document.getElementById('editUserModal');
    
    if (addModal && !addModal.classList.contains('hidden')) closeAddUserModal();
    if (editModal && !editModal.classList.contains('hidden')) closeEditUserModal();
});