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
</head>
<body>

@include("includes.navbar")
@include('includes.sidebar')

<div class="main-content">
    <div class="flex items-center gap-2 text-sm text-gray-500 my-6">
        <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
        <span>/</span>
        <span class="text-[#1C1C1D] font-medium">Student</span>
    </div>
    <h1 class="text-4xl font-bold text-[#1C1C1D] mb-4">Student</h1>
    <div class="content-card">
        <div class="bg-[#1C1C1D] p-3 rounded-t-xl text-white font-semibold text-xl text-center">
            <h1>Student List</h1>
        </div>
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
                                data-status="{{ $student['status'] }}">
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
                                <td><span class="belt {{ strtolower($student['belt']) }}">{{ ucfirst($student['belt']) }}</span></td>
                                <td><span class="status {{ $student['status'] }}">{{ ucfirst($student['status']) }}</span></td>
                                <td>{{ $student['parent_name'] }}</td>
                                <td class="balance {{ $student['balance'] > 0 ? 'due' : 'paid' }}">
                                    ₱{{ number_format($student['balance'], 0) }}
                                </td>
                                <td>{{ $student['attendance_rate'] }}%</td>
                                <td>
                                    <a href="#" class="btn-view" onclick="openModal({{ $student['id'] }})">View</a>
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

        <!-- Modal Overlay -->
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

        // Note: Class and instructor filtering would require additional data attributes
        // or AJAX call to server-side filter

        row.style.display = show ? '' : 'none';
    });
}

function openModal(studentId) {
    currentStudentId = studentId;
    document.getElementById("studentModal").style.display = "flex";
    document.body.style.overflow = 'hidden';

    // Reset tabs
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelector('.tab').classList.add('active');

    loadTabContent('profile');
}

function closeModal() {
    document.getElementById("studentModal").style.display = "none";
    document.body.style.overflow = 'auto';
    currentStudentId = null;
    currentThreadId = null;

    if (chatRefreshInterval) clearInterval(chatRefreshInterval);
    if (attendanceChart) {
        attendanceChart.destroy();
        attendanceChart = null;
    }
}

window.onclick = function(e) {
    const modal = document.getElementById("studentModal");
    if (e.target === modal) {
        closeModal();
    }
}

function switchTab(tabName) {
    // Update tab buttons
    document.querySelectorAll(".tab").forEach(tab => {
        tab.classList.remove("active");
        if (tab.textContent.toLowerCase().includes(tabName)) {
            tab.classList.add("active");
        }
    });

    loadTabContent(tabName);
}

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
        }

    } catch (error) {
        console.error('Error loading tab:', error);
        loadingDiv.innerHTML = '<p class="text-red-500">Error loading content. Please try again.</p>';
    }
}

async function loadProfile(studentId) {
    const response = await fetch(`/api/students/${studentId}/profile`);
    const data = await response.json();
    const p = data.profile;

    return `
        <div class="profile-section">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p><strong>Student Code:</strong> ${p.student_code}</p>
                    <p><strong>Name:</strong> ${p.name}</p>
                    <p><strong>Age:</strong> ${p.age} years old</p>
                    <p><strong>Gender:</strong> ${p.gender}</p>
                    <p><strong>Birthdate:</strong> ${p.birthdate}</p>
                </div>
                <div>
                    <p><strong>Current Belt:</strong> <span class="belt-badge">${p.current_belt}</span></p>
                    <p><strong>Join Date:</strong> ${p.join_date}</p>
                    <p><strong>Status:</strong> <span class="status-badge ${p.status}">${p.status}</span></p>
                    <p><strong>Emergency Contact:</strong> ${p.emergency_contact || 'N/A'}</p>
                    <p><strong>Emergency Mobile:</strong> ${p.emergency_mobile || 'N/A'}</p>
                </div>
            </div>

            <h4 class="font-bold mt-4 mb-2">Parents/Guardians</h4>
            ${data.parents.length ? data.parents.map(parent => `
                <div class="parent-card mb-2 p-2 bg-gray-50 rounded">
                    <p><strong>${parent.name}</strong> (${parent.relationship}) ${parent.is_primary ? '<span class="badge badge-primary">Primary</span>' : ''}</p>
                    <p class="text-sm">${parent.mobile} • ${parent.email}</p>
                </div>
            `).join('') : '<p>No parent information</p>'}

            <h4 class="font-bold mt-4 mb-2">Current Classes</h4>
            ${data.classes.length ? data.classes.map(c => `
                <div class="class-card mb-2 p-2 bg-gray-50 rounded">
                    <p><strong>${c.name}</strong> - ${c.level}</p>
                    <p class="text-sm">Instructor: ${c.instructor}</p>
                    <p class="text-sm">Schedule: ${c.schedule.join(', ')}</p>
                </div>
            `).join('') : '<p>Not enrolled in any classes</p>'}

            ${data.subscription ? `
                <h4 class="font-bold mt-4 mb-2">Subscription</h4>
                <p>Plan: ${data.subscription.plan}</p>
                <p>Status: ${data.subscription.status}</p>
                <p>Valid: ${data.subscription.start} to ${data.subscription.end || 'Ongoing'}</p>
            ` : ''}
        </div>
    `;
}

async function loadAttendance(studentId) {
    const response = await fetch(`/api/students/${studentId}/attendance`);
    const data = await response.json();

    return `
        <div class="attendance-section">
            <div class="stats-grid grid grid-cols-5 gap-2 mb-4">
                <div class="stat-box text-center p-3 bg-blue-50 rounded">
                    <div class="text-2xl font-bold text-blue-600">${data.stats.total_sessions}</div>
                    <div class="text-xs">Total Sessions</div>
                </div>
                <div class="stat-box text-center p-3 bg-green-50 rounded">
                    <div class="text-2xl font-bold text-green-600">${data.stats.present}</div>
                    <div class="text-xs">Present</div>
                </div>
                <div class="stat-box text-center p-3 bg-yellow-50 rounded">
                    <div class="text-2xl font-bold text-yellow-600">${data.stats.late}</div>
                    <div class="text-xs">Late</div>
                </div>
                <div class="stat-box text-center p-3 bg-red-50 rounded">
                    <div class="text-2xl font-bold text-red-600">${data.stats.absent}</div>
                    <div class="text-xs">Absent</div>
                </div>
                <div class="stat-box text-center p-3 bg-gray-50 rounded">
                    <div class="text-2xl font-bold text-gray-600">${data.stats.excused}</div>
                    <div class="text-xs">Excused</div>
                </div>
            </div>

            <div class="attendance-methods mb-4">
                <p class="text-sm"><strong>Check-in Methods:</strong>
                    Face: ${data.stats.by_method.face} |
                    QR: ${data.stats.by_method.qr} |
                    Manual: ${data.stats.by_method.manual}
                </p>
            </div>

            <canvas id="attendanceChart" width="400" height="200"></canvas>

            <h4 class="font-bold mt-4 mb-2">Recent Attendance</h4>
            <div class="attendance-list max-h-48 overflow-y-auto">
                ${data.logs.data.map(log => `
                    <div class="attendance-item flex justify-between p-2 border-b">
                        <span>${new Date(log.checkin_time).toLocaleDateString()}</span>
                        <span>${log.class_session?.class?.class_name || 'Unknown Class'}</span>
                        <span class="badge badge-${log.status}">${log.status}</span>
                        <span class="text-xs text-gray-500">${log.method}</span>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}

function initAttendanceChart() {
    const ctx = document.getElementById('attendanceChart');
    if (!ctx) return;

    // Sample data - in production, use actual monthly data
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
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

async function loadBilling(studentId) {
    const response = await fetch(`/api/students/${studentId}/billing`);
    const data = await response.json();

    return `
        <div class="billing-section">
            <div class="billing-summary grid grid-cols-4 gap-2 mb-4">
                <div class="summary-card p-3 bg-green-50 rounded text-center">
                    <div class="text-lg font-bold text-green-600">₱${numberFormat(data.summary.total_paid)}</div>
                    <div class="text-xs">Total Paid</div>
                </div>
                <div class="summary-card p-3 bg-yellow-50 rounded text-center">
                    <div class="text-lg font-bold text-yellow-600">₱${numberFormat(data.summary.total_pending)}</div>
                    <div class="text-xs">Pending</div>
                </div>
                <div class="summary-card p-3 bg-red-50 rounded text-center">
                    <div class="text-lg font-bold text-red-600">₱${numberFormat(data.summary.total_overdue)}</div>
                    <div class="text-xs">Overdue</div>
                </div>
                <div class="summary-card p-3 bg-blue-50 rounded text-center">
                    <div class="text-lg font-bold text-blue-600">₱${numberFormat(data.summary.lifetime_total)}</div>
                    <div class="text-xs">Lifetime Total</div>
                </div>
            </div>

            ${data.current_subscription ? `
                <div class="current-plan mb-4 p-3 bg-gray-50 rounded">
                    <h4 class="font-bold">Current Plan: ${data.current_subscription.plan}</h4>
                    <p class="text-sm">₱${numberFormat(data.current_subscription.monthly_price)}/month • ${data.current_subscription.status}</p>
                </div>
            ` : ''}

            <h4 class="font-bold mb-2">Invoice History</h4>
            <div class="invoices-list max-h-64 overflow-y-auto">
                ${data.invoices.map(inv => `
                    <div class="invoice-item p-3 border-b ${inv.status === 'overdue' ? 'bg-red-50' : inv.status === 'paid' ? 'bg-green-50' : 'bg-yellow-50'}">
                        <div class="flex justify-between">
                            <span class="font-bold">${inv.invoice_no}</span>
                            <span class="badge badge-${inv.status}">${inv.status}</span>
                        </div>
                        <p class="text-sm">Period: ${inv.period}</p>
                        <p class="text-sm">Due: ${inv.due_date} • Total: ₱${numberFormat(inv.total_due)}</p>
                        ${inv.payments.length ? `
                            <div class="payments mt-2 pl-4 border-l-2 border-green-400">
                                ${inv.payments.map(p => `
                                    <p class="text-xs">✓ Paid ₱${numberFormat(p.amount)} via ${p.method} on ${new Date(p.date).toLocaleDateString()}</p>
                                `).join('')}
                            </div>
                        ` : ''}
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}

async function loadCompetitions(studentId) {
    const response = await fetch(`/api/students/${studentId}/competitions`);
    const data = await response.json();

    return `
        <div class="competition-section">
            <div class="medal-stats grid grid-cols-4 gap-2 mb-4">
                <div class="medal-box text-center p-3 bg-yellow-100 rounded">
                    <div class="text-2xl">🥇</div>
                    <div class="font-bold">${data.stats.gold}</div>
                    <div class="text-xs">Gold</div>
                </div>
                <div class="medal-box text-center p-3 bg-gray-100 rounded">
                    <div class="text-2xl">🥈</div>
                    <div class="font-bold">${data.stats.silver}</div>
                    <div class="text-xs">Silver</div>
                </div>
                <div class="medal-box text-center p-3 bg-orange-100 rounded">
                    <div class="text-2xl">🥉</div>
                    <div class="font-bold">${data.stats.bronze}</div>
                    <div class="text-xs">Bronze</div>
                </div>
                <div class="medal-box text-center p-3 bg-blue-50 rounded">
                    <div class="text-2xl">🏅</div>
                    <div class="font-bold">${data.stats.total_competitions}</div>
                    <div class="text-xs">Total</div>
                </div>
            </div>

            <h4 class="font-bold mb-2">Competition History</h4>
            <div class="competitions-list">
                ${data.entries.length ? data.entries.map(entry => `
                    <div class="competition-card p-3 border-b ${entry.medal !== 'none' ? 'bg-yellow-50' : ''}">
                        <div class="flex justify-between">
                            <span class="font-bold">${entry.competition_name}</span>
                            ${entry.medal !== 'none' ? `<span class="text-2xl">${getMedalEmoji(entry.medal)}</span>` : ''}
                        </div>
                        <p class="text-sm">${entry.location} • ${entry.date}</p>
                        <p class="text-sm">Category: ${entry.category} • Division: ${entry.division}</p>
                        <p class="text-sm">Result: ${entry.result || 'N/A'}</p>
                        <p class="text-xs text-gray-500">Instructor: ${entry.instructor}</p>
                        ${entry.remarks ? `<p class="text-xs italic">"${entry.remarks}"</p>` : ''}
                    </div>
                `).join('') : '<p>No competition records</p>'}
            </div>
        </div>
    `;
}

function getMedalEmoji(medal) {
    const emojis = { gold: '🥇', silver: '🥈', bronze: '🥉', none: '' };
    return emojis[medal] || '';
}

async function loadCertificates(studentId) {
    const response = await fetch(`/api/students/${studentId}/certificates`);
    const data = await response.json();

    return `
        <div class="certificates-section">
            <div class="cert-stats grid grid-cols-3 gap-2 mb-4">
                <div class="stat-box text-center p-2 bg-purple-50 rounded">
                    <div class="font-bold text-lg">${data.belt_promotions}</div>
                    <div class="text-xs">Belt Promotions</div>
                </div>
                <div class="stat-box text-center p-2 bg-blue-50 rounded">
                    <div class="font-bold text-lg">${data.competition_certs}</div>
                    <div class="text-xs">Competition</div>
                </div>
                <div class="stat-box text-center p-2 bg-green-50 rounded">
                    <div class="font-bold text-lg">${data.participation_certs}</div>
                    <div class="text-xs">Participation</div>
                </div>
            </div>

            <div class="certificates-grid grid grid-cols-2 gap-3">
                ${data.certificates.map(cert => `
                    <div class="certificate-card p-4 border rounded-lg ${cert.type === 'belt_promotion' ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200'}">
                        <div class="flex items-start justify-between">
                            <div>
                                <h5 class="font-bold">${cert.title}</h5>
                                <p class="text-xs text-gray-500">${cert.type}</p>
                            </div>
                            <i class="fas fa-certificate text-2xl ${cert.type === 'belt_promotion' ? 'text-yellow-500' : 'text-gray-400'}"></i>
                        </div>
                        <p class="text-sm mt-2">${cert.description || ''}</p>
                        <p class="text-xs mt-2">Issued: ${cert.issued_date} by ${cert.issued_by}</p>
                        <div class="mt-3 flex gap-2">
                            ${cert.pdf_url ? `<a href="${cert.pdf_url}" target="_blank" class="text-xs text-blue-600"><i class="fas fa-download"></i> Download</a>` : ''}
                            <span class="text-xs text-gray-400">QR: ${cert.qr_code.substring(0, 8)}...</span>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}

async function loadProgress(studentId) {
    const response = await fetch(`/api/students/${studentId}/progress`);
    const data = await response.json();

    const progressPercent = data.progress_summary.percentage;

    return `
        <div class="progress-section">
            <div class="belt-status mb-4 p-4 bg-gradient-to-r from-yellow-100 to-orange-100 rounded-lg">
                <h3 class="font-bold text-lg">Current Belt: ${data.current_belt}</h3>
                <div class="progress-bar mt-2 bg-gray-200 rounded-full h-4">
                    <div class="progress-fill bg-green-500 h-4 rounded-full transition-all duration-500" style="width: ${progressPercent}%"></div>
                </div>
                <p class="text-sm mt-1">${progressPercent}% Complete - ${data.progress_summary.mastered}/${data.progress_summary.total} skills mastered</p>
            </div>

            <div class="skills-grid grid grid-cols-2 gap-3 mb-4">
                <div class="skill-stat p-3 bg-green-50 rounded text-center">
                    <div class="text-2xl font-bold text-green-600">${data.progress_summary.mastered}</div>
                    <div class="text-xs">Mastered</div>
                </div>
                <div class="skill-stat p-3 bg-blue-50 rounded text-center">
                    <div class="text-2xl font-bold text-blue-600">${data.progress_summary.in_progress}</div>
                    <div class="text-xs">In Progress</div>
                </div>
            </div>

            <h4 class="font-bold mb-2">Skill Checklist</h4>
            <div class="skills-list max-h-48 overflow-y-auto">
                ${data.skills.map(skill => `
                    <div class="skill-item flex justify-between items-center p-2 border-b">
                        <div>
                            <p class="font-medium ${skill.status === 'mastered' ? 'text-green-600' : skill.status === 'in_progress' ? 'text-blue-600' : 'text-gray-400'}">
                                ${skill.status === 'mastered' ? '✓' : skill.status === 'in_progress' ? '◐' : '○'}
                                ${skill.name}
                            </p>
                            <p class="text-xs text-gray-500">${skill.description || ''}</p>
                        </div>
                        <span class="badge badge-${skill.status}">${skill.status.replace('_', ' ')}</span>
                    </div>
                `).join('')}
            </div>

            ${data.evaluations.length ? `
                <h4 class="font-bold mt-4 mb-2">Recent Evaluations</h4>
                ${data.evaluations.map(eval => `
                    <div class="evaluation-card p-3 bg-gray-50 rounded mb-2">
                        <p class="text-sm"><strong>${eval.date}</strong> by ${eval.instructor}</p>
                        <div class="grid grid-cols-4 gap-2 mt-2 text-center text-xs">
                            <div>Technique: ${eval.technique || '-'}/10</div>
                            <div>Discipline: ${eval.discipline || '-'}/10</div>
                            <div>Fitness: ${eval.fitness || '-'}/10</div>
                            <div>Sparring: ${eval.sparring || '-'}/10</div>
                        </div>
                        ${eval.belt_ready ? '<span class="badge badge-success mt-2">Ready for next belt!</span>' : ''}
                    </div>
                `).join('')}
            ` : ''}

            ${data.exam_history.length ? `
                <h4 class="font-bold mt-4 mb-2">Belt Exam History</h4>
                ${data.exam_history.map(exam => `
                    <div class="exam-item p-2 border-l-4 ${exam.result === 'pass' ? 'border-green-500 bg-green-50' : exam.result === 'fail' ? 'border-red-500 bg-red-50' : 'border-yellow-500 bg-yellow-50'}">
                        <p class="font-medium">${exam.belt_level} Belt Exam - ${exam.result.toUpperCase()}</p>
                        <p class="text-xs">${exam.exam_date} • Score: ${exam.score || 'N/A'} • By: ${exam.approved_by}</p>
                    </div>
                `).join('')}
            ` : ''}
        </div>
    `;
}

async function loadChat(studentId) {
    // Load initial student data to get thread info
    const response = await fetch(`/api/students/${studentId}`);
    const data = await response.json();

    currentThreadId = data.chat_thread_id;

    const messagesHtml = data.chat_messages.map(msg => `
        <div class="message ${msg.sender_user_id === {{ Auth::id() }} ? 'own' : 'other'}">
            <div class="message-bubble">
                <strong>${msg.sender.name}:</strong> ${msg.message}
                <div class="text-xs text-gray-400 mt-1">${new Date(msg.sent_at).toLocaleTimeString()}</div>
            </div>
        </div>
    `).join('');

    return `
        <div class="chat-section">
            <div class="chat-messages h-64 overflow-y-auto border rounded p-3 mb-3 bg-gray-50" id="chatMessages">
                ${messagesHtml || '<p class="text-center text-gray-400">No messages yet</p>'}
            </div>
            <div class="chat-input flex gap-2">
                <input type="text" id="chatInput" class="flex-1 border rounded px-3 py-2" placeholder="Type a message..." maxlength="1000">
                <button onclick="sendChatMessage()" class="bg-blue-500 text-white px-4 py-2 rounded">Send</button>
            </div>
        </div>
    `;
}

function startChatRefresh() {
    if (chatRefreshInterval) clearInterval(chatRefreshInterval);

    chatRefreshInterval = setInterval(async () => {
        if (currentStudentId && currentThreadId) {
            // Refresh messages
            const response = await fetch(`/api/chat-threads/${currentThreadId}/messages`);
            const data = await response.json();

            const container = document.getElementById('chatMessages');
            if (container && data.messages) {
                container.innerHTML = data.messages.map(msg => `
                    <div class="message ${msg.sender_user_id === {{ Auth::id() }} ? 'own' : 'other'}">
                        <div class="message-bubble">
                            <strong>${msg.sender.name}:</strong> ${msg.message}
                            <div class="text-xs text-gray-400 mt-1">${new Date(msg.sent_at).toLocaleTimeString()}</div>
                        </div>
                    </div>
                `).join('');
                container.scrollTop = container.scrollHeight;
            }
        }
    }, 5000);
}

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
            const refreshResponse = await fetch(`/api/chat-threads/${currentThreadId}/messages`);
            const data = await refreshResponse.json();

            const container = document.getElementById('chatMessages');
            container.innerHTML = data.messages.map(msg => `
                <div class="message ${msg.sender_user_id === {{ Auth::id() }} ? 'own' : 'other'}">
                    <div class="message-bubble">
                        <strong>${msg.sender.name}:</strong> ${msg.message}
                        <div class="text-xs text-gray-400 mt-1">${new Date(msg.sent_at).toLocaleTimeString()}</div>
                    </div>
                </div>
            `).join('');
            container.scrollTop = container.scrollHeight;
        }
    } catch (error) {
        console.error('Error sending message:', error);
    }
}

// Enter key to send
document.addEventListener('keypress', function(e) {
    if (e.target.id === 'chatInput' && e.key === 'Enter') {
        sendChatMessage();
    }
});

async function loadDocuments(studentId) {
    const response = await fetch(`/api/students/${studentId}/documents`);
    const data = await response.json();

    return `
        <div class="documents-section">
            <h4 class="font-bold mb-2">Available Documents</h4>
            <div class="documents-list">
                ${Object.entries(data.available_documents).map(([key, label]) => `
                    <div class="document-item flex justify-between items-center p-3 border-b">
                        <span><i class="fas fa-file-alt mr-2"></i> ${label}</span>
                        <button onclick="generateDocument('${key}')" class="text-sm text-blue-600">
                            <i class="fas fa-plus"></i> Generate
                        </button>
                    </div>
                `).join('')}
            </div>

            ${data.generated_documents.length ? `
                <h4 class="font-bold mt-4 mb-2">Generated Documents</h4>
                ${data.generated_documents.map(doc => `
                    <div class="generated-doc p-2 bg-gray-50 rounded mb-2">
                        <p class="font-medium">${doc.type}</p>
                        <p class="text-xs">Generated on ${new Date(doc.generated_at).toLocaleString()} by ${doc.generated_by}</p>
                    </div>
                `).join('')}
            ` : ''}
        </div>
    `;
}

function generateDocument(type) {
    alert(`Generating ${type} document... (Feature to be implemented)`);
}

function numberFormat(num) {
    return parseFloat(num).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}
</script>

</body>
</html>
