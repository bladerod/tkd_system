let dataTable = null;

// Initialize DataTable
window.initializeInvoiceDataTable = function() {
    const invoiceTable = document.getElementById('invoiceTable');
    if (!invoiceTable) return;
    
    const tbody = invoiceTable.querySelector('tbody');
    const rows = tbody ? tbody.querySelectorAll('tr') : [];
    const hasData = rows.length > 0;
    
    if (hasData && typeof simpleDatatables !== 'undefined') {
        try {
            dataTable = new simpleDatatables.DataTable(invoiceTable, {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50, 100],
                searchable: true,
                sortable: true,
                labels: {
                    placeholder: "Search...",
                    perPage: "Entries per page",
                    noRows: "No invoices found",
                    info: "Showing {start} to {end} of {rows} entries",
                    noResults: "No results match your search query"
                }
            });
            
            console.log('Invoice DataTable initialized successfully');
        } catch (error) {
            console.error('Invoice DataTable initialization failed:', error);
        }
    }
}

// Generate monthly invoices
window.generateMonthlyInvoices = async function() {
    Swal.fire({
        title: 'Generate Monthly Invoices?',
        text: 'This will generate invoices for all active students for the current month.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#63ad35',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, generate!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while invoices are being generated.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            try {
                const response = await fetch('/billing/generate-monthly', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire('Error!', error.message, 'error');
            }
        }
    });
}

// Mark overdue invoices
window.markOverdueInvoices = async function() {
    Swal.fire({
        title: 'Mark Overdue Invoices?',
        text: 'This will mark all past due invoices as overdue.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#63ad35',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, mark!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            try {
                const response = await fetch('/billing/mark-overdue', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire('Error!', error.message, 'error');
            }
        }
    });
}

// Open payment modal for a specific invoice
window.processPayment = function(invoiceId, invoiceNo, studentName, balanceDue) {
    // We no longer need to search the DOM for the row, we have the exact data!
    const numericBalance = parseFloat(balanceDue);

    document.getElementById('invoiceId').value = invoiceId;
    document.getElementById('invoiceNoDisplay').textContent = invoiceNo;
    document.getElementById('studentNameDisplay').textContent = studentName;
    
    // Format the remaining balance
    document.getElementById('totalDueDisplay').textContent = '₱' + numericBalance.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    // Set max limits to prevent overpaying
    document.getElementById('paymentAmount').value = '';
    document.getElementById('paymentAmount').max = numericBalance;
    document.getElementById('paymentAmount').placeholder = 'Max: ₱' + numericBalance.toLocaleString();
    
    // Show the modal
    document.getElementById('paymentModal').classList.remove('hidden');
}

// Open payment modal with invoice selection (for the Record Payment button)
window.openPaymentModal = function() {
    // Check if there are any invoices
    const invoiceRows = document.querySelectorAll('#invoiceTable tbody tr[data-invoice-id]');
    
    if (invoiceRows.length === 0) {
        Swal.fire({
            title: 'No Invoices',
            text: 'There are no invoices to process payment for.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    // Create a list of invoices to choose from
    let invoiceOptions = [];
    invoiceRows.forEach(row => {
        const invoiceId = row.getAttribute('data-invoice-id');
        const invoiceNo = row.getAttribute('data-invoice-no');
        const studentName = row.getAttribute('data-student-name');
        const totalDue = row.getAttribute('data-total-due');
        const status = row.getAttribute('data-status');
        
        // Only show pending and partial invoices for payment
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
    
    if (invoiceOptions.length === 0) {
        Swal.fire({
            title: 'No Pending Invoices',
            text: 'All invoices are already paid.',
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

window.closePaymentModal = function() {
    document.getElementById('paymentModal').classList.add('hidden');
    document.getElementById('paymentForm').reset();
}

// Generate receipt
window.generateReceipt = function(invoiceId) {
    window.open(`/billing/${invoiceId}/receipt`, '_blank');
}

// Send reminder
window.sendReminder = async function(invoiceId) {
    Swal.fire({
        title: 'Send Reminder?',
        text: 'This will send a payment reminder to the parent.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0096FF',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, send!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Sending...',
                text: 'Please wait.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            try {
                const response = await fetch(`/billing/${invoiceId}/reminder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success'
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire('Error!', error.message, 'error');
            }
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeInvoiceDataTable();
    
    // Setup payment form submit handler
    const paymentForm = document.getElementById('paymentForm');
    if (paymentForm) {
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
});

// Close modal on click outside
window.onclick = function(event) {
    const modal = document.getElementById('paymentModal');
    if (event.target === modal) {
        closePaymentModal();
    }
}