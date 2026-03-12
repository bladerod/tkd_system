<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD</title>
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
                <!-- Main Content -->
                <div class="container-fluid m-0">
                    <div class="row">
                        <main class=""> 
                            <div class="container-fluid">
                                <!-- Breadcrumb -->
                                <div class="flex  gap-2 text-sm text-gray-500 mb-6">
                                    <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
                                    <span>/</span>
                                    <span class="text-[#1C1C1D] font-medium">Billing</span>
                                </div>
                                <h1 class="text-3xl mb-4 text-4xl font-bold text-[#1C1C1D]">Invoices</h1>

                                <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                    <div class="card-header pb-0 bg-transparent">
                                        <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                            <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Invoices Log</h6>
                                        </div>
                                    </div>
                                    <div style="height: 37vh" class=" p-3" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                        <div class="justify-evenly float-end">
                                            <button class="bg-[#63ad35] p-3 rounded-xl hover:bg-[#71c93e] font-medium text-white">
                                                Record Payment
                                            </button>
                                        </div>
                                        <div class="mt-10 ms-4">

                                             <!-- Filter Controls in a Grid Layout -->
                                            <div class="grid grid-cols-12 gap-4 items-end">
                                                
                                                <!-- Table Section -->
                                                <div style="width: 1500%" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-5">
                                                    
                                                    <!-- Table -->
                                                    <div class="overflow-x-auto">
                                                        <table class="w-full text-sm">
                                                            <!-- Table Head -->
                                                            <thead>
                                                                <tr class="bg-gray-50 border-b border-gray-200">
                                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice#</th>
                                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                                                </tr>
                                                            </thead>
                                                            
                                                            <!-- Table Body -->
                                                            <tbody class="divide-y divide-gray-100">
                                                                <!-- Row 1 - IN -->
                                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">1044</td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <div class="flex items-center">
                                                                            <div class="ml-3">
                                                                                <p class="text-sm font-medium text-gray-800">Ana</p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Maria</td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱2,500</td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">September 30</td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="px-2 py-1 text-xs">Unpaid</span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap items-center">
                                                                        <button class="bg-[#72A0C1] p-2 rounded-xl" title="generate receipt">
                                                                           <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>

                                                                        </button>
                                                                        <button class="bg-[#0096FF] p-2 rounded-xl" title="send reminder">
                                                                             <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                
                                                                <!-- Row 2 - OUT -->
                                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">1045</td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <div class="flex items-center">
                                                                            <div class="ml-3">
                                                                                <p class="text-sm font-medium text-gray-800">Juan Dela Cruz</p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Carlos Reyes</td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱3,500</td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">October 5</td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span class="px-2 py-1 text-xs">Paid</span>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <button class="bg-[#72A0C1] p-2 rounded-xl" title="generate receipt">
                                                                           <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>

                                                                        </button>
                                                                        <button class="bg-[#0096FF] p-2 rounded-xl" title="send reminder">
                                                                             <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    
                                                    <!-- Table Footer with Pagination -->
                                                    <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                                                        <div class="text-xs text-gray-400">
                                                            Showing 1 to 2 of 2 entries
                                                        </div>
                                                        <div class="flex gap-2">
                                                            <button class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-500 hover:bg-gray-50 disabled:opacity-50" disabled>
                                                                Previous
                                                            </button>
                                                            <button class="px-3 py-1 text-xs bg-[#1C1C1D] text-white rounded hover:bg-[#2C2C2D]">
                                                                1
                                                            </button>
                                                            <button class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-500 hover:bg-gray-50">
                                                                2
                                                            </button>
                                                            <button class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-500 hover:bg-gray-50">
                                                                3
                                                            </button>
                                                            <button class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-500 hover:bg-gray-50">
                                                                Next
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card z-index-2 bg-white rounded-xl shadow-sm mt-5 p-5">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <h1 class="text-xl font-bold mb-5">Invoices View</h1>
                                            <div class="grid grid-cols-2 gap-4 justify-between border-1 border-dashed rounded-xl p-2 mb-2">
                                                <p>Changes</p>
                                                <p class="text-right">0.00</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 border-1 border-dashed rounded-xl p-2 mb-2">
                                                <p>Discounts (Family Auto)</p>
                                                <p class="text-right">0.00</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 border-1 border-dashed rounded-xl p-2 mb-2">
                                                <p>Payment</p>
                                                <p class="text-right">0.00</p>
                                            </div>
                                            <div class=" grid grid-cols-2 gap-4 border-1 border-dashed rounded-xl p-2 mb-2">
                                                <p>Changes</p>
                                                <p class="text-right">0.00</p>
                                            </div>
                                        </div>
                                        <div>
                                            <h1 class="text-xl font-bold mb-5">Plan Setup</h1>
                                            <div style="border-bottom: solid #000 1px;" class="grid grid-cols-2 gap-4 p-2 mb-2">
                                                <p>Changes</p>
                                                <p class="text-right">0.00</p>
                                            </div>
                                            <div style="border-bottom: solid #000 1px;" class="grid grid-cols-2 gap-4 p-2 mb-2">
                                                <p>Discounts (Family Auto)</p>
                                                <p class="text-right">0.00</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 p-2 mb-2">
                                                <p>Payment</p>
                                                <p class="text-right">0.00</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
            </main>
        </div>
    </div>
    {{-- start of modals --}}


    {{-- end of modals --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/dashboard.js'])
</body>
</html>