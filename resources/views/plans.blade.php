@php
    $canCreatePlans = auth()->user()->canCreate('classes');
    $canEditPlans = auth()->user()->canEdit('classes');
    $canDeletePlans = auth()->user()->canDelete('classes');
@endphp

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrainNova | Plans Management</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/user.css'])
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
                        <span class="text-[#1C1C1D] font-medium">Plans</span>
                    </div>

                    <!-- Header with Title and Add Button -->
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-4xl font-bold text-[#1C1C1D]">Plan Management</h1>
                    </div>

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
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Plans
                                            Table</h6>
                                    </div>
                                </div>
                                <div class="" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <div class="m-4 flex justify-end">
                                        <button @if ($canCreatePlans) onclick="openAddPlanModal()" @endif class="bg-[#1c1c1d] text-white px-4 py-2 rounded-lg 
                                            @if (!$canCreatePlans)
                                                hidden
                                            @endif
                                            hover:bg-[#2f2f2f] transition-colors flex items-center gap-2">
                                            <i class="fa-solid fa-plus"></i>
                                            Add Plan
                                        </button>
                                    </div>

                                    <!-- Plans Table -->
                                    <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                                        <table id="userTable" class="min-w-full divide-y divide-gray-200 p-3">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Plan Name</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Description</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Monthly Price</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Billing Cycle</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Actions</th>
                                                </tr>
                                            </thead>

                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse($plans as $plan)
                                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm">
                                                                {{ ucfirst(trim(($plan->plan_name ?? ''))) }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm">
                                                                {{ ucfirst(trim(($plan->description ?? ''))) }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm">
                                                                {{ ucfirst(trim(($plan->monthly_price ?? ''))) }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm">
                                                                {{ ucfirst(trim(($plan->billing_cycle ?? ''))) }}</div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center gap-3">
                                                                <button 
                                                                    @if ($canEditPlans)
                                                                        onclick="openEditPlanModal({{ $plan->id }})"
                                                                    @endif
                                                                    class="text-white bg-green-500 hover:bg-green-600 
                                                                    @if (!$canEditPlans) hidden @endif
                                                                    p-2.5 rounded-lg edit-btn">
                                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                                </button>
                                                            
                                                                <button 
                                                                    @if ($canDeletePlans)
                                                                        onclick="deletePlan({{ $plan->id }}, '{{ addslashes($plan->plan_name) }}')"
                                                                    @endif
                                                                    class="bg-red-500 hover:bg-red-600
                                                                    @if (!$canDeletePlans) hidden @endif
                                                                    p-2.5 rounded-lg delete-plan-btn"> 
                                                                    <i class="fa-regular fa-trash-can text-white"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                                            <div class="flex flex-col items-center">
                                                                <i class="fas fa-sync-alt text-4xl text-gray-300 mb-3"></i>
                                                                <p class="text-lg font-medium">No Plans found</p>
                                                                <p class="text-sm">Click the "Add Plan" button to create a
                                                                    new Plan.</p>
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

    {{-- Add Plan Modal --}}
    <div id="addPlanModal"
        class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white">
            <!-- Modal Header -->
            <div class="flex items-center p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <i class="fa-solid fa-plus text-white text-xl pe-1"></i>
                <h3 class="text-2xl font-bold text-[#ffffff]">Add Plan</h3>
            </div>
            <div class="p-4">
                <!-- Modal Body - Form -->
                <form id="addPlanForm" method="POST" action="{{ route('plans.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <!-- plan_name -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Plan Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="plan_name" id="plan_name" required maxlength="150"
                                placeholder="e.g., Basic, Premium, Elite"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_plan_name"></div>
                        </div>

                        <!-- description -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="3"
                                placeholder="Describe the plan details, benefits, etc."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]"></textarea>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_description"></div>
                        </div>


                        <!-- monthly_price -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Monthly Price (₱)
                            </label>
                            <input type="number" name="monthly_price" id="monthly_price" step="0.01" min="0"
                                value="0.00" placeholder="0.00"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_monthly_price"></div>
                        </div>


                        <!-- billing_cycle -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Billing Cycle
                            </label>
                            <select name="billing_cycle" id="billing_cycle"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_billing_cycle"></div>
                        </div>

                        <!-- active_flag -->
                        <div class="form-group">
                            <label class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-700">Status</span>
                            </label>
                            <select name="status" id="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 mt-6 pt-3 border-t">
                        <button type="button" onclick="closeAddPlanModal()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] transition-colors">
                            Add Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Edit Plan Modal --}}
    <div id="editPlanModal"
        class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white">
            <!-- Modal Header -->
            <div class="flex items-center p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <i class="fa-regular fa-pen-to-square text-white text-xl pe-1"></i>
                <h3 class="text-2xl font-bold text-[#ffffff]">Edit Plan</h3>
            </div>
            <div class="p-4">
                <!-- Modal Body - Form -->
                <form id="editPlanForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <!-- plan_name -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Plan Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="plan_name" id="edit_plan_name" required maxlength="150"
                                placeholder="e.g., Basic, Premium, Elite"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_plan_name"></div>
                        </div>

                        <!-- description -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="edit_description" rows="3"
                                placeholder="Describe the plan details, benefits, etc."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]"></textarea>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_description"></div>
                        </div>

                        <!-- monthly_price -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Monthly Price (₱)
                            </label>
                            <input type="number" name="monthly_price" id="edit_monthly_price" step="0.01" min="0"
                                value="0.00" placeholder="0.00"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_monthly_price"></div>
                        </div>

                        <!-- billing_cycle -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Billing Cycle
                            </label>
                            <select name="billing_cycle" id="edit_billing_cycle"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_billing_cycle"></div>
                        </div>

                        <!-- status (active_flag) -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select name="status" id="edit_status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 mt-6 pt-3 border-t">
                        <button type="button" onclick="closeEditPlanModal()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] transition-colors">
                            Update Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/js/plans.js', 'resources/js/navbarDrop.js'])
    <script>
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function () {
                // If there are errors, automatically re-open the Add Modal
                openAddUserModal();
            });
        @endif
    </script>

</body>

</html>