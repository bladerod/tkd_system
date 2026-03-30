<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrainNova</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/billing.css'])
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

                                <div class="bg-white rounded-xl shadow-sm">
                                    <div class=" pb-0 bg-transparent">
                                        <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                            <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Invoices Log</h6>
                                        </div>
                                    </div>
                                    <div class="flex justify-end p-3">
                                        <button class="bg-[#63ad35] p-3 rounded-xl hover:bg-[#71c93e] font-medium text-white">
                                            Record Payment
                                        </button>
                                    </div>
                                    <!-- Table Section -->
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-5">
                                        <!-- Table -->
                                        <div class="overflow-x-auto">
                                            <table id="invoiceTable" class="min-w-full divide-y divide-gray-200">
                                                <!-- Table Head -->
                                                <thead class="bg-gray-50">
                                                    <tr>
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
                                                <tbody class="bg-white divide-y divide-gray-200">
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
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/navbarDrop.js'])
    
    <script>
    // Initialize DataTable
    function initializeInvoiceDataTable() {
        const invoiceTable = document.getElementById('invoiceTable');
        if (!invoiceTable) return;
        
        const tbody = invoiceTable.querySelector('tbody');
        const rows = tbody ? tbody.querySelectorAll('tr') : [];
        const hasData = rows.length > 0;
        
        if (hasData && typeof simpleDatatables !== 'undefined') {
            try {
                const dataTable = new simpleDatatables.DataTable(invoiceTable, {
                    perPage: 10,
                    perPageSelect: [5, 10, 25, 50, 100],
                    searchable: true,
                    sortable: true,
                    labels: {
                        placeholder: "Search...",
                        perPage: "Entries per page",
                        noRows: "No invoices found",
                        info: "Showing {start} to {end} of {rows} entries",
                        noResults: "No results match your search query"
                    }
                });
                
                console.log('Invoice DataTable initialized successfully');
            } catch (error) {
                console.error('Invoice DataTable initialization failed:', error);
            }
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeInvoiceDataTable();
    });
    </script>
    
    <style>
        /* DataTable custom styling */
        .datatable-table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        
        .datatable-wrapper {
            border: none !important;
        }
        
        .datatable-table thead th {
            background-color: #1c1c1d !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: #ffffff !important;
        }
        
        .datatable-table tbody td {
            white-space: nowrap !important;
        }
        
        .datatable-top, .datatable-bottom {
            background-color: #ffffff !important;
        }
        
        .datatable-search input {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem !important;
            margin-left: 0.5rem !important;
        }
        
        .datatable-selector {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem !important;
            margin: 0 0.5rem !important;
        }
    </style>
</body>
</html>