@php
    $branding = \App\Models\Branding::first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(isset($branding) && $branding->logo_path)
        <link rel="icon" href="{{ Storage::url($branding->logo_path) }}">
    @endif
    <title>TrainNova | Parent Management </title>
    @vite(['resources/css/app.css'])
    @vite(['resources/css/parent.css'])
    @vite(['resources/css/student.css'])
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <div class="avatar-circle large" id="modalAvatar"></div>
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
                        {{-- <i class="fas fa-spinner fa-spin"></i> Loading children data... --}}
                    </div>
                    <div id="childrenContent"  class=" mb-2 cursor-pointer hover:bg-gray-100">
                        <!-- Dynamic content loaded here -->
                    </div>
                </div>

                <!-- Family Billing Tab -->
                <div id="billing" class="tab-pane">

                    <div id="billingContent" class="hidden">
                        <!-- Dynamic content loaded here -->
                    </div>
                </div>

                <!-- Payments Tab -->
                <div id="payments" class="tab-pane">

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

                    <div id="activityContent" class="hidden">
                        <!-- Dynamic content loaded here -->
                    </div>
                </div>

                <!-- Notifications Tab -->
                <div id="notifications" class="tab-pane">

                    <div id="notificationsContent" class="hidden">
                        <!-- Dynamic content loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================= -->
<!-- STUDENT MODAL -->
<!-- ========================= -->
<div id="studentModal" class="modal hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg p-6 w-3/4 max-w-3xl relative">

        <div class="modal-header flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Student Profile</h2>
            <span class="cursor-pointer text-xl font-bold" onclick="closeStudentModal()">&times;</span>
        </div>

        <div class="tabs flex gap-2 mb-4 border-b">
            <button class="tab active px-4 py-2 bg-blue-600 text-gray-500 rounded-t font-medium"
                data-tab="profile" onclick="switchStudentTab(this)">Profile</button>

            <button class="tab px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-t font-medium"
                data-tab="attendance" onclick="switchStudentTab(this)">Attendance</button>

            <button class="tab px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-t font-medium"
                data-tab="billing" onclick="switchStudentTab(this)">Billing</button>

            <button class="tab px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-t font-medium"
                data-tab="competition" onclick="switchStudentTab(this)">Competition</button>

            <button class="tab px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-t font-medium"
                data-tab="certificates" onclick="switchStudentTab(this)">Certificates</button>
        </div>

        <!-- PROFILE -->
        <div id="profileTab" class="tab-content block"></div>

        <!-- ATTENDANCE -->
        <div id="attendanceTab" class="tab-content hidden">
            <div class="attendance-summary grid grid-cols-3 gap-4 mb-4">
                <div class="stat-card p-3 bg-gray-100 rounded text-center">
                    <span class="block text-sm text-gray-500">Total Sessions</span>
                    <span id="totalSessions" class="text-xl font-bold">0</span>
                </div>
                <div class="stat-card p-3 bg-green-50 rounded text-center text-green-700">
                    <span class="block text-sm">Present</span>
                    <span id="presentCount" class="text-xl font-bold">0</span>
                </div>
                <div class="stat-card p-3 bg-yellow-50 rounded text-center text-yellow-700">
                    <span class="block text-sm">Late</span>
                    <span id="lateCount" class="text-xl font-bold">0</span>
                </div>
                <div class="stat-card p-3 bg-red-50 rounded text-center text-red-700">
                    <span class="block text-sm">Absent</span>
                    <span id="absentCount" class="text-xl font-bold">0</span>
                </div>
                <div class="stat-card p-3 bg-amber-50 rounded text-center text-amber-700">
                    <span class="block text-sm">Excused</span>
                    <span id="excusedCount" class="text-xl font-bold">0</span>
                </div>
            </div>

            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Method</th>
                    </tr>
                </thead>
                <tbody id="attendanceTableBody"></tbody>
            </table>
        </div>

        <!-- OTHER TABS -->
        <div id="billingTab" class="tab-content hidden"></div>
        <div id="competitionTab" class="tab-content hidden"></div>
        <div id="certificatesTab" class="tab-content hidden"></div>
    </div>
</div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

<script>
let currentParentId = null;

// =========================
// OPEN MODAL
// =========================
window.openModal = async function(parentId) {

    currentParentId = parentId;

    const modal = document.getElementById("parentModal");
    modal.style.display = "flex";

    document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
    document.querySelectorAll(".tab-pane").forEach(p => p.classList.remove("active"));

    document.querySelector('[data-tab="children"]').classList.add("active");
    document.getElementById("children").classList.add("active");

    try {
        const res = await fetch(`/parents/${parentId}`);
        const data = await res.json();

        document.getElementById("modalParentName").innerText = data.full_name;
        document.getElementById("modalParentContact").innerText =
            `${data.email} • ${data.mobile}`;

        loadChildren(data.students);

    } catch (err) {
        console.error(err);
    }
};

// =========================
// TAB SWITCH (FULLY WORKING)
// =========================
window.switchTab = function(tabName) {

    document.querySelectorAll(".tab").forEach(tab => {
        tab.classList.remove("active");
        if (tab.dataset.tab === tabName) tab.classList.add("active");
    });

    document.querySelectorAll(".tab-pane").forEach(p => p.classList.remove("active"));

    const pane = document.getElementById(tabName);
    if (!pane) return;

    pane.classList.add("active");

    if (!currentParentId) return;

    if (tabName === "billing") loadBilling(currentParentId);
    if (tabName === "payments") loadPayments(currentParentId);
    if (tabName === "chat") loadChat(currentParentId);
    if (tabName === "activity") loadActivity(currentParentId);
    if (tabName === "notifications") loadNotifications(currentParentId);
};

// =========================
// CHILDREN
// =========================
function loadChildren(students) {
    const container = document.getElementById("childrenContent");

    if (!students || students.length === 0) {
        container.innerHTML = "<p>No children found</p>";
        return;
    }

    let html = "";

    students.forEach((c, i) => {

        const id        = c.id || c.student_id;
        const firstName = c.first_name || c.student_name || '';
        const lastName  = c.last_name || '';
        const code      = c.student_code || 'N/A';

        html += `
            <div onclick="openStudentModal(${id})"
                class="p-3 border rounded mb-2 cursor-pointer hover:bg-gray-100">
                <div class="font-semibold">${firstName} ${lastName}</div>
                <div class="text-sm text-gray-500">ID: ${code}</div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// =========================
// BILLING
// =========================
async function loadBilling(parentId) {

    const container = document.getElementById("billingContent");
    container.innerHTML = "Loading...";
    container.classList.remove("hidden");

    const res = await fetch(`/parents/${parentId}/billing`);
    const data = await res.json();

    if (!data.length) {
        container.innerHTML = "No billing records";
        return;
    }

    let html = `
        <table class="min-w-full">
        <tr><th>Date</th><th>Student</th><th>Amount</th><th>Status</th></tr>
    `;

    data.forEach(b => {
        html += `
            <tr>
                <td>${new Date(b.created_at).toLocaleDateString()}</td>
                <td>${b.student_name ?? 'N/A'}</td>
                <td>₱${b.amount}</td>
                <td>${b.status}</td>
            </tr>
        `;
    });

    html += "</table>";
    container.innerHTML = html;
}

// =========================
// PAYMENTS
// =========================
async function loadPayments(parentId) {

    const container = document.getElementById("paymentsContent");
    container.innerHTML = "Loading...";
    container.classList.remove("hidden");

    const res = await fetch(`/parents/${parentId}/payments`);
    const data = await res.json();

    if (!data.length) {
        container.innerHTML = "No payments found";
        return;
    }

    let html = "";

    data.forEach(p => {
        html += `
            <div class="p-3 border mb-2 rounded">
                <div><b>₱${p.amount}</b></div>
                <div>${new Date(p.created_at).toLocaleDateString()}</div>
                <div class="text-sm text-gray-500">${p.method}</div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// =========================
// CHAT
// =========================
async function loadChat(parentId) {

    const container = document.getElementById("chatMessages");
    container.innerHTML = "Loading...";

    const res = await fetch(`/parents/${parentId}/chat`);
    const data = await res.json();

    let html = "";

    data.forEach(m => {
        html += `
            <div class="mb-2">
                <b>${m.sender}</b>: ${m.message}
            </div>
        `;
    });

    container.innerHTML = html;
}

// =========================
// ACTIVITY
// =========================
async function loadActivity(parentId) {

    const container = document.getElementById("activityContent");
    container.innerHTML = "Loading...";
    container.classList.remove("hidden");

    const res = await fetch(`/parents/${parentId}/activity`);
    const data = await res.json();

    let html = "";

    data.forEach(a => {
        html += `
            <div class="border-b p-2">
                ${a.description}
                <div class="text-xs text-gray-500">${a.created_at}</div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// =========================
// NOTIFICATIONS
// =========================
async function loadNotifications(parentId) {

    const container = document.getElementById("notificationsContent");
    container.innerHTML = "Loading...";
    container.classList.remove("hidden");

    const res = await fetch(`/parents/${parentId}/notifications`);
    const data = await res.json();

    let html = "";

    data.forEach(n => {
        html += `
            <div class="border-b p-2">
                ${n.message}
            </div>
        `;
    });

    container.innerHTML = html;
}

// =========================
// CLOSE
// =========================
window.closeModal = function() {
    document.getElementById("parentModal").style.display = "none";
};
</script>
<script>

// =========================
// OPEN STUDENT MODAL (FIXED)
// =========================
window.openStudentModal = function(studentId) {
    const modal = document.getElementById("studentModal");

    modal.classList.remove("hidden");
    modal.style.display = "flex";
    modal.dataset.studentId = studentId;

    // reset UI
    document.querySelectorAll("#studentModal .tab").forEach(t => t.classList.remove("active"));
    document.querySelector('#studentModal .tab[data-tab="profile"]').classList.add("active");

    document.querySelectorAll("#studentModal .tab-content").forEach(c => {
        c.classList.add("hidden");
        c.classList.remove("block");
    });

    document.getElementById("profileTab").classList.remove("hidden");
    document.getElementById("profileTab").classList.add("block");

    loadStudentProfile(studentId);
};

// =========================
// CLOSE
// =========================
function closeStudentModal() {
    const modal = document.getElementById("studentModal");
    modal.classList.add("hidden");
    modal.style.display = "none";
}

// =========================
// PROFILE (EXACT COPY)
// =========================
async function loadStudentProfile(studentId) {
    const tab = document.getElementById("profileTab");

    tab.innerHTML = `<p>Loading profile...</p>`;

    try {
        const res = await fetch(`/students/${studentId}`);
        const data = await res.json();

        const fullName =
            data.student_name ||
            `${data.first_name ?? ''} ${data.last_name ?? ''}`.trim();

        tab.innerHTML = `
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><strong>Name:</strong> ${fullName}</div>
                <div><strong>Belt:</strong> ${data.current_belt ?? 'N/A'}</div>
                <div><strong>Status:</strong> ${data.status ?? 'N/A'}</div>
                <div><strong>Parent:</strong> ${data.parent_name ?? 'N/A'}</div>
                <div>
  <strong>Birthdate:</strong>
  ${data.birthdate
    ? new Date(data.birthdate).toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric'
      })
    : 'N/A'}
</div>
                <div><strong>Gender:</strong> ${data.gender ?? 'N/A'}</div>
         <div>
  <strong>Join Date:</strong>
  ${data.join_date
        ? new Date(data.join_date).toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric'
      })
    : 'N/A'}
</div>
            </div>
        `;
    } catch {
        tab.innerHTML = "Failed to load profile";
    }
}

// =========================
// SWITCH TAB (EXACT LOGIC)
// =========================
function switchStudentTab(button) {
    document.querySelectorAll("#studentModal .tab").forEach(t => t.classList.remove("active"));
    button.classList.add("active");

    document.querySelectorAll("#studentModal .tab-content").forEach(c => {
        c.classList.add("hidden");
        c.classList.remove("block");
    });

    const tab = document.getElementById(button.dataset.tab + "Tab");
    tab.classList.remove("hidden");
    tab.classList.add("block");

    const studentId = document.getElementById("studentModal").dataset.studentId;

    if (button.dataset.tab === "attendance") loadAttendance(studentId);
    if (button.dataset.tab === "billing") loadBillingData(studentId);
    if (button.dataset.tab === "competition") loadCompetitionData(studentId);
    if (button.dataset.tab === "certificates") loadCertificatesData(studentId);
}

// =========================
// ATTENDANCE (SAME DATA)
// =========================
async function loadAttendance(studentId) {
    const tbody = document.getElementById("attendanceTableBody");
    tbody.innerHTML = `<tr><td colspan="5">Loading...</td></tr>`;

    try {
        const res = await fetch(`/students/${studentId}/attendance`);
        const response = await res.json();

        const logs = response.logs.data;
        const stats = response.stats;

        tbody.innerHTML = "";

        logs.forEach(log => {
            tbody.innerHTML += `
                <tr>
                    <td>${new Date(log.checkin_time).toLocaleDateString()}</td>
                    <td>${new Date(log.checkin_time).toLocaleTimeString()}</td>
                    <td>${log.checkout_time ? new Date(log.checkout_time).toLocaleTimeString() : '-'}</td>
                    <td class="${getStatusColor(log.attendance_status)}">${log.attendance_status ?? '-'}</td>
                    <td>${log.method ?? 'Manual'}</td>
                </tr>
            `;
        });

        document.getElementById("totalSessions").textContent = stats.total_sessions;
        document.getElementById("presentCount").textContent = stats.present;
        document.getElementById("absentCount").textContent = stats.absent;
        document.getElementById("lateCount").textContent = stats.late;
        document.getElementById("excusedCount").textContent = stats.excused;

    } catch {
        tbody.innerHTML = `<tr><td colspan="5">Error loading</td></tr>`;
    }
}


async function loadCompetitionData(studentId) {
    const tab = document.getElementById("competitionTab");
    tab.innerHTML = "Loading competition...";

    try {
        const res = await fetch(`/students/${studentId}/competition`);
        const data = await res.json();

        if (!data.length) {
            tab.innerHTML = "<p>No competition history</p>";
            return;
        }

        let html = "";

        data.forEach(c => {
            html += `
    <div class="p-4 bg-white rounded shadow mb-2">
        <div class="font-bold text-lg">${c.competition_name}</div>
        <div class="text-sm text-gray-500">${new Date(c.competition_date).toDateString()}</div>
        <div class="text-sm">Instructor: ${c.instructor_name}</div>
        <div class="mt-1 font-semibold">Result: ${c.result ?? 'Pending'}</div>
    </div>
`;
        });

        tab.innerHTML = html;

    } catch (err) {
        console.error(err);
        tab.innerHTML = "Failed to load competition";
    }
}

async function loadBillingData(studentId) {
    const tab = document.getElementById("billingTab");
    tab.innerHTML = "Loading billing...";

    try {
        const res = await fetch(`/students/${studentId}/billing`);
        const data = await res.json();

        if (!data.length) {
            tab.innerHTML = "<p>No billing records</p>";
            return;
        }

        let html = `
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
        `;

        data.forEach(b => {
            html += `
                <tr>
                    <td>${new Date(b.created_at).toLocaleDateString()}</td>
                    <td>₱${b.amount}</td>
                    <td>${b.status}</td>
                </tr>
            `;
        });

        html += `</tbody></table>`;
        tab.innerHTML = html;

    } catch (err) {
        tab.innerHTML = "Failed to load billing";
    }
}

async function loadCompetitionData(studentId) {
    const tab = document.getElementById("competitionTab");
    tab.innerHTML = "Loading competition...";

    try {
        const res = await fetch(`/students/${studentId}/competition`);
        const data = await res.json();

        if (!data.length) {
            tab.innerHTML = "<p>No competition history</p>";
            return;
        }

        let html = "";

        data.forEach(c => {
            html += `
    <div class="p-4 bg-white rounded shadow mb-2">
        <div class="font-bold text-lg">${c.competition_name}</div>
        <div class="text-sm text-gray-500">${new Date(c.competition_date).toDateString()}</div>
        <div class="text-sm">Instructor: ${c.instructor_name}</div>
        <div class="mt-1 font-semibold">Result: ${c.result ?? 'Pending'}</div>
    </div>
`;
        });

        tab.innerHTML = html;

    } catch (err) {
        console.error(err);
        tab.innerHTML = "Failed to load competition";
    }
}
async function loadCertificatesData(studentId) {
    const tab = document.getElementById("certificatesTab");
    tab.innerHTML = "Loading certificates...";

    try {
        const res = await fetch(`/students/${studentId}/certificates`);
        const data = await res.json();

        if (!data.length) {
            tab.innerHTML = "<p>No certificates</p>";
            return;
        }

        let html = "";

        data.forEach(cert => {
            html += `
                <div class="p-3 border-b">
                    <strong>${cert.title}</strong><br>
                    Issued: ${new Date(cert.created_at).toLocaleDateString()}
                </div>
            `;
        });

        tab.innerHTML = html;

    } catch (err) {
        tab.innerHTML = "Failed to load certificates";
    }
}


function getStatusColor(status) {
    const s = status ? status.toLowerCase() : '';
    switch (s) {
        case 'present': return 'text-green-600 ';
        case 'late':    return 'text-yellow-500 ';
        case 'absent':  return 'text-red-600 ';
        case 'excused': return 'text-amber-700 '; // Brownish/Orange
        default:        return 'text-gray-500';
    }
}

</script>
    @vite("resources/js/parents.js")
    @vite(['resources/js/navbarDrop.js'])
</body>
</html>
