// let branchDataTable = null;

// window.confirmDelete = function(branchId, branchName) {
//     Swal.fire({
//         title: 'Are you sure?',
//         text: `You are about to delete "${branchName}". This action cannot be undone.`,
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#d33',
//         cancelButtonColor: '#3085d6',
//         confirmButtonText: 'Yes, delete it!',
//         cancelButtonText: 'Cancel'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             // Create and submit delete form
//             const form = document.createElement('form');
//             form.method = 'POST';
//             form.action = `/branch/delete/${branchId}`;
//             form.style.display = 'none';
            
//             const csrfToken = document.createElement('input');
//             csrfToken.type = 'hidden';
//             csrfToken.name = '_token';
//             csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            
//             const methodField = document.createElement('input');
//             methodField.type = 'hidden';
//             methodField.name = '_method';
//             methodField.value = 'DELETE';
            
//             form.appendChild(csrfToken);
//             form.appendChild(methodField);
//             document.body.appendChild(form);
//             form.submit();
//         }
//     });
// }

// function initializeBranchDataTable() {
//     const branchTable = document.getElementById('branchTable');
//     if (!branchTable) return;
    
//     const tbody = branchTable.querySelector('tbody');
//     const rows = tbody ? tbody.querySelectorAll('tr') : [];
//     const hasData = rows.length > 0 && !rows[0].querySelector('td[colspan]');
    
//     if (hasData && typeof simpleDatatables !== 'undefined') {
//         try {
//             branchDataTable = new simpleDatatables.DataTable(branchTable, {
//                 perPage: 10,
//                 perPageSelect: [5, 10, 25, 50, 100],
//                 searchable: true,
//                 sortable: true,
//                 labels: {
//                     placeholder: "Search...",
//                     perPage: "Entries per page",
//                     noRows: "No branches found",
//                     info: "Showing {start} to {end} of {rows} entries",
//                     noResults: "No results match your search query"
//                 }
//             });
            
//             console.log('Branch DataTable initialized successfully');
            
//             // Add custom filters
//             setupCustomFilters();
//         } catch (error) {
//             console.error('Branch DataTable initialization failed:', error);
//         }
//     }
// }

// function setupCustomFilters() {
//     const cityFilter = document.getElementById('cityFilter');
//     const statusFilter = document.getElementById('statusFilter');
//     const customSearch = document.getElementById('branchSearch');
    
//     if (cityFilter) {
//         cityFilter.addEventListener('change', function() {
//             if (branchDataTable) {
//                 const selectedCity = this.value;
//                 branchDataTable.search('');
                
//                 if (selectedCity) {
//                     const rows = branchDataTable.data.data;
//                     const filteredData = rows.filter(row => {
//                         return row[2] === selectedCity; // City is the 3rd column (index 2)
//                     });
//                     branchDataTable.data.setData(filteredData);
//                 } else {
//                     branchDataTable.data.setData(originalData);
//                 }
//             }
//         });
//     }
    
//     if (statusFilter) {
//         statusFilter.addEventListener('change', function() {
//             if (branchDataTable) {
//                 const selectedStatus = this.value;
//                 branchDataTable.search('');
                
//                 if (selectedStatus) {
//                     const rows = branchDataTable.data.data;
//                     const filteredData = rows.filter(row => {
//                         const statusCell = row[6]; // Status is the 7th column (index 6)
//                         return statusCell.toLowerCase().includes(selectedStatus);
//                     });
//                     branchDataTable.data.setData(filteredData);
//                 } else {
//                     branchDataTable.data.setData(originalData);
//                 }
//             }
//         });
//     }
    
//     if (customSearch) {
//         customSearch.addEventListener('input', function() {
//             if (branchDataTable) {
//                 branchDataTable.search(this.value);
//             }
//         });
//     }
// }

// // Store original data for filtering
// let originalData = null;

// window.branchModal = function(checkUrl) {
//     return {
//         showModal: false,
//         isEdit: false,
//         form: {},
//         errors: {},

//         openAdd() {
//             this.isEdit = false;
//             this.errors = {};
//             this.form = {
//                 id: null,
//                 name: '',
//                 code: '',
//                 address: '',
//                 city: '',
//                 province: '',
//                 mobile: '',
//                 email: '',
//                 status: 'active'
//             };
//             this.showModal = true;
//         },

//         openEdit(data) {
//             this.isEdit = true;
//             this.errors = {};
//             this.form = { ...data };
//             this.showModal = true;
//         },

//         closeModal() {
//             this.showModal = false;
//         },
        
//         submitForm() {
//             // Check if there are any errors
//             const hasErrors = Object.values(this.errors).some(error => error === true);
//             if (hasErrors) {
//                 Swal.fire({
//                     icon: 'error',
//                     title: 'Validation Error',
//                     text: 'Please fix the duplicate field errors before submitting.',
//                     confirmButtonColor: '#3085d6'
//                 });
//                 return;
//             }
//             this.$el.submit();
//         },

//         async checkField(field) {
//             if (!this.form[field]) return;

//             try {
//                 let res = await fetch("{{ route('branch.check') }}", {
//                     method: "POST",
//                     headers: {
//                         "Content-Type": "application/json",
//                         "X-CSRF-TOKEN": "{{ csrf_token() }}"
//                     },
//                     body: JSON.stringify({
//                         field: field,
//                         value: this.form[field],
//                         id: this.form.id
//                     })
//                 });

//                 let data = await res.json();
//                 this.errors[field] = data.exists;
//             } catch (e) {
//                 console.error(e);
//             }
//         }
//     }
// }

// // Initialize DataTable on page load
// document.addEventListener('DOMContentLoaded', function() {
//     initializeBranchDataTable();
// });