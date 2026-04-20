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
    
    const chartData = window.dynamicRevenueData;
    
    const weeklyData = {
        labels: chartData.daily.labels,
        values: chartData.daily.values
    };
    
    const monthlyData = {
        labels: chartData.monthly.labels,
        values: chartData.monthly.values
    };
    
    // Helper function to format currency
    const formatCurrency = (value) => {
        if (value >= 1000) {
            return '₱' + (value / 1000).toFixed(1) + 'K'; // E.g., ₱12.5K
        }
        return '₱' + value;
    };
    
    let chart = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: weeklyData.labels,
            datasets: [{
                label: 'Revenue',
                data: weeklyData.values,
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
                        label: (context) => '₱' + context.raw.toLocaleString()
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
                    formatter: (value) => formatCurrency(value) 
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: {
                        callback: (value) => formatCurrency(value),
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
    
    window.updateRevenueChartPeriod = function(period) {
        if (period === 'weekly') {
            chart.data.labels = weeklyData.labels;
            chart.data.datasets[0].data = weeklyData.values;
        } else {
            chart.data.labels = monthlyData.labels;
            chart.data.datasets[0].data = monthlyData.values;
        }
        chart.update();
    };
});

// Enrollees Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chart-monthly-enrollees');
    if (!ctx) return;
    
    const chartData = window.dynamicEnrolleesData;
    
    const monthlyData = {
        labels: chartData.daily.labels, // Last 7 days
        values: chartData.daily.values
    };
    
    const annualData = {
        labels: chartData.monthly.labels, // 12 Months
        values: chartData.monthly.values
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
                        text: 'Daily Enrollees', // Default title
                        font: { size: 10, weight: '500' },
                        color: '#6B7280'
                    },
                    ticks: {
                        callback: (value) => value,
                        font: { size: 10 },
                        stepSize: 1 // Forces whole numbers (you can't have 1.5 students!)
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






// ============================================
// Payment Modal Functions (for Record Payment button)
// ============================================

// Store invoice data globally for the dashboard
window.dashboardInvoices = null;

// Fetch invoices from server for the Record Payment button
async function fetchInvoicesForPayment() {
    try {
        // Get CSRF token safely
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.warn('CSRF token meta tag not found');
            return [];
        }
        
        // Use the correct route WITHOUT /billing prefix
        const response = await fetch('/invoices-json', {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content
            }
        });
        
        // Check if response is OK before trying to parse JSON
        if (!response.ok) {
            console.error('HTTP error:', response.status);
            return [];
        }
        
        const data = await response.json();
        return data.success ? data.invoices : [];
    } catch (error) {
        console.error('Error fetching invoices:', error);
        return [];
    }
}

// Open payment modal with invoice selection (for the Record Payment button on dashboard)
window.openPaymentModal = async function() {
    let invoiceOptions = [];
    
    // Try to get invoice data from the table if it exists (billing page style)
    const invoiceRows = document.querySelectorAll('#invoiceTable tbody tr[data-invoice-id]');
    
    if (invoiceRows.length > 0) {
        // Use data from table if available
        invoiceRows.forEach(row => {
            const invoiceId = row.getAttribute('data-invoice-id');
            const invoiceNo = row.getAttribute('data-invoice-no');
            const studentName = row.getAttribute('data-student-name');
            const totalDue = row.getAttribute('data-total-due');
            const status = row.getAttribute('data-status');
            
            if (status !== 'paid') {
                invoiceOptions.push({
                    id: invoiceId,
                    text: `${invoiceNo} - ${studentName} - ₱${parseFloat(totalDue).toLocaleString()} (${status})`,
                    invoiceNo: invoiceNo,
                    studentName: studentName,
                    totalDue: totalDue
                });
            }
        });
    } else {
        // Fallback: Fetch from API
        const invoices = await fetchInvoicesForPayment();
        invoiceOptions = invoices
            .filter(inv => inv.status !== 'paid')
            .map(inv => ({
                id: inv.id,
                text: `${inv.invoice_no} - ${inv.student_name} - ₱${parseFloat(inv.total_due).toLocaleString()} (${inv.status})`,
                invoiceNo: inv.invoice_no,
                studentName: inv.student_name,
                totalDue: inv.total_due
            }));
    }
    
    if (invoiceOptions.length === 0) {
        Swal.fire({
            title: 'No Pending Invoices',
            text: 'All invoices are already paid or no invoices exist.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    // Create HTML for invoice selection
    let optionsHtml = '';
    invoiceOptions.forEach(option => {
        optionsHtml += `<option value="${option.id}" data-invoice-no="${option.invoiceNo}" data-student-name="${option.studentName}" data-total-due="${option.totalDue}">${option.text}</option>`;
    });
    
    Swal.fire({
        title: 'Select Invoice',
        html: `
            <div class="text-left">
                <label class="block text-sm font-medium text-gray-700 mb-2">Choose Invoice:</label>
                <select id="invoiceSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]" style="width: 100%;">
                    ${optionsHtml}
                </select>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Proceed to Payment',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const select = document.getElementById('invoiceSelect');
            const selectedOption = select.options[select.selectedIndex];
            return {
                invoiceId: select.value,
                invoiceNo: selectedOption.getAttribute('data-invoice-no'),
                studentName: selectedOption.getAttribute('data-student-name'),
                totalDue: selectedOption.getAttribute('data-total-due')
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { invoiceId, invoiceNo, studentName, totalDue } = result.value;
            
            document.getElementById('invoiceId').value = invoiceId;
            document.getElementById('invoiceNoDisplay').textContent = invoiceNo;
            document.getElementById('studentNameDisplay').textContent = studentName;
            document.getElementById('totalDueDisplay').textContent = '₱' + parseFloat(totalDue).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('paymentAmount').value = '';
            document.getElementById('paymentAmount').max = parseFloat(totalDue);
            document.getElementById('paymentAmount').placeholder = 'Max: ₱' + parseFloat(totalDue).toLocaleString();
            
            document.getElementById('paymentModal').classList.remove('hidden');
        }
    });
}

// Close payment modal
window.closePaymentModal = function() {
    const modal = document.getElementById('paymentModal');
    if (modal) {
        modal.classList.add('hidden');
    }
    const form = document.getElementById('paymentForm');
    if (form) {
        form.reset();
    }
}

// Setup payment form submit handler
function setupPaymentForm() {
    const paymentForm = document.getElementById('paymentForm');
    if (!paymentForm) return;
    
    // Remove any existing event listeners to avoid duplicates
    const newForm = paymentForm.cloneNode(true);
    paymentForm.parentNode.replaceChild(newForm, paymentForm);
    
    newForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const invoiceId = document.getElementById('invoiceId').value;
        const amount = document.getElementById('paymentAmount').value;
        const paymentMethod = document.getElementById('paymentMethod').value;
        const transactionReference = document.getElementById('transactionReference').value;
        
        if (!invoiceId) {
            Swal.fire('Error!', 'No invoice selected.', 'error');
            return;
        }
        
        if (!amount || parseFloat(amount) <= 0) {
            Swal.fire('Error!', 'Please enter a valid amount.', 'error');
            return;
        }
        
        const maxAmount = parseFloat(document.getElementById('paymentAmount').max);
        if (parseFloat(amount) > maxAmount) {
            Swal.fire('Error!', `Payment amount cannot exceed ₱${maxAmount.toLocaleString()}`, 'error');
            return;
        }
        
        Swal.fire({
            title: 'Processing Payment...',
            text: 'Please wait.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        try {
            const response = await fetch(`/billing/${invoiceId}/payment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    amount: amount,
                    payment_method: paymentMethod,
                    transaction_reference: transactionReference
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success'
                }).then(() => {
                    closePaymentModal();
                    location.reload();
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire('Error!', error.message, 'error');
        }
    });
}

// Close modal on click outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('paymentModal');
    if (event.target === modal) {
        closePaymentModal();
    }
});

// Initialize payment form when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    setupPaymentForm();
    
    // Attach click handler to the Record Payment button if it exists
    const recordPaymentBtn = document.querySelector('button i.fa-solid.fa-file')?.closest('button');
    if (recordPaymentBtn && !recordPaymentBtn.hasAttribute('data-payment-handler')) {
        recordPaymentBtn.setAttribute('data-payment-handler', 'true');
        recordPaymentBtn.onclick = function(e) {
            e.preventDefault();
            openPaymentModal();
        };
    }
});