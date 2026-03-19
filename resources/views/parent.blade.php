<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD | Parent Management </title>

    @vite(['resources/css/app.css'])
    @vite(['resources/css/parent.css'])
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Add SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

@include("includes.navbar")
@include("includes.sidebar")

<div class="main-content parents-page">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 mt-1">
        <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
        <span>/</span>
        <span class="text-[#1C1C1D] font-medium">Parents</span>
    </div>
    <!-- Page Title -->
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-4xl font-bold mb-4 ">Parent Management</h1>
    </div>

    <div class="content-card">
        <!-- Parent Table -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                    <div class="card-header pb-0 bg-transparent">
                        <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                            <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Parent List</h6>
                        </div>
                    </div>
                    <div class="" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                        <!-- ADD BUTTON -->
                        <div class="flex justify-end items-center m-3">
                            <button onclick="openAddModal()" class="btn-primary bg-green-800 p-3 rounded-xl text-white hover:bg-green-600 font-bold">
                                <i class="fas fa-plus"></i> Add Parent
                            </button>
                        </div>

                        <!-- Users Table -->
                        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                            <table id="parentTable" class="min-w-full divide-y divide-gray-200 p-3">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Children</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Balance</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Verified</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($parentList as $parent)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150" data-parent-id="{{ $parent['id'] }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="avatar-circle">
                                                        {{ strtoupper(substr($parent['fname'], 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-medium">{{ $parent['fname'] }}</div>
                                                        <div class="text-sm text-gray-500">{{ $parent['email'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="badge badge-info px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                                                    <i class="fas fa-child"></i> {{ $parent['children_count'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-700">{{ $parent['mobile'] }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm {{ $parent['total_balance'] > 0 ? 'text-red-600 font-semibold' : 'text-green-600' }}">
                                                    ₱{{ number_format($parent['total_balance'], 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($parent['status'] == 'active')
                                                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($parent['id_verified'])
                                                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full"><i class="fas fa-check"></i> Verified</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full"><i class="fas fa-clock"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button class="text-white bg-blue-500 hover:bg-blue-600 p-2.5 rounded-lg" onclick="openModal({{ $parent['id'] }})">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <i class="fa-solid fa-users text-4xl text-gray-300 mb-3"></i>
                                                    <p class="text-lg font-medium">No parents found</p>
                                                    <p class="text-sm">Click the "Add Parent" button to create a new parent.</p>
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

    <!-- Parent Detail Modal -->
    <div id="parentModal" class="modal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <div class="flex items-center gap-4">
                    <div class="avatar-circle large" id="modalAvatar">M</div>
                    <div>
                        <h2 id="modalParentName">Parent Profile</h2>
                        <p class="text-sm text-gray-500" id="modalParentContact">Loading...</p>
                    </div>
                </div>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" data-tab="children" onclick="switchTab('children')">
                    <i class="fas fa-child"></i> Children
                </button>
                <button class="tab" data-tab="billing" onclick="switchTab('billing')">
                    <i class="fas fa-file-invoice-dollar"></i> Family Billing
                </button>
                <button class="tab" data-tab="payments" onclick="switchTab('payments')">
                    <i class="fas fa-money-bill-wave"></i> Payments
                </button>
                <button class="tab" data-tab="chat" onclick="switchTab('chat')">
                    <i class="fas fa-comments"></i> Chat
                    <span class="notification-badge" id="chatBadge" style="display:none">0</span>
                </button>
                <button class="tab" data-tab="activity" onclick="switchTab('activity')">
                    <i class="fas fa-history"></i> Activity Log
                </button>
                <button class="tab" data-tab="notifications" onclick="switchTab('notifications')">
                    <i class="fas fa-bell"></i> Notifications
                    <span class="notification-badge" id="notifBadge" style="display:none">0</span>
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="tab-contents">
                <!-- Children Tab -->
                <div id="children" class="tab-pane active">
                    <div class="loading-spinner" id="childrenLoading">
                        <i class="fas fa-spinner fa-spin"></i> Loading children data...
                    </div>
                    <div id="childrenContent" class="hidden">
                        <!-- Dynamic content loaded here -->
                    </div>
                </div>

                <!-- Family Billing Tab -->
                <div id="billing" class="tab-pane">
                    <div class="loading-spinner" id="billingLoading">
                        <i class="fas fa-spinner fa-spin"></i> Loading billing data...
                    </div>
                    <div id="billingContent" class="hidden">
                        <!-- Dynamic content loaded here -->
                    </div>
                </div>

                <!-- Payments Tab -->
                <div id="payments" class="tab-pane">
                    <div class="loading-spinner" id="paymentsLoading">
                        <i class="fas fa-spinner fa-spin"></i> Loading payment history...
                    </div>
                    <div id="paymentsContent" class="hidden">
                        <!-- Dynamic content loaded here -->
                    </div>
                </div>

                <!-- Chat Tab -->
                <div id="chat" class="tab-pane">
                    <div class="chat-container">
                        <div class="chat-messages" id="chatMessages">
                            <!-- Messages loaded here -->
                        </div>
                        <div class="chat-input-area">
                            <input type="text" id="chatInput" placeholder="Type a message..." maxlength="1000">
                            <button onclick="sendMessage()" class="btn-send">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Activity Log Tab -->
                <div id="activity" class="tab-pane">
                    <div class="loading-spinner" id="activityLoading">
                        <i class="fas fa-spinner fa-spin"></i> Loading activity log...
                    </div>
                    <div id="activityContent" class="hidden">
                        <!-- Dynamic content loaded here -->
                    </div>
                </div>

                <!-- Notifications Tab -->
                <div id="notifications" class="tab-pane">
                    <div class="loading-spinner" id="notificationsLoading">
                        <i class="fas fa-spinner fa-spin"></i> Loading notifications...
                    </div>
                    <div id="notificationsContent" class="hidden">
                        <!-- Dynamic content loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="//unpkg.com/alpinejs" defer></script>
@vite("resources/js/parents.js")
@vite(['resources/js/navbarDrop.js'])
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
</body>
</html>