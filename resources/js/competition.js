// Modal functions
window.addCompetitionModal = function() {
    document.getElementById('addCompetitionModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

window.closeAddCompetitionModal = function() {
    document.getElementById('addCompetitionModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('addCompetitionForm').reset();
}

window.closeEditCompetitionModal = function() {
    document.getElementById('editCompetitionModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editCompetitionForm').reset();
}

// Edit competition function
window.openEditCompetitionModal = function(competitionId) {
    console.log('Opening edit modal for competition ID:', competitionId);
    
    fetch(`/competition/${competitionId}/json`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(competition => {
            console.log('Competition data received:', competition);
            
            // Populate form fields
            document.getElementById('edit_name').value = competition.name || '';
            document.getElementById('edit_location').value = competition.location || '';
            let formattedDate = '';
            if (competition.date) {
                formattedDate = competition.date.split('T')[0];
            }
            document.getElementById('edit_date').value = formattedDate;
            document.getElementById('edit_organizer').value = competition.organizer || '';
            document.getElementById('edit_level').value = competition.level || '';
            
            // Set form action URL
            const editForm = document.getElementById('editCompetitionForm');
            editForm.action = `/competition/${competition.id}`;
            
            console.log('Form action set to:', editForm.action);
            
            // Show modal
            document.getElementById('editCompetitionModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error loading competition:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load competition data. Please try again.',
                confirmButtonColor: '#3085d6'
            });
        });
};

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
                noRows: "No competitions found",
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

// Handle delete competition with SweetAlert2 and AJAX
function deleteCompetition(competitionId, competitionName) {
    Swal.fire({
        title: 'Delete Competition?',
        html: `Are you sure you want to delete <strong>${competitionName}</strong>?<br><br>This will mark the competition as inactive. You can restore it later if needed.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait while we delete the competition.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send delete request via AJAX
            fetch(`/competition/${competitionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        html: `Competition "<strong>${competitionName}</strong>" has been deleted.`,
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        // Reload the page to show updated list
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Failed to delete competition.',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while deleting the competition.',
                    confirmButtonColor: '#3085d6'
                });
            });
        }
    });
}

// Handle table clicks using event delegation
function handleTableClick(e) {
    // Handle view button clicks (eye icon)
    const viewButton = e.target.closest('.view-btn');
    if (viewButton) {
        e.preventDefault();
        const competitionId = viewButton.getAttribute('data-competition-id');
        console.log('View button clicked, competition ID:', competitionId);
        // Redirect to competition show page
        window.location.href = `/competition/${competitionId}`;
        return;
    }
    
    // Handle edit button clicks
    const editButton = e.target.closest('.edit-btn');
    if (editButton) {
        e.preventDefault();
        const competitionId = editButton.getAttribute('data-competition-id');
        console.log('Edit button clicked via delegation, competition ID:', competitionId);
        if (competitionId) {
            openEditCompetitionModal(competitionId);
        }
        return;
    }
    
    // Handle delete button clicks
    const deleteButton = e.target.closest('.delete-competition-btn');
    if (deleteButton) {
        e.preventDefault();
        const competitionId = deleteButton.getAttribute('data-competition-id');
        const competitionName = deleteButton.getAttribute('data-competition-name') || 'this competition';
        
        // Call the delete function with AJAX
        deleteCompetition(competitionId, competitionName);
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById('addCompetitionModal');
    const editModal = document.getElementById('editCompetitionModal');
    
    if (event.target === addModal) closeAddCompetitionModal();
    if (event.target === editModal) closeEditCompetitionModal();
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key !== 'Escape') return;
    
    const addModal = document.getElementById('addCompetitionModal');
    const editModal = document.getElementById('editCompetitionModal');
    
    if (addModal && !addModal.classList.contains('hidden')) closeAddCompetitionModal();
    if (editModal && !editModal.classList.contains('hidden')) closeEditCompetitionModal();
});

// Initialize form handling
function initializeFormHandling() {
    // Add form
    const addForm = document.getElementById('addCompetitionForm');
    if (addForm) {
        const newAddForm = addForm.cloneNode(true);
        addForm.parentNode.replaceChild(newAddForm, addForm);
        
        newAddForm.addEventListener('submit', function(e) {
            console.log('Add form submitted');
            const submitBtn = document.getElementById('submitAddBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Adding...';
                submitBtn.disabled = true;
            }
            return true;
        });
    }
    
    // Edit form
    const editForm = document.getElementById('editCompetitionForm');
    if (editForm) {
        const newEditForm = editForm.cloneNode(true);
        editForm.parentNode.replaceChild(newEditForm, editForm);
        
        newEditForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Edit form submitted');
            const submitBtn = document.getElementById('submitEditBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Updating...';
                submitBtn.disabled = true;
            }
            
            const formData = new FormData(this);
            const url = this.action;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: data.message || 'Competition updated successfully.',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Failed to update competition.',
                        confirmButtonColor: '#3085d6'
                    });
                    // Re-enable submit button on error
                    if (submitBtn) {
                        submitBtn.innerHTML = 'Update Competition';
                        submitBtn.disabled = false;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while updating the competition.',
                    confirmButtonColor: '#3085d6'
                });
                // Re-enable submit button on error
                if (submitBtn) {
                    submitBtn.innerHTML = 'Update Competition';
                    submitBtn.disabled = false;
                }
            });
        });
    }
}

// Attach button listeners using event delegation
function attachButtonListeners() {
    console.log('Attaching button listeners using event delegation');
    
    const table = document.getElementById('competitionTable');
    if (!table) return;
    
    const tbody = table.querySelector('tbody');
    if (!tbody) return;
    
    // Remove old listener
    tbody.removeEventListener('click', handleTableClick);
    
    // Add new listener using delegation
    tbody.addEventListener('click', handleTableClick);
}

// javascript for show.blade
// --- ENTRY MODAL FUNCTIONS (For show.blade.php) ---

window.openAddEntryModal = function() {
    document.getElementById('addEntryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

window.closeAddEntryModal = function() {
    document.getElementById('addEntryModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('addEntryForm').reset();
}

window.closeEditEntryModal = function() {
    document.getElementById('editEntryModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('editEntryForm').reset();
}

window.openEditEntryModal = function(competitionId, entryId) {
    console.log('Opening edit modal for entry ID:', entryId);
    
    // Fetch the entry data
    fetch(`/competition/${competitionId}/entries/${entryId}/json`)
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(entry => {
            // Populate form fields
            document.getElementById('edit_entry_student').value = entry.student_id;
            document.getElementById('edit_entry_instructor').value = entry.instructor_id;
            document.getElementById('edit_entry_category').value = entry.category || '';
            document.getElementById('edit_entry_division').value = entry.division || '';
            document.getElementById('edit_entry_result').value = entry.result || 'pending';
            document.getElementById('edit_entry_medal').value = entry.medal || 'none';
            document.getElementById('edit_entry_remarks').value = entry.remarks || '';
            
            // Set the form action URL dynamically
            const editForm = document.getElementById('editEntryForm');
            editForm.action = `/competition/${competitionId}/entries/${entryId}`;
            
            // Show modal
            document.getElementById('editEntryModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error loading entry:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to load entry data. Please try again.',
                confirmButtonColor: '#3085d6'
            });
        });
};

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing competitions...');
    
    // Initialize form handling
    initializeFormHandling();
    
    // Attach button listeners using event delegation
    attachButtonListeners();
    
    // Initialize DataTable if table exists
    const competitionTable = document.getElementById('competitionTable');
    if (competitionTable) {
        const tbody = competitionTable.querySelector('tbody');
        const rows = tbody ? tbody.querySelectorAll('tr') : [];
        const hasData = rows.length > 0 && !rows[0].querySelector('td[colspan]');
        
        if (hasData) {
            setTimeout(() => {
                initializeDataTable(competitionTable);
            }, 100);
        }
    }
});