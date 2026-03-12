<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD - Devices</title>
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
                        <span class="text-[#1C1C1D] font-medium">Devices</span>
                    </div>
                    <h1 class="text-4xl font-bold text-[#1C1C1D]">Settings</h1>
                    
                    

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Devices Management</h6>
                                    </div>
                                </div>
                                <div class="p-8" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <!-- Registered Devices Table -->
                                    <div class="">
                                        <div class="grid grid-cols-2">
                                            <h2 class="text-xl font-semibold text-[#1C1C1D] mb-4">Registered Devices</h2>
                                            <!-- Add Device Button -->
                                            <div class="flex justify-end mb-4">
                                                <button class="px-4 py-2 bg-[#1C1C1D] text-white rounded-md hover:bg-[#2f2f2f] transition-colors">
                                                    <i class="fas fa-plus mr-2"></i>Register New Device
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device Name</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MAC Address</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recognition</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <!-- Main Gate Camera Row -->
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">Main Gate Camera</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Camera</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Gate</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">192.168.1.100</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">00:1B:44:11:3A:B7</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">85%</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold ">Online</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center gap-2">
                                                                <!-- Edit Button -->
                                                                <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Device">
                                                                    <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                                </button>
                                                                <!-- Delete Button -->
                                                                <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Delete Device">
                                                                    <i class="fa-solid fa-trash-can text-white text-sm"></i>
                                                                </button>
                                                                <!-- Test Connection Button -->
                                                                <button class="bg-blue-500 hover:bg-blue-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Test Connection">
                                                                    <i class="fa-solid fa-wifi text-white text-sm"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    
                                                    <!-- Studio Room 1 Camera Row -->
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">Studio Room 1 Camera</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Camera</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Studio Room 1</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">192.168.1.101</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">00:1B:44:11:3A:B8</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">85%</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold ">Offline</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center gap-2">
                                                                <!-- Edit Button -->
                                                                <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Device">
                                                                    <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                                </button>
                                                                <!-- Delete Button -->
                                                                <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Delete Device">
                                                                    <i class="fa-solid fa-trash-can text-white text-sm"></i>
                                                                </button>
                                                                <!-- Test Connection Button -->
                                                                <button class="bg-blue-500 hover:bg-blue-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Test Connection">
                                                                    <i class="fa-solid fa-wifi text-white text-sm"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Reception Biometric Row -->
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">Reception Biometric</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Biometric</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Reception</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">192.168.1.102</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">00:1B:44:11:3A:B9</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">92%</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold ">Online</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center gap-2">
                                                                <!-- Edit Button -->
                                                                <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Device">
                                                                    <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                                </button>
                                                                <!-- Delete Button -->
                                                                <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Delete Device">
                                                                    <i class="fa-solid fa-trash-can text-white text-sm"></i>
                                                                </button>
                                                                <!-- Test Connection Button -->
                                                                <button class="bg-blue-500 hover:bg-blue-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Test Connection">
                                                                    <i class="fa-solid fa-wifi text-white text-sm"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Studio Room 2 Camera Row -->
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">Studio Room 2 Camera</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Camera</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Studio Room 2</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">192.168.1.103</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">00:1B:44:11:3A:C0</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">85%</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold">Maintenance</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center gap-2">
                                                                <!-- Edit Button -->
                                                                <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Device">
                                                                    <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                                </button>
                                                                <!-- Delete Button -->
                                                                <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Delete Device">
                                                                    <i class="fa-solid fa-trash-can text-white text-sm"></i>
                                                                </button>
                                                                <!-- Test Connection Button -->
                                                                <button class="bg-blue-500 hover:bg-blue-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Test Connection">
                                                                    <i class="fa-solid fa-wifi text-white text-sm"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Office Biometric Row -->
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">Office Biometric</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Biometric</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">Office</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">192.168.1.104</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">00:1B:44:11:3A:C1</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-500">90%</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold ">Online</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center gap-2">
                                                                <!-- Edit Button -->
                                                                <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Device">
                                                                    <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                                </button>
                                                                <!-- Delete Button -->
                                                                <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Delete Device">
                                                                    <i class="fa-solid fa-trash-can text-white text-sm"></i>
                                                                </button>
                                                                <!-- Test Connection Button -->
                                                                <button class="bg-blue-500 hover:bg-blue-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Test Connection">
                                                                    <i class="fa-solid fa-wifi text-white text-sm"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Table Footer with Summary -->
                                        <div class="flex items-center justify-between mt-4">
                                            <div class="text-sm text-gray-500">
                                                Showing <span class="font-medium">5</span> of <span class="font-medium">5</span> devices
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

                                    {{-- dapat nasa modal 'to --}}
                                    {{-- <!-- Add/Edit Device Form -->
                                    <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3">Register New Device / Edit Device</h2>
                                    <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                    <!-- Device Info -->
                                    <h3 class="text-lg font-semibold text-[#1C1C1D] mb-3">Device Information</h3>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Device Name <span class="text-red-500">*</span></label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="e.g., Main Gate Camera">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Device Type</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                <option>Camera</option>
                                                <option>Biometric</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Location</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                <option>Gate</option>
                                                <option>Studio Room 1</option>
                                                <option>Studio Room 2</option>
                                                <option>Reception</option>
                                                <option>Office</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">IP Address <span class="text-red-500">*</span></label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="192.168.1.100">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">MAC Address</label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="00:1B:44:11:3A:B7">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Status</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                <option>Online</option>
                                                <option>Offline</option>
                                                <option>Maintenance</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-sm text-gray-600 mb-1">Assigned Class (Optional)</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                            <option>None</option>
                                            <option>Beginners Class</option>
                                            <option>Intermediate Class</option>
                                            <option>Advanced Class</option>
                                            <option>Sparring Class</option>
                                        </select>
                                    </div>

                                    <!-- Form Buttons -->
                                    <div class="flex items-center justify-end gap-3 pt-6">
                                        <button type="button" class="px-8 py-2 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                                        <button type="submit" class="px-8 py-2 text-sm text-white bg-[#1C1C1D] rounded-md hover:bg-[#2f2f2f] transition-colors">Save Device</button>
                                    </div> --}}
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
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Device</h3>
                <p class="text-sm text-gray-500 mb-4">Are you sure you want to delete this device? This action cannot be undone.</p>
                <div class="flex justify-center gap-3">
                    <button class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Connection Success Modal (Hidden by default) -->
    <div id="testModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm mx-auto">
            <div class="text-center">
                <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Connection Successful</h3>
                <p class="text-sm text-gray-500 mb-4">Device is responding properly. Connection test passed.</p>
                <button class="px-4 py-2 bg-[#1C1C1D] text-white rounded-md hover:bg-[#2f2f2f] transition-colors">OK</button>
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