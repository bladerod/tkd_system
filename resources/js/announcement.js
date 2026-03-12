// Add Announcement Modal Functions
window.openAddAnnouncementModal = function () {
    document.getElementById("addAnnouncementModal").classList.remove("hidden");
    document.body.style.overflow = 'hidden';
    
    // Reset the form and hide all conditional fields
    const form = document.getElementById('addAnnouncementForm');
    if (form) form.reset();
    
    document.getElementById('class_field').classList.add('hidden');
    document.getElementById('belt_field').classList.add('hidden');
    document.getElementById('branch_field').classList.add('hidden');
}

window.closeAddAnnouncementModal = function () {
    document.getElementById("addAnnouncementModal").classList.add("hidden");
    document.getElementById('addAnnouncementForm').reset();
    document.body.style.overflow = 'auto';
    
    // Reset targeted by fields
    document.getElementById('class_field').classList.add('hidden');
    document.getElementById('belt_field').classList.add('hidden');
    document.getElementById('branch_field').classList.add('hidden');
}

// View Announcement Modal Functions
window.openViewAnnouncementModal = function(announcementId) {
    fetch(`/announcement/${announcementId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(announcement => {
            document.getElementById("view_announcement_title").textContent = announcement.title;
            document.getElementById("view_announcement_message").textContent = announcement.message;
            document.getElementById("view_announcement_target").textContent = announcement.target_type;
            document.getElementById("view_announcement_class").textContent = announcement.class_name || 'N/A';
            document.getElementById("view_announcement_belt").textContent = announcement.belt_level || 'N/A';
            document.getElementById("view_announcement_branch").textContent = announcement.branch_name || 'N/A';
            
            // Display channels
            const channelsContainer = document.getElementById("view_announcement_channels");
            channelsContainer.innerHTML = '';
            if (announcement.channel && announcement.channel.length > 0) {
                announcement.channel.forEach(channel => {
                    const span = document.createElement('span');
                    span.className = 'px-2 py-1 bg-gray-200 text-gray-700 rounded-full text-xs';
                    span.textContent = channel;
                    channelsContainer.appendChild(span);
                });
            } else {
                channelsContainer.innerHTML = '<span class="text-gray-500">None</span>';
            }
            
            document.getElementById("view_announcement_publish").textContent = announcement.publish_date;
            document.getElementById("view_announcement_expire").textContent = announcement.expire_date;
            document.getElementById("view_announcement_creator").textContent = announcement.creator_name;
            
            document.getElementById("viewAnnouncementModal").classList.remove("hidden");
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load announcement data. Please try again.',
                confirmButtonColor: '#1c1c1d'
            });
        });
}

window.closeViewAnnouncementModal = function () {
    document.getElementById("viewAnnouncementModal").classList.add("hidden");
    document.body.style.overflow = 'auto';
}

// Edit Announcement Modal Functions
window.openEditAnnouncementModal = function(announcementId) {
    fetch(`/announcement/${announcementId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(announcement => {
            // Populate form fields
            document.getElementById('edit_announcement_id').value = announcement.id;
            document.getElementById('edit_title').value = announcement.title || '';
            document.getElementById('edit_message').value = announcement.message || '';
            document.getElementById('edit_target_type').value = announcement.target_type || '';
            document.getElementById('edit_class_id').value = announcement.class_id || '';
            document.getElementById('edit_belt_level').value = announcement.belt_level || '';
            document.getElementById('edit_branch_id').value = announcement.branch_id || '';
            document.getElementById('edit_expire_date').value = announcement.expire_date;
            
            // Handle channel checkboxes
            document.querySelectorAll('input[name="edit_channel[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            if (announcement.channel && announcement.channel.length > 0) {
                announcement.channel.forEach(channel => {
                    const checkbox = document.querySelector(`input[name="edit_channel[]"][value="${channel}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }
            
            // Show/hide conditional fields based on target_type
            toggleEditTargetTypeFields(announcement.target_type);
            
            // Set form action URL
            document.getElementById('editAnnouncementForm').action = `/announcement/${announcement.id}`;
            
            // Show modal
            document.getElementById("editAnnouncementModal").classList.remove("hidden");
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load announcement data. Please try again.',
                confirmButtonColor: '#1c1c1d'
            });
        });
}

window.closeEditAnnouncementModal = function () {
    document.getElementById("editAnnouncementModal").classList.add("hidden");
    document.body.style.overflow = 'auto';
    document.getElementById('editAnnouncementForm').reset();
    
    // Reset conditional fields
    document.getElementById('edit_class_field').classList.add('hidden');
    document.getElementById('edit_belt_field').classList.add('hidden');
    document.getElementById('edit_branch_field').classList.add('hidden');
}

// Toggle target type fields for Add Modal
window.toggleTargetTypeFields = function() {
    const targetType = document.getElementById('target_type').value;
    const classField = document.getElementById('class_field');
    const beltField = document.getElementById('belt_field');
    const branchField = document.getElementById('branch_field');
    
    // Hide all fields first
    if (classField) classField.classList.add('hidden');
    if (beltField) beltField.classList.add('hidden');
    if (branchField) branchField.classList.add('hidden');
    
    // Show the relevant field
    if (targetType === 'class') {
        if (classField) classField.classList.remove('hidden');
    } else if (targetType === 'belt') {
        if (beltField) beltField.classList.remove('hidden');
    } else if (targetType === 'branch') {
        if (branchField) branchField.classList.remove('hidden');
    }
}

// Toggle target type fields for Edit Modal
window.toggleEditTargetTypeFields = function(selectedValue) {
    const targetType = selectedValue || document.getElementById('edit_target_type').value;
    const classField = document.getElementById('edit_class_field');
    const beltField = document.getElementById('edit_belt_field');
    const branchField = document.getElementById('edit_branch_field');
    
    // Hide all fields first
    if (classField) classField.classList.add('hidden');
    if (beltField) beltField.classList.add('hidden');
    if (branchField) branchField.classList.add('hidden');
    
    // Show the relevant field
    if (targetType === 'class') {
        if (classField) classField.classList.remove('hidden');
    } else if (targetType === 'belt') {
        if (beltField) beltField.classList.remove('hidden');
    } else if (targetType === 'branch') {
        if (branchField) branchField.classList.remove('hidden');
    }
}

// Apply Filters function
window.applyFilters = function() {
    const filterTarget = document.getElementById('filter_target').value.toLowerCase();
    const filterChannel = document.getElementById('filter_channel').value.toLowerCase();
    const filterDateFrom = document.getElementById('filter_date_from').value;
    const filterDateTo = document.getElementById('filter_date_to').value;
    const filterClass = document.getElementById('filter_class').value.toLowerCase();
    const filterBelt = document.getElementById('filter_belt').value.toLowerCase();
    const filterBranch = document.getElementById('filter_branch').value.toLowerCase();
    const filterTeam = document.getElementById('filter_team').value.toLowerCase();
    const filterStatus = document.getElementById('filter_status').value.toLowerCase();
    
    const rows = document.querySelectorAll('.announcement-row');
    let visibleCount = 0;
    const today = new Date().toISOString().split('T')[0];
    
    rows.forEach(row => {
        let showRow = true;
        
        // Filter by Target
        if (filterTarget && row.dataset.target && row.dataset.target.toLowerCase() !== filterTarget) {
            showRow = false;
        }
        
        // Filter by Channel
        if (showRow && filterChannel && row.dataset.channel) {
            const channels = row.dataset.channel.toLowerCase();
            if (!channels.includes(filterChannel)) {
                showRow = false;
            }
        }
        
        // Filter by Class
        if (showRow && filterClass && row.dataset.class && row.dataset.class.toLowerCase() !== filterClass) {
            showRow = false;
        }
        
        // Filter by Belt
        if (showRow && filterBelt && row.dataset.belt && row.dataset.belt.toLowerCase() !== filterBelt) {
            showRow = false;
        }
        
        // Filter by Branch
        if (showRow && filterBranch && row.dataset.branch && row.dataset.branch.toLowerCase() !== filterBranch) {
            showRow = false;
        }
        
        // Filter by Team
        if (showRow && filterTeam && row.dataset.team && !row.dataset.team.toLowerCase().includes(filterTeam)) {
            showRow = false;
        }
        
        // Filter by Status (Active/Expired)
        if (showRow && filterStatus && row.dataset.expire) {
            const expireDate = row.dataset.expire;
            const isExpired = expireDate < today;
            const rowStatus = isExpired ? 'expired' : 'active';
            
            if (rowStatus !== filterStatus) {
                showRow = false;
            }
        }
        
        // Filter by Expiration Date Range
        if (showRow && (filterDateFrom || filterDateTo) && row.dataset.expire) {
            const expireDate = row.dataset.expire;
            
            if (filterDateFrom && expireDate < filterDateFrom) {
                showRow = false;
            }
            
            if (filterDateTo && expireDate > filterDateTo) {
                showRow = false;
            }
        }
        
        // Show/Hide row
        if (showRow) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update result count
    const totalRows = rows.length;
    const resultCountElement = document.getElementById('filter_result_count');
    if (resultCountElement) {
        resultCountElement.textContent = `Showing ${visibleCount} of ${totalRows} entries`;
    }
}

window.resetFilters = function() {
    // Reset all filter inputs
    document.getElementById('filter_target').value = '';
    document.getElementById('filter_channel').value = '';
    document.getElementById('filter_date_from').value = '';
    document.getElementById('filter_date_to').value = '';
    document.getElementById('filter_class').value = '';
    document.getElementById('filter_belt').value = '';
    document.getElementById('filter_branch').value = '';
    document.getElementById('filter_team').value = '';
    document.getElementById('filter_status').value = '';
    
    // Show all rows
    const rows = document.querySelectorAll('.announcement-row');
    rows.forEach(row => {
        row.style.display = '';
    });
    
    // Update result count
    const totalRows = rows.length;
    const resultCountElement = document.getElementById('filter_result_count');
    if (resultCountElement) {
        resultCountElement.textContent = `Showing all ${totalRows} entries`;
    }
}

// Initialize event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize target type toggle for add modal
    const targetTypeSelect = document.getElementById('target_type');
    if (targetTypeSelect) {
        targetTypeSelect.addEventListener('change', toggleTargetTypeFields);
    }
    
    // Initialize target type toggle for edit modal
    const editTargetTypeSelect = document.getElementById('edit_target_type');
    if (editTargetTypeSelect) {
        editTargetTypeSelect.addEventListener('change', function() {
            toggleEditTargetTypeFields(this.value);
        });
    }
    
    // Initialize filter counts on page load
    const totalRows = document.querySelectorAll('.announcement-row').length;
    const resultCountElement = document.getElementById('filter_result_count');
    if (resultCountElement) {
        resultCountElement.textContent = `Showing all ${totalRows} entries`;
    }
    
    // Attach delete button listeners using event delegation
    const tableBody = document.getElementById('announcementTableBody');
    if (tableBody) {
        tableBody.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-announcement-btn');
            if (deleteBtn) {
                e.preventDefault();
                const announcementId = deleteBtn.getAttribute('data-announcement-id');
                const announcementTitle = deleteBtn.getAttribute('data-announcement-title') || 'this announcement';
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete "${announcementTitle}". This action cannot be undone.`,
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
                    form.action = `/announcement/${announcementId}`;
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
        });
    }
});

// Close modals when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById("addAnnouncementModal");
    const viewModal = document.getElementById("viewAnnouncementModal");
    const editModal = document.getElementById("editAnnouncementModal");
    
    if (event.target === addModal) {
        closeAddAnnouncementModal();
    }
    if (event.target === viewModal) {
        closeViewAnnouncementModal();
    }
    if (event.target === editModal) {
        closeEditAnnouncementModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const addModal = document.getElementById("addAnnouncementModal");
        const viewModal = document.getElementById("viewAnnouncementModal");
        const editModal = document.getElementById("editAnnouncementModal");
        
        if (addModal && !addModal.classList.contains('hidden')) {
            closeAddAnnouncementModal();
        }
        if (viewModal && !viewModal.classList.contains('hidden')) {
            closeViewAnnouncementModal();
        }
        if (editModal && !editModal.classList.contains('hidden')) {
            closeEditAnnouncementModal();
        }
    }
});