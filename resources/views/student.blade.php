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
            <div class="dropdown">
                <label for="belt">Belt</label>
                <select name="belt" id="belt">
                    <option value="" disabled selected>-- Select a belt --</option>
                    @foreach ($beltlevels as $belt)
                        <option value="{{ $belt->id }}">{{ $belt->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="dropdown">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="" disabled selected>-- Select status --</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="dropdown">
                <label for="class">Class</label>
                <select name="class" id="class">
                    <option value="" disabled selected>-- Select class --</option>
                    {{-- @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach --}}
                </select>
            </div>

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

<div id="studentModal" class="modal hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg p-6 w-3/4 max-w-3xl relative">
        <div class="modal-header flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Student Profile</h2>
            <span class="cursor-pointer text-xl font-bold" onclick="closeModal()">&times;</span>
        </div>

        <div class="tabs flex gap-2 mb-4 border-b">
            <button class="tab active px-4 py-2 border-b-2 border-blue-600 font-medium" data-tab="profile" onclick="switchTab(this)">Profile</button>
            <button class="tab px-4 py-2 text-gray-500 hover:text-gray-700 font-medium" data-tab="attendance" onclick="switchTab(this)">Attendance</button>
            <button class="tab px-4 py-2 text-gray-500 hover:text-gray-700 font-medium" data-tab="billing" onclick="switchTab(this)">Billing</button>
            <button class="tab px-4 py-2 text-gray-500 hover:text-gray-700 font-medium" data-tab="competition" onclick="switchTab(this)">Competition</button>
            <button class="tab px-4 py-2 text-gray-500 hover:text-gray-700 font-medium" data-tab="certificates" onclick="switchTab(this)">Certificates</button>
        </div>

        <div id="profileTab" class="tab-content block">
            <p class="text-gray-500">Loading profile...</p>
        </div>

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
                <div class="stat-card p-3 bg-red-50 rounded text-center text-red-700">
                    <span class="block text-sm">Absent</span>
                    <span id="absentCount" class="text-xl font-bold">0</span>
                </div>
            </div>
            <div class="attendance-table-container overflow-x-auto">
                <table class="attendance-table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Check Out</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTableBody" class="bg-white divide-y divide-gray-200"></tbody>
                </table>
            </div>
            <div id="noAttendanceData" class="text-gray-500 text-center py-4 hidden">No attendance records found.</div>
        </div>

        <div id="billingTab" class="tab-content hidden"><p>Loading billing details...</p></div>
        <div id="competitionTab" class="tab-content hidden"><p>Loading competition history...</p></div>
        <div id="certificatesTab" class="tab-content hidden"><p>Loading certificates...</p></div>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
<script>
async function openModal(button) {
    const modal = document.getElementById("studentModal");

    // Force the display states
    modal.classList.remove("hidden");
    modal.classList.add("flex");

    const studentId = button.getAttribute("data-id");
    modal.dataset.studentId = studentId;

    // Reset tabs to profile on open
    const profileBtn = document.querySelector('.tab[data-tab="profile"]');
    switchTab(profileBtn);

    try {
        const res = await fetch(`/api/students/${studentId}`);
        const data = await res.json();

        // ================= PROFILE =================
        // Aligned with DB: first_name, last_name, birthdate, gender, join_date, emergency_contact_name, emergency_contact_mobile
        const fullName = data.name || `${data.first_name || ''} ${data.last_name || ''}`.trim();
        const parentName = data.parent_name || data.parent || 'N/A';
        const emergencyName = data.emergency_contact_name || 'N/A';
        const emergencyMobile = data.emergency_contact_mobile || 'N/A';

        document.getElementById("profileTab").innerHTML = `
            <div class="grid grid-cols-2 gap-4">
                <div><strong class="text-gray-600">Name:</strong> ${fullName}</div>
                <div><strong class="text-gray-600">Belt:</strong> ${data.current_belt || data.belt || 'N/A'}</div>
                <div><strong class="text-gray-600">Status:</strong> <span class="capitalize">${data.status || 'N/A'}</span></div>
                <div><strong class="text-gray-600">Birthdate:</strong> ${data.birthdate || 'N/A'}</div>
                <div><strong class="text-gray-600">Gender:</strong> <span class="capitalize">${data.gender || 'N/A'}</span></div>
                <div><strong class="text-gray-600">Join Date:</strong> ${data.join_date || 'N/A'}</div>
                <div><strong class="text-gray-600">Parent:</strong> ${parentName}</div>
                <div><strong class="text-gray-600">Emergency Contact:</strong> ${emergencyName} <br><span class="text-sm text-gray-500">${emergencyMobile}</span></div>
            </div>
        `;

        // ================= BILLING =================
        let billingHtml = '';
        if (data.billing && data.billing.length) {
            data.billing.forEach(b => {
                billingHtml += `
                    <tr>
                        <td class="px-4 py-2">${b.invoice_no}</td>
                        <td class="px-4 py-2">₱${b.amount}</td>
                        <td class="px-4 py-2">₱${b.total_due}</td>
                        <td class="px-4 py-2 capitalize">${b.status}</td>
                        <td class="px-4 py-2">${b.due_date}</td>
                    </tr>
                `;
            });
        } else {
            billingHtml = `<tr><td colspan="5" class="text-center py-4 text-gray-500">No billing records found.</td></tr>`;
        }

        document.getElementById("billingTab").innerHTML = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Due</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">${billingHtml}</tbody>
                </table>
            </div>
        `;

        // ================= COMPETITION =================
        let compHtml = '';
        if (data.competitions && data.competitions.length) {
            data.competitions.forEach(c => {
                compHtml += `
                    <tr>
                        <td class="px-4 py-2">${c.name}</td>
                        <td class="px-4 py-2">${c.category}</td>
                        <td class="px-4 py-2 capitalize">${c.result}</td>
                        <td class="px-4 py-2 capitalize">${c.medal}</td>
                    </tr>
                `;
            });
        } else {
            compHtml = `<tr><td colspan="4" class="text-center py-4 text-gray-500">No competition history found.</td></tr>`;
        }

        document.getElementById("competitionTab").innerHTML = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Competition</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Result</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Medal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">${compHtml}</tbody>
                </table>
            </div>
        `;

        // ================= CERTIFICATES =================
        // Aligned with DB: title, certificate_type, issued_date
        let certHtml = '';
        if (data.certificates && data.certificates.length) {
            data.certificates.forEach(c => {
                certHtml += `
                    <tr>
                        <td class="px-4 py-2">${c.title}</td>
                        <td class="px-4 py-2">${c.certificate_type || c.type}</td>
                        <td class="px-4 py-2">${c.issued_date || c.date}</td>
                    </tr>
                `;
            });
        } else {
            certHtml = `<tr><td colspan="3" class="text-center py-4 text-gray-500">No certificates found.</td></tr>`;
        }

        document.getElementById("certificatesTab").innerHTML = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Issued</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">${certHtml}</tbody>
                </table>
            </div>
        `;

    } catch (err) {
        console.error("Failed to load student profile:", err);
        document.getElementById("profileTab").innerHTML = `<p class="text-red-500">Failed to load data. Please try again.</p>`;
    }
}

function closeModal() {
    const modal = document.getElementById("studentModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

function switchTab(button) {
    // Styling tabs
    document.querySelectorAll(".tab").forEach(t => {
        t.classList.remove("active", "border-b-2", "border-blue-600", "text-black");
        t.classList.add("text-gray-500");
    });
    button.classList.add("active", "border-b-2", "border-blue-600", "text-black");
    button.classList.remove("text-gray-500");

    // Switching content mapping properly with hidden/block classes
    document.querySelectorAll(".tab-content").forEach(c => {
        c.classList.add("hidden");
        c.classList.remove("block");
    });

    const tabName = button.dataset.tab + "Tab";
    const targetTab = document.getElementById(tabName);
    targetTab.classList.remove("hidden");
    targetTab.classList.add("block");

    // Load attendance dynamically only if it hasn't been handled yet or requires refresh
    if (button.dataset.tab === 'attendance') {
        const studentId = document.getElementById("studentModal").dataset.studentId;
        loadAttendanceData(studentId);
    }
}

async function loadAttendanceData(studentId) {
    const tbody = document.getElementById("attendanceTableBody");
    const noData = document.getElementById("noAttendanceData");
    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-500">Loading attendance...</td></tr>';
    noData.classList.add("hidden");

    try {
        const res = await fetch(`/api/students/${studentId}/attendance`);
        const data = await res.json();

        tbody.innerHTML = ''; // clear loading state

        if (!data || !data.length) {
            noData.classList.remove("hidden");
            document.getElementById("totalSessions").textContent = 0;
            document.getElementById("presentCount").textContent = 0;
            document.getElementById("absentCount").textContent = 0;
            return;
        }

        let present = 0, absent = 0;
        data.forEach(log => {
            // DB has log.status as integer 1 (active/present)
            const isPresent = log.status === 1 || log.status === "present";
            if (isPresent) present++; else absent++;

            // DB default checkout empty state is '0000-00-00 00:00:00'
            const validCheckout = log.checkout_time && log.checkout_time !== '0000-00-00 00:00:00';
            const statusDisplay = isPresent ? '<span class="text-green-600 font-medium">Present</span>' : '<span class="text-red-600 font-medium">Absent</span>';

            const row = document.createElement("tr");
            row.innerHTML = `
                <td class="px-4 py-2">${new Date(log.checkin_time).toLocaleDateString()}</td>
                <td class="px-4 py-2">${new Date(log.checkin_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</td>
                <td class="px-4 py-2">${validCheckout ? new Date(log.checkout_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '-'}</td>
                <td class="px-4 py-2">${statusDisplay}</td>
                <td class="px-4 py-2 capitalize">${log.method || 'Manual'}</td>
            `;
            tbody.appendChild(row);
        });

        document.getElementById("totalSessions").textContent = data.length;
        document.getElementById("presentCount").textContent = present;
        document.getElementById("absentCount").textContent = absent;

    } catch (err) {
        console.error("Failed to load attendance:", err);
        tbody.innerHTML = '';
        noData.textContent = "Failed to load attendance data.";
        noData.classList.remove("hidden");
    }
}
</script>
</body>
</html>
