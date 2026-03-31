<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TrainNova - Billing</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/billing.css'])
    <!-- Add Simple-Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
    <!-- SweetAlert2 for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                                <div class="flex gap-2 text-sm text-gray-500 mb-6">
                                    <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
                                    <span>/</span>
                                    <span class="text-[#1C1C1D] font-medium">Billing</span>
                                </div>
                                <h1 class="text-3xl mb-4 text-4xl font-bold text-[#1C1C1D]">Invoices</h1>

                                <!-- Action Buttons -->
                                <div class="flex justify-end gap-3 mb-4">
                                    <button onclick="generateMonthlyInvoices()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm">
                                        <i class="fas fa-sync-alt"></i>
                                        Generate Invoices
                                    </button>
                                    <button onclick="markOverdueInvoices()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Mark Overdue
                                    </button>
                                </div>

                                <div class="bg-white rounded-xl shadow-sm">
                                    <div class="pb-0 bg-transparent">
                                        <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                            <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Invoices Log</h6>
                                        </div>
                                    </div>
                                    <div class="flex justify-end p-3">
                                        <button onclick="openPaymentModal()" class="bg-[#63ad35] p-3 rounded-xl hover:bg-[#71c93e] font-medium text-white">
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
                                                    @forelse($invoices as $invoice)
                                                    <tr class="hover:bg-gray-50 transition-colors duration-150" data-invoice-id="{{ $invoice->id }}" data-invoice-no="{{ $invoice->invoice_no }}" data-student-name="{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}" data-total-due="{{ $invoice->total_due }}" data-status="{{ $invoice->status }}">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $invoice->invoice_no }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="ml-3">
                                                                    <p class="text-sm font-medium text-gray-800">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            @if($invoice->student->parent->user)
                                                                {{ $invoice->student->parent->user->fname }} {{ $invoice->student->parent->user->lname }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱{{ number_format($invoice->total_due, 2) }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            @php
                                                                $statusColors = [
                                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                                    'paid' => 'bg-green-100 text-green-800',
                                                                    'overdue' => 'bg-red-100 text-red-800',
                                                                    'partial' => 'bg-blue-100 text-blue-800',
                                                                    'void' => 'bg-gray-100 text-gray-800'
                                                                ];
                                                                $statusColor = $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800';
                                                                $statusText = ucfirst($invoice->status);
                                                            @endphp
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">{{ $statusText }}</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap items-center">
                                                            <div class="flex gap-2">
                                                                <button onclick="generateReceipt({{ $invoice->id }})" class="bg-[#72A0C1] p-2 rounded-xl hover:bg-[#5f8bad] transition-colors" title="generate receipt">
                                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                </button>
                                                                @if($invoice->status != 'paid')
                                                                <button type="button" onclick="processPayment({{ $invoice->id }}, '{{ $invoice->invoice_no }}', '{{ addslashes($invoice->student->first_name . ' ' . $invoice->student->last_name) }}', {{ max(0, $invoice->total_due - $invoice->payments->sum('amount')) }})" class="bg-[#63ad35] p-2 rounded-xl hover:bg-[#71c93e] transition-colors" title="record payment">
                                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                                    </svg>
                                                                </button>
                                                                @endif
                                                                <button onclick="sendReminder({{ $invoice->id }})" class="bg-[#0096FF] p-2 rounded-xl hover:bg-[#007acc] transition-colors" title="send reminder">
                                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                                            <i class="fas fa-inbox text-4xl mb-2"></i>
                                                            <p>No invoices found</p>
                                                        </td>
                                                    </tr>
                                                    @endforelse
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
                                                <p>Total Invoices</p>
                                                <p class="text-right font-semibold">{{ $invoices->count() }}</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 border-1 border-dashed rounded-xl p-2 mb-2">
                                                <p>Total Amount</p>
                                                <p class="text-right font-semibold">₱{{ number_format($invoices->sum('total_due'), 2) }}</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 border-1 border-dashed rounded-xl p-2 mb-2">
                                                <p>Total Paid</p>
                                                <p class="text-right font-semibold text-green-600">₱{{ number_format($invoices->sum(function($inv) { return $inv->payments->sum('amount'); }), 2) }}</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 border-1 border-dashed rounded-xl p-2 mb-2">
                                                <p>Outstanding Balance</p>
                                                <p class="text-right font-semibold text-red-600">₱{{ number_format($invoices->sum('total_due') - $invoices->sum(function($inv) { return $inv->payments->sum('amount'); }), 2) }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <h1 class="text-xl font-bold mb-5">Plan Setup</h1>
                                            <div style="border-bottom: solid #000 1px;" class="grid grid-cols-2 gap-4 p-2 mb-2">
                                                <p>Pending Invoices</p>
                                                <p class="text-right font-semibold text-yellow-600">{{ $invoices->where('status', 'pending')->count() }}</p>
                                            </div>
                                            <div style="border-bottom: solid #000 1px;" class="grid grid-cols-2 gap-4 p-2 mb-2">
                                                <p>Overdue Invoices</p>
                                                <p class="text-right font-semibold text-red-600">{{ $invoices->where('status', 'overdue')->count() }}</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 p-2 mb-2">
                                                <p>Paid Invoices</p>
                                                <p class="text-right font-semibold text-green-600">{{ $invoices->where('status', 'paid')->count() }}</p>
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
    
    {{-- Payment Modal --}}
    <div id="paymentModal" class="fixed inset-0 bg-gray-500 bg-opacity-90 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Record Payment</h3>
            </div>
            <form id="paymentForm">
                <input type="hidden" id="invoiceId" name="invoice_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice #</label>
                    <p id="invoiceNoDisplay" class="text-gray-900 font-medium"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                    <p id="studentNameDisplay" class="text-gray-900"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Due</label>
                    <p id="totalDueDisplay" class="text-xl font-bold text-gray-900"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount to Pay</label>
                    <input type="number" id="paymentAmount" name="amount" step="0.01" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select id="paymentMethod" name="payment_method" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                        <option value="cash">Cash</option>
                        <option value="gcash">GCash</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference No. (Optional)</label>
                    <input type="text" id="transactionReference" name="transaction_reference"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closePaymentModal()"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-[#63ad35] rounded-md hover:bg-[#71c93e] transition-colors">
                        Process Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/billing.js'])
    @vite(['resources/js/navbarDrop.js'])
</body>
</html>