<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Management - TKD System</title>

    @vite(['resources/css/app.css'])
    @vite(['resources/css/parent.css'])
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <h1 class="text-4xl font-bold text-[#1C1C1D] mb-4">Parent Management</h1>

    <div class="content-card">
        <!-- Page Title -->
        <div class="table-header flex justify-between items-center mb-4">
            <h2 class="report-header">Parent List</h2>
            <button onclick="openAddModal()" class="btn-primary">
                <i class="fas fa-plus"></i> Add Parent
            </button>
        </div>

        <!-- Parent Table -->
        <div class="table-card">
            <div class="table-content">
                <table class="parent-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Children</th>
                            <th>Contact</th>
                            <th>Total Balance</th>
                            <th>Status</th>
                            <th>ID Verified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($parentList as $parent)
                            <tr data-parent-id="{{ $parent['id'] }}">
                                <td>
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
                                <td>
                                    <span class="badge badge-info">
                                        <i class="fas fa-child"></i> {{ $parent['children_count'] }}
                                    </span>
                                </td>
                                <td>{{ $parent['mobile'] }}</td>
                                <td class="balance {{ $parent['total_balance'] > 0 ? 'due' : 'paid' }} text-end">
                                    ₱{{ number_format($parent['total_balance'], 2) }}
                                </td>
                                <td>
                                    <span class="status {{ $parent['status'] }}">
                                        {{ ucfirst($parent['status']) }}
                                    </span>
                                </td>
                                <td>
                                    @if($parent['id_verified'])
                                        <span class="badge badge-success"><i class="fas fa-check"></i> Verified</span>
                                    @else
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn-view" onclick="openModal({{ $parent['id'] }})">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">
                                    No parents found in the system
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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

@vite(['resources/js/dashboard.js'])

<script>
let currentParentId = null;
let currentThreadId = null;
let chatRefreshInterval = null;

function openModal(parentId) {
    currentParentId = parentId;
    document.getElementById("parentModal").style.display = "flex";
    document.body.style.overflow = 'hidden';

    // Load initial data
    loadParentData(parentId);
    switchTab('children');
}

function closeModal() {
    document.getElementById("parentModal").style.display = "none";
    document.body.style.overflow = 'auto';
    currentParentId = null;
    currentThreadId = null;

    if (chatRefreshInterval) {
        clearInterval(chatRefreshInterval);
    }
}

window.onclick = function(e) {
    const modal = document.getElementById("parentModal");
    if (e.target === modal) {
        closeModal();
    }
}

async function loadParentData(parentId) {
    try {
        const response = await fetch(`/api/parents/${parentId}`);
        const data = await response.json();

        // Update header
        document.getElementById('modalParentName').textContent = data.parent.user.name;
        document.getElementById('modalParentContact').textContent =
            `${data.parent.user.mobile} • ${data.parent.user.email}`;
        document.getElementById('modalAvatar').textContent =
            data.parent.user.name.charAt(0).toUpperCase();

        currentThreadId = data.chat_thread_id;

        // Load chat messages
        loadChatMessages(data.chat_messages);

    } catch (error) {
        console.error('Error loading parent data:', error);
    }
}

function switchTab(tabName) {
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
    document.getElementById(tabName).classList.add("active");

    // Load tab-specific data
    if (currentParentId) {
        switch(tabName) {
            case 'children':
                loadChildrenData(currentParentId);
                break;
            case 'billing':
                loadBillingData(currentParentId);
                break;
            case 'payments':
                loadPaymentsData(currentParentId);
                break;
            case 'chat':
                startChatRefresh();
                break;
            case 'activity':
                loadActivityData(currentParentId);
                break;
            case 'notifications':
                loadNotificationsData(currentParentId);
                break;
        }

        if (tabName !== 'chat' && chatRefreshInterval) {
            clearInterval(chatRefreshInterval);
        }
    }
}

async function loadChildrenData(parentId) {
    showLoading('children');
    try {
        const response = await fetch(`/api/parents/${parentId}/children`);
        const data = await response.json();

        const content = document.getElementById('childrenContent');
        content.innerHTML = data.children.map(child => `
            <div class="child-card">
                <div class="child-header">
                    <img src="${child.photo || '/images/default-avatar.png'}" class="child-photo" alt="${child.name}">
                    <div class="child-info">
                        <h3>${child.name} <span class="badge badge-${child.status}">${child.status}</span></h3>
                        <p>Student Code: ${child.student_code} • ${child.age} years old</p>
                        <p>Current Belt: <span class="belt-badge">${child.current_belt}</span></p>
                    </div>
                </div>
                <div class="child-stats">
                    <div class="stat">
                        <label>Classes</label>
                        <value>${child.classes.join(', ') || 'Not enrolled'}</value>
                    </div>
                    <div class="stat">
                        <label>Plan</label>
                        <value>${child.subscription}</value>
                    </div>
                    <div class="stat">
                        <label>Skills</label>
                        <value>${child.skills_mastered}/${child.total_skills} mastered</value>
                    </div>
                    <div class="stat">
                        <label>Attendance</label>
                        <value>${child.attendance_rate}%</value>
                    </div>
                </div>
            </div>
        `).join('');

        hideLoading('children');
    } catch (error) {
        console.error('Error loading children:', error);
        showError('children', 'Failed to load children data');
    }
}

async function loadBillingData(parentId) {
    showLoading('billing');
    try {
        const response = await fetch(`/api/parents/${parentId}/billing`);
        const data = await response.json();

        const content = document.getElementById('billingContent');
        content.innerHTML = `
            <div class="billing-summary">
                <div class="summary-card warning">
                    <h4>Total Outstanding</h4>
                    <div class="amount">₱${numberFormat(data.total_outstanding)}</div>
                </div>
                <div class="summary-card success">
                    <h4>Paid This Month</h4>
                    <div class="amount">₱${numberFormat(data.total_paid_this_month)}</div>
                </div>
                <div class="summary-card danger">
                    <h4>Overdue</h4>
                    <div class="amount">₱${numberFormat(data.overdue_amount)}</div>
                </div>
            </div>

            <h4 class="mt-4 mb-2">Invoices by Student</h4>
            ${data.invoices_by_student.map(student => `
                <div class="student-invoices">
                    <h5>${student.student_name}</h5>
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Period</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${student.invoices.map(inv => `
                                <tr>
                                    <td>${inv.invoice_no}</td>
                                    <td>${inv.billing_period}</td>
                                    <td>₱${numberFormat(inv.total_due)}</td>
                                    <td>${inv.due_date}</td>
                                    <td><span class="badge badge-${inv.status}">${inv.status}</span></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `).join('')}
        `;

        hideLoading('billing');
    } catch (error) {
        console.error('Error loading billing:', error);
        showError('billing', 'Failed to load billing data');
    }
}

async function loadPaymentsData(parentId) {
    showLoading('payments');
    try {
        const response = await fetch(`/api/parents/${parentId}/payments`);
        const data = await response.json();

        const content = document.getElementById('paymentsContent');
        content.innerHTML = `
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Received By</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.payments.data.map(payment => `
                        <tr>
                            <td>${new Date(payment.paid_at).toLocaleDateString()}</td>
                            <td>${payment.invoice.student.first_name}</td>
                            <td>₱${numberFormat(payment.amount)}</td>
                            <td><span class="badge badge-info">${payment.payment_method}</span></td>
                            <td>${payment.reference_no || '-'}</td>
                            <td>${payment.received_by?.name || 'System'}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;

        hideLoading('payments');
    } catch (error) {
        console.error('Error loading payments:', error);
        showError('payments', 'Failed to load payment history');
    }
}

function loadChatMessages(messages) {
    const container = document.getElementById('chatMessages');
    container.innerHTML = messages.map(msg => `
        <div class="message ${msg.sender_user_id === {{ Auth::id() }} ? 'own' : 'other'}">
            <div class="message-bubble">
                <div class="message-sender">${msg.sender.name}</div>
                <div class="message-text">${escapeHtml(msg.message)}</div>
                <div class="message-time">${new Date(msg.sent_at).toLocaleTimeString()}</div>
            </div>
        </div>
    `).join('');

    container.scrollTop = container.scrollHeight;
}

function startChatRefresh() {
    if (chatRefreshInterval) clearInterval(chatRefreshInterval);

    // Refresh every 5 seconds when chat tab is active
    chatRefreshInterval = setInterval(() => {
        if (currentParentId && currentThreadId) {
            refreshChat();
        }
    }, 5000);
}

async function refreshChat() {
    try {
        const response = await fetch(`/api/chat-threads/${currentThreadId}/messages`);
        const data = await response.json();
        loadChatMessages(data.messages);
    } catch (error) {
        console.error('Error refreshing chat:', error);
    }
}

async function sendMessage() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();

    if (!message || !currentParentId) return;

    try {
        const response = await fetch(`/api/parents/${currentParentId}/send-message`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message })
        });

        if (response.ok) {
            input.value = '';
            refreshChat();
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
    }
}

// Enter key to send
document.getElementById('chatInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') sendMessage();
});

async function loadActivityData(parentId) {
    showLoading('activity');
    try {
        const response = await fetch(`/api/parents/${parentId}/activity`);
        const data = await response.json();

        const content = document.getElementById('activityContent');
        content.innerHTML = `
            <div class="activity-timeline">
                ${data.logs.map(log => `
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas ${getActivityIcon(log.action)}"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-title">${log.description}</p>
                            <p class="activity-meta">
                                ${log.entity} • ${log.user} • ${new Date(log.timestamp).toLocaleString()}
                            </p>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;

        hideLoading('activity');
    } catch (error) {
        console.error('Error loading activity:', error);
        showError('activity', 'Failed to load activity log');
    }
}

async function loadNotificationsData(parentId) {
    showLoading('notifications');
    try {
        const response = await fetch(`/api/parents/${parentId}/notifications`);
        const data = await response.json();

        // Update badge
        const unreadCount = data.notifications.filter(n => !n.read).length;
        const badge = document.getElementById('notifBadge');
        if (unreadCount > 0) {
            badge.textContent = unreadCount;
            badge.style.display = 'inline';
        } else {
            badge.style.display = 'none';
        }

        const content = document.getElementById('notificationsContent');
        content.innerHTML = data.notifications.length ? data.notifications.map(notif => `
            <div class="notification-item ${notif.read ? 'read' : 'unread'}">
                <div class="notification-icon">
                    <i class="fas ${getNotificationIcon(notif.type)}"></i>
                </div>
                <div class="notification-content">
                    <h5>${notif.title}</h5>
                    <p>${notif.message}</p>
                    <span class="notification-time">${notif.time}</span>
                </div>
            </div>
        `).join('') : '<p class="text-center text-gray-500">No notifications</p>';

        hideLoading('notifications');
    } catch (error) {
        console.error('Error loading notifications:', error);
        showError('notifications', 'Failed to load notifications');
    }
}

// Utility functions
function showLoading(tab) {
    document.getElementById(`${tab}Loading`).classList.remove('hidden');
    document.getElementById(`${tab}Content`).classList.add('hidden');
}

function hideLoading(tab) {
    document.getElementById(`${tab}Loading`).classList.add('hidden');
    document.getElementById(`${tab}Content`).classList.remove('hidden');
}

function showError(tab, message) {
    document.getElementById(`${tab}Loading`).innerHTML =
        `<div class="text-red-500"><i class="fas fa-exclamation-circle"></i> ${message}</div>`;
}

function numberFormat(num) {
    return parseFloat(num).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getActivityIcon(action) {
    const icons = {
        'create': 'fa-plus-circle',
        'update': 'fa-edit',
        'delete': 'fa-trash',
        'payment': 'fa-money-bill',
        'attendance': 'fa-calendar-check'
    };
    return icons[action] || 'fa-circle';
}

function getNotificationIcon(type) {
    const icons = {
        'chat': 'fa-comments',
        'payment': 'fa-file-invoice',
        'attendance': 'fa-calendar-check',
        'announcement': 'fa-bullhorn',
        'evaluation': 'fa-clipboard-check'
    };
    return icons[type] || 'fa-bell';
}
</script>

</body>
</html>
