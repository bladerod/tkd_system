<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD - Roles & Permissions</title>
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
                        <span class="text-[#1C1C1D] font-medium">Roles & Permissions</span>
                    </div>
                    <h1 class="text-4xl font-bold text-[#1C1C1D]">Settings</h1>
                    
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Roles & Permissions</h6>
                                    </div>
                                </div>
                                <div class="p-8" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <!-- Role Management -->
                                    <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3"> Role Management</h2>
                                    <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                    <div class="grid grid-cols-3 gap-4 mb-6">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Role Name <span class="text-red-500">*</span></label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="e.g., Admin">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">&nbsp;</label>
                                            <button class="w-full px-4 py-2 bg-[#1C1C1D] text-white rounded-md hover:bg-[#2f2f2f] transition-colors">
                                                <i class="fas fa-plus mr-2"></i>Add Role
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Existing Roles -->
                                    <div class="flex flex-wrap gap-2 mb-6">
                                        <span class="px-3 py-1 bg-[#1C1C1D] text-white rounded-full text-sm">Admin</span>
                                        <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">Instructor</span>
                                        <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">Cashier</span>
                                        <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">Parent</span>
                                        <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">Viewer</span>
                                    </div>

                                    <!-- Permissions Matrix -->
                                    <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3"> Permissions Matrix</h2>
                                    <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full bg-white border border-gray-200">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Module</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Admin</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Instructor</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Cashier</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Parent</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Viewer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Dashboard -->
                                                <tr class="border-b border-gray-200">
                                                    <td class="px-4 py-3 font-medium">Dashboard</td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1" checked> View</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Edit</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1" checked> View</label>
                                                            <label><input type="checkbox" class="mr-1"> Edit</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1" checked> View</label>
                                                            <label><input type="checkbox" class="mr-1"> Edit</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1" checked> View</label>
                                                            <label><input type="checkbox" class="mr-1"> Edit</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1" checked> View</label>
                                                            <label><input type="checkbox" class="mr-1"> Edit</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <!-- Students -->
                                                <tr class="border-b border-gray-200">
                                                    <td class="px-4 py-3 font-medium">Students</td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1" checked> Add</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Edit</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Delete</label>
                                                            <label><input type="checkbox" class="mr-1" checked> View</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Add</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Edit</label>
                                                            <label><input type="checkbox" class="mr-1"> Delete</label>
                                                            <label><input type="checkbox" class="mr-1" checked> View</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Add</label>
                                                            <label><input type="checkbox" class="mr-1"> Edit</label>
                                                            <label><input type="checkbox" class="mr-1"> Delete</label>
                                                            <label><input type="checkbox" class="mr-1" checked> View</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Add</label>
                                                            <label><input type="checkbox" class="mr-1"> Edit</label>
                                                            <label><input type="checkbox" class="mr-1"> Delete</label>
                                                            <label><input type="checkbox" class="mr-1" checked> View</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Add</label>
                                                            <label><input type="checkbox" class="mr-1"> Edit</label>
                                                            <label><input type="checkbox" class="mr-1"> Delete</label>
                                                            <label><input type="checkbox" class="mr-1" checked> View</label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Attendance -->
                                                <tr class="border-b border-gray-200">
                                                    <td class="px-4 py-3 font-medium">Attendance</td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1" checked> Manual Override</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Add Manual Log</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Export CSV</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1" checked> Manual Override</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Add Manual Log</label>
                                                            <label><input type="checkbox" class="mr-1"> Export CSV</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Manual Override</label>
                                                            <label><input type="checkbox" class="mr-1"> Add Manual Log</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Export CSV</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Manual Override</label>
                                                            <label><input type="checkbox" class="mr-1"> Add Manual Log</label>
                                                            <label><input type="checkbox" class="mr-1"> Export CSV</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Manual Override</label>
                                                            <label><input type="checkbox" class="mr-1"> Add Manual Log</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Export CSV</label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Billing -->
                                                <tr class="border-b border-gray-200">
                                                    <td class="px-4 py-3 font-medium">Billing</td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1" checked> Generate Invoice</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Record Payment</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Apply Discount</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Generate Invoice</label>
                                                            <label><input type="checkbox" class="mr-1"> Record Payment</label>
                                                            <label><input type="checkbox" class="mr-1"> Apply Discount</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Generate Invoice</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Record Payment</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Apply Discount</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Generate Invoice</label>
                                                            <label><input type="checkbox" class="mr-1"> Record Payment</label>
                                                            <label><input type="checkbox" class="mr-1"> Apply Discount</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Generate Invoice</label>
                                                            <label><input type="checkbox" class="mr-1"> Record Payment</label>
                                                            <label><input type="checkbox" class="mr-1"> Apply Discount</label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Certificates -->
                                                <tr class="border-b border-gray-200">
                                                    <td class="px-4 py-3 font-medium">Certificates</td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1" checked> Generate</label>
                                                            <label><input type="checkbox" class="mr-1" checked> Edit Template</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1" checked> Generate</label>
                                                            <label><input type="checkbox" class="mr-1"> Edit Template</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Generate</label>
                                                            <label><input type="checkbox" class="mr-1"> Edit Template</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Generate</label>
                                                            <label><input type="checkbox" class="mr-1"> Edit Template</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex flex-col gap-1">
                                                            <label><input type="checkbox" class="mr-1"> Generate</label>
                                                            <label><input type="checkbox" class="mr-1"> Edit Template</label>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Settings -->
                                                <tr class="border-b border-gray-200">
                                                    <td class="px-4 py-3 font-medium">Settings</td>
                                                    <td class="px-4 py-3">
                                                        <label><input type="checkbox" class="mr-1" checked> Full Access</label>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <label><input type="checkbox" class="mr-1"> Full Access</label>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <label><input type="checkbox" class="mr-1"> Full Access</label>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <label><input type="checkbox" class="mr-1"> Full Access</label>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <label><input type="checkbox" class="mr-1"> Full Access</label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex items-center justify-end gap-3 pt-6">
                                        <button type="button" class="px-8 py-2 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                                        <button type="submit" class="px-8 py-2 text-sm text-white bg-[#1C1C1D] rounded-md hover:bg-[#2f2f2f] transition-colors">Save Permissions</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/dashboard.js'])
</body>
</html>