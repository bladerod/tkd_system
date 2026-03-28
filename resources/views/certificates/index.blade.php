<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css'])
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/certificates.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <title>Certificates</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@include("includes.navbar")
@include("includes.sidebar")

<div class="main-content">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 mt-1">
        <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
        <span>/</span>
        <span class="text-[#1C1C1D] font-medium">Certificate</span>
    </div>

    <h1 class="text-4xl font-bold text-[#1C1C1D] mb-4">Certificate</h1>

    <div class="content-card">

        <!-- ACTIONS -->
        <div class="action-buttons">
            <button class="btn-action" onclick="openGenerateModal()">
                <i class="fa-solid fa-floppy-disk"></i> Generate
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
            <select id="typeFilter" onchange="filterCertificates()" class="border rounded px-3 py-2">
                <option value="">All Types</option>
                <option value="belt_promotion">Belt Promotion</option>
                <option value="competition">Competition</option>
                <option value="participation">Participation</option>
                <option value="achievement">Achievement</option>
            </select>

            <input type="text" id="searchStudent"
                   placeholder="Search student..."
                   onkeyup="filterCertificates()"
                   class="border rounded px-3 py-2">
        </div>

        <!-- TABLE -->
        <div class="table-card">
            <div class="table-header">
                <h2>Certificates List</h2>
                <span id="certCount" class="text-sm text-gray-500">0 certificates</span>
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
                            <th>QR Code</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="certificatesTableBody">
                        @forelse ($certificates as $cert)
                            <tr
                                data-id="{{ $cert->id }}"
                                data-type="{{ $cert->type }}"
                                data-student="{{ strtolower($cert->student_name) }}"
                            >
                                <td>
                                    <input type="checkbox"
                                           class="cert-checkbox"
                                           value="{{ $cert->id }}">
                                </td>

                                <td>{{ $cert->student_name }}</td>

                                <td>
                                    <span class="badge badge-{{ $cert->type }}">
                                        {{ ucfirst(str_replace('_',' ', $cert->type)) }}
                                    </span>
                                </td>

                                <td>{{ $cert->title }}</td>

                                <td>{{ \Carbon\Carbon::parse($cert->date)->format('F d, Y') }}</td>

                                <td class="text-center">
                                    @if($cert->has_qr)
                                        <i class="fa-solid fa-qrcode text-green-600 text-xl"
                                           title="{{ $cert->qr_code }}"></i>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td>
                                    <button class="btn-view" onclick="viewCertificate({{ $cert->id }})">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <button class="btn-view" onclick="downloadCertificate({{ $cert->id }})">
                                        <i class="fa-solid fa-download"></i>
                                    </button>

                                    <button class="btn-view" onclick="verifyLink('{{ $cert->verification_url }}')">
                                        <i class="fa-solid fa-link"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">
                                    No certificates found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- GENERATE MODAL -->
<div id="generateModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Generate Certificate</h2>
            <span class="close" onclick="closeGenerateModal()">&times;</span>
        </div>

        <form id="generateForm" onsubmit="return submitGenerate(event)">
            <select name="student_id" id="studentSelect" required class="w-full border rounded px-3 py-2 mb-3">
                <option value="">Select Student</option>
            </select>

            <select name="type" required class="w-full border rounded px-3 py-2 mb-3">
                <option value="belt_promotion">Belt Promotion</option>
                <option value="competition">Competition</option>
                <option value="participation">Participation</option>
                <option value="achievement">Achievement</option>
            </select>

            <input type="text" name="title" required
                   class="w-full border rounded px-3 py-2 mb-3"
                   placeholder="Title">

            <textarea name="description"
                      class="w-full border rounded px-3 py-2 mb-3"></textarea>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeGenerateModal()">Cancel</button>
                <button type="submit">Generate</button>
            </div>
        </form>
    </div>
</div>

<!-- VIEW MODAL -->
<div id="viewModal" class="modal" style="display:none;">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2>Certificate Details</h2>
            <span class="close" onclick="closeViewModal()">&times;</span>
        </div>
        <div id="certificateDetails"></div>
    </div>
</div>

<script>
let selectedCerts = [];

// SELECT ALL
document.getElementById('selectAll').addEventListener('change', function () {
    const checked = this.checked;
    document.querySelectorAll('.cert-checkbox').forEach(cb => {
        cb.checked = checked;
        toggleSelection(cb.value, checked);
    });
});

document.querySelectorAll('.cert-checkbox').forEach(cb => {
    cb.addEventListener('change', function () {
        toggleSelection(this.value, this.checked);
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
    document.getElementById('bulkBtn').style.display = selectedCerts.length >= 2 ? 'inline-flex' : 'none';
}

// FILTER
function filterCertificates() {
    const type = document.getElementById('typeFilter').value;
    const search = document.getElementById('searchStudent').value.toLowerCase();

    let count = 0;

    document.querySelectorAll('#certificatesTableBody tr').forEach(row => {
        const match =
            (!type || row.dataset.type === type) &&
            (!search || row.dataset.student.includes(search));

        row.style.display = match ? '' : 'none';
        if (match) count++;
    });

    document.getElementById('certCount').innerText = count + ' certificates';
}

// MODAL
function openGenerateModal() {
    fetch('/api/certificates/students')
        .then(res => res.json())
        .then(data => {
            let html = '<option value="">Select Student</option>';
            data.forEach(s => {
                html += `<option value="${s.id}">${s.name} (${s.belt})</option>`;
            });
            document.getElementById('studentSelect').innerHTML = html;
            document.getElementById('generateModal').style.display = 'flex';
        });
}

function closeGenerateModal() {
    document.getElementById('generateModal').style.display = 'none';
}

// GENERATE
function submitGenerate(e) {
    e.preventDefault();

    fetch('/api/certificates/generate', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: new FormData(e.target)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

// VIEW
function viewCertificate(id) {
    fetch(`/api/certificates/${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('certificateDetails').innerHTML =
                `<h3>${data.student.name}</h3>
                 <p>${data.title}</p>
                 <p>${data.type}</p>`;
            document.getElementById('viewModal').style.display = 'flex';
        });
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

// ACTIONS
function downloadCertificate(id) {
    window.open(`/certificates/${id}/download`);
}

function verifyLink(url) {
    window.open(url);
}

function printSelected() {
    window.open(`/certificates/print?ids=${selectedCerts.join(',')}`);
}

function emailSelected() {
    alert('Email sent (mock)');
}

// INIT
filterCertificates();
</script>

</body>
</html>
