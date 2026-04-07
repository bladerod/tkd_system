document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation before form submission
    const permissionsForm = document.getElementById('permissionsForm');
    if (permissionsForm) {
        permissionsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Save Permissions?',
                text: 'Are you sure you want to save these permission settings?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1C1C1D',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, save changes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form programmatically
                    this.submit();
                }
            });
        });
    }
});

// Select all staff permissions
// window.selectAllStaff = function () {
//     const checkboxes = document.querySelectorAll('input[name^="permissions[staff]"]');
//     checkboxes.forEach(checkbox => {
//         checkbox.checked = true;
//     });
//     Swal.fire({
//         icon: 'success',
//         title: 'All Staff Permissions Selected',
//         text: 'All permissions for staff role have been checked.',
//         timer: 1500,
//         showConfirmButton: false
//     });
// }

// Deselect all staff permissions
window.deselectAllStaff = function () {
    const checkboxes = document.querySelectorAll('input[name^="permissions[staff]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    // Swal.fire({
    //     icon: 'info',
    //     title: 'All Staff Permissions Deselected',
    //     text: 'All permissions for staff role have been unchecked.',
    //     timer: 1500,
    //     showConfirmButton: false
    // });
}

// Set staff to read-only (only view permissions)
window.setStaffReadOnly = function () {
    const staffCheckboxes = document.querySelectorAll('input[name^="permissions[staff]"]');
    staffCheckboxes.forEach(checkbox => {
        const name = checkbox.getAttribute('name');
        if (name && name.includes('can_view')) {
            checkbox.checked = true;
        } else {
            checkbox.checked = false;
        }
    });
    // Swal.fire({
    //     icon: 'success',
    //     title: 'Staff Set to Read-Only',
    //     text: 'Staff can now only view content, no create/edit/delete actions.',
    //     timer: 2000,
    //     showConfirmButton: false
    // });
}

// Set staff to full access (not recommended)
window.setStaffFullAccess = function () {
    Swal.fire({
        title: 'Warning!',
        text: 'Setting staff to full access gives them the same permissions as admin. This is not recommended for security reasons.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, set full access',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const checkboxes = document.querySelectorAll('input[name^="permissions[staff]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            Swal.fire({
                icon: 'warning',
                title: 'Staff Set to Full Access',
                text: 'Staff now has full access to all modules. Review permissions carefully.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

// Reset permissions via AJAX
window.resetPermissions = function (resetUrl) {
    Swal.fire({
        title: 'Reset Staff Permissions?',
        text: 'This will reset all staff permissions to default values.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, reset it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Resetting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Use the resetUrl parameter
            fetch(resetUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Reset Successful!',
                        text: 'Staff permissions have been reset to default values.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Reset Failed',
                        text: data.message || 'An error occurred while resetting permissions.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Reset Failed',
                    text: 'An error occurred. Please try again.'
                });
            });
        }
    });
}