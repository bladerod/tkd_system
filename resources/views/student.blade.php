<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students</title>
    @vite(['resources/css/app.css'])
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])
    @vite(['resources/css/student.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-50">

@include("includes.navbar")
@include("includes.sidebar")

<div class="main-content px-6 py-6 mt-7">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
        <span>/</span>
        <span class="text-[#1C1C1D] font-medium">Student</span>
    </div>

    <h1 class="text-4xl font-bold text-[#1C1C1D] mb-4">Student</h1>

    <div class="content-card mb-6">
        <div class="bg-[#1C1C1D] p-3 rounded-t-xl text-white font-semibold text-xl text-center">
            Student List
        </div>

        <div class="grid grid-cols-4 gap-4 px-4 pt-4">
            <!-- Belt Filter -->
            <div class="dropdown">
                <label for="belt">Belt</label>
                <select name="belt" id="belt">
                    <option value="" disabled selected>-- Select a belt --</option>
                    @foreach ($beltlevels as $belt)
                        <option value="{{ $belt->id }}">{{ $belt->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="dropdown">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="" disabled selected>-- Select status --</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Class Filter -->
            <div class="dropdown">
                <label for="class">Class</label>
                <select name="class" id="class">
                    <option value="" disabled selected>-- Select class --</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Instructor Filter -->
            <div class="dropdown">
                <label for="instructor">Instructor</label>
                <select name="instructor" id="instructor">
                    <option value="" disabled selected>-- Select instructor --</option>
                    @foreach ($users->where('role', 'instructor') as $user)
                        <option value="{{ $user->id }}">{{ $user->fname }} {{ $user->lname }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-card mt-4">
            <div class="table-content overflow-x-auto">
                <table class="student-table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
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
                    <tbody>
                        @foreach ($vwstudents as $student)
                        <tr>
                            <td>{{ $student->student_name ?? 'NO NAME' }}</td>
                            <td>
                                <span class="belt" style="background-color:#3d3d3d; color: #fff;">
                                    {{ $student->current_belt }}
                                </span>
                            </td>
                            <td>
                                <span class="status {{ $student->status == 'active' ? 'active' : 'inactive' }}">
                                    {{ $student->status }}
                                </span>
                            </td>
                            <td>{{ $student->parent_name }}</td>
                            <td class="balance paid">{{ $student->balance }}</td>
                            <td>{{ $student->attendance }}%</td>
                            <td>
                                <a href="#"
                                   class="btn-view text-blue-600 hover:underline"
                                   onclick="openModal(this)"
                                   data-id="{{ $student->id }}"
                                   data-name="{{ $student->student_name }}"
                                   data-belt="{{ $student->current_belt }}"
                                   data-status="{{ $student->status }}">
                                   View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="studentModal" class="modal hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg p-6 w-3/4 max-w-3xl relative">
        <div class="modal-header flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Student Profile</h2>
            <span class="cursor-pointer text-xl font-bold" onclick="closeModal()">&times;</span>
        </div>

        <!-- Tabs -->
        <div class="tabs flex gap-2 mb-4">
            <button class="tab active px-3 py-1 border-b-2 border-blue-600" data-tab="profile" onclick="switchTab(this)">Profile</button>
            <button class="tab px-3 py-1" data-tab="attendance" onclick="switchTab(this)">Attendance</button>
            <button class="tab px-3 py-1" data-tab="billing" onclick="switchTab(this)">Billing</button>
            <button class="tab px-3 py-1" data-tab="competition" onclick="switchTab(this)">Competition</button>
            <button class="tab px-3 py-1" data-tab="certificates" onclick="switchTab(this)">Certificates</button>
        </div>

        <!-- Tab Contents -->
        <div id="profileTab" class="tab-content block">
            <p><strong>Profile Information</strong></p>
        </div>

        <div id="attendanceTab" class="tab-content hidden">
            <p><strong>Attendance Records</strong></p>
            <div class="attendance-summary grid grid-cols-3 gap-4 mb-4">
                <div class="stat-card"><span>Total Sessions:</span> <span id="totalSessions">0</span></div>
                <div class="stat-card text-green-600"><span>Present:</span> <span id="presentCount">0</span></div>
                <div class="stat-card text-red-600"><span>Absent:</span> <span id="absentCount">0</span></div>
            </div>
            <div class="attendance-table-container overflow-x-auto">
                <table class="attendance-table min-w-full divide-y divide-gray-200">
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
            <div id="noAttendanceData" class="text-gray-500 text-center py-4 hidden">No attendance records found.</div>
        </div>

        <div id="billingTab" class="tab-content hidden"><p>Billing details will appear here.</p></div>
        <div id="competitionTab" class="tab-content hidden"><p>Competition history will appear here.</p></div>
        <div id="certificatesTab" class="tab-content hidden"><p>Certificates earned will appear here.</p></div>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
<script>
function openModal(button) {
    const modal = document.getElementById("studentModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");

    const studentId = button.getAttribute("data-id");
    const name = button.getAttribute("data-name");
    const belt = button.getAttribute("data-belt");
    const status = button.getAttribute("data-status");

    document.getElementById("profileTab").innerHTML = `
        <p><strong>Profile Information</strong></p>
        <p><strong>Name:</strong> ${name}</p>
        <p><strong>Belt:</strong> ${belt}</p>
        <p><strong>Status:</strong> ${status}</p>
    `;

    modal.dataset.studentId = studentId;
    loadAttendanceData(studentId);
}

function closeModal() {
    const modal = document.getElementById("studentModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

function switchTab(button) {
    document.querySelectorAll(".tab").forEach(t => t.classList.remove("active", "border-b-2", "border-blue-600"));
    button.classList.add("active", "border-b-2", "border-blue-600");

    document.querySelectorAll(".tab-content").forEach(c => c.classList.add("hidden"));
    const tabName = button.dataset.tab + "Tab";
    document.getElementById(tabName).classList.remove("hidden");

    if (button.dataset.tab === 'attendance') {
        const studentId = document.getElementById("studentModal").dataset.studentId;
        loadAttendanceData(studentId);
    }
}

async function loadAttendanceData(studentId) {
    const tbody = document.getElementById("attendanceTableBody");
    const noData = document.getElementById("noAttendanceData");
    tbody.innerHTML = '';
    noData.classList.add("hidden");

    try {
        const res = await fetch(`/api/students/${studentId}/attendance`);
        const data = await res.json();

        if (!data.length) {
            noData.classList.remove("hidden");
            document.getElementById("totalSessions").textContent = 0;
            document.getElementById("presentCount").textContent = 0;
            document.getElementById("absentCount").textContent = 0;
            return;
        }

        let present = 0, absent = 0;
        data.forEach(log => {
            if (log.status === "present") present++; else if (log.status === "absent") absent++;

            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${new Date(log.checkin_time).toLocaleDateString()}</td>
                <td>${new Date(log.checkin_time).toLocaleTimeString()}</td>
                <td>${log.checkout_time ? new Date(log.checkout_time).toLocaleTimeString() : '-'}</td>
                <td>${log.status}</td>
                <td>${log.method || 'Manual'}</td>
            `;
            tbody.appendChild(row);
        });

        document.getElementById("totalSessions").textContent = data.length;
        document.getElementById("presentCount").textContent = present;
        document.getElementById("absentCount").textContent = absent;

    } catch (err) {
        console.error(err);
    }
}
</script>
</body>
</html>
