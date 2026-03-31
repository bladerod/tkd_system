// Make instructorModal function globally available
window.instructorModal = function() {
     return {
        showModal: false,
        isEdit: false,
        form: {
            branch_id: '',
            fname: '',
            lname: '',
            email: '',
            username: '',
            rank_belt: '',
            certification_level: '',
            contact: '',
            status: '',
            specialization: '',
            bio: ''
        },

        openEdit(data) {
            this.isEdit = true;

            // 🔥 important: assign properly
            this.form = {
                ...this.form,
                ...data
            };

            this.showModal = true;
        },

        openAdd() {
            this.isEdit = false;
            this.form = {
                branch_id: '',
                fname: '',
                lname: '',
                email: '',
                username: '',
                rank_belt: '',
                certification_level: '',
                contact: '',
                status: '',
                specialization: '',
                bio: ''
            };
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        }
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Instructor JS loaded');

    // Initialize DataTable
    initializeDataTable();

    // Initialize delete buttons
    initializeDeleteButtons();
});

function initializeDataTable() {
    const instructorTable = document.getElementById('instructorTable');
    if (!instructorTable) return;

    const tbody = instructorTable.querySelector('tbody');
    const rows = tbody ? tbody.querySelectorAll('tr') : [];
    const hasData = rows.length > 0 && !rows[0].querySelector('td[colspan]');

    if (hasData && typeof simpleDatatables !== 'undefined') {
        try {
            const dataTable = new simpleDatatables.DataTable(instructorTable, {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50, 100],
                searchable: true,
                sortable: true,
                labels: {
                    placeholder: "Search...",
                    perPage: "Entries per page",
                    noRows: "No instructors found",
                    info: "Showing {start} to {end} of {rows} entries",
                    noResults: "No results match your search query"
                }
            });

            // Re-attach delete listeners after DataTable redraws
            if (dataTable && typeof dataTable.on === 'function') {
                dataTable.on('datatable.draw', function() {
                    initializeDeleteButtons();
                });
            }

            console.log('DataTable initialized successfully');
        } catch (error) {
            console.error('DataTable initialization failed:', error);
        }
    }
}

function initializeDeleteButtons() {
    // Remove any existing event listeners by cloning and replacing buttons
    document.querySelectorAll('.delete-instructor-btn').forEach(button => {
        // Remove old event listeners by cloning
        const newButton = button.cloneNode(true);
        button.parentNode.replaceChild(newButton, button);

        // Add new event listener
        newButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const instructorId = this.getAttribute('data-instructor-id');
            const instructorName = this.getAttribute('data-instructor-name') || 'this instructor';
            const form = this.closest('form');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete ${instructorName}. This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                // Fallback if SweetAlert is not loaded
                if (confirm(`Are you sure you want to delete ${instructorName}?`)) {
                    form.submit();
                }
            }
        });
    });
}

// Handle modal close on escape key and outside click
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modalElement = document.querySelector('[x-data="instructorModal()"]');
        if (modalElement && modalElement.__x) {
            modalElement.__x.$data.closeModal();
        }
    }
});
