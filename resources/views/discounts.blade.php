<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD - Discounts</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])
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
                    
                    <!-- Add Discount Button -->
                    

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Discounts Management</h6>
                                    </div>
                                </div>
                                <div class="px-5 py-3 mt-3">
                                    <!-- Active Discounts Table -->
                                    <div class="mb-3">
                                        <div class="">
                                            <div class="flex justify-end mb-4">
                                                <button class="px-4 py-2 bg-[#1C1C1D] text-white rounded-md hover:bg-[#2f2f2f] transition-colors">
                                                    <i class="fas fa-plus mr-2"></i>Add New Discount
                                                </button>
                                            </div>
                                        </div>
                                        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                                            <table class="min-w-full divide-y divide-gray-200">
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
                                                    <!-- Sibling Discount Row -->
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">Sibling Discount</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Percentage</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900 font-medium">10%</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Monthly Fee</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Jan 1, 2025 - Dec 31, 2025</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold ">Active</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                                            <!-- Edit Button -->
                                                            <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Discount">
                                                                <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                            </button>
                                                            <!-- Delete Button -->
                                                            <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Delete Discount">
                                                                <i class="fa-solid fa-trash-can text-white text-sm"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    
                                                    <!-- Early Payment Discount Row -->
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">Early Payment Discount</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Fixed Amount</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900 font-medium">₱200</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Monthly Fee</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Feb 1, 2025 - Dec 31, 2025</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold ">Active</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                                            <!-- Edit Button -->
                                                            <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Discount">
                                                                <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                            </button>
                                                            <!-- Delete Button -->
                                                            <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Delete Discount">
                                                                <i class="fa-solid fa-trash-can text-white text-sm"></i>
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <!-- Family Package Row -->
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">Family Package</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Percentage</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900 font-medium">15%</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">All</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Mar 1, 2025 - Aug 31, 2025</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold ">Active</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                                            <!-- Edit Button -->
                                                            <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Discount">
                                                                <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                            </button>
                                                            <!-- Delete Button -->
                                                            <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Delete Discount">
                                                                <i class="fa-solid fa-trash-can text-white text-sm"></i>
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <!-- Referral Discount Row -->
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">Referral Discount</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Fixed Amount</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900 font-medium">₱500</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Enrollment</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Jan 1, 2025 - Dec 31, 2025</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold ">Active</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                                            <!-- Edit Button -->
                                                            <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Discount">
                                                                <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                            </button>
                                                            <!-- Delete Button -->
                                                            <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Delete Discount">
                                                                <i class="fa-solid fa-trash-can text-white text-sm"></i>
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <!-- Expired Discount Row Example -->
                                                    <tr class="hover:bg-gray-50 bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">Holiday Special 2024</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Percentage</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900 font-medium">20%</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">All</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Dec 1, 2024 - Dec 31, 2024</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Expired</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                                            <!-- Edit Button -->
                                                            <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Discount">
                                                                <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                            </button>
                                                            <!-- Delete Button -->
                                                            <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Delete Discount">
                                                                <i class="fa-solid fa-trash-can text-white text-sm"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Table Footer with Summary -->
                                        <div class="flex items-center justify-between mt-4">
                                            <div class="text-sm text-gray-500">
                                                Showing <span class="font-medium">5</span> of <span class="font-medium">5</span> discounts
                                            </div>
                                            <div class="flex gap-2">
                                                <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50" disabled>
                                                    Previous
                                                </button>
                                                <button class="px-3 py-1 bg-[#1C1C1D] text-white rounded-md text-sm">1</button>
                                                <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">2</button>
                                                <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">3</button>
                                                <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">
                                                    Next
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ilalagay 'to sa modal --}}
                                    <!-- Add/Edit Discount Form -->
                                    {{-- <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3"> Add New Discount / Edit Discount</h2>
                                    <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Discount Name <span class="text-red-500">*</span></label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="e.g., Sibling Discount">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Discount Type</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                <option>Percentage</option>
                                                <option>Fixed Amount</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Discount Value <span class="text-red-500">*</span></label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="10% or 200">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Applicable To</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                <option>Monthly Fee</option>
                                                <option>Enrollment</option>
                                                <option>All</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Eligible Role</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                <option>Student</option>
                                                <option>Parent</option>
                                                <option>All</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Max Usage Limit</label>
                                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="Unlimited">
                                        </div>
                                    </div>

                                    <!-- Rules -->
                                    <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3 mt-6"> Rules</h2>
                                    <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Valid From</label>
                                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" value="2025-01-01">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Valid Until</label>
                                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" value="2025-12-31">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Stackable?</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Active Status</label>
                                            <div class="flex items-center mt-2">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" checked>
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1C1C1D]"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div> 

                                    <!-- Buttons -->
                                    <div class="flex items-center justify-end gap-3 pt-6">
                                        <button type="button" class="px-8 py-2 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                                        <button type="submit" class="px-8 py-2 text-sm text-white bg-[#1C1C1D] rounded-md hover:bg-[#2f2f2f] transition-colors">Save Discount</button>
                                    </div>
                                    --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Delete Confirmation Modal (Hidden by default) -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm mx-auto">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Discount</h3>
                <p class="text-sm text-gray-500 mb-4">Are you sure you want to delete this discount? This action cannot be undone.</p>
                <div class="flex justify-center gap-3">
                    <button class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/dashboard.js'])

</body>
</html>