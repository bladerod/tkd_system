<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Branch</title>
    @vite(['resources/css/app.css', 'resources/css/instructor.css', 'resources/css/dashboard.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
    <!-- Add Simple-Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
    <!-- Add SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">

@include('includes.navbar')
@include('includes.sidebar')

<main x-data="branchModal('{{ route('branch.check') }}')" class="ml-64 pt-4 p-6 mt-2">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
        <span>/</span>
        <span class="text-[#1C1C1D] font-medium">Settings</span>
        <span>/</span>
        <span class="text-[#1C1C1D] font-medium">Branch Management</span>
    </div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-4xl font-bold text-[#1C1C1D]">Branch Management</h1>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- table --}}
    <div class="card z-index-2 bg-white rounded-xl shadow-sm">
        <div class="card-header pb-0 bg-transparent">
            <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Branch List</h6>
            </div>
        </div>
        
        <!-- FILTER SECTION -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex gap-3">
                    <!-- SEARCH INPUT (will be replaced by DataTable search) -->
                    <div class="relative">
                        <input type="text" id="branchSearch" placeholder="Search branches..." 
                            class="border border-gray-300 rounded-lg px-4 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                    </div>
                    
                    <!-- CITY FILTER -->
                    <select id="cityFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                        <option value="">All Cities</option>
                        @php
                            $uniqueCities = $branches->unique('city')->pluck('city')->filter();
                        @endphp
                        @foreach($uniqueCities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>
                    
                    <!-- STATUS FILTER -->
                    <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <!-- ADD BUTTON -->
                <button @click="openAdd()" class="bg-[#1C1C1D] text-white px-4 py-2 rounded-lg hover:bg-[#2f2f2f] transition-colors flex items-center gap-2">
                    <i class="fa fa-plus"></i> Add Branch
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
            <table id="branchTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Province</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($branches as $branch)
                    <tr class="hover:bg-gray-50 transition-colors duration-150" 
                        data-name="{{ $branch->name }}"
                        data-code="{{ $branch->code }}"
                        data-city="{{ $branch->city }}"
                        data-status="{{ $branch->status }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $branch->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">{{ $branch->code }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">{{ $branch->city }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">{{ $branch->province }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">{{ $branch->mobile }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">{{ $branch->email }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($branch->status == 'active')
                                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <!-- EDIT -->
                                <button 
                                    @click.prevent="openEdit({
                                        id: '{{ $branch->id }}',
                                        name: '{{ addslashes($branch->name) }}',
                                        code: '{{ $branch->code }}',
                                        address: '{{ addslashes($branch->address) }}',
                                        city: '{{ $branch->city }}',
                                        province: '{{ $branch->province }}',
                                        mobile: '{{ $branch->mobile }}',
                                        email: '{{ $branch->email }}',
                                        status: '{{ $branch->status }}'
                                    })"
                                    class="bg-green-500 hover:bg-green-600 p-2.5 rounded-lg text-white transition-colors">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>

                                <!-- DELETE -->
                                <button onclick="confirmDelete('{{ $branch->id }}', '{{ addslashes($branch->name) }}')" 
                                    class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg text-white transition-colors">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL -->
    <div x-show="showModal" x-transition class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
        <!-- BACKDROP -->
        <div class="absolute inset-0 bg-black/50" @click="closeModal()"></div>

        <!-- MODAL BOX -->
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-xl  z-10 max-h-[90vh] overflow-y-auto">
            <!-- HEADER -->
            <div class="flex justify-between items-center p-3 bg-[#1C1C1D]">
                <h2 class="text-2xl font-bold text-white" x-text="isEdit ? 'Edit Branch' : 'Add Branch'"></h2>
            </div>

            <div class="px-4 py-3">
                <!-- FORM -->
                <form :action="isEdit ? '/branch/update/' + form.id : '/branch/store'" method="POST" @submit.prevent="submitForm()">
                    @csrf
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc ml-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- NAME + CODE -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Branch Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="form.name"
                                @input.debounce.500ms="checkField('name')"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]"
                                :class="{'border-red-500': errors.name}">
                            <p x-show="errors.name" class="text-red-500 text-xs mt-1">
                                Branch name already exists. Please choose a different name.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Code <span class="text-red-500">*</span></label>
                            <input type="text" name="code" x-model="form.code"
                                @input.debounce.500ms="checkField('code')"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]"
                                :class="{'border-red-500': errors.code}">
                            <p x-show="errors.code" class="text-red-500 text-xs mt-1">
                                Code already used. Please choose a different code.
                            </p>
                        </div>
                    </div>

                    <!-- ADDRESS -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" name="address" x-model="form.address"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                    </div>

                    <!-- CITY + PROVINCE -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" name="city" x-model="form.city"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                            <input type="text" name="province" x-model="form.province"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                        </div>
                    </div>

                    <!-- MOBILE + EMAIL -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile</label>
                            <input type="text" name="mobile" x-model="form.mobile"
                                @input.debounce.500ms="checkField('mobile')"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]"
                                :class="{'border-red-500': errors.mobile}">
                            <p x-show="errors.mobile" class="text-red-500 text-xs mt-1">
                                Mobile already used. Please enter a different mobile number.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" x-model="form.email"
                                @input.debounce.500ms="checkField('email')"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]"
                                :class="{'border-red-500': errors.email}">
                            <p x-show="errors.email" class="text-red-500 text-xs mt-1">
                                Email already used. Please enter a different email address.
                            </p>
                        </div>
                    </div>

                    <!-- STATUS -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" x-model="form.status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- BUTTONS -->
                    <div class="flex justify-end gap-3 mt-6 pt-3 border-t">
                        <button type="button" @click="closeModal()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] transition-colors">
                            <span x-text="isEdit ? 'Update Branch' : 'Add Branch'"></span>
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script>
    let branchDataTable = null;

window.confirmDelete = function(branchId, branchName) {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete "${branchName}". This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/branch/delete/${branchId}`;
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function initializeBranchDataTable() {
    const branchTable = document.getElementById('branchTable');
    if (!branchTable) return;
    
    const tbody = branchTable.querySelector('tbody');
    const rows = tbody ? tbody.querySelectorAll('tr') : [];
    const hasData = rows.length > 0 && !rows[0].querySelector('td[colspan]');
    
    if (hasData && typeof simpleDatatables !== 'undefined') {
        try {
            branchDataTable = new simpleDatatables.DataTable(branchTable, {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50, 100],
                searchable: true,
                sortable: true,
                labels: {
                    placeholder: "Search...",
                    perPage: "Entries per page",
                    noRows: "No branches found",
                    info: "Showing {start} to {end} of {rows} entries",
                    noResults: "No results match your search query"
                }
            });
            
            console.log('Branch DataTable initialized successfully');
            
            // Add custom filters
            setupCustomFilters();
        } catch (error) {
            console.error('Branch DataTable initialization failed:', error);
        }
    }
}

function setupCustomFilters() {
    const cityFilter = document.getElementById('cityFilter');
    const statusFilter = document.getElementById('statusFilter');
    const customSearch = document.getElementById('branchSearch');
    
    if (cityFilter) {
        cityFilter.addEventListener('change', function() {
            if (branchDataTable) {
                const selectedCity = this.value;
                branchDataTable.search('');
                
                if (selectedCity) {
                    const rows = branchDataTable.data.data;
                    const filteredData = rows.filter(row => {
                        return row[2] === selectedCity; // City is the 3rd column (index 2)
                    });
                    branchDataTable.data.setData(filteredData);
                } else {
                    branchDataTable.data.setData(originalData);
                }
            }
        });
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            if (branchDataTable) {
                const selectedStatus = this.value;
                branchDataTable.search('');
                
                if (selectedStatus) {
                    const rows = branchDataTable.data.data;
                    const filteredData = rows.filter(row => {
                        const statusCell = row[6]; // Status is the 7th column (index 6)
                        return statusCell.toLowerCase().includes(selectedStatus);
                    });
                    branchDataTable.data.setData(filteredData);
                } else {
                    branchDataTable.data.setData(originalData);
                }
            }
        });
    }
    
    if (customSearch) {
        customSearch.addEventListener('input', function() {
            if (branchDataTable) {
                branchDataTable.search(this.value);
            }
        });
    }
}

// Store original data for filtering
let originalData = null;

window.branchModal = function(checkUrl) {
    return {
        showModal: false,
        isEdit: false,
        form: {},
        errors: {},

        openAdd() {
            this.isEdit = false;
            this.errors = {};
            this.form = {
                id: null,
                name: '',
                code: '',
                address: '',
                city: '',
                province: '',
                mobile: '',
                email: '',
                status: 'active'
            };
            this.showModal = true;
        },

        openEdit(data) {
            this.isEdit = true;
            this.errors = {};
            this.form = { ...data };
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        },
        
        submitForm() {
            // Check if there are any errors
            const hasErrors = Object.values(this.errors).some(error => error === true);
            if (hasErrors) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fix the duplicate field errors before submitting.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
            this.$el.submit();
        },

        async checkField(field) {
            if (!this.form[field]) return;

            // 2. Get the CSRF token from the DOM
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            try {
                // 3. Use the checkUrl variable instead of Blade syntax
                let res = await fetch(checkUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        field: field,
                        value: this.form[field],
                        id: this.form.id
                    })
                });

                let data = await res.json();
                this.errors[field] = data.exists;
            } catch (e) {
                console.error(e);
            }
        }
    }
}

// Initialize DataTable on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeBranchDataTable();
});
</script>
</body>
</html>