<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrainNova - Discounts</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/discounts.css'])
    <!-- Add SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Add Simple-Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
</head>
<body class="bg-gray-50">
    <!-- navbar -->
    @include("includes.navbar")
    <!-- Sidebar -->
    @include('includes.sidebar')
    <!-- Main Content -->
    <div class="container-fluid m-0">
        <div class="row">
             <main class="ml-64 p-6"> 
                <div class="container-fluid">
                    <!-- Breadcrumb -->
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                        <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
                        <span>/</span>
                        <span class="text-[#1C1C1D] font-medium">Settings</span>
                        <span>/</span>
                        <span class="text-[#1C1C1D] font-medium">Discounts</span>
                    </div>
                    <h1 class="text-4xl font-bold text-[#1C1C1D]">Settings</h1>
                    
                    <!-- Success/Error Messages -->
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

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Discounts Management</h6>
                                    </div>
                                </div>
                                <div class="" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <div class="m-4 flex justify-end">
                                        <button onclick="openAddDiscountModal()" class="bg-[#1c1c1d] text-white px-4 py-2 rounded-lg hover:bg-[#2f2f2f] transition-colors flex items-center gap-2">
                                            <i class="fa-solid fa-plus"></i>
                                            Add Discount
                                        </button>
                                    </div>

                                    <!-- Discounts Table -->
                                    <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                                        <table id="discountTable" class="min-w-full divide-y divide-gray-200 p-3">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount Name</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicable To</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid Period</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse($discounts as $discount)
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $discount->name }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500">{{ ucfirst($discount->type) }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 font-medium">
                                                            @if($discount->type == 'percent')
                                                                {{ $discount->value }}%
                                                            @else
                                                                ₱{{ number_format($discount->value, 2) }}
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500">
                                                            @if($discount->applicable_to == 'monthly_fee')
                                                                Monthly Fee
                                                            @elseif($discount->applicable_to == 'enrollment')
                                                                Enrollment
                                                            @else
                                                                All
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500">
                                                            {{ \Carbon\Carbon::parse($discount->valid_from)->format('M d, Y') }} - 
                                                            {{ \Carbon\Carbon::parse($discount->valid_to)->format('M d, Y') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @php
                                                            $now = \Carbon\Carbon::now();
                                                            $validTo = \Carbon\Carbon::parse($discount->valid_to);
                                                            $isExpired = $now->gt($validTo);
                                                        @endphp
                                                        @if($discount->status == 1 && !$isExpired)
                                                            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                                                        @elseif($isExpired)
                                                            <span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Expired</span>
                                                        @else
                                                            <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center gap-3">
                                                            <button class="text-white bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg edit-discount-btn" 
                                                                    data-discount-id="{{ $discount->id }}">
                                                                <i class="fa-regular fa-pen-to-square"></i>
                                                            </button>
                                                            <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg delete-discount-btn" 
                                                                    data-discount-id="{{ $discount->id }}"
                                                                    data-discount-name="{{ $discount->name }}"> 
                                                                <i class="fa-regular fa-trash-can text-white"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                                        <div class="flex flex-col items-center">
                                                            <i class="fa-solid fa-tags text-4xl text-gray-300 mb-3"></i>
                                                            <p class="text-lg font-medium">No discounts found</p>
                                                            <p class="text-sm">Click the "Add Discount" button to create a new discount.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    {{-- Add Discount Modal --}}
    <div id="addDiscountModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white">
            <div class="flex items-center p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <i class="fa-solid fa-plus text-white text-xl pe-1"></i>
                <h3 class="text-2xl font-bold text-[#ffffff]">Add Discount</h3>
            </div>
            <div class="p-4">
                <form id="addDiscountForm" method="POST" action="{{ route('discounts.store') }}">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Name <span class="text-[#FF0000]">*</span></label>
                            <input type="text" name="name" id="add_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type <span class="text-[#FF0000]">*</span></label>
                            <select name="type" id="add_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="">Select Type</option>
                                <option value="percent">Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Value <span class="text-[#FF0000]">*</span></label>
                            <input type="number" name="value" id="add_value" required min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Applicable To <span class="text-[#FF0000]">*</span></label>
                            <select name="applicable_to" id="add_applicable_to" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="">Select Applicable Fee</option>
                                <option value="monthly_fee">Monthly Fee</option>
                                <option value="enrollment">Enrollment</option>
                                <option value="all">All</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Valid From <span class="text-[#FF0000]">*</span></label>
                            <input type="date" name="valid_from" id="add_valid_from" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Valid To <span class="text-[#FF0000]">*</span></label>
                            <input type="date" name="valid_to" id="add_valid_to" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>

                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="add_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-3 border-t">
                        <button type="button" onclick="closeAddDiscountModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                        <button type="submit" id="submitAddBtn" class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] transition-colors">Add Discount</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Discount Modal --}}
    <div id="editDiscountModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white">
            <div class="flex items-center p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <i class="fa-regular fa-pen-to-square text-white text-xl pe-1"></i>
                <h3 class="text-2xl font-bold text-[#ffffff]">Edit Discount</h3>
            </div>
            <div class="p-4">
                <form id="editDiscountForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Name <span class="text-[#FF0000]">*</span></label>
                            <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type <span class="text-[#FF0000]">*</span></label>
                            <select name="type" id="edit_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="percent">Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Value <span class="text-[#FF0000]">*</span></label>
                            <input type="number" name="value" id="edit_value" required min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Applicable To <span class="text-[#FF0000]">*</span></label>
                            <select name="applicable_to" id="edit_applicable_to" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="monthly_fee">Monthly Fee</option>
                                <option value="enrollment">Enrollment</option>
                                <option value="all">All</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Valid From <span class="text-[#FF0000]">*</span></label>
                            <input type="date" name="valid_from" id="edit_valid_from" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Valid To <span class="text-[#FF0000]">*</span></label>
                            <input type="date" name="valid_to" id="edit_valid_to" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>

                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="edit_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-3 border-t">
                        <button type="button" onclick="closeEditDiscountModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                        <button type="submit" id="submitEditBtn" class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] transition-colors">Update Discount</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/navbarDrop.js'])
    @vite(['resources/js/discount.js'])
    
    <script>
        // Debug: Check if discount.js is loaded
        console.log('Discounts page loaded');
        
        // Make sure functions are globally available
        window.openAddDiscountModal = function() {
            console.log('Opening add modal');
            const modal = document.getElementById('addDiscountModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
        
        window.closeAddDiscountModal = function() {
            console.log('Closing add modal');
            const modal = document.getElementById('addDiscountModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                const form = document.getElementById('addDiscountForm');
                if (form) form.reset();
            }
        }
        
        window.closeEditDiscountModal = function() {
            console.log('Closing edit modal');
            const modal = document.getElementById('editDiscountModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
        
        // Test delete button click
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-discount-btn');
            if (deleteBtn) {
                console.log('Delete button clicked');
                e.preventDefault();
                const discountId = deleteBtn.getAttribute('data-discount-id');
                const discountName = deleteBtn.getAttribute('data-discount-name');
                console.log('Delete discount ID:', discountId, 'Name:', discountName);
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete "${discountName}". This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/discounts/${discountId}`;
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                        form.innerHTML = `
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        });
        
        // Test edit button click
        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.edit-discount-btn');
            if (editBtn) {
                e.preventDefault();
                const discountId = editBtn.getAttribute('data-discount-id');
                console.log('Edit button clicked for ID:', discountId);
                
                fetch(`/discounts/${discountId}`)
                    .then(response => response.json())
                    .then(discount => {
                        console.log('Discount data:', discount);
                        document.getElementById('edit_name').value = discount.name;
                        document.getElementById('edit_type').value = discount.type;
                        document.getElementById('edit_value').value = discount.value;
                        document.getElementById('edit_applicable_to').value = discount.applicable_to;
                        document.getElementById('edit_valid_from').value = discount.valid_from;
                        document.getElementById('edit_valid_to').value = discount.valid_to;
                        document.getElementById('edit_status').value = discount.status;
                        
                        const editForm = document.getElementById('editDiscountForm');
                        editForm.action = `/discounts/${discount.id}`;
                        
                        document.getElementById('editDiscountModal').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Failed to load discount data', 'error');
                    });
            }
        });
    </script>
</body>
</html>