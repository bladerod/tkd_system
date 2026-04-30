@php
    $branding = \App\Models\Branding::first();
@endphp
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(isset($branding) && $branding->logo_path)
        <link rel="icon" href="{{ Storage::url($branding->logo_path) }}">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TrainNova | Billing</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/billing.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">
    @include("includes.navbar")
    @include('includes.sidebar')
    <div class="container-fluid m-0">
        <div class="row">
            <main class="ml-64 p-6">
                <div class="container-fluid m-0">
                    <div class="row">
                        <main class="">
                            <div class="container-fluid">
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
                                    <form method="GET" action="{{ route('billing.index') }}" class="mb-6 p-4 mx-4 ">
                                        <div class="flex items-center gap-2 mb-4">
                                            <h1 class="font-semibold text-gray-700">Filter Options</h1>
                                        </div>
                                        
                                        <div class="grid grid-cols-12 gap-4 items-end">
                                            <div class="col-span-2">
                                                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Due From</label>
                                                <input type="date" name="from_date" value="{{ request('from_date') }}" 
                                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-white">
                                            </div>
                                            
                                            <div class="col-span-2">
                                                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Due To</label>
                                                <input type="date" name="to_date" value="{{ request('to_date') }}" 
                                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-white">
                                            </div>

                                            <div class="col-span-2">
                                                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Status</label>
                                                <select name="status" 
                                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-white">
                                                    <option value="">All Statuses</option>
                                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                                    <option value="void" {{ request('status') == 'void' ? 'selected' : '' }}>Void</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-span-3">
                                                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Class</label>
                                                <select name="class_id" 
                                                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-white">
                                                    <option value="">All Classes</option>
                                                    @foreach($classes ?? [] as $class)
                                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->class_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-span-3">
                                                <div class="flex gap-2">
                                                    <button type="submit" class="flex-1 bg-[#1C1C1D] text-white px-4 py-2.5 rounded-lg hover:bg-[#2C2C2D] transition-all duration-200 font-medium text-sm shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                                                        <i class="fas fa-search"></i> Apply Filters
                                                    </button>
                                                    <a href="{{ route('billing.index') }}" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium flex items-center justify-center">
                                                        Clear
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- Table Section -->
                                    <div class="bg-white rounded-b-xl shadow-sm border border-gray-100 overflow-hidden">
                                        <div class="overflow-x-auto">
                                            <table id="invoiceTable" class="min-w-full divide-y divide-gray-200">
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
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @forelse($billings as $billing)
                                                    <tr class="hover:bg-gray-50 transition-colors duration-150" 
                                                        data-invoice-id="{{ $billing->id }}" 
                                                        data-invoice-no="{{ $billing->invoice_no }}" 
                                                        data-student-name="{{ $billing->student->first_name }} {{ $billing->student->last_name }}" 
                                                        data-total-due="{{ $billing->total_due }}" 
                                                        data-status="{{ $billing->status }}">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $billing->invoice_no }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="ml-3">
                                                                    <p class="text-sm font-medium text-gray-800">{{ $billing->student->first_name }} {{ $billing->student->last_name }}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            @php
                                                                $classNames = [];
                                                                if ($billing->student) {
                                                                    $activeEnrollments = $billing->student->classes->where('status', 'active');
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
                                                                $parentUser = $billing->student ? \App\Models\User::find($billing->student->primary_parent_id) : null;
                                                            @endphp
                                                            @if($parentUser)
                                                                {{ $parentUser->fname }} {{ $parentUser->lname }}
                                                            @else
                                                                <span class="text-gray-400 italic">N/A</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            <div class="text-right">
                                                                ₱{{ number_format($billing->total_due, 2) }}
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($billing->due_date)->format('F j, Y') }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            @php
                                                                $statusColors = [
                                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                                    'paid' => 'bg-green-100 text-green-800',
                                                                    'overdue' => 'bg-red-100 text-red-800',
                                                                    'partial' => 'bg-blue-100 text-blue-800',
                                                                    'void' => 'bg-gray-100 text-gray-800',
                                                                    'pending_verification' => 'bg-purple-100 text-purple-800',
                                                                ];
                                                                $statusColor = $statusColors[$billing->status] ?? 'bg-gray-100 text-gray-800';
                                                                $statusText = $billing->status === 'pending_verification' ? 'Awaiting Approval' : ucfirst($billing->status);
                                                            @endphp
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">{{ $statusText }}</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap items-center">
                                                            <div class="flex gap-2">
                                                                <button onclick="generateReceipt({{ $billing->id }})" class="bg-[#72A0C1] p-2 rounded-xl hover:bg-[#5f8bad] transition-colors" title="Generate Receipt">
                                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                </button>

                                                                {{-- View Proof button — lalabas lang kung pending_verification --}}
                                                                @if($billing->status == 'pending_verification')
                                                                <button onclick="viewPaymentProof({{ $billing->id }}, '{{ $billing->payment_proof }}')" 
                                                                    class="bg-purple-600 p-2 rounded-xl hover:bg-purple-700 transition-colors" 
                                                                    title="View Payment Proof">
                                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                    </svg>
                                                                </button>
                                                                @endif

                                                                @if($billing->status != 'paid' && $billing->status != 'pending_verification')
                                                                <button type="button" onclick="processPayment({{ $billing->id }}, '{{ $billing->invoice_no }}', '{{ addslashes($billing->student->first_name . ' ' . $billing->student->last_name) }}', {{ max(0, $billing->total_due - $billing->payments->sum('amount')) }})" 
                                                                    class="bg-[#63ad35] p-2 rounded-xl hover:bg-[#71c93e] transition-colors" title="Record Payment">
                                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                                    </svg>
                                                                </button>
                                                                @endif

                                                                <button onclick="sendReminder({{ $billing->id }})" class="bg-[#0096FF] p-2 rounded-xl hover:bg-[#007acc] transition-colors" title="Send Reminder">
                                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
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
                                                <p class="text-right font-semibold">{{ $billings->count() }}</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 border-1 border-dashed rounded-xl p-2 mb-2">
                                                <p>Total Amount</p>
                                                <p class="text-right font-semibold">₱{{ number_format($billings->sum('total_due'), 2) }}</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 border-1 border-dashed rounded-xl p-2 mb-2">
                                                <p>Total Paid</p>
                                                <p class="text-right font-semibold text-green-600">₱{{ number_format($billings->sum(function($inv) { return $inv->payments->sum('amount'); }), 2) }}</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 border-1 border-dashed rounded-xl p-2 mb-2">
                                                <p>Outstanding Balance</p>
                                                <p class="text-right font-semibold text-red-600">₱{{ number_format($billings->sum('total_due') - $billings->sum(function($inv) { return $inv->payments->sum('amount'); }), 2) }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            {{-- <h1 class="text-xl font-bold mb-5">Plan Setup</h1> --}}
                                            <div style="border-bottom: solid #000 1px;" class="grid grid-cols-2 gap-4 p-2 mb-2 pt-13">
                                                <p>Pending Invoices</p>
                                                <p class="text-right font-semibold text-yellow-600">{{ $billings->where('status', 'pending')->count() }}</p>
                                            </div>
                                            <div style="border-bottom: solid #000 1px;" class="grid grid-cols-2 gap-4 p-2 mb-2">
                                                <p>Awaiting Approval</p>
                                                <p class="text-right font-semibold text-purple-600">{{ $billings->where('status', 'pending_verification')->count() }}</p>
                                            </div>
                                            <div style="border-bottom: solid #000 1px;" class="grid grid-cols-2 gap-4 p-2 mb-2">
                                                <p>Overdue Invoices</p>
                                                <p class="text-right font-semibold text-red-600">{{ $billings->where('status', 'overdue')->count() }}</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 p-2 mb-2">
                                                <p>Paid Invoices</p>
                                                <p class="text-right font-semibold text-green-600">{{ $billings->where('status', 'paid')->count() }}</p>
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
                <h3 class="text-2xl font-semibold text-white">Add New Invoice</h3>
            </div>
            <form id="addInvoiceForm">
                <div class="grid grid-cols-2 gap-4 p-5">
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Student *</label>
                            <select id="studentSelect" name="student_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                    @php
                                        $parentUser = \App\Models\User::find($student->primary_parent_id);
                                        $parentName = $parentUser ? $parentUser->fname . ' ' . $parentUser->lname : 'N/A';
                                        $activeClassIds = $student->classes->where('status', 'active')->pluck('class_id')->toJson();
                                    @endphp
                                    <option value="{{ $student->id }}" 
                                            data-student-code="{{ $student->student_code }}" 
                                            data-parent="{{ $parentName }}"
                                            data-class-ids="{{ $activeClassIds }}">
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
                            <select id="classSelect" name="class_id" required disabled
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1C1C1D] disabled:bg-gray-100 disabled:text-gray-500 cursor-not-allowed">
                                <option value="">-- Select a Student First --</option>
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
                                class="w-full px-3 py-2 border text-right border-gray-300 rounded-md bg-gray-100 focus:outline-none">
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
                                class="w-full px-3 text-right py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none">
                            <p class="text-xs text-gray-500 mt-1">Auto-calculated from billing rules for overdue invoices</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total Due (₱)</label>
                            <p id="totalDuePreview" class="text-2xl font-bold text-gray-900 bg-green-50 p-2  text-right rounded-md">₱0.00</p>
                        </div>
                    </div>
                </div>
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

    {{-- Payment Proof Modal --}}
    <div id="paymentProofModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto border w-full max-w-lg shadow-lg rounded-lg bg-white">
            <div class="flex items-center justify-between bg-[#1C1C1D] p-4 rounded-t-lg">
                <h3 class="text-xl font-semibold text-white">Payment Proof</h3>
                <button onclick="closeProofModal()" class="text-white hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="proofImageContainer" class="mb-4">
                    <img id="proofImage" src="" alt="Payment Proof" class="w-full rounded-lg border border-gray-200">
                </div>
                <div class="flex gap-3 justify-end">
                    <button onclick="rejectPayment()"
                        class="px-4 py-2 text-sm text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors flex items-center gap-2">
                        <i class="fas fa-times"></i> Reject
                    </button>
                    <button onclick="approvePayment()"
                        class="px-4 py-2 text-sm text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors flex items-center gap-2">
                        <i class="fas fa-check"></i> Approve Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    @vite(['resources/js/billing.js'])
    @vite(['resources/js/navbarDrop.js'])

    <script>
        let currentProofInvoiceId = null;

        function viewPaymentProof(invoiceId, proofPath) {
            currentProofInvoiceId = invoiceId;
            const baseUrl = '{{ asset("storage") }}/';
            document.getElementById('proofImage').src = baseUrl + proofPath;
            document.getElementById('paymentProofModal').classList.remove('hidden');
        }

        function closeProofModal() {
            document.getElementById('paymentProofModal').classList.add('hidden');
            currentProofInvoiceId = null;
        }

        function approvePayment() {
            if (!currentProofInvoiceId) return;
            Swal.fire({
                title: 'Approve Payment?',
                text: 'This will mark the invoice as Paid.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Approve',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/settings/billing/${currentProofInvoiceId}/approve-proof`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Approved!', 'Payment has been approved.', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
                }
            });
        }

        function rejectPayment() {
            if (!currentProofInvoiceId) return;
            Swal.fire({
                title: 'Reject Payment?',
                text: 'This will set the invoice back to Pending.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Reject',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/settings/billing/${currentProofInvoiceId}/reject-proof`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Rejected', 'Payment has been rejected.', 'info')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    });
                }
            });
        }
    </script>
</body>
</html>