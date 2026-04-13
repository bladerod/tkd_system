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

        <!-- FILTERS (UNCHANGED) -->
        <div class="grid grid-cols-4 gap-4 px-4 pt-4">
            <div class="dropdown">
                <label>Belt</label>
                <select>
                    <option disabled selected>-- Select a belt --</option>
                    @foreach ($beltlevels as $belt)
                        <option>{{ $belt->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="dropdown">
                <label>Status</label>
                <select>
                    <option disabled selected>-- Select status --</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
            </div>

            <div class="dropdown">
                <label>Class</label>
                <select>
                    <option disabled selected>-- Select class --</option>
                    @foreach ($classes as $class)
                        <option>{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="dropdown">
                <label>Instructor</label>
                <select>
                    <option disabled selected>-- Select instructor --</option>
                    @foreach ($users->where('role','instructor') as $user)
                        <option>{{ $user->fname }} {{ $user->lname }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- TABLE (UNCHANGED) -->
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
                            <td>{{ $student->student_name }}</td>
                            <td><span class="belt">{{ $student->current_belt }}</span></td>
                            <td>
                                <span class="status {{ $student->status == 'active' ? 'active' : 'inactive' }}">
                                    {{ $student->status }}
                                </span>
                            </td>
                            <td>{{ $student->parent_name }}</td>
                            <td>{{ $student->balance }}</td>
                            <td>{{ $student->attendance }}%</td>
                            <td>
                                <a href="#"
                                   class="btn-view text-blue-600 hover:underline"
                                   onclick="openModal(this)"
                                   data-id="{{ $student->id }}">
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

<!-- MODAL -->
<div id="studentModal" class="modal hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="modal-content bg-white rounded-lg p-6 w-3/4 max-w-3xl relative">

        <div class="flex justify-between mb-4">
            <h2 class="text-2xl font-bold">Student Profile</h2>
            <span onclick="closeModal()" class="cursor-pointer">&times;</span>
        </div>

        <!-- ✅ ALL WIREFRAME TABS -->
        <div class="tabs flex gap-2 mb-4 flex-wrap">
            <button class="tab active" data-tab="profile" onclick="switchTab(this)">Profile</button>
            <button class="tab" data-tab="attendance" onclick="switchTab(this)">Attendance</button>
            <button class="tab" data-tab="billing" onclick="switchTab(this)">Billing</button>
            <button class="tab" data-tab="competition" onclick="switchTab(this)">Competition</button>
            <button class="tab" data-tab="certificates" onclick="switchTab(this)">Certificates</button>
            <button class="tab" data-tab="progress" onclick="switchTab(this)">Progress Notes</button>
            <button class="tab" data-tab="chat" onclick="switchTab(this)">Chat</button>
            <button class="tab" data-tab="documents" onclick="switchTab(this)">Documents</button>
        </div>

        <!-- TAB CONTENT -->
        <div id="profileTab" class="tab-content"></div>

        <div id="attendanceTab" class="tab-content hidden">
            <table class="min-w-full">
                <thead>
                    <tr><th>Date</th><th>Status</th></tr>
                </thead>
                <tbody id="attendanceTableBody"></tbody>
            </table>
        </div>

        <div id="billingTab" class="tab-content hidden"></div>
        <div id="competitionTab" class="tab-content hidden"></div>
        <div id="certificatesTab" class="tab-content hidden"></div>
        <div id="progressTab" class="tab-content hidden"></div>
        <div id="chatTab" class="tab-content hidden"></div>
        <div id="documentsTab" class="tab-content hidden"></div>

    </div>
</div>

<script>
async function openModal(btn) {
    const modal = document.getElementById("studentModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");

    const id = btn.dataset.id;
    modal.dataset.studentId = id;

    const res = await fetch(`/api/students/${id}`);
    const data = await res.json();

    // PROFILE
    document.getElementById("profileTab").innerHTML = `
        <p><b>Name:</b> ${data.first_name} ${data.last_name}</p>
        <p><b>Belt:</b> ${data.belt_name}</p>
        <p><b>Status:</b> ${data.status}</p>
        <p><b>Birthdate:</b> ${data.birthdate}</p>
    `;

    // BILLING
    let bill = '';
    data.invoices.forEach(i=>{
        bill += `<tr>
            <td>${i.invoice_no}</td>
            <td>${i.total_due}</td>
            <td>${i.status}</td>
        </tr>`;
    });
    document.getElementById("billingTab").innerHTML = `
        <table><tr><th>Invoice</th><th>Amount</th><th>Status</th></tr>${bill}</table>
    `;

    // COMPETITION
    let comp = '';
    data.competitions.forEach(c=>{
        comp += `<tr><td>${c.name}</td><td>${c.result}</td></tr>`;
    });
    document.getElementById("competitionTab").innerHTML = `
        <table><tr><th>Competition</th><th>Result</th></tr>${comp}</table>
    `;

    // CERTIFICATES
    let cert = '';
    data.certificates.forEach(c=>{
        cert += `<tr><td>${c.title}</td><td>${c.issued_date}</td></tr>`;
    });
    document.getElementById("certificatesTab").innerHTML = `
        <table><tr><th>Title</th><th>Date</th></tr>${cert}</table>
    `;

    // EXTRA TABS
    document.getElementById("progressTab").innerHTML = "No progress notes";
    document.getElementById("chatTab").innerHTML = "Chat coming soon";
    document.getElementById("documentsTab").innerHTML = "No documents";

    loadAttendance(id);
}

async function loadAttendance(id){
    const res = await fetch(`/api/students/${id}/attendance`);
    const data = await res.json();

    let html='';
    data.forEach(a=>{
        html += `<tr>
            <td>${new Date(a.checkin_time).toLocaleDateString()}</td>
            <td>${a.status == 1 ? 'Present':'Absent'}</td>
        </tr>`;
    });

    document.getElementById("attendanceTableBody").innerHTML = html;
}

function switchTab(btn){
    document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.tab-content').forEach(c=>c.classList.add('hidden'));
    document.getElementById(btn.dataset.tab + 'Tab').classList.remove('hidden');
}

function closeModal(){
    document.getElementById("studentModal").classList.add("hidden");
}
</script>

</body>
</html>
