<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TrainNova | Billing</title>
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

                                <div class="bg-white rounded-xl shadow-sm">
                                    <div class="pb-0 bg-transparent">
                                        <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                            <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Invoices Log</h6>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-3 m-4">
                                        <button onclick="openAddInvoiceModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm">
                                            <i class="fas fa-plus"></i>
                                            Add Invoice
                                        </button>
                                        <button onclick="generateMonthlyInvoices()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm">
                                            <i class="fas fa-sync-alt"></i>
                                            Generate Invoices
                                        </button>
                                        <button onclick="markOverdueInvoices()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Mark Overdue
                                        </button>
                                    </div>
                                    <!-- Table Section -->
                                    <div class="bg-white rounded-b-xl shadow-sm border border-gray-100 overflow-hidden">
                                        <!-- Table -->
                                        <div class="overflow-x-auto">
                                            <table id="invoiceTable" class="min-w-full divide-y divide-gray-200">
                                                <!-- Table Head -->
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Invoice#</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Student Name</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Class</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Parent</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Due</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
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
                                                            @php
                                                                $classNames = [];
                                                                if ($invoice->student) {
                                                                    // Find all active class enrollments for this student
                                                                    $activeEnrollments = $invoice->student->classes->where('status', 'active');
                                                                    foreach($activeEnrollments as $enrollment) {
                                                                        if ($enrollment->class) {
                                                                            $classNames[] = $enrollment->class->class_name;
                                                                        }
                                                                    }
                                                                }
                                                            @endphp
                                                            
                                                            @if(count($classNames) > 0)
                                                                {{ implode(', ', $classNames) }}
                                                            @else
                                                                <span class="text-gray-400 italic">No Active Class</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                           @php
                                                                // Safely fetch the parent directly from the Users table, just like we did in the modal
                                                                $parentUser = $invoice->student ? \App\Models\User::find($invoice->student->primary_parent_id) : null;
                                                            @endphp
                                                            
                                                            @if($parentUser)
                                                                {{ $parentUser->fname }} {{ $parentUser->lname }}
                                                            @else
                                                                <span class="text-gray-400 italic">N/A</span>
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
    
    {{-- Add Invoice Modal --}}
    <div id="addInvoiceModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-full max-w-2xl shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex items-center sticky top-0 bg-[#1C1C1D] pb-2 p-3">
                <i class="fas fa-plus text-white text-2xl pe-1"></i>
                <h3 class="text-2xl  font-semibold text-white">Add New Invoice</h3>
            </div>
            <form id="addInvoiceForm">
                <div class="grid grid-cols-2 gap-4 p-5">
                    <!-- Left Column -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Student *</label>
                            <select id="studentSelect" name="student_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                    @php
                                        // Safely grab the parent directly from the Users table
                                        $parentUser = \App\Models\User::find($student->primary_parent_id);
                                        $parentName = $parentUser ? $parentUser->fname . ' ' . $parentUser->lname : 'N/A';
                                    @endphp
                                    <option value="{{ $student->id }}" 
                                            data-student-code="{{ $student->student_code }}" 
                                            data-parent="{{ $parentName }}">
                                        {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student Code</label>
                            <p id="studentCodeDisplay" class="text-gray-600 text-sm bg-gray-50 p-2 rounded-md"></p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Parent/Guardian</label>
                            <p id="parentDisplay" class="text-gray-600 text-sm bg-gray-50 p-2 rounded-md"></p>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Class *</label>
                            <select id="classSelect" name="class_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Plan/Subscription *</label>
                            <select id="planSelect" name="plan_id" required disabled
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D] disabled:bg-gray-100 disabled:text-gray-500 cursor-not-allowed">
                                <option value="">-- Select a Class First --</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" 
                                        data-class-id="{{ $plan->class_id }}"
                                        data-monthly-price="{{ $plan->monthly_price }}" 
                                        data-plan-name="{{ $plan->plan_name }}" 
                                        data-billing-cycle="{{ $plan->billing_cycle }}">
                                        {{ $plan->plan_name }} - ₱{{ number_format($plan->monthly_price, 2) }} ({{ ucfirst($plan->billing_cycle) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Plan Details</label>
                            <div id="planDetails" class="text-gray-600 text-sm bg-gray-50 p-3 rounded-md hidden">
                                <p><strong>Plan Name:</strong> <span id="planNameDisplay"></span></p>
                                <p><strong>Billing Cycle:</strong> <span id="planBillingCycleDisplay"></span></p>
                                <p><strong>Base Amount:</strong> ₱<span id="planAmountDisplay">0.00</span></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type</label>
                            <select id="discountType" name="discount_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                                <option value="none" data-type="none" data-value="0" selected>No Discount</option>
                                @foreach ($discounts as $discount)
                                    <option value="{{ $discount->id }}" 
                                            data-type="{{ $discount->type }}" 
                                            data-value="{{ $discount->value }}">
                                        {{ $discount->name }} ({{ $discount->type == 'percent' ? $discount->value.'%' : '₱'.$discount->value }})
                                    </option>
                                @endforeach
                                <option value="custom" data-type="custom" data-value="0">Custom Discount</option>
                            </select>
                        </div>
                        
                        <div class="mb-4" id="customDiscountContainer" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Custom Discount Amount (₱)</label>
                            <input type="number" id="customDiscountAmount" name="custom_discount" step="0.01" value="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Applied Discount (₱)</label>
                            <input type="number" id="invoiceDiscount" name="discount" step="0.01" readonly
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Base Amount (₱)</label>
                            <input type="number" id="invoiceAmount" name="amount" step="0.01" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]"
                                placeholder="Amount will auto-fill from plan">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Penalty (₱)</label>
                            <input type="number" id="invoicePenalty" name="penalty" step="0.01" readonly
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none">
                            <p class="text-xs text-gray-500 mt-1">Auto-calculated from billing rules for overdue invoices</p>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total Due (₱)</label>
                            <p id="totalDuePreview" class="text-2xl font-bold text-gray-900 bg-green-50 p-2 rounded-md">₱0.00</p>
                        </div>
                    </div>
                </div>
                
                <!-- Full Width Section -->
                <div class="grid grid-cols-3 gap-4 mt-4 px-5">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Billing Period Start *</label>
                        <input type="date" id="billingPeriodStart" name="billing_period_start" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Billing Period End *</label>
                        <input type="date" id="billingPeriodEnd" name="billing_period_end" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                        <input type="date" id="dueDate" name="due_date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                    </div>
                </div>
                
                <div class="flex gap-3 justify-end sticky bottom-0 bg-white p-4 mt-2 border-t">
                    <button type="button" onclick="closeAddInvoiceModal()"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                        Create Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    {{-- @vite(['resources/js/app.js']) --}}
    @vite(['resources/js/billing.js'])
    @vite(['resources/js/navbarDrop.js'])
</body>
</html>