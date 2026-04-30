@php
    $branding = \App\Models\Branding::first();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    @if(isset($branding) && $branding->logo_path)
        <link rel="icon" href="{{ Storage::url($branding->logo_path) }}">
    @endif
    <title>TrainNova | Students</title>
    @vite(['resources/css/app.css'])
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])
    @vite(['resources/css/student.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Add Simple-Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
    <!-- Add SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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

        <form method="GET" action="{{ route('student') }}" class="mb-4 p-4 mx-4 mt-4">
    <div class="flex items-center gap-2 mb-4">
        <h1 class="font-semibold text-gray-700">Filter Options</h1>
    </div>

    {{-- Row 1: Status, Belt, Search, Buttons --}}
    <div class="grid grid-cols-12 gap-4 items-end mb-4">
        <div class="col-span-3">
            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Status</label>
            <select name="status" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-white">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-span-3">
            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Belt Level</label>
            <select name="belt" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-white">
                <option value="">All Belts</option>
                @foreach ($beltlevels as $belt)
                    <option value="{{ $belt->name }}" {{ request('belt') == $belt->name ? 'selected' : '' }}>
                        {{ $belt->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-span-4">
            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Quick Search</label>
            <div class="flex gap-2">
                <input type="text" id="jqSearchInput" value="{{ request('search') }}" placeholder="Search student name..."
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-white">
                <button type="button" id="jqSearchBtn" class="bg-gray-800 text-white px-4 py-2.5 rounded-lg hover:bg-gray-900 transition-all duration-200 font-medium text-sm">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <input type="hidden" name="search" id="hiddenSearch" value="{{ request('search') }}">
        </div>

        <div class="col-span-2">
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-[#1C1C1D] text-white px-3 py-2.5 rounded-lg hover:bg-[#2C2C2D] transition-all duration-200 font-medium text-sm shadow-sm flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('student') }}" class="px-3 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium flex items-center justify-center">
                    Clear
                </a>
            </div>
        </div>
    </div>

    {{-- Row 2: Date Range --}}
    <div class="grid grid-cols-12 gap-4 items-end">
        <div class="col-span-3">
            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Enrolled Date From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-white">
        </div>

        <div class="col-span-3">
            <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Enrolled Date To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-white">
        </div>
    </div>
</form>


        <div class="mt-2">
            <div class="table-content overflow-x-auto">
                <table id="studentTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
    <th onclick="sortTable('student_name')">Name ⬍</th>
    <th onclick="sortTable('current_belt')">Belt ⬍</th>
    <th onclick="sortTable('status')">Status ⬍</th>
    <th onclick="sortTable('parent_name')">Parent ⬍</th>
    <th onclick="sortTable('balance')">Balance ⬍</th>
    <th onclick="sortTable('attendance')">Attendance ⬍</th>
    <th onclick="sortTable('join_date')">Enrolled Date ⬍</th>
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
                            <td >
                                <span class="{{ $student->balance > 0 ? 'text-red-600 font-semibold' : 'text-green-600' }}">
                                    Php {{ number_format($student->balance, 2) }}
                                </span>
                            </td>
                            <td>{{ $student->attendance }}%</td>
                            <td>{{ $student->join_date }}</td>
                            <td>
                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm"
                                        onclick="openModal(this)"
                                        data-id="{{ $student->id }}"
                                        data-name="{{ $student->student_name }}"
                                        data-belt="{{ $student->current_belt }}"
                                        data-status="{{ $student->status }}">
                                    View
                                </button>
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
    <button class="tab active px-4 py-2 bg-blue-600 text-gray-500 rounded-t font-medium"
        data-tab="profile" onclick="switchTab(this)">Profile</button>

    <button class="tab px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-t font-medium"
        data-tab="attendance" onclick="switchTab(this)">Attendance</button>

    <button class="tab px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-t font-medium"
        data-tab="billing" onclick="switchTab(this)">Billing</button>

    <button class="tab px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-t font-medium"
        data-tab="competition" onclick="switchTab(this)">Competition</button>

    <button class="tab px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-t font-medium"
        data-tab="certificates" onclick="switchTab(this)">Certificates</button>
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
                <div class="stat-card p-3 bg-yellow-50 rounded text-center text-yellow-700">
                    <span class="block text-sm">Late</span>
                    <span id="lateCount" class="text-xl font-bold">0</span>
                </div>
                <div class="stat-card p-3 bg-red-50 rounded text-center text-red-700">
                    <span class="block text-sm">Absent</span>
                    <span id="absentCount" class="text-xl font-bold">0</span>
                </div>
                <div class="stat-card p-3 bg-brown-50 rounded text-center text-brown-700">
                    <span class="block text-sm">Excused</span>
                    <span id="excusedCount" class="text-xl font-bold">0</span>
                </div>
            </div>
            <div class="attendance-table-container overflow-x-auto">
                <table class="attendance-table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-xs font-medium text-gray uppercase text-center">Date</th>
                            <th class="px-4 py-2 text-xs font-medium text-gray uppercase text-center">Check In</th>
                            <th class="px-4 py-2 text-xs font-medium text-gray uppercase text-center">Check Out</th>
                            <th class="px-4 py-2 text-xs font-medium text-gray uppercase text-center">Status</th>
                            <th class="px-4 py-2 text-xs font-medium text-gray uppercase text-center">Method</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTableBody" class="bg-white divide-y divide-gray-200"></tbody>
                </table>
            </div>
        </div>
        <div id="billingTab" class="tab-content hidden"></div>

<div id="competitionTab" class="tab-content hidden"></div>

<div id="certificatesTab" class="tab-content hidden"></div>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
@vite('resources/js/student.js')
@vite('resources/js/navbarDrop.js')
<script>

let currentSort = {
    column: null,
    direction: 'asc'
};

function applyFilters() {
    const belt = document.getElementById("belt").value;
    const from = document.getElementById("from_date").value;
    const to = document.getElementById("to_date").value;

    fetchData(belt, from, to);
}

function resetFilters() {
    document.getElementById("belt").value = "";
    document.getElementById("from_date").value = "";
    document.getElementById("to_date").value = "";

    fetchData();
}

function sortTable(column) {
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.direction = 'asc';
    }

    applyFilters();
}

function fetchData(belt = '', from = '', to = '') {
    const params = new URLSearchParams({
        belt: belt,
        from_date: from,
        to_date: to,
        sort_by: currentSort.column,
        sort_dir: currentSort.direction
    });

    fetch(`/students/filter?${params.toString()}`)
    .then(res => res.json())
    .then(data => {
        console.log("FILTER RESULT:", data); // 👈 ADD THIS
        renderTable(data);
    });
}

function renderTable(students) {
    const tbody = document.querySelector("#studentTable tbody");
    tbody.innerHTML = "";

    students.forEach(student => {
        tbody.innerHTML += `
        <tr>
            <td>${student.student_name ?? 'NO NAME'}</td>
            <td>${student.current_belt}</td>
            <td>${student.status}</td>
            <td>${student.parent_name}</td>
            <td>Php ${parseFloat(student.balance).toFixed(2)}</td>
            <td>${student.attendance}%</td>
            <td>${student.join_date ?? '-'}</td>
            <td>
                <button class="bg-blue-500 text-white px-2 py-1 rounded"
                    onclick="openModalById(${student.id})">
                    View
                </button>
            </td>
        </tr>
        `;
    });
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

async function openModal(button) {
    const modal = document.getElementById("studentModal");
    modal.classList.remove("hidden");
    modal.style.display = "flex";

            const studentId = button.getAttribute("data-id");
            modal.dataset.studentId = studentId;

    // Loading UI
    document.getElementById("profileTab").innerHTML = `
        <p class="text-gray-500">Loading profile...</p>
    `;

    try {
        const res = await fetch(`/students/${studentId}`);

        if (!res.ok) {
            throw new Error(`HTTP error! Status: ${res.status}`);
        }

        const data = await res.json();

        // Safe mapping
        const fullName =
            data.student_name ||
            `${data.first_name ?? ''} ${data.last_name ?? ''}`.trim() ||
            'N/A';

        document.getElementById("profileTab").innerHTML = `
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><strong>Name:</strong> <span class="capitalize">${fullName}</span></div>
                <div><strong>Belt:</strong> ${data.current_belt ?? 'N/A'}</div>
                <div>
  <strong>Status:</strong>
  <span class="status ${data.status === 'active' ? 'active' : 'inactive'}">
    ${data.status ?? 'N/A'}
  </span>
</div>
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
                <div><strong>Gender:</strong> <span class="capitalize">${data.gender ?? 'N/A'}</span></div>
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

    } catch (err) {
        console.error(err);

        document.getElementById("profileTab").innerHTML = `
            <div class="text-red-500 text-center">
                Failed to load profile<br>
                <small>${err.message}</small>
            </div>
        `;
    }
}

function closeModal() {
    const modal = document.getElementById("studentModal");
    modal.classList.add("hidden");
    modal.style.display = "none";
}

function switchTab(button) {
    const studentId = document.getElementById("studentModal").dataset.studentId;

    document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
    button.classList.add("active");

    document.querySelectorAll(".tab-content").forEach(c => {
        c.classList.add("hidden");
        c.classList.remove("block");
    });

    const tabName = button.dataset.tab + "Tab";
    const tab = document.getElementById(tabName);

    tab.classList.remove("hidden");
    tab.classList.add("block");

    // ✅ DEBUG LOG
    console.log("Switching to:", button.dataset.tab, "Student ID:", studentId);

    // ✅ LOAD DATA
    switch (button.dataset.tab) {
        case "attendance":
            loadAttendanceData(studentId);
            break;
        case "billing":
            loadBillingData(studentId);
            break;
        case "competition":
            loadCompetitionData(studentId);
            break;
        case "certificates":
            loadCertificatesData(studentId);
            break;
    }
}

async function loadAttendanceData(studentId) {
    const tbody = document.getElementById("attendanceTableBody");

    tbody.innerHTML = `<tr><td colspan="5">Loading...</td></tr>`;

    try {
        const res = await fetch(`/students/${studentId}/attendance`);

        if (!res.ok) {
            throw new Error(`HTTP error! Status: ${res.status}`);
        }

        const response = await res.json();

        const logs = response.logs.data; // ✅ IMPORTANT
        const stats = response.stats;

        tbody.innerHTML = '';

        // ❌ No records
        if (!logs.length) {
            tbody.innerHTML = `<tr><td colspan="5">No records</td></tr>`;
        } else {
            logs.forEach(log => {
                tbody.innerHTML += `
                    <tr>
                        <td class="text-center">${log.checkin_time ? new Date(log.checkin_time).toLocaleDateString() : '-'}</td>
                        <td class="text-center">${log.checkin_time ? new Date(log.checkin_time).toLocaleTimeString() : '-'}</td>
                        <td class="text-center">${log.checkout_time ? new Date(log.checkout_time).toLocaleTimeString() : '-'}</td>
                        <td class="text-center ${getStatusColor(log.attendance_status)}">${log.attendance_status ?? '-'}</td>
                        <td class="text-center">${log.method ?? 'Manual'}</td>
                    </tr>
                `;
            });
        }

        // ✅ USE YOUR REAL STATS (not manual counting anymore)
        document.getElementById("totalSessions").textContent = stats.total_sessions;
        document.getElementById("presentCount").textContent = stats.present;
        document.getElementById("absentCount").textContent = stats.absent;
        document.getElementById("lateCount").textContent = stats.late;
        document.getElementById("excusedCount").textContent = stats.excused;

        // (Optional if you add UI later)
        // stats.late
        // stats.excused
        // stats.by_method.face / qr / manual

    } catch (err) {
        console.error("ATTENDANCE ERROR:", err);

        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-red-500 text-center">
                    Failed to load attendance
                </td>
            </tr>
        `;
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
                    <td class="text-center">${new Date(b.created_at).toLocaleDateString()}</td>
<td class="text-center">Php ${Number(b.amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
<td class="text-center">
    <span class="status_pay ${b.status.toLowerCase()}">
        ${b.status}
    </span>
</td>
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

    tab.innerHTML = "<p>Loading competition...</p>";

    try {
        const res = await fetch(`/students/${studentId}/competition`);

        if (!res.ok) {
            throw new Error(`HTTP error! Status: ${res.status}`);
        }

        const data = await res.json();
        console.log("Competition data:", data); // ✅ DEBUG

        if (!Array.isArray(data) || data.length === 0) {
            tab.innerHTML = "<p>No competition history</p>";
            return;
        }

        let html = "";

        data.forEach(c => {
            html += `
                <div class="p-4 bg-white rounded shadow mb-2">
                    <div class="font-bold text-lg">${c.competition_name ?? 'No name'}</div>
                    <div class="text-sm text-gray-500">${new Date(c.competition_date).toDateString()}</div>
                    <div class="text-sm">Instructor: ${c.instructor_name ?? 'N/A'}</div>
                    <div class="mt-1 font-semibold">Result: ${c.result ?? 'Pending'}</div>
                </div>
            `;
        });

        tab.innerHTML = html;

    } catch (err) {
        console.error("COMPETITION ERROR:", err);
        tab.innerHTML = "<p class='text-red-500'>Failed to load competition</p>";
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

// click outside modal
window.onclick = function(e) {
    const modal = document.getElementById("studentModal");
    if (e.target === modal) closeModal();
};

document.getElementById('jqSearchBtn').addEventListener('click', function () {
        const val = document.getElementById('jqSearchInput').value;
        document.getElementById('hiddenSearch').value = val;
        this.closest('form').submit();
    });

    // Also trigger search on Enter key
    document.getElementById('jqSearchInput').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('hiddenSearch').value = this.value;
            this.closest('form').submit();
        }
    });


</script>
</body>

</html>
