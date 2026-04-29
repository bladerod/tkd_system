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
    <title>TrainNova | Billing Rules</title>
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
                        <span class="text-[#1C1C1D] font-medium">Billing Rules</span>
                    </div>
                    <h1 class="text-4xl font-bold text-[#1C1C1D]">Settings</h1>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Billing Rules</h6>
                                    </div>
                                </div>
                                <div class="p-8" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <!-- Billing Rules Form -->
                                    <div class="">
                                        <!-- Payment Structure -->
                                        <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3"> Payment Structure</h2>
                                        <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">
                                        <form action="/settings/billing-rules" method="POST">
                                            @csrf
                                            <div class="grid grid-cols-3 gap-4 mb-4">
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Default Monthly Fee <span class="text-red-500">*</span></label>
                                                    <div class="relative">
                                                        <span class="absolute left-3 top-2 text-gray-500">₱</span>
                                                        <input type="number" name="monthlyFee" value="{{ $rules->monthly_fee ?? '' }}" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Enrollment Fee</label>
                                                    <div class="relative">
                                                        <span class="absolute left-3 top-2 text-gray-500">₱</span>
                                                        <input type="number" name="enrollmentFees" value="{{ $rules->enrollment_fees ?? '' }}" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Uniform Fee</label>
                                                    <div class="relative">
                                                        <span class="absolute left-3 top-2 text-gray-500">₱</span>
                                                        <input type="number"  name="uniformFees" value="{{ $rules->uniform_fees ?? '' }}" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4 mb-6">
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Belt Promotion Fee</label>
                                                    <div class="relative">
                                                        <span class="absolute left-3 top-2 text-gray-500">₱</span>
                                                        <input type="number" name="beltPromotionFees" value="{{ $rules->belt_promotion_fees ?? '' }}" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Competition Fee</label>
                                                    <div class="relative">
                                                        <span class="absolute left-3 top-2 text-gray-500">₱</span>
                                                        <input type="number" name="competitionFees" value="{{ $rules->competition_fees ?? '' }}" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Billing Logic -->
                                            <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3"> Billing Logic</h2>
                                            <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                            <div class="grid grid-cols-3 gap-4 mb-4">
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Billing Cycle</label>
                                                    <select name="billingCycle" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                        <option value="Monthly" {{ ($rules->billing_cycle ?? '') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                                        <option value="Quarterly" {{ ($rules->billing_cycle ?? '') == 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Due Date Rule</label>
                                                    <select name="dueDateRule" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                        <option value="1st of Month" {{ ($rules->due_date_rule ?? '') == '1st of Month' ? 'selected' : '' }}>1st of Month</option>
                                                        <option value="5th of Month" {{ ($rules->due_date_rule ?? '') == '5th of Month' ? 'selected' : '' }}>5th of Month</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Grace Period (Days)</label>
                                                    <input type="number" name="gracePeriod" value="{{ $rules->grace_period ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" value="0">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4 mb-6">
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Late Fee Type</label>
                                                    <select name="lateFeesType" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                        <option value="Fixed" {{ ($rules->late_fees_type ?? '') == 'Fixed' ? 'selected' : '' }}>Fixed</option>
                                                        <option value="Percentage" {{ ($rules->late_fees_type ?? '') == 'Percentage' ? 'selected' : '' }}>Percentage</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Late Fee Amount</label>
                                                    <input type="text" name="lateFeeAmount" value="{{ $rules->late_fee_amount ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="₱0 or 5%">
                                                </div>
                                            </div>

                                            <!-- Payment Rules -->
                                            <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3"> Payment Rules</h2>
                                            <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                            <div class="grid grid-cols-3 gap-4 mb-4">
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Allow Partial Payments</label>
                                                    <select name="allowPartialPayment" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                        <option value="Yes" {{ ($rules->allow_partial_payment ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                                        <option value="No" {{ ($rules->allow_partial_payment ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Auto-Mark Overdue</label>
                                                    <select name="autoMarkOverdue" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                        <option value="Yes" {{ ($rules->auto_mark_overdue ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                                        <option value="No" {{ ($rules->auto_mark_overdue ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Auto-Generate Monthly Invoice</label>
                                                    <select name="autoGenerateMonthlyInvoice" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                        <option value="Yes" {{ ($rules->auto_generate_monthly_invoice ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                                        <option value="No" {{ ($rules->auto_generate_monthly_invoice ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Buttons -->
                                            <div class="flex items-center justify-end gap-3 pt-6">
                                                <button type="submit" class="px-8 py-2 text-sm text-white bg-[#1C1C1D] rounded-md hover:bg-[#2f2f2f] transition-colors">Save</button>
                                            </div>
                                        </form>
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
    @vite(['resources/js/navbarDrop.js'])
</body>
</html>