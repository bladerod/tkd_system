// ============================================
// Chart Initialization
// ============================================

// Register Chart.js plugins
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
        console.log('Chart.js DataLabels plugin registered successfully');
    } else {
        console.warn('Chart.js or ChartDataLabels not loaded yet');
    }
});

// Revenue Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chart-monthly-revenue');
    if (!ctx) return;
    
    const monthlyData = {
        labels: ['Feb 17', 'Feb 18', 'Feb 19', 'Feb 20', 'Feb 21', 'Feb 22', 'Feb 23'],
        values: [70.1, 80.1, 90.1, 100.1, 110.1, 120.1, 130.1]
    };
    
    const annualData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        values: [450, 650, 800, 720, 950, 1100, 1250, 1400, 1350, 1200, 980, 1500]
    };
    
    let chart = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: monthlyData.labels,
            datasets: [{
                label: 'Sales (P)',
                data: monthlyData.values,
                borderColor: '#1C1C1D',
                backgroundColor: 'rgba(28, 28, 29, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#1C1C1D',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2,
                datalabels: { align: 'top', offset: 4 }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 5,
            layout: { padding: { top: 24 } },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => '₱' + context.raw + 'K'
                    }
                },
                datalabels: {
                    display: true,
                    color: '#1C1C1D',
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    borderRadius: 4,
                    padding: { top: 2, right: 4, bottom: 2, left: 4 },
                    font: { size: 10, weight: 'bold' },
                    align: 'top',
                    offset: 8,
                    formatter: (value) => '₱' + value + 'K'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: {
                        callback: (value) => '₱' + value + 'K',
                        font: { size: 10 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
    
    window.updateChartPeriod = function(period) {
        if (period === 'monthly') {
            chart.data.labels = monthlyData.labels;
            chart.data.datasets[0].data = monthlyData.values;
        } else {
            chart.data.labels = annualData.labels;
            chart.data.datasets[0].data = annualData.values;
        }
        chart.update();
    };
});

// Enrollees Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chart-monthly-enrollees');
    if (!ctx) return;
    
    const monthlyData = {
        labels: ['Feb 17', 'Feb 18', 'Feb 19', 'Feb 20', 'Feb 21', 'Feb 22', 'Feb 23'],
        values: [8, 12, 15, 10, 18, 22, 25]
    };
    
    const annualData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        values: [45, 52, 68, 58, 72, 85, 92, 105, 98, 82, 70, 120]
    };
    
    let chart = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: monthlyData.labels,
            datasets: [{
                label: 'New Enrollees',
                data: monthlyData.values,
                borderColor: '#1C1C1D',
                backgroundColor: 'rgba(28, 28, 29, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#1C1C1D',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2,
                datalabels: { align: 'top', offset: 4 }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 5,
            layout: { padding: { top: 24 } },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => context.raw + ' enrollees'
                    }
                },
                datalabels: {
                    display: true,
                    color: '#1C1C1D',
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    borderRadius: 4,
                    padding: { top: 2, right: 4, bottom: 2, left: 4 },
                    font: { size: 10, weight: 'bold' },
                    align: 'top',
                    offset: 8,
                    formatter: (value) => value
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    title: {
                        display: true,
                        text: 'Number of Enrollees',
                        font: { size: 10, weight: '500' },
                        color: '#6B7280'
                    },
                    ticks: {
                        callback: (value) => value,
                        font: { size: 10 },
                        stepSize: 5
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
    
    window.updateEnrolleesChartPeriod = function(period) {
        if (period === 'monthly') {
            chart.data.labels = monthlyData.labels;
            chart.data.datasets[0].data = monthlyData.values;
            chart.options.scales.y.title.text = 'Daily Enrollees';
        } else {
            chart.data.labels = annualData.labels;
            chart.data.datasets[0].data = annualData.values;
            chart.options.scales.y.title.text = 'Monthly Enrollees';
        }
        chart.update();
    };
});

// ============================================
// Helper Functions
// ============================================

// Clear field error styling
function clearFieldError(field) {
    field.classList.remove('border-red-500', 'border-red-400');
    field.classList.add('border-gray-300');
    
    const errorDiv = field.parentElement?.querySelector('.field-error-message');
    if (errorDiv) errorDiv.remove();
}

// Show field error message
function showFieldError(field, message) {
    field.classList.remove('border-gray-300');
    field.classList.add('border-red-500');
    
    let errorDiv = field.parentElement?.querySelector('.field-error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'field-error-message text-red-500 text-xs mt-1';
        field.parentElement?.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
}

// Toggle password visibility
window.togglePasswordVisibility = function(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(iconId);
    
    if (!passwordInput || !eyeIcon) return;
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    }
};

// ============================================
// Student Form Validation
// ============================================

function validateStudentField(field) {
    const value = field.value.trim();
    const fieldName = field.name;
    let isValid = true;
    let errorMessage = '';
    
    clearFieldError(field);
    
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = 'This field is required';
    } else if (value) {
        switch (fieldName) {
            case 'first_name':
            case 'last_name':
                if (value.length < 2) {
                    errorMessage = 'Must be at least 2 characters';
                    isValid = false;
                } else if (!/^[A-Za-z\s\-]+$/.test(value)) {
                    errorMessage = 'Only letters, spaces, and hyphens allowed';
                    isValid = false;
                }
                break;
                
            case 'email':
                if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)) {
                    errorMessage = 'Please enter a valid email address';
                    isValid = false;
                }
                break;
                
            case 'password':
                if (value.length < 6) {
                    errorMessage = 'Password must be at least 6 characters';
                    isValid = false;
                } else if (!/(?=.*[A-Za-z])(?=.*\d)/.test(value)) {
                    errorMessage = 'Password must contain at least one letter and one number';
                    isValid = false;
                }
                break;
                
            case 'birthdate':
                const birthDate = new Date(value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) age--;
                
                if (age < 4) {
                    errorMessage = 'Student must be at least 4 years old';
                    isValid = false;
                } else if (age > 100) {
                    errorMessage = 'Please enter a valid birth date';
                    isValid = false;
                }
                break;
                
            case 'contact_number':
                if (!/^(09|\+639)\d{9}$/.test(value)) {
                    errorMessage = 'Enter a valid Philippine mobile number (09XXXXXXXXX)';
                    isValid = false;
                }
                break;
                
            case 'contact_person':
                if (value.length < 2) {
                    errorMessage = 'Contact person name must be at least 2 characters';
                    isValid = false;
                }
                break;
        }
    }
    
    if (!isValid) showFieldError(field, errorMessage);
    return isValid;
}

function validateStudentForm() {
    const form = document.querySelector('#dialog form');
    if (!form) return false;
    
    let isValid = true;
    form.querySelectorAll('[required]').forEach(field => {
        if (!validateStudentField(field)) isValid = false;
    });
    
    // Password match validation
    const password = document.getElementById('student_password');
    const confirmPassword = document.getElementById('student_confirm_password');
    if (password && confirmPassword && confirmPassword.value) {
        if (password.value !== confirmPassword.value) {
            showFieldError(confirmPassword, 'Passwords do not match');
            isValid = false;
        }
    }
    
    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please fix the errors in the form before submitting.',
            confirmButtonColor: '#1C1C1D'
        });
        
        const firstError = form.querySelector('.border-red-500');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }
    
    return isValid;
}

// ============================================
// Parent Form Validation
// ============================================

function validateParentField(field) {
    const value = field.value.trim();
    const fieldName = field.name;
    let isValid = true;
    let errorMessage = '';
    
    clearFieldError(field);
    
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = 'This field is required';
    } else if (value) {
        switch (fieldName) {
            case 'fname':
            case 'lname':
                if (value.length < 2) {
                    errorMessage = 'Must be at least 2 characters';
                    isValid = false;
                } else if (!/^[A-Za-z\s\-]+$/.test(value)) {
                    errorMessage = 'Only letters, spaces, and hyphens allowed';
                    isValid = false;
                }
                break;
                
            case 'email':
                if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)) {
                    errorMessage = 'Please enter a valid email address';
                    isValid = false;
                }
                break;
                
            case 'mobile':
                if (!/^(09|\+639)\d{9}$/.test(value)) {
                    errorMessage = 'Enter a valid Philippine mobile number (09XXXXXXXXX)';
                    isValid = false;
                }
                break;
                
            case 'password':
                if (value.length < 6) {
                    errorMessage = 'Password must be at least 6 characters';
                    isValid = false;
                } else if (!/(?=.*[A-Za-z])(?=.*\d)/.test(value)) {
                    errorMessage = 'Password must contain at least one letter and one number';
                    isValid = false;
                }
                break;
                
            case 'address':
                if (value.length < 10) {
                    errorMessage = 'Please enter a complete address (minimum 10 characters)';
                    isValid = false;
                }
                break;
        }
    }
    
    if (!isValid) showFieldError(field, errorMessage);
    return isValid;
}

function validateParentForm() {
    const form = document.getElementById('parentForm');
    if (!form) return false;
    
    let isValid = true;
    
    // Clear previous errors
    document.querySelectorAll('.field-error').forEach(el => el.remove());
    document.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500');
        el.classList.add('border-gray-300');
    });
    
    // Validate required fields
    form.querySelectorAll('[required]').forEach(field => {
        if (!validateParentField(field)) isValid = false;
    });
    
    // Password confirmation validation
    const password = form.querySelector('input[name="password"]');
    const confirmPassword = form.querySelector('input[name="password_confirmation"]');
    const errorMsg = document.getElementById('parent_password_match_error');
    
    if (password && confirmPassword) {
        if (password.value !== confirmPassword.value) {
            isValid = false;
            if (errorMsg) errorMsg.classList.remove('hidden');
            confirmPassword.classList.add('border-red-500');
        } else {
            if (errorMsg) errorMsg.classList.add('hidden');
            confirmPassword.classList.remove('border-red-500');
        }
    }
    
    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please fix the errors in the form before submitting.',
            confirmButtonColor: '#1C1C1D'
        });
        
        const firstError = form.querySelector('.border-red-500');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }
    
    return isValid;
}

// ============================================
// Form Setup Functions
// ============================================

function setupStudentFormValidation() {
    const form = document.querySelector('#dialog form');
    if (!form) return;
    
    // Real-time validation on blur
    form.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('blur', () => validateStudentField(field));
        field.addEventListener('input', () => {
            if (field.classList.contains('border-red-500')) validateStudentField(field);
        });
    });
    
    // Password match real-time validation
    const password = document.getElementById('student_password');
    const confirmPassword = document.getElementById('student_confirm_password');
    const errorMsg = document.getElementById('password_match_error');
    
    if (password && confirmPassword) {
        const checkPasswordMatch = () => {
            if (password.value !== confirmPassword.value && confirmPassword.value) {
                if (errorMsg) errorMsg.classList.remove('hidden');
                confirmPassword.classList.add('border-red-500');
            } else {
                if (errorMsg) errorMsg.classList.add('hidden');
                confirmPassword.classList.remove('border-red-500');
            }
        };
        
        confirmPassword.addEventListener('input', checkPasswordMatch);
        password.addEventListener('input', () => {
            if (confirmPassword.value) checkPasswordMatch();
        });
    }
    
    // Form submission
    form.addEventListener('submit', (e) => {
        if (!validateStudentForm()) e.preventDefault();
    });
}

function setupParentFormValidation() {
    const form = document.getElementById('parentForm');
    if (!form) return;
    
    // Real-time validation on blur
    form.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('blur', () => validateParentField(field));
        field.addEventListener('input', () => {
            if (field.classList.contains('border-red-500')) validateParentField(field);
        });
    });
    
    // Mobile number formatting
    const mobileInput = form.querySelector('input[name="mobile"]');
    if (mobileInput) {
        mobileInput.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9+]/g, '');
            if (value.startsWith('+')) {
                value = '+' + value.replace(/[^0-9]/g, '');
            } else {
                value = value.replace(/[^0-9]/g, '');
            }
            this.value = value;
        });
    }
    
    // Password match real-time validation
    const password = form.querySelector('input[name="password"]');
    const confirmPassword = form.querySelector('input[name="password_confirmation"]');
    const errorMsg = document.getElementById('parent_password_match_error');
    
    if (password && confirmPassword) {
        const checkPasswordMatch = () => {
            if (password.value !== confirmPassword.value && confirmPassword.value) {
                if (errorMsg) errorMsg.classList.remove('hidden');
                confirmPassword.classList.add('border-red-500');
            } else {
                if (errorMsg) errorMsg.classList.add('hidden');
                confirmPassword.classList.remove('border-red-500');
            }
        };
        
        confirmPassword.addEventListener('input', checkPasswordMatch);
        password.addEventListener('input', () => {
            if (confirmPassword.value) checkPasswordMatch();
        });
        
        // Password strength indicator
        password.addEventListener('input', function() {
            const value = this.value;
            if (value && (value.length < 6 || !/(?=.*[A-Za-z])(?=.*\d)/.test(value))) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
    }
    
    // Form submission
    form.addEventListener('submit', (e) => {
        if (!validateParentForm()) e.preventDefault();
    });
}

// ============================================
// Initialize All Validations
// ============================================

function initFormValidations() {
    console.log('Initializing form validations...');
    setupStudentFormValidation();
    setupParentFormValidation();
}

// Initialize on DOM load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFormValidations);
} else {
    initFormValidations();
}