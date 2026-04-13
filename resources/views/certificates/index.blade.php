<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificates</title>

    @vite(['resources/css/app.css'])
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/certificates.css'])

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@include("includes.navbar")
@include("includes.sidebar")

<div class="main-content">

    <!-- BREADCRUMB -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 mt-1">
        <a href="/dashboard">Dashboard</a>
        <span>/</span>
        <span class="text-[#1C1C1D] font-medium">Certificate</span>
    </div>

    <h1 class="text-4xl font-bold mb-4">Certificate</h1>

    <div class="content-card">

        <!-- ACTIONS -->
        <div class="action-buttons">
            <button class="btn-action" onclick="openGenerateModal()">
                <i class="fa-solid fa-plus"></i> Generate
            </button>

            <button class="btn-action" onclick="bulkGenerate()" id="bulkBtn" style="display:none">
                <i class="fa-solid fa-users"></i> Bulk Generate
            </button>

            <button class="btn-action" onclick="printSelected()" id="printBtn" disabled>
                <i class="fa-solid fa-print"></i> Print
            </button>

            <button class="btn-action" onclick="emailSelected()" id="emailBtn" disabled>
                <i class="fa-solid fa-envelope"></i> Email
            </button>
        </div>

        <!-- FILTERS -->
        <div class="filters mb-4 flex gap-3">
            <select id="typeFilter" onchange="filterCertificates()" class="border px-3 py-2">
                <option value="">All Types</option>
                <option value="promotion">Belt Promotion</option>
                <option value="competition">Competition</option>
            </select>

            <input type="text" id="searchStudent"
                   placeholder="Search student..."
                   onkeyup="filterCertificates()"
                   class="border px-3 py-2">
        </div>

        <!-- TABLE -->
        <div class="table-card">
            <div class="table-header">
                <h2>Certificates List</h2>
                <span id="certCount">0 certificates</span>
            </div>

            <div class="table-content">
                <table class="certificate-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Student</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>QR</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="certificatesTableBody">

                    @forelse ($certificates as $cert)
                    <tr data-id="{{ $cert->id }}"
                        data-type="{{ $cert->certificate_type }}"
                        data-student="{{ strtolower($cert->student->name) }}">

                        <td>
                            <input type="checkbox"
                                   class="cert-checkbox"
                                   value="{{ $cert->id }}"
                                   onchange="toggleSelection({{ $cert->id }}, this.checked)">
                        </td>

                        <td>{{ $cert->student->name }}</td>

                        <td>{{ ucfirst(str_replace('_',' ', $cert->certificate_type)) }}</td>

                        <td>{{ $cert->title }}</td>

                        <td>{{ \Carbon\Carbon::parse($cert->issued_date)->format('F d, Y') }}</td>

                        <td>
                            @if($cert->qr_code_value)
                                <i class="fa-solid fa-qrcode text-green-600"></i>
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            <button onclick="viewCertificate({{ $cert->id }})">
                                <i class="fa-solid fa-eye"></i>
                            </button>

                            <button onclick="downloadCertificate({{ $cert->id }})">
                                <i class="fa-solid fa-download"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No certificates</td>
                    </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- ================= MODALS ================= -->

<!-- GENERATE -->
<div id="generateModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h2>Generate Certificate</h2>

        <form id="generateForm">

            <select name="student_id" id="studentSelect" required class="w-full border px-3 py-2 mb-3"></select>

            <select name="template_id" id="templateSelect" required class="w-full border px-3 py-2 mb-3"></select>

            <div id="dynamicFields"></div>

            <div class="flex gap-2 justify-end">
                <button type="button" onclick="closeGenerateModal()">Cancel</button>
                <button type="button" onclick="previewCertificate()">Preview</button>
                <button type="submit">Generate</button>
            </div>

        </form>
    </div>
</div>

<!-- VIEW MODAL -->
<div id="viewModal" class="modal" style="display:none;">
    <div class="modal-content modal-lg">
        <span onclick="closeViewModal()" style="cursor:pointer;">&times;</span>
        <div id="certificateDetails"></div>
    </div>
</div>

<!-- ================= JS ================= -->

<script>
let selectedCerts = [];

/* SELECT ALL */
document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('.cert-checkbox').forEach(cb => {
        cb.checked = this.checked;
        toggleSelection(cb.value, this.checked);
    });
});

function toggleSelection(id, checked) {
    if (checked) {
        if (!selectedCerts.includes(id)) selectedCerts.push(id);
    } else {
        selectedCerts = selectedCerts.filter(c => c != id);
    }

    document.getElementById('printBtn').disabled = selectedCerts.length === 0;
    document.getElementById('emailBtn').disabled = selectedCerts.length === 0;
}

/* FILTER */
function filterCertificates() {
    let type = document.getElementById('typeFilter').value;
    let search = document.getElementById('searchStudent').value.toLowerCase();

    let count = 0;

    document.querySelectorAll('#certificatesTableBody tr').forEach(row => {
        let match =
            (!type || row.dataset.type === type) &&
            (!search || row.dataset.student.includes(search));

        row.style.display = match ? '' : 'none';
        if (match) count++;
    });

    document.getElementById('certCount').innerText = count + " certificates";
}

/* MODAL */
function openGenerateModal() {
    loadStudents();
    loadTemplates();
    document.getElementById('generateModal').style.display = 'flex';
}

function closeGenerateModal() {
    document.getElementById('generateModal').style.display = 'none';
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

/* LOAD DATA */
function loadStudents() {
    fetch('/api/certificates/students')
    .then(res => res.json())
    .then(data => {
        let html = '<option value="">Select Student</option>';
        data.forEach(s => {
            html += `<option value="${s.id}">${s.name}</option>`;
        });
        document.getElementById('studentSelect').innerHTML = html;
    });
}

function loadTemplates() {
    fetch('/api/certificate-templates')
    .then(res => res.json())
    .then(data => {
        let html = '<option value="">Select Template</option>';
        data.forEach(t => {
            html += `<option value="${t.id}">${t.name}</option>`;
        });
        document.getElementById('templateSelect').innerHTML = html;
    });
}

/* PREVIEW */
function previewCertificate() {
    let form = document.getElementById('generateForm');

    fetch('/certificates/preview', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: new FormData(form)
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('certificateDetails').innerHTML = html;
        document.getElementById('viewModal').style.display = 'flex';
    });
}

/* GENERATE */
document.getElementById('generateForm').addEventListener('submit', function(e){
    e.preventDefault();

    fetch('/api/certificates/generate', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            location.reload();
        }
    });
});

/* ACTIONS */
function viewCertificate(id) {
    fetch(`/certificates/${id}`)
    .then(res => res.text())
    .then(html => {
        document.getElementById('certificateDetails').innerHTML = html;
        document.getElementById('viewModal').style.display = 'flex';
    });
}

function downloadCertificate(id) {
    window.open(`/certificates/${id}/download`);
}

/* INIT */
filterCertificates();
</script>

</body>
</html>
