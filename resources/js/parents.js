// Make all functions globally available
let currentParentId = null;
let currentThreadId = null;
let chatRefreshInterval = null;

// Make functions global
window.openModal = function(parentId) {
    currentParentId = parentId;
    const modal = document.getElementById("parentModal");
    if (modal) {
        modal.style.display = "flex";
        document.body.style.overflow = 'hidden';

        // Load initial data
        loadParentData(parentId);
        switchTab('children');
    }
}

window.closeModal = function() {
    const modal = document.getElementById("parentModal");
    if (modal) {
        modal.style.display = "none";
        document.body.style.overflow = 'auto';
    }
    currentParentId = null;
    currentThreadId = null;

    if (chatRefreshInterval) {
        clearInterval(chatRefreshInterval);
        chatRefreshInterval = null;
    }
}

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
            chatRefreshInterval = null;
        }
    }
}

window.sendMessage = function() {
    const input = document.getElementById('chatInput');
    if (!input) return;
    
    const message = input.value.trim();

    if (!message || !currentParentId) return;

    fetch(`/api/parents/${currentParentId}/send-message`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ message })
    })
    .then(response => {
        if (response.ok) {
            input.value = '';
            refreshChat();
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to send message. Please try again.',
                confirmButtonColor: '#3085d6'
            });
        }
    });
}

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

async function loadChildrenData(parentId) {
    showLoading('children');
    try {
        const response = await fetch(`/api/parents/${parentId}/children`);
        if (!response.ok) throw new Error('Failed to load children data');
        
        const data = await response.json();

        const content = document.getElementById('childrenContent');
        if (content) {
            content.innerHTML = data.children?.map(child => `
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
                            <value>${child.classes?.join(', ') || 'Not enrolled'}</value>
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
            `).join('') || '<p class="text-center text-gray-500">No children found</p>';
        }

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
        if (!response.ok) throw new Error('Failed to load billing data');
        
        const data = await response.json();

        const content = document.getElementById('billingContent');
        if (content) {
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
                ${data.invoices_by_student?.map(student => `
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
                                ${student.invoices?.map(inv => `
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
        }

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
        if (!response.ok) throw new Error('Failed to load payments data');
        
        const data = await response.json();

        const content = document.getElementById('paymentsContent');
        if (content) {
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
                        ${data.payments?.data?.map(payment => `
                            <tr>
                                <td>${new Date(payment.paid_at).toLocaleDateString()}</td>
                                <td>${payment.invoice?.student?.first_name || 'N/A'}</td>
                                <td>₱${numberFormat(payment.amount)}</td>
                                <td><span class="badge badge-info">${payment.payment_method}</span></td>
                                <td>${payment.reference_no || '-'}</td>
                                <td>${payment.received_by?.name || 'System'}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }

        hideLoading('payments');
    } catch (error) {
        console.error('Error loading payments:', error);
        showError('payments', 'Failed to load payment history');
    }
}

function loadChatMessages(messages) {
    const container = document.getElementById('chatMessages');
    if (!container) return;
    
    // Get current user ID from a meta tag or data attribute
    const currentUserId = document.querySelector('meta[name="user-id"]')?.content || 0;
    
    container.innerHTML = messages?.map(msg => `
        <div class="message ${msg.sender_user_id == currentUserId ? 'own' : 'other'}">
            <div class="message-bubble">
                <div class="message-sender">${msg.sender?.name || 'Unknown'}</div>
                <div class="message-text">${escapeHtml(msg.message)}</div>
                <div class="message-time">${new Date(msg.sent_at).toLocaleTimeString()}</div>
            </div>
        </div>
    `).join('') || '';

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
        if (!response.ok) throw new Error('Failed to refresh chat');
        
        const data = await response.json();
        loadChatMessages(data.messages);
    } catch (error) {
        console.error('Error refreshing chat:', error);
    }
}

async function loadActivityData(parentId) {
    showLoading('activity');
    try {
        const response = await fetch(`/api/parents/${parentId}/activity`);
        if (!response.ok) throw new Error('Failed to load activity data');
        
        const data = await response.json();

        const content = document.getElementById('activityContent');
        if (content) {
            content.innerHTML = `
                <div class="activity-timeline">
                    ${data.logs?.map(log => `
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
        }

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
        if (!response.ok) throw new Error('Failed to load notifications');
        
        const data = await response.json();

        // Update badge
        const unreadCount = data.notifications?.filter(n => !n.read).length || 0;
        const badge = document.getElementById('notifBadge');
        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
            }
        }

        const content = document.getElementById('notificationsContent');
        if (content) {
            content.innerHTML = data.notifications?.length ? data.notifications.map(notif => `
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
        }

        hideLoading('notifications');
    } catch (error) {
        console.error('Error loading notifications:', error);
        showError('notifications', 'Failed to load notifications');
    }
}

// Utility functions
function showLoading(tab) {
    const loadingEl = document.getElementById(`${tab}Loading`);
    const contentEl = document.getElementById(`${tab}Content`);
    
    if (loadingEl) loadingEl.classList.remove('hidden');
    if (contentEl) contentEl.classList.add('hidden');
}

function hideLoading(tab) {
    const loadingEl = document.getElementById(`${tab}Loading`);
    const contentEl = document.getElementById(`${tab}Content`);
    
    if (loadingEl) loadingEl.classList.add('hidden');
    if (contentEl) contentEl.classList.remove('hidden');
}

function showError(tab, message) {
    const loadingEl = document.getElementById(`${tab}Loading`);
    if (loadingEl) {
        loadingEl.innerHTML = `<div class="text-red-500"><i class="fas fa-exclamation-circle"></i> ${message}</div>`;
    }
}

function numberFormat(num) {
    return parseFloat(num || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function escapeHtml(text) {
    if (!text) return '';
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

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Parents JS loaded');
    
    // Close modal when clicking outside
    window.onclick = function(e) {
        const modal = document.getElementById("parentModal");
        if (e.target === modal) {
            closeModal();
        }
    }

    // Enter key to send message
    const chatInput = document.getElementById('chatInput');
    if (chatInput) {
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });
    }

    // Initialize DataTable
    initializeParentDataTable();
});

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
}