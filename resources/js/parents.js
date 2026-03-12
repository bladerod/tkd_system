// parents.js

// Add Parent Modal Functions
window.openAddParentModal = function () {
    document.getElementById("addParentModal").classList.remove("hidden");
    document.body.style.overflow = 'hidden';
}

window.closeAddParentModal = function () {
    document.getElementById("addParentModal").classList.add("hidden");
    document.getElementById('addParentForm').reset();
    document.getElementById('userAccountFields').classList.add('hidden');
    document.getElementById('create_user_account').checked = false;
    document.body.style.overflow = 'auto';
}

// Edit Parent Modal Functions
window.openEditParentModal = function(parentId) {
    fetch(`/parent/${parentId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(parent => {
            // Populate form fields
            document.getElementById('edit_firstname').value = parent.fname || '';
            document.getElementById('edit_lastname').value = parent.lname || '';
            document.getElementById('edit_gender').value = parent.gender || '';
            document.getElementById('edit_address').value = parent.address || '';
            document.getElementById('edit_relationship_note').value = parent.relationship_note || '';
            document.getElementById('edit_phone').value = parent.emergency_contact || '';
            document.getElementById('edit_status').value = parent.status || 'active';
            
            // Set form action URL
            document.getElementById('editParentForm').action = `/parent/${parent.parent_id}`;
            
            // Check associated students
            document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            if (parent.students && parent.students.length > 0) {
                parent.students.forEach(student => {
                    const checkbox = document.getElementById(`edit_student${student.student_id}`);
                    if (checkbox) checkbox.checked = true;
                });
            }
            
            // Show modal
            document.getElementById('editParentModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load parent data. Please try again.',
                confirmButtonColor: '#1c1c1d'
            });
        });
}

window.closeEditParentModal = function() {
    document.getElementById('editParentModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editParentForm').reset();
}

// View Parent Modal Functions
window.openViewParentModal = function(parentId) {
    fetch(`/parent/${parentId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(parent => {
            document.getElementById("parentName").textContent = parent.full_name;
            
            // Store parent data for tabs
            window.currentParentData = parent;
            
            // Load default tab (Children)
            loadChildrenTab(parent);
            
            document.getElementById("viewParentModal").classList.remove("hidden");
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load parent data. Please try again.',
                confirmButtonColor: '#1c1c1d'
            });
        });
}

window.closeViewParentModal = function() {
    document.getElementById("viewParentModal").classList.add("hidden");
    document.body.style.overflow = 'auto';
    window.currentParentData = null;
}

// Tab switching function
window.switchTab = function(button, tabName, parentId) {
    // Update tab styles
    document.querySelectorAll(".tab").forEach(tab => {
        tab.classList.remove("bg-[#1C1C1D]", "text-white");
        tab.classList.add("bg-gray-100", "text-gray-700");
    });
    
    button.classList.remove("bg-gray-100", "text-gray-700");
    button.classList.add("bg-[#1C1C1D]", "text-white");

    // Load tab content
    const content = document.getElementById("tabContent");
    
    if (parentId && parentId > 0) {
        loadTabContent(tabName, parentId, content);
    } else {
        // Fallback to sample data if no parent ID
        loadSampleTabContent(tabName, content);
    }
}

function loadTabContent(tabName, parentId, contentElement) {
    // Show loading
    contentElement.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-gray-500"></i> Loading...</div>';
    
    // In a real application, you would fetch data from an API
    // For now, we'll use the data from window.currentParentData or fetch it
    if (window.currentParentData && window.currentParentData.parent_id == parentId) {
        const parent = window.currentParentData;
        let html = '';
        
        switch(tabName) {
            case 'children':
                html = `
                    <div class="space-y-3">
                        <p class="text-gray-600 font-medium">Children Information</p>
                        <div class="bg-white p-3 rounded border">
                            ${parent.students && parent.students.length > 0 ? 
                                parent.students.map(student => 
                                    `<p class="text-gray-600">• ${student.fname} ${student.lname} (Student ID: ${student.student_code || 'N/A'})</p>`
                                ).join('') : 
                                '<p class="text-gray-500">No children associated</p>'
                            }
                        </div>
                    </div>
                `;
                break;
            case 'billing':
                html = `
                    <div class="space-y-3">
                        <p class="text-gray-600 font-medium">Family Billing</p>
                        <div class="bg-white p-3 rounded border">
                            <p class="text-gray-600">Current Balance: ₱${parent.billing?.total_balance || '0.00'}</p>
                            <p class="text-gray-600">Due Date: ${parent.billing?.due_date || 'No due date'}</p>
                            <p class="text-gray-600">Last Payment: ${parent.billing?.last_payment || 'No payments yet'}</p>
                        </div>
                    </div>
                `;
                break;
            case 'payments':
                html = `
                    <div class="space-y-3">
                        <p class="text-gray-600 font-medium">Payment History</p>
                        <div class="bg-white p-3 rounded border">
                            ${parent.payments && parent.payments.length > 0 ?
                                parent.payments.map(payment => 
                                    `<p class="text-gray-600">${payment.date} - ₱${payment.amount} (${payment.method})</p>`
                                ).join('') :
                                '<p class="text-gray-500">No payment history</p>'
                            }
                        </div>
                    </div>
                `;
                break;
            case 'chat':
                html = `
                    <div class="space-y-3">
                        <p class="text-gray-600 font-medium">Chat History</p>
                        <div class="bg-white p-3 rounded border">
                            <p class="text-gray-500">Chat functionality coming soon...</p>
                        </div>
                    </div>
                `;
                break;
            case 'activity':
                html = `
                    <div class="space-y-3">
                        <p class="text-gray-600 font-medium">Activity Log</p>
                        <div class="bg-white p-3 rounded border max-h-60 overflow-y-auto">
                            ${parent.activity_log && parent.activity_log.length > 0 ?
                                parent.activity_log.map(log => 
                                    `<p class="text-gray-600 text-sm">${log.timestamp} - ${log.action}</p>`
                                ).join('') :
                                '<p class="text-gray-500">No activity logged</p>'
                            }
                        </div>
                    </div>
                `;
                break;
        }
        
        contentElement.innerHTML = html;
    } else {
        loadSampleTabContent(tabName, contentElement);
    }
}

function loadSampleTabContent(tabName, contentElement) {
    let html = '';
    
    switch(tabName) {
        case 'children':
            html = `
                <div class="space-y-3">
                    <p class="text-gray-600 font-medium">Children Information</p>
                    <div class="bg-white p-3 rounded border">
                        <p class="text-gray-600">• Maria Cruz Jr. - Age 8</p>
                        <p class="text-gray-600">• Ana Cruz - Age 6</p>
                    </div>
                </div>
            `;
            break;
        case 'billing':
            html = `
                <div class="space-y-3">
                    <p class="text-gray-600 font-medium">Family Billing</p>
                    <div class="bg-white p-3 rounded border">
                        <p class="text-gray-600">Current Balance: ₱1,200</p>
                        <p class="text-gray-600">Last Payment: ₱500 (Jan 15, 2024)</p>
                    </div>
                </div>
            `;
            break;
        case 'payments':
            html = `
                <div class="space-y-3">
                    <p class="text-gray-600 font-medium">Payment History</p>
                    <div class="bg-white p-3 rounded border">
                        <p class="text-gray-600">Jan 15, 2024 - ₱500</p>
                        <p class="text-gray-600">Dec 15, 2023 - ₱500</p>
                        <p class="text-gray-600">Nov 15, 2023 - ₱500</p>
                    </div>
                </div>
            `;
            break;
        default:
            html = `
                <div class="space-y-3">
                    <p class="text-gray-600 font-medium">${document.querySelector('.tab.bg-\\[\\#1C1C1D\\]')?.innerText || 'Information'}</p>
                    <p class="text-gray-600">Content for this tab will appear here.</p>
                </div>
            `;
    }
    
    contentElement.innerHTML = html;
}

function loadChildrenTab(parent) {
    const content = document.getElementById("tabContent");
    loadTabContent('children', parent.parent_id, content);
}

// Delete parent function
function handleDeleteClick(e) {
    e.preventDefault();
    const parentId = this.getAttribute('data-parent-id');
    const parentName = this.getAttribute('data-parent-name') || 'this parent';
    
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete ${parentName}. This action cannot be undone.`,
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
        form.action = `/parent/${parentId}`;
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

// Show/hide user account fields based on checkbox
document.addEventListener('DOMContentLoaded', function() {
    const createAccountCheckbox = document.getElementById('create_user_account');
    const userAccountFields = document.getElementById('userAccountFields');
    
    if (createAccountCheckbox && userAccountFields) {
        createAccountCheckbox.addEventListener('change', function() {
            if (this.checked) {
                userAccountFields.classList.remove('hidden');
                // Make fields required
                document.querySelectorAll('#userAccountFields input').forEach(input => {
                    if (input.name !== 'mobile_no') { // mobile_no might be optional
                        input.setAttribute('required', 'required');
                    }
                });
            } else {
                userAccountFields.classList.add('hidden');
                // Remove required attribute
                document.querySelectorAll('#userAccountFields input').forEach(input => {
                    input.removeAttribute('required');
                });
            }
        });
    }
    
    // Attach delete button listeners
    document.querySelectorAll('.delete-parent-btn').forEach(button => {
        button.removeEventListener('click', handleDeleteClick);
        button.addEventListener('click', handleDeleteClick);
    });
    
    // Initialize DataTable
    const parentTable = document.getElementById('parentTable');
    if (parentTable && parentTable.querySelector('tbody tr') && !parentTable.querySelector('td[colspan]')) {
        try {
            const dataTable = new simpleDatatables.DataTable(parentTable, {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50, 100],
                searchable: true,
                sortable: true,
                labels: {
                    placeholder: "Search...",
                    perPage: "Entries per page",
                    noRows: "No parents found",
                    info: "Showing {start} to {end} of {rows} entries",
                    noResults: "No results match your search query"
                }
            });
        } catch (error) {
            console.error('DataTable initialization failed:', error);
        }
    }
});

// Close modals when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById("addParentModal");
    const viewModal = document.getElementById("viewParentModal");
    const editModal = document.getElementById("editParentModal");
    
    if (event.target === addModal) {
        closeAddParentModal();
    }
    if (event.target === viewModal) {
        closeViewParentModal();
    }
    if (event.target === editModal) {
        closeEditParentModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const addModal = document.getElementById("addParentModal");
        const viewModal = document.getElementById("viewParentModal");
        const editModal = document.getElementById("editParentModal");
        
        if (addModal && !addModal.classList.contains('hidden')) {
            closeAddParentModal();
        }
        if (viewModal && !viewModal.classList.contains('hidden')) {
            closeViewParentModal();
        }
        if (editModal && !editModal.classList.contains('hidden')) {
            closeEditParentModal();
        }
    }
});