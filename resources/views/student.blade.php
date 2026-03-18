<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])
    @vite(['resources/css/student.css'])
    <title>Students</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fefefe;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #000;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            overflow-x: auto;
        }
        .tab {
            padding: 12px 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
            color: #6b7280;
            white-space: nowrap;
        }
        .tab.active {
            color: #1C1C1D;
            border-bottom: 2px solid #1C1C1D;
        }
        .tab:hover {
            color: #1C1C1D;
        }
        .tab-content {
            padding: 20px;
        }

        /* Table Styles */
        .student-table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-table th,
        .student-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .student-table th {
            background-color: #f9fafb;
            font-weight: 600;
        }

        /* Belt Colors */
        .belt {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .belt.white { background-color: #f3f4f6; color: #1f2937; }
        .belt.yellow { background-color: #fef3c7; color: #92400e; }
        .belt.green { background-color: #d1fae5; color: #065f46; }
        .belt.blue { background-color: #dbeafe; color: #1e40af; }
        .belt.red { background-color: #fee2e2; color: #991b1b; }
        .belt.black { background-color: #1f2937; color: #ffffff; }

        /* Status Badges */
        .status {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status.active { background-color: #d1fae5; color: #065f46; }
        .status.inactive { background-color: #f3f4f6; color: #6b7280; }
        .status.suspended { background-color: #fee2e2; color: #991b1b; }

        /* Balance */
        .balance.due { color: #dc2626; font-weight: 600; }
        .balance.paid { color: #16a34a; }

        /* Buttons */
        .btn-view {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        .btn-view:hover {
            text-decoration: underline;
        }

        /* Dropdown Filters */
        .dropdown {
            display: flex;
            flex-direction: column;
        }
        .dropdown label {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 4px;
            color: #374151;
        }
        .dropdown select {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
        }

        /* Chat Styles */
        .message {
            margin-bottom: 12px;
            display: flex;
        }
        .message.own {
            justify-content: flex-end;
        }
        .message.own .message-bubble {
            background-color: #3b82f6;
            color: white;
        }
        .message-bubble {
            background-color: #e5e7eb;
            padding: 8px 12px;
            border-radius: 12px;
            max-width: 70%;
        }

        /* Badges */
        .badge {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-present { background-color: #d1fae5; color: #065f46; }
        .badge-late { background-color: #fef3c7; color: #92400e; }
        .badge-absent { background-color: #fee2e2; color: #991b1b; }
        .badge-excused { background-color: #e0e7ff; color: #3730a3; }
        .badge-paid { background-color: #d1fae5; color: #065f46; }
        .badge-pending { background-color: #fef3c7; color: #92400e; }
        .badge-overdue { background-color: #fee2e2; color: #991b1b; }
        .badge-mastered { background-color: #d1fae5; color: #065f46; }
        .badge-in_progress { background-color: #dbeafe; color: #1e40af; }
        .badge-not_started { background-color: #f3f4f6; color: #6b7280; }
        .badge-primary { background-color: #3b82f6; color: white; }
        .badge-success { background-color: #10b981; color: white; }

        /* Content Card */
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .table-card {
            padding: 20px;
        }
    </style>
</head>
<body>
    @include("includes.navbar")
    @include('includes.sidebar')

    <div class="main-content">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-500 my-6">
            <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
            <span>/</span>
            <span class="text-[#1C1C1D] font-medium">Student</span>
        </div>

        <h1 class="text-4xl font-bold text-[#1C1C1D] mb-4">Student</h1>

        <div class="content-card">
            <!-- Header -->
            <div class="bg-[#1C1C1D] p-3 rounded-t-xl text-white font-semibold text-xl text-center">
                <h1>Student List</h1>
            </div>

            <!-- Filters -->
            <div class="grid grid-cols-4 gap-4 px-4 pt-4">
                <div class="dropdown">
                    <label for="belt-filter">Belt</label>
                    <select id="belt-filter" name="belt" onchange="filterStudents()">
                        <option value="">All Belts</option>
                        @foreach ($beltLevels as $belt)
                            <option value="{{ $belt->belt_level }}">{{ ucfirst($belt->belt_level) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="dropdown">
                    <label for="status-filter">Status</label>
                    <select id="status-filter" name="status" onchange="filterStudents()">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="dropdown">
                    <label for="class-filter">Class</label>
                    <select id="class-filter" name="class" onchange="filterStudents()">
                        <option value="">All Classes</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="dropdown">
                    <label for="instructor-filter">Instructor</label>
                    <select id="instructor-filter" name="instructor" onchange="filterStudents()">
                        <option value="">All Instructors</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}">{{ $instructor->user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-card">
                <div class="table-content">
                    <table class="student-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Belt</th>
                                <th>Status</th>
                                <th>Parent</th>
                                <th>Balance</th>
                                <th>Attendance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                            @forelse ($students as $student)
                                <tr data-student-id="{{ $student['id'] }}"
                                    data-belt="{{ $student['belt'] }}"
                                    data-status="{{ $student['status'] }}"
                                    data-class="{{ $student['class_id'] ?? '' }}"
                                    data-instructor="{{ $student['instructor_id'] ?? '' }}">
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @if($student['photo'])
                                                <img src="{{ asset('storage/' . $student['photo']) }}"
                                                     class="w-8 h-8 rounded-full object-cover"
                                                     alt="{{ $student['name'] }}">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-xs">
                                                    {{ strtoupper(substr($student['first_name'], 0, 1)) }}
                                                </div>
                                            @endif
                                            {{ $student['name'] }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="belt {{ strtolower($student['belt']) }}">
                                            {{ ucfirst($student['belt']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status {{ $student['status'] }}">
                                            {{ ucfirst($student['status']) }}
                                        </span>
                                    </td>
                                    <td>{{ $student['parent_name'] ?? 'N/A' }}</td>
                                    <td class="balance {{ $student['balance'] > 0 ? 'due' : 'paid' }}">
                                        ₱{{ number_format($student['balance'], 2) }}
                                    </td>
                                    <td>{{ $student['attendance_rate'] ?? 0 }}%</td>
                                    <td>
                                        <a href="#" class="btn-view" onclick="openModal({{ $student['id'] }})">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No students found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Modal -->
    <div id="studentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Student Profile</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" onclick="switchTab('profile')">Profile</button>
                <button class="tab" onclick="switchTab('attendance')">Attendance</button>
                <button class="tab" onclick="switchTab('billing')">Billing</button>
                <button class="tab" onclick="switchTab('competition')">Competition</button>
                <button class="tab" onclick="switchTab('certificates')">Certificates</button>
                <button class="tab" onclick="switchTab('progress')">Progress</button>
                <button class="tab" onclick="switchTab('chat')">Chat</button>
                <button class="tab" onclick="switchTab('documents')">Documents</button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="tabContent">
                <div id="tab-loading" class="text-center py-8">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
                <div id="tab-dynamic-content"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/dashboard.js'])

    <script>
        let currentStudentId = null;
        let currentThreadId = null;
        let chatRefreshInterval = null;
        let attendanceChart = null;
        const currentUserId = {{ Auth::id() ?? 'null' }};

        // Filter students based on dropdown selections
        function filterStudents() {
            const belt = document.getElementById('belt-filter').value;
            const status = document.getElementById('status-filter').value;
            const classId = document.getElementById('class-filter').value;
            const instructorId = document.getElementById('instructor-filter').value;

            const rows = document.querySelectorAll('#studentTableBody tr');

            rows.forEach(row => {
                let show = true;

                if (belt && row.dataset.belt !== belt) show = false;
                if (status && row.dataset.status !== status) show = false;
                if (classId && row.dataset.class !== classId) show = false;
                if (instructorId && row.dataset.instructor !== instructorId) show = false;

                row.style.display = show ? '' : 'none';
            });
        }

        // Open modal and load profile
        function openModal(studentId) {
            currentStudentId = studentId;
            document.getElementById("studentModal").style.display = "flex";
            document.body.style.overflow = 'hidden';

            // Reset tabs
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelector('.tab').classList.add('active');

            loadTabContent('profile');
        }

        // Close modal and cleanup
        function closeModal() {
            document.getElementById("studentModal").style.display = "none";
            document.body.style.overflow = 'auto';
            currentStudentId = null;
            currentThreadId = null;

            if (chatRefreshInterval) {
                clearInterval(chatRefreshInterval);
                chatRefreshInterval = null;
            }

            if (attendanceChart) {
                attendanceChart.destroy();
                attendanceChart = null;
            }
        }

        // Close modal on outside click
        window.onclick = function(e) {
            const modal = document.getElementById("studentModal");
            if (e.target === modal) {
                closeModal();
            }
        }

        // Switch between tabs
        function switchTab(tabName) {
            // Update active tab styling
            document.querySelectorAll(".tab").forEach(tab => {
                tab.classList.remove("active");
                if (tab.textContent.toLowerCase().includes(tabName) ||
                    (tabName === 'competition' && tab.textContent.includes('Competition'))) {
                    tab.classList.add("active");
                }
            });

            loadTabContent(tabName);
        }

        // Load content for selected tab
        async function loadTabContent(tabName) {
            const loadingDiv = document.getElementById('tab-loading');
            const contentDiv = document.getElementById('tab-dynamic-content');

            loadingDiv.style.display = 'block';
            contentDiv.innerHTML = '';

            if (!currentStudentId) return;

            try {
                let html = '';

                switch(tabName) {
                    case 'profile':
                        html = await loadProfile(currentStudentId);
                        break;
                    case 'attendance':
                        html = await loadAttendance(currentStudentId);
                        break;
                    case 'billing':
                        html = await loadBilling(currentStudentId);
                        break;
                    case 'competition':
                        html = await loadCompetitions(currentStudentId);
                        break;
                    case 'certificates':
                        html = await loadCertificates(currentStudentId);
                        break;
                    case 'progress':
                        html = await loadProgress(currentStudentId);
                        break;
                    case 'chat':
                        html = await loadChat(currentStudentId);
                        break;
                    case 'documents':
                        html = await loadDocuments(currentStudentId);
                        break;
                    default:
                        html = '<p>Select a tab to view content</p>';
                }

                loadingDiv.style.display = 'none';
                contentDiv.innerHTML = html;

                // Initialize charts if attendance tab
                if (tabName === 'attendance') {
                    initAttendanceChart();
                }

                // Start chat refresh if chat tab
                if (tabName === 'chat') {
                    startChatRefresh();
                } else if (chatRefreshInterval) {
                    clearInterval(chatRefreshInterval);
                    chatRefreshInterval = null;
                }

            } catch (error) {
                console.error('Error loading tab:', error);
                loadingDiv.innerHTML = '<p class="text-red-500">Error loading content. Please try again.</p>';
            }
        }

        // Load Profile Tab
        async function loadProfile(studentId) {
            const response = await fetch(`/api/students/${studentId}/profile`);
            const data = await response.json();
            const p = data.profile;

            return `
                <div class="profile-section">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p><strong>Student Code:</strong> ${p.student_code || 'N/A'}</p>
                            <p><strong>Name:</strong> ${p.name}</p>
                            <p><strong>Age:</strong> ${p.age || 'N/A'} years old</p>
                            <p><strong>Gender:</strong> ${p.gender || 'N/A'}</p>
                            <p><strong>Birthdate:</strong> ${p.birthdate || 'N/A'}</p>
                        </div>
                        <div>
                            <p><strong>Current Belt:</strong> <span class="belt ${(p.current_belt || 'white').toLowerCase()}">${p.current_belt || 'White'}</span></p>
                            <p><strong>Join Date:</strong> ${p.join_date || 'N/A'}</p>
                            <p><strong>Status:</strong> <span class="status ${p.status}">${p.status || 'N/A'}</span></p>
                            <p><strong>Emergency Contact:</strong> ${p.emergency_contact_name || 'N/A'}</p>
                            <p><strong>Emergency Mobile:</strong> ${p.emergency_contact_mobile || 'N/A'}</p>
                        </div>
                    </div>

                    <h4 class="font-bold mt-4 mb-2">Parents/Guardians</h4>
                    ${data.parents && data.parents.length ? data.parents.map(parent => `
                        <div class="parent-card mb-2 p-2 bg-gray-50 rounded">
                            <p><strong>${parent.name}</strong> (${parent.relationship || 'Guardian'})
                                ${parent.is_primary ? '<span class="badge badge-primary">Primary</span>' : ''}
                            </p>
                            <p class="text-sm">${parent.mobile || 'N/A'} • ${parent.email || 'N/A'}</p>
                        </div>
                    `).join('') : '<p>No parent information</p>'}

                    <h4 class="font-bold mt-4 mb-2">Current Classes</h4>
                    ${data.classes && data.classes.length ? data.classes.map(c => `
                        <div class="class-card mb-2 p-2 bg-gray-50 rounded">
                            <p><strong>${c.name}</strong> - ${c.level || 'N/A'}</p>
                            <p class="text-sm">Instructor: ${c.instructor || 'N/A'}</p>
                            <p class="text-sm">Schedule: ${c.schedule ? c.schedule.join(', ') : 'N/A'}</p>
                        </div>
                    `).join('') : '<p>Not enrolled in any classes</p>'}

                    ${data.subscription ? `
                        <h4 class="font-bold mt-4 mb-2">Subscription</h4>
                        <div class="p-2 bg-gray-50 rounded">
                            <p>Plan: ${data.subscription.plan || 'N/A'}</p>
                            <p>Status: ${data.subscription.status || 'N/A'}</p>
                            <p>Valid: ${data.subscription.start || 'N/A'} to ${data.subscription.end || 'Ongoing'}</p>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        // Load Attendance Tab
        async function loadAttendance(studentId) {
            const response = await fetch(`/api/students/${studentId}/attendance`);
            const data = await response.json();

            return `
                <div class="attendance-section">
                    <div class="stats-grid grid grid-cols-5 gap-2 mb-4">
                        <div class="stat-box text-center p-3 bg-blue-50 rounded">
                            <div class="text-2xl font-bold text-blue-600">${data.stats?.total_sessions || 0}</div>
                            <div class="text-xs">Total Sessions</div>
                        </div>
                        <div class="stat-box text-center p-3 bg-green-50 rounded">
                            <div class="text-2xl font-bold text-green-600">${data.stats?.present || 0}</div>
                            <div class="text-xs">Present</div>
                        </div>
                        <div class="stat-box text-center p-3 bg-yellow-50 rounded">
                            <div class="text-2xl font-bold text-yellow-600">${data.stats?.late || 0}</div>
                            <div class="text-xs">Late</div>
                        </div>
                        <div class="stat-box text-center p-3 bg-red-50 rounded">
                            <div class="text-2xl font-bold text-red-600">${data.stats?.absent || 0}</div>
                            <div class="text-xs">Absent</div>
                        </div>
                        <div class="stat-box text-center p-3 bg-gray-50 rounded">
                            <div class="text-2xl font-bold text-gray-600">${data.stats?.excused || 0}</div>
                            <div class="text-xs">Excused</div>
                        </div>
                    </div>

                    <div class="attendance-methods mb-4 p-2 bg-gray-50 rounded">
                        <p class="text-sm"><strong>Check-in Methods:</strong>
                            Face: ${data.stats?.by_method?.face || 0} |
                            QR: ${data.stats?.by_method?.qr || 0} |
                            Manual: ${data.stats?.by_method?.manual || 0}
                        </p>
                    </div>

                    <canvas id="attendanceChart" width="400" height="200"></canvas>

                    <h4 class="font-bold mt-4 mb-2">Recent Attendance</h4>
                    <div class="attendance-list max-h-48 overflow-y-auto">
                        ${data.logs && data.logs.data && data.logs.data.length ? data.logs.data.map(log => `
                            <div class="attendance-item flex justify-between p-2 border-b">
                                <span>${log.checkin_time ? new Date(log.checkin_time).toLocaleDateString() : 'N/A'}</span>
                                <span>${log.class_session?.class?.class_name || 'Unknown Class'}</span>
                                <span class="badge badge-${log.status}">${log.status || 'N/A'}</span>
                                <span class="text-xs text-gray-500">${log.method || 'manual'}</span>
                            </div>
                        `).join('') : '<p class="text-center text-gray-400">No attendance records</p>'}
                    </div>
                </div>
            `;
        }

        // Initialize Attendance Chart
        function initAttendanceChart() {
            const ctx = document.getElementById('attendanceChart');
            if (!ctx) return;

            if (attendanceChart) {
                attendanceChart.destroy();
            }

            // Sample data - replace with actual API data in production
            attendanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Present',
                        data: [12, 15, 14, 16, 13, 15],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)'
                    }, {
                        label: 'Absent',
                        data: [2, 1, 3, 1, 2, 1],
                        backgroundColor: 'rgba(239, 68, 68, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Load Billing Tab
        async function loadBilling(studentId) {
            const response = await fetch(`/api/students/${studentId}/billing`);
            const data = await response.json();

            return `
                <div class="billing-section">
                    <div class="billing-summary grid grid-cols-4 gap-2 mb-4">
                        <div class="summary-card p-3 bg-green-50 rounded text-center">
                            <div class="text-lg font-bold text-green-600">₱${numberFormat(data.summary?.total_paid || 0)}</div>
                            <div class="text-xs">Total Paid</div>
                        </div>
                        <div class="summary-card p-3 bg-yellow-50 rounded text-center">
                            <div class="text-lg font-bold text-yellow-600">₱${numberFormat(data.summary?.total_pending || 0)}</div>
                            <div class="text-xs">Pending</div>
                        </div>
                        <div class="summary-card p-3 bg-red-50 rounded text-center">
                            <div class="text-lg font-bold text-red-600">₱${numberFormat(data.summary?.total_overdue || 0)}</div>
                            <div class="text-xs">Overdue</div>
                        </div>
                        <div class="summary-card p-3 bg-blue-50 rounded text-center">
                            <div class="text-lg font-bold text-blue-600">₱${numberFormat(data.summary?.lifetime_total || 0)}</div>
                            <div class="text-xs">Lifetime Total</div>
                        </div>
                    </div>

                    ${data.current_subscription ? `
                        <div class="current-plan mb-4 p-3 bg-gray-50 rounded">
                            <h4 class="font-bold">Current Plan: ${data.current_subscription.plan || 'N/A'}</h4>
                            <p class="text-sm">₱${numberFormat(data.current_subscription.monthly_price || 0)}/month • ${data.current_subscription.status || 'N/A'}</p>
                        </div>
                    ` : ''}

                    <h4 class="font-bold mb-2">Invoice History</h4>
                    <div class="invoices-list max-h-64 overflow-y-auto">
                        ${data.invoices && data.invoices.length ? data.invoices.map(inv => `
                            <div class="invoice-item p-3 border-b ${inv.status === 'overdue' ? 'bg-red-50' : inv.status === 'paid' ? 'bg-green-50' : 'bg-yellow-50'}">
                                <div class="flex justify-between">
                                    <span class="font-bold">${inv.invoice_no || 'N/A'}</span>
                                    <span class="badge badge-${inv.status}">${inv.status || 'N/A'}</span>
                                </div>
                                <p class="text-sm">Period: ${inv.period || 'N/A'}</p>
                                <p class="text-sm">Due: ${inv.due_date || 'N/A'} • Total: ₱${numberFormat(inv.total_due || 0)}</p>
                                ${inv.payments && inv.payments.length ? `
                                    <div class="payments mt-2 pl-4 border-l-2 border-green-400">
                                        ${inv.payments.map(p => `
                                            <p class="text-xs">✓ Paid ₱${numberFormat(p.amount || 0)} via ${p.method || 'N/A'} on ${p.date ? new Date(p.date).toLocaleDateString() : 'N/A'}</p>
                                        `).join('')}
                                    </div>
                                ` : ''}
                            </div>
                        `).join('') : '<p class="text-center text-gray-400">No invoices found</p>'}
                    </div>
                </div>
            `;
        }

        // Load Competitions Tab
        async function loadCompetitions(studentId) {
            const response = await fetch(`/api/students/${studentId}/competitions`);
            const data = await response.json();

            return `
                <div class="competition-section">
                    <div class="medal-stats grid grid-cols-4 gap-2 mb-4">
                        <div class="medal-box text-center p-3 bg-yellow-100 rounded">
                            <div class="text-2xl">🥇</div>
                            <div class="font-bold">${data.stats?.gold || 0}</div>
                            <div class="text-xs">Gold</div>
                        </div>
                        <div class="medal-box text-center p-3 bg-gray-100 rounded">
                            <div class="text-2xl">🥈</div>
                            <div class="font-bold">${data.stats?.silver || 0}</div>
                            <div class="text-xs">Silver</div>
                        </div>
                        <div class="medal-box text-center p-3 bg-orange-100 rounded">
                            <div class="text-2xl">🥉</div>
                            <div class="font-bold">${data.stats?.bronze || 0}</div>
                            <div class="text-xs">Bronze</div>
                        </div>
                        <div class="medal-box text-center p-3 bg-blue-50 rounded">
                            <div class="text-2xl">🏅</div>
                            <div class="font-bold">${data.stats?.total_competitions || 0}</div>
                            <div class="text-xs">Total</div>
                        </div>
                    </div>

                    <h4 class="font-bold mb-2">Competition History</h4>
                    <div class="competitions-list">
                        ${data.entries && data.entries.length ? data.entries.map(entry => `
                            <div class="competition-card p-3 border-b ${entry.medal && entry.medal !== 'none' ? 'bg-yellow-50' : ''}">
                                <div class="flex justify-between">
                                    <span class="font-bold">${entry.competition_name || 'Unknown'}</span>
                                    ${entry.medal && entry.medal !== 'none' ? `<span class="text-2xl">${getMedalEmoji(entry.medal)}</span>` : ''}
                                </div>
                                <p class="text-sm">${entry.location || 'N/A'} • ${entry.date || 'N/A'}</p>
                                <p class="text-sm">Category: ${entry.category || 'N/A'} • Division: ${entry.division || 'N/A'}</p>
                                <p class="text-sm">Result: ${entry.result || 'N/A'}</p>
                                <p class="text-xs text-gray-500">Instructor: ${entry.instructor || 'N/A'}</p>
                                ${entry.remarks ? `<p class="text-xs italic">"${entry.remarks}"</p>` : ''}
                            </div>
                        `).join('') : '<p class="text-center text-gray-400">No competition records</p>'}
                    </div>
                </div>
            `;
        }

        function getMedalEmoji(medal) {
            const emojis = { gold: '🥇', silver: '🥈', bronze: '🥉', none: '' };
            return emojis[medal] || '';
        }

        // Load Certificates Tab
        async function loadCertificates(studentId) {
            const response = await fetch(`/api/students/${studentId}/certificates`);
            const data = await response.json();

            return `
                <div class="certificates-section">
                    <div class="cert-stats grid grid-cols-3 gap-2 mb-4">
                        <div class="stat-box text-center p-2 bg-purple-50 rounded">
                            <div class="font-bold text-lg">${data.belt_promotions || 0}</div>
                            <div class="text-xs">Belt Promotions</div>
                        </div>
                        <div class="stat-box text-center p-2 bg-blue-50 rounded">
                            <div class="font-bold text-lg">${data.competition_certs || 0}</div>
                            <div class="text-xs">Competition</div>
                        </div>
                        <div class="stat-box text-center p-2 bg-green-50 rounded">
                            <div class="font-bold text-lg">${data.participation_certs || 0}</div>
                            <div class="text-xs">Participation</div>
                        </div>
                    </div>

                    <div class="certificates-grid grid grid-cols-2 gap-3">
                        ${data.certificates && data.certificates.length ? data.certificates.map(cert => `
                            <div class="certificate-card p-4 border rounded-lg ${cert.certificate_type === 'belt_promotion' ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200'}">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h5 class="font-bold">${cert.title || 'Untitled'}</h5>
                                        <p class="text-xs text-gray-500">${cert.certificate_type || 'N/A'}</p>
                                    </div>
                                    <i class="fas fa-certificate text-2xl ${cert.certificate_type === 'belt_promotion' ? 'text-yellow-500' : 'text-gray-400'}"></i>
                                </div>
                                <p class="text-sm mt-2">${cert.description || ''}</p>
                                <p class="text-xs mt-2">Issued: ${cert.issued_date || 'N/A'} by ${cert.issued_by || 'N/A'}</p>
                                <div class="mt-3 flex gap-2">
                                    ${cert.pdf_path ? `<a href="${cert.pdf_path}" target="_blank" class="text-xs text-blue-600"><i class="fas fa-download"></i> Download</a>` : ''}
                                    ${cert.qr_code_value ? `<span class="text-xs text-gray-400">QR: ${cert.qr_code_value.substring(0, 8)}...</span>` : ''}
                                </div>
                            </div>
                        `).join('') : '<p class="text-center text-gray-400 col-span-2">No certificates found</p>'}
                    </div>
                </div>
            `;
        }

        // Load Progress Tab
        async function loadProgress(studentId) {
            const response = await fetch(`/api/students/${studentId}/progress`);
            const data = await response.json();
            const progressPercent = data.progress_summary?.percentage || 0;

            return `
                <div class="progress-section">
                    <div class="belt-status mb-4 p-4 bg-gradient-to-r from-yellow-100 to-orange-100 rounded-lg">
                        <h3 class="font-bold text-lg">Current Belt: ${data.current_belt || 'White'}</h3>
                        <div class="progress-bar mt-2 bg-gray-200 rounded-full h-4">
                            <div class="progress-fill bg-green-500 h-4 rounded-full transition-all duration-500" style="width: ${progressPercent}%"></div>
                        </div>
                        <p class="text-sm mt-1">${progressPercent}% Complete - ${data.progress_summary?.mastered || 0}/${data.progress_summary?.total || 0} skills mastered</p>
                    </div>

                    <div class="skills-grid grid grid-cols-2 gap-3 mb-4">
                        <div class="skill-stat p-3 bg-green-50 rounded text-center">
                            <div class="text-2xl font-bold text-green-600">${data.progress_summary?.mastered || 0}</div>
                            <div class="text-xs">Mastered</div>
                        </div>
                        <div class="skill-stat p-3 bg-blue-50 rounded text-center">
                            <div class="text-2xl font-bold text-blue-600">${data.progress_summary?.in_progress || 0}</div>
                            <div class="text-xs">In Progress</div>
                        </div>
                    </div>

                    <h4 class="font-bold mb-2">Skill Checklist</h4>
                    <div class="skills-list max-h-48 overflow-y-auto">
                        ${data.skills && data.skills.length ? data.skills.map(skill => `
                            <div class="skill-item flex justify-between items-center p-2 border-b">
                                <div>
                                    <p class="font-medium ${skill.status === 'mastered' ? 'text-green-600' : skill.status === 'in_progress' ? 'text-blue-600' : 'text-gray-400'}">
                                        ${skill.status === 'mastered' ? '✓' : skill.status === 'in_progress' ? '◐' : '○'} ${skill.skill_name || 'Unnamed Skill'}
                                    </p>
                                    <p class="text-xs text-gray-500">${skill.description || ''}</p>
                                </div>
                                <span class="badge badge-${skill.status}">${skill.status ? skill.status.replace('_', ' ') : 'N/A'}</span>
                            </div>
                        `).join('') : '<p class="text-center text-gray-400">No skills recorded</p>'}
                    </div>

                    ${data.evaluations && data.evaluations.length ? `
                        <h4 class="font-bold mt-4 mb-2">Recent Evaluations</h4>
                        ${data.evaluations.map(eval => `
                            <div class="evaluation-card p-3 bg-gray-50 rounded mb-2">
                                <p class="text-sm"><strong>${eval.evaluation_date || 'N/A'}</strong> by ${eval.instructor || 'N/A'}</p>
                                <div class="grid grid-cols-4 gap-2 mt-2 text-center text-xs">
                                    <div>Technique: ${eval.technique_score || '-'}/10</div>
                                    <div>Discipline: ${eval.discipline_score || '-'}/10</div>
                                    <div>Fitness: ${eval.fitness_score || '-'}/10</div>
                                    <div>Sparring: ${eval.sparring_score || '-'}/10</div>
                                </div>
                                ${eval.belt_ready_flag ? '<span class="badge badge-success mt-2">Ready for next belt!</span>' : ''}
                            </div>
                        `).join('')}
                    ` : ''}

                    ${data.exam_history && data.exam_history.length ? `
                        <h4 class="font-bold mt-4 mb-2">Belt Exam History</h4>
                        ${data.exam_history.map(exam => `
                            <div class="exam-item p-2 border-l-4 ${exam.result === 'pass' ? 'border-green-500 bg-green-50' : exam.result === 'fail' ? 'border-red-500 bg-red-50' : 'border-yellow-500 bg-yellow-50'}">
                                <p class="font-medium">${exam.belt_level || 'N/A'} Belt Exam - ${exam.result ? exam.result.toUpperCase() : 'N/A'}</p>
                                <p class="text-xs">${exam.exam_date || 'N/A'} • Score: ${exam.score || 'N/A'} • By: ${exam.approved_by || 'N/A'}</p>
                            </div>
                        `).join('')}
                    ` : ''}
                </div>
            `;
        }

        // Load Chat Tab
        async function loadChat(studentId) {
            const response = await fetch(`/api/students/${studentId}`);
            const data = await response.json();

            currentThreadId = data.chat_thread_id;

            const messagesHtml = data.chat_messages && data.chat_messages.length ?
                data.chat_messages.map(msg => `
                    <div class="message ${msg.sender_user_id === currentUserId ? 'own' : 'other'}">
                        <div class="message-bubble">
                            <strong>${msg.sender?.name || 'Unknown'}:</strong> ${msg.message || ''}
                            <div class="text-xs text-gray-400 mt-1">${msg.sent_at ? new Date(msg.sent_at).toLocaleTimeString() : 'N/A'}</div>
                        </div>
                    </div>
                `).join('') : '<p class="text-center text-gray-400">No messages yet</p>';

            return `
                <div class="chat-section">
                    <div class="chat-messages h-64 overflow-y-auto border rounded p-3 mb-3 bg-gray-50" id="chatMessages">
                        ${messagesHtml}
                    </div>
                    <div class="chat-input flex gap-2">
                        <input type="text" id="chatInput" class="flex-1 border rounded px-3 py-2" placeholder="Type a message..." maxlength="1000">
                        <button onclick="sendChatMessage()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Send</button>
                    </div>
                </div>
            `;
        }

        // Start chat message refresh
        function startChatRefresh() {
            if (chatRefreshInterval) clearInterval(chatRefreshInterval);

            chatRefreshInterval = setInterval(async () => {
                if (currentStudentId && currentThreadId) {
                    try {
                        const response = await fetch(`/api/chat-threads/${currentThreadId}/messages`);
                        const data = await response.json();
                        const container = document.getElementById('chatMessages');

                        if (container && data.messages) {
                            container.innerHTML = data.messages.map(msg => `
                                <div class="message ${msg.sender_user_id === currentUserId ? 'own' : 'other'}">
                                    <div class="message-bubble">
                                        <strong>${msg.sender?.name || 'Unknown'}:</strong> ${msg.message || ''}
                                        <div class="text-xs text-gray-400 mt-1">${msg.sent_at ? new Date(msg.sent_at).toLocaleTimeString() : 'N/A'}</div>
                                    </div>
                                </div>
                            `).join('');
                            container.scrollTop = container.scrollHeight;
                        }
                    } catch (error) {
                        console.error('Error refreshing chat:', error);
                    }
                }
            }, 5000);
        }

        // Send chat message
        async function sendChatMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();

            if (!message || !currentStudentId) return;

            try {
                const response = await fetch(`/api/students/${currentStudentId}/send-message`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message })
                });

                if (response.ok) {
                    input.value = '';
                    // Trigger immediate refresh
                    if (currentThreadId) {
                        const refreshResponse = await fetch(`/api/chat-threads/${currentThreadId}/messages`);
                        const data = await refreshResponse.json();
                        const container = document.getElementById('chatMessages');

                        if (container && data.messages) {
                            container.innerHTML = data.messages.map(msg => `
                                <div class="message ${msg.sender_user_id === currentUserId ? 'own' : 'other'}">
                                    <div class="message-bubble">
                                        <strong>${msg.sender?.name || 'Unknown'}:</strong> ${msg.message || ''}
                                        <div class="text-xs text-gray-400 mt-1">${msg.sent_at ? new Date(msg.sent_at).toLocaleTimeString() : 'N/A'}</div>
                                    </div>
                                </div>
                            `).join('');
                            container.scrollTop = container.scrollHeight;
                        }
                    }
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        }

        // Enter key to send message
        document.addEventListener('keypress', function(e) {
            if (e.target.id === 'chatInput' && e.key === 'Enter') {
                sendChatMessage();
            }
        });

        // Load Documents Tab
        async function loadDocuments(studentId) {
            const response = await fetch(`/api/students/${studentId}/documents`);
            const data = await response.json();

            const availableDocs = data.available_documents || {
                'enrollment_form': 'Enrollment Form',
                'waiver': 'Waiver Form',
                'medical_clearance': 'Medical Clearance',
                'belt_certificate': 'Belt Certificate'
            };

            return `
                <div class="documents-section">
                    <h4 class="font-bold mb-2">Available Documents</h4>
                    <div class="documents-list">
                        ${Object.entries(availableDocs).map(([key, label]) => `
                            <div class="document-item flex justify-between items-center p-3 border-b">
                                <span><i class="fas fa-file-alt mr-2"></i> ${label}</span>
                                <button onclick="generateDocument('${key}')" class="text-sm text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-plus"></i> Generate
                                </button>
                            </div>
                        `).join('')}
                    </div>

                    ${data.generated_documents && data.generated_documents.length ? `
                        <h4 class="font-bold mt-4 mb-2">Generated Documents</h4>
                        ${data.generated_documents.map(doc => `
                            <div class="generated-doc p-2 bg-gray-50 rounded mb-2">
                                <p class="font-medium">${doc.type || 'Unknown'}</p>
                                <p class="text-xs">Generated on ${doc.generated_at ? new Date(doc.generated_at).toLocaleString() : 'N/A'} by ${doc.generated_by || 'N/A'}</p>
                            </div>
                        `).join('')}
                    ` : ''}
                </div>
            `;
        }

        // Generate document (placeholder)
        function generateDocument(type) {
            alert(`Generating ${type} document... (Feature to be implemented)`);
        }

        // Number formatter for PHP currency
        function numberFormat(num) {
            return parseFloat(num).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    </script>
</body>
</html>
