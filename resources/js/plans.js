// ==================== ADD PLAN MODAL FUNCTIONS ====================
window.openAddPlanModal = function () {
    document.getElementById('addPlanModal').classList.remove('hidden');
    // Reset form
    document.getElementById('addPlanForm').reset();
    // Reset any additional fields
    if (document.getElementById('unlimited_flag')) {
        document.getElementById('unlimited_flag').checked = false;
    }
    if (document.getElementById('active_flag')) {
        document.getElementById('active_flag').checked = true;
    }
    // Clear any error messages
    document.querySelectorAll('.error-message').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
}

window.closeAddPlanModal = function() {
    document.getElementById('addPlanModal').classList.add('hidden');
}

// Close Add modal when clicking outside
if (document.getElementById('addPlanModal')) {
    document.getElementById('addPlanModal').addEventListener('click', function(e) {
        if (e.target === this) {
            window.closeAddPlanModal();
        }
    });
}

// ==================== EDIT PLAN MODAL FUNCTIONS ====================
window.openEditPlanModal = function(planId) {
    // Fetch plan data via AJAX
    fetch(`/plans/${planId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const plan = data.plan;
                
                // Set form action URL
                document.getElementById('editPlanForm').action = `/plans/${plan.id}`;
                
                // Populate form fields
                document.getElementById('edit_plan_name').value = plan.plan_name || '';
                document.getElementById('edit_description').value = plan.description || '';
                document.getElementById('edit_monthly_price').value = plan.monthly_price || '0.00';
                document.getElementById('edit_billing_cycle').value = plan.billing_cycle || 'monthly';
                document.getElementById('edit_status').value = plan.active_flag !== undefined ? plan.active_flag : 1;
                
                // Clear any previous error messages
                document.querySelectorAll('#editPlanForm .error-message').forEach(el => {
                    el.classList.add('hidden');
                    el.textContent = '';
                });
                
                // Show the modal
                document.getElementById('editPlanModal').classList.remove('hidden');
            } else {
                Swal.fire('Error', 'Failed to load plan data', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'An error occurred while loading plan data', 'error');
        });
}

window.closeEditPlanModal = function() {
    document.getElementById('editPlanModal').classList.add('hidden');
    // Reset form
    if (document.getElementById('editPlanForm')) {
        document.getElementById('editPlanForm').reset();
    }
}

// Close Edit modal when clicking outside
if (document.getElementById('editPlanModal')) {
    document.getElementById('editPlanModal').addEventListener('click', function(e) {
        if (e.target === this) {
            window.closeEditPlanModal();
        }
    });
}

// ==================== DELETE PLAN FUNCTION ====================
window.deletePlan = function(planId, planName) {
    Swal.fire({
        title: 'Delete Plan?',
        text: `Are you sure you want to delete "${planName}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform delete request
            fetch(`/plans/${planId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'Plan has been deleted.', 'success');
                    // Reload the page or remove the row from table
                    location.reload();
                } else {
                    Swal.fire('Error', data.message || 'Failed to delete plan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while deleting the plan', 'error');
            });
        }
    });
}

// ==================== EDIT FORM SUBMIT HANDLER (AJAX) ====================
if (document.getElementById('editPlanForm')) {
    document.getElementById('editPlanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success!', 'Plan has been updated successfully.', 'success');
                window.closeEditPlanModal();
                location.reload(); // Reload to show updated data
            } else {
                // Display validation errors
                if (data.errors) {
                    for (const [key, messages] of Object.entries(data.errors)) {
                        const errorElement = document.getElementById(`error_edit_${key}`);
                        if (errorElement) {
                            errorElement.textContent = messages[0];
                            errorElement.classList.remove('hidden');
                        }
                    }
                } else {
                    Swal.fire('Error', data.message || 'Failed to update plan', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'An error occurred while updating the plan', 'error');
        });
    });
}

console.log('Plans.js loaded successfully');