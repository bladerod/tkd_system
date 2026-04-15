<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrainNova | Parent Management </title>

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
                        {{-- <!-- ADD BUTTON -->
                        <div class="flex justify-end items-center m-3">
                            <button onclick="openAddModal()" class="btn-primary bg-green-800 p-3 rounded-xl text-white hover:bg-green-600 font-bold">
                                <i class="fas fa-plus"></i> Add Parent
                            </button>
                        </div> --}}

                        <!-- Users Table -->
                        <div class="overflow-x-auto bg-white rounded-b-lg border border-gray-200">
                            <table id="parentTable" class="min-w-full divide-y divide-gray-200">
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

                                                    <div>
                                                        <div class="font-medium">{{ $parent['name'] }}</div>
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
                                                @if($parent['status'] == '1')
                                                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($parent['id_verified_flag'])
                                                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full"><i class="fas fa-check"></i> Verified</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full"><i class="fas fa-clock"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button class="text-white bg-blue-500 hover:bg-blue-600 p-2.5 rounded-lg" onclick="openModal({{ $parent['user_id'] }})">
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

                {{-- <!-- Family Billing Tab -->
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
                </div> --}}
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        // Make all functions globally available
let currentParentId = null;
let currentThreadId = null;
let chatRefreshInterval = null;

// Make functions global
window.openModal = async function(parentId) {
    const modal = document.getElementById("parentModal");
    modal.style.display = "flex";

    document.getElementById("modalParentName").innerText = "Loading...";
    document.getElementById("modalParentContact").innerText = "";

    try {
        const res = await fetch(`/parents/${parentId}`, {
            headers: { 'Accept': 'application/json' }
        });

        const data = await res.json();

        console.log("DATA:", data);

        // ❌ HANDLE ERROR RESPONSE
        if (data.error) {
            alert("Parent not found");
            closeModal();
            return;
        }

        // ✅ SAFE ASSIGN
        document.getElementById("modalParentName").innerText = data.full_name || "No Name";

        document.getElementById("modalParentContact").innerText =
            (data.email ?? "No email") + " • " +
            (data.mobile ?? "No contact");

        // ✅ SAFE AVATAR
        document.getElementById("modalAvatar").innerText =
            data.full_name ? data.full_name.charAt(0).toUpperCase() : "?";

        loadChildren(data.students);

    } catch (err) {
        console.error(err);
        alert("Failed to load parent data");
    }
};

// CLOSE MODAL
window.closeModal = function() {
    document.getElementById("parentModal").style.display = "none";
};


window.switchTab = function(tabName) {
    // Update tab buttons
    document.querySelectorAll(".tab").forEach(tab => {
        tab.classList.remove("active");
        if (tab.dataset.tab === tabName) {
            tab.classList.add("active");
        }
    });

    // Update tab panes
    document.querySelectorAll(".tab-pane").forEach(pane => {
        pane.classList.remove("active");
    });

    const tabPane = document.getElementById(tabName);
    if (tabPane) {
        tabPane.classList.add("active");
    }

    // Load tab-specific data
    if (currentParentId) {
        switch(tabName) {
            case 'children':
                loadChildrenData(currentParentId);
                break;
            // case 'billing':
            //     loadBillingData(currentParentId);
            //     break;
            // case 'payments':
            //     loadPaymentsData(currentParentId);
            //     break;
            // case 'chat':
            //     startChatRefresh();
            //     break;
            // case 'activity':
            //     loadActivityData(currentParentId);
            //     break;
            // case 'notifications':
            //     loadNotificationsData(currentParentId);
            //     break;
        }

        if (tabName !== 'chat' && chatRefreshInterval) {
            clearInterval(chatRefreshInterval);
            chatRefreshInterval = null;
        }
    }
}

// window.sendMessage = function() {
//     const input = document.getElementById('chatInput');
//     if (!input) return;

//     const message = input.value.trim();

//     if (!message || !currentParentId) return;

//     fetch(`/api/parents/${currentParentId}/send-message`, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
//         },
//         body: JSON.stringify({ message })
//     })
//     .then(response => {
//         if (response.ok) {
//             input.value = '';
//             refreshChat();
//         }
//     })
//     .catch(error => {
//         console.error('Error sending message:', error);
//         if (typeof Swal !== 'undefined') {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Error!',
//                 text: 'Failed to send message. Please try again.',
//                 confirmButtonColor: '#3085d6'
//             });
//         }
//     });
// }

async function loadParentData(parentId) {
    try {
        const response = await fetch(`/api/parents/${parentId}`);
        if (!response.ok) throw new Error('Failed to load parent data');

        const data = await response.json();

        // Update header
        const modalParentName = document.getElementById('modalParentName');
        const modalParentContact = document.getElementById('modalParentContact');
        const modalAvatar = document.getElementById('modalAvatar');

        if (modalParentName) modalParentName.textContent = data.parent?.user?.name || 'Parent Profile';
        if (modalParentContact) {
            modalParentContact.textContent =
                `${data.parent?.user?.mobile || ''} • ${data.parent?.user?.email || ''}`;
        }
        if (modalAvatar) {
            modalAvatar.textContent = (data.parent?.user?.name || 'P').charAt(0).toUpperCase();
        }

        currentThreadId = data.chat_thread_id;

        // Load chat messages
        if (data.chat_messages) {
            loadChatMessages(data.chat_messages);
        }

    } catch (error) {
        console.error('Error loading parent data:', error);
    }
}
function loadChildren(students) {
    const container = document.getElementById("childrenContent");

    document.getElementById("childrenLoading").style.display = "none";
    container.classList.remove("hidden");

    if (!students || students.length === 0) {
        container.innerHTML = "<p>No children found</p>";
        return;
    }

    let html = "";

    students.forEach((child, index) => {
        html += `
            <div class="p-3 border rounded-lg mb-2 flex justify-between items-center">
                <div>
                    <div class="font-semibold">
                        ${child.first_name ?? ''} ${child.last_name ?? ''}
                    </div>
                    <div class="text-sm text-gray-500">
                        Student ID: ${child.student_code}
                    </div>
                </div>

                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                    Child ${index + 1}
                </span>
            </div>
        `;
    });

    container.innerHTML = html;
}

// async function loadBillingData(parentId) {
//     showLoading('billing');
//     try {
//         const response = await fetch(`/api/parents/${parentId}/billing`);
//         if (!response.ok) throw new Error('Failed to load billing data');

//         const data = await response.json();

//         const content = document.getElementById('billingContent');
//         if (content) {
//             content.innerHTML = `
//                 <div class="billing-summary">
//                     <div class="summary-card warning">
//                         <h4>Total Outstanding</h4>
//                         <div class="amount">₱${numberFormat(data.total_outstanding)}</div>
//                     </div>
//                     <div class="summary-card success">
//                         <h4>Paid This Month</h4>
//                         <div class="amount">₱${numberFormat(data.total_paid_this_month)}</div>
//                     </div>
//                     <div class="summary-card danger">
//                         <h4>Overdue</h4>
//                         <div class="amount">₱${numberFormat(data.overdue_amount)}</div>
//                     </div>
//                 </div>

//                 <h4 class="mt-4 mb-2">Invoices by Student</h4>
//                 ${data.invoices_by_student?.map(student => `
//                     <div class="student-invoices">
//                         <h5>${student.student_name}</h5>
//                         <table class="invoice-table">
//                             <thead>
//                                 <tr>
//                                     <th>Invoice #</th>
//                                     <th>Period</th>
//                                     <th>Amount</th>
//                                     <th>Due Date</th>
//                                     <th>Status</th>
//                                 </tr>
//                             </thead>
//                             <tbody>
//                                 ${student.invoices?.map(inv => `
//                                     <tr>
//                                         <td>${inv.invoice_no}</td>
//                                         <td>${inv.billing_period}</td>
//                                         <td>₱${numberFormat(inv.total_due)}</td>
//                                         <td>${inv.due_date}</td>
//                                         <td><span class="badge badge-${inv.status}">${inv.status}</span></td>
//                                     </tr>
//                                 `).join('')}
//                             </tbody>
//                         </table>
//                     </div>
//                 `).join('')}
//             `;
//         }

//         hideLoading('billing');
//     } catch (error) {
//         console.error('Error loading billing:', error);
//         showError('billing', 'Failed to load billing data');
//     }
// }

// async function loadPaymentsData(parentId) {
//     showLoading('payments');
//     try {
//         const response = await fetch(`/api/parents/${parentId}/payments`);
//         if (!response.ok) throw new Error('Failed to load payments data');

//         const data = await response.json();

//         const content = document.getElementById('paymentsContent');
//         if (content) {
//             content.innerHTML = `
//                 <table class="payment-table">
//                     <thead>
//                         <tr>
//                             <th>Date</th>
//                             <th>Student</th>
//                             <th>Amount</th>
//                             <th>Method</th>
//                             <th>Reference</th>
//                             <th>Received By</th>
//                         </tr>
//                     </thead>
//                     <tbody>
//                         ${data.payments?.data?.map(payment => `
//                             <tr>
//                                 <td>${new Date(payment.paid_at).toLocaleDateString()}</td>
//                                 <td>${payment.invoice?.student?.first_name || 'N/A'}</td>
//                                 <td>₱${numberFormat(payment.amount)}</td>
//                                 <td><span class="badge badge-info">${payment.payment_method}</span></td>
//                                 <td>${payment.reference_no || '-'}</td>
//                                 <td>${payment.received_by?.name || 'System'}</td>
//                             </tr>
//                         `).join('')}
//                     </tbody>
//                 </table>
//             `;
//         }

//         hideLoading('payments');
//     } catch (error) {
//         console.error('Error loading payments:', error);
//         showError('payments', 'Failed to load payment history');
//     }
// }

// function loadChatMessages(messages) {
//     const container = document.getElementById('chatMessages');
//     if (!container) return;

//     // Get current user ID from a meta tag or data attribute
//     const currentUserId = document.querySelector('meta[name="user-id"]')?.content || 0;

//     container.innerHTML = messages?.map(msg => `
//         <div class="message ${msg.sender_user_id == currentUserId ? 'own' : 'other'}">
//             <div class="message-bubble">
//                 <div class="message-sender">${msg.sender?.name || 'Unknown'}</div>
//                 <div class="message-text">${escapeHtml(msg.message)}</div>
//                 <div class="message-time">${new Date(msg.sent_at).toLocaleTimeString()}</div>
//             </div>
//         </div>
//     `).join('') || '';

//     container.scrollTop = container.scrollHeight;
// }

// function startChatRefresh() {
//     if (chatRefreshInterval) clearInterval(chatRefreshInterval);

//     // Refresh every 5 seconds when chat tab is active
//     chatRefreshInterval = setInterval(() => {
//         if (currentParentId && currentThreadId) {
//             refreshChat();
//         }
//     }, 5000);
// }

// async function refreshChat() {
//     try {
//         const response = await fetch(`/api/chat-threads/${currentThreadId}/messages`);
//         if (!response.ok) throw new Error('Failed to refresh chat');

//         const data = await response.json();
//         loadChatMessages(data.messages);
//     } catch (error) {
//         console.error('Error refreshing chat:', error);
//     }
// }

// async function loadActivityData(parentId) {
//     showLoading('activity');
//     try {
//         const response = await fetch(`/api/parents/${parentId}/activity`);
//         if (!response.ok) throw new Error('Failed to load activity data');

//         const data = await response.json();

//         const content = document.getElementById('activityContent');
//         if (content) {
//             content.innerHTML = `
//                 <div class="activity-timeline">
//                     ${data.logs?.map(log => `
//                         <div class="activity-item">
//                             <div class="activity-icon">
//                                 <i class="fas ${getActivityIcon(log.action)}"></i>
//                             </div>
//                             <div class="activity-content">
//                                 <p class="activity-title">${log.description}</p>
//                                 <p class="activity-meta">
//                                     ${log.entity} • ${log.user} • ${new Date(log.timestamp).toLocaleString()}
//                                 </p>
//                             </div>
//                         </div>
//                     `).join('')}
//                 </div>
//             `;
//         }

//         hideLoading('activity');
//     } catch (error) {
//         console.error('Error loading activity:', error);
//         showError('activity', 'Failed to load activity log');
//     }
// }

// async function loadNotificationsData(parentId) {
//     showLoading('notifications');
//     try {
//         const response = await fetch(`/api/parents/${parentId}/notifications`);
//         if (!response.ok) throw new Error('Failed to load notifications');

//         const data = await response.json();

//         // Update badge
//         const unreadCount = data.notifications?.filter(n => !n.read).length || 0;
//         const badge = document.getElementById('notifBadge');
//         if (badge) {
//             if (unreadCount > 0) {
//                 badge.textContent = unreadCount;
//                 badge.style.display = 'inline';
//             } else {
//                 badge.style.display = 'none';
//             }
//         }

//         const content = document.getElementById('notificationsContent');
//         if (content) {
//             content.innerHTML = data.notifications?.length ? data.notifications.map(notif => `
//                 <div class="notification-item ${notif.read ? 'read' : 'unread'}">
//                     <div class="notification-icon">
//                         <i class="fas ${getNotificationIcon(notif.type)}"></i>
//                     </div>
//                     <div class="notification-content">
//                         <h5>${notif.title}</h5>
//                         <p>${notif.message}</p>
//                         <span class="notification-time">${notif.time}</span>
//                     </div>
//                 </div>
//             `).join('') : '<p class="text-center text-gray-500">No notifications</p>';
//         }

//         hideLoading('notifications');
//     } catch (error) {
//         console.error('Error loading notifications:', error);
//         showError('notifications', 'Failed to load notifications');
//     }
// }

// // Utility functions
// function showLoading(tab) {
//     const loadingEl = document.getElementById(`${tab}Loading`);
//     const contentEl = document.getElementById(`${tab}Content`);

//     if (loadingEl) loadingEl.classList.remove('hidden');
//     if (contentEl) contentEl.classList.add('hidden');
// }

// function hideLoading(tab) {
//     const loadingEl = document.getElementById(`${tab}Loading`);
//     const contentEl = document.getElementById(`${tab}Content`);

//     if (loadingEl) loadingEl.classList.add('hidden');
//     if (contentEl) contentEl.classList.remove('hidden');
// }

// function showError(tab, message) {
//     const loadingEl = document.getElementById(`${tab}Loading`);
//     if (loadingEl) {
//         loadingEl.innerHTML = `<div class="text-red-500"><i class="fas fa-exclamation-circle"></i> ${message}</div>`;
//     }
// }

// function numberFormat(num) {
//     return parseFloat(num || 0).toLocaleString('en-PH', {
//         minimumFractionDigits: 2,
//         maximumFractionDigits: 2
//     });
// }

// function escapeHtml(text) {
//     if (!text) return '';
//     const div = document.createElement('div');
//     div.textContent = text;
//     return div.innerHTML;
// }

// function getActivityIcon(action) {
//     const icons = {
//         'create': 'fa-plus-circle',
//         'update': 'fa-edit',
//         'delete': 'fa-trash',
//         'payment': 'fa-money-bill',
//         'attendance': 'fa-calendar-check'
//     };
//     return icons[action] || 'fa-circle';
// }

// function getNotificationIcon(type) {
//     const icons = {
//         'chat': 'fa-comments',
//         'payment': 'fa-file-invoice',
//         'attendance': 'fa-calendar-check',
//         'announcement': 'fa-bullhorn',
//         'evaluation': 'fa-clipboard-check'
//     };
//     return icons[type] || 'fa-bell';
// }

// // Initialize everything when DOM is loaded
// document.addEventListener('DOMContentLoaded', function() {
//     console.log('Parents JS loaded');

//     // Close modal when clicking outside
//     window.onclick = function(e) {
//         const modal = document.getElementById("parentModal");
//         if (e.target === modal) {
//             closeModal();
//         }
//     }

//     // Enter key to send message
//     const chatInput = document.getElementById('chatInput');
//     if (chatInput) {
//         chatInput.addEventListener('keypress', function(e) {
//             if (e.key === 'Enter') sendMessage();
//         });
//     }

//     // Initialize DataTable
//     initializeParentDataTable();
// });

function initializeParentDataTable() {
    const parentTable = document.getElementById('parentTable');
    if (!parentTable) return;

    const tbody = parentTable.querySelector('tbody');
    const rows = tbody ? tbody.querySelectorAll('tr') : [];
    const hasData = rows.length > 0 && !rows[0].querySelector('td[colspan]');

    if (hasData && typeof simpleDatatables !== 'undefined') {
        try {
            const dataTable = new simpleDatatables.DataTable(parentTable, {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50, 100],
                searchable: true,
                sortable: true,
                labels: {
                    placeholder: "Search...",
                    perPage: "Entries per page",
                    noRows: "No parents found",
                    info: "Showing {start} to {end} of {rows} entries",
                    noResults: "No results match your search query"
                }
            });

            console.log('Parent DataTable initialized successfully');
        } catch (error) {
            console.error('Parent DataTable initialization failed:', error);
        }
    }
};

console.log("tang ina mo");

    </script>
    @vite("resources/js/parents.js")
    @vite(['resources/js/navbarDrop.js'])
</body>
</html>
