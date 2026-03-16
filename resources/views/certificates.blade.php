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

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn-action" onclick="openGenerateModal()">
                <span><i class="fa-solid fa-floppy-disk"></i></span> Generate
            </button>
            <button class="btn-action" onclick="bulkGenerate()" id="bulkBtn" style="display:none">
                <span><i class="fa-solid fa-users"></i></span> Bulk Generate
            </button>
            <button class="btn-action" onclick="printSelected()" id="printBtn" disabled>
                <span><i class="fa-solid fa-print"></i></span> Print
            </button>
            <button class="btn-action" onclick="emailSelected()" id="emailBtn" disabled>
                <span><i class="fa-solid fa-envelope"></i></span> Email
            </button>
        </div>

        <!-- Filters -->
        <div class="filters mb-4 flex gap-3">
            <select id="typeFilter" onchange="filterCertificates()" class="border rounded px-3 py-2">
                <option value="">All Types</option>
                <option value="belt_promotion">Belt Promotion</option>
                <option value="competition">Competition</option>
                <option value="participation">Participation</option>
                <option value="achievement">Achievement</option>
            </select>
            <input type="text" id="searchStudent" placeholder="Search student..."
                   onkeyup="filterCertificates()" class="border rounded px-3 py-2">
        </div>

        <!-- Certificates Table -->
        <div class="table-card">
            <div class="table-header">
                <h2>Certificates List</h2>
                <span id="certCount" class="text-sm text-gray-500">0 certificates</span>
            </div>

            <div class="table-content">
                <table class="certificate-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"></th>
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
                            <tr data-id="{{ $cert['id'] }}"
                                data-type="{{ $cert['type'] }}"
                                data-student="{{ strtolower($cert['student_name']) }}">
                                <td><input type="checkbox" class="cert-checkbox" value="{{ $cert['id'] }}"></td>
                                <td>{{ $cert['student_name'] }}</td>
                                <td>
                                    <span class="badge badge-{{ $cert['type'] }}">
                                        {{ str_replace('_', ' ', ucfirst($cert['type'])) }}
                                    </span>
                                </td>
                                <td>{{ $cert['title'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($cert['date'])->format('F d, Y') }}</td>
                                <td class="text-center">
                                    @if($cert['has_qr'])
                                        <i class="fa-solid fa-qrcode text-green-600 text-xl" title="{{ $cert['qr_code'] }}"></i>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn-view" onclick="viewCertificate({{ $cert['id'] }})">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button class="btn-view" onclick="downloadCertificate({{ $cert['id'] }})">
                                        <i class="fa-solid fa-download"></i>
                                    </button>
                                    <button class="btn-view" onclick="verifyLink('{{ $cert['verification_url'] }}')">
                                        <i class="fa-solid fa-link"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">
                                    No certificates found. Click "Generate" to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Generate Certificate Modal -->
<div id="generateModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Generate Certificate</h2>
            <span class="close" onclick="closeGenerateModal()">&times;</span>
        </div>

        <form id="generateForm" onsubmit="return submitGenerate(event)">
            <div class="form-group mb-3">
                <label>Student</label>
                <select name="student_id" id="studentSelect" required class="w-full border rounded px-3 py-2">
                    <option value="">Select Student</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Certificate Type</label>
                <select name="type" required class="w-full border rounded px-3 py-2">
                    <option value="belt_promotion">Belt Promotion</option>
                    <option value="competition">Competition</option>
                    <option value="participation">Participation</option>
                    <option value="achievement">Achievement</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Title</label>
                <input type="text" name="title" required class="w-full border rounded px-3 py-2"
                       placeholder="e.g., Belt Promotion - Yellow Belt">
            </div>

            <div class="form-group mb-3">
                <label>Description (Optional)</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
            </div>

            <div class="form-actions flex justify-end gap-2">
                <button type="button" onclick="closeGenerateModal()" class="px-4 py-2 border rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                    <i class="fa-solid fa-floppy-disk"></i> Generate
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Certificate Modal -->
<div id="viewModal" class="modal" style="display:none;">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2>Certificate Details</h2>
            <span class="close" onclick="closeViewModal()">&times;</span>
        </div>
        <div id="certificateDetails" class="p-4">
            <!-- Dynamic content -->
        </div>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@vite(['resources/js/dashboard.js'])

<script>
let selectedCerts = [];

function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.cert-checkbox');
    const selectAll = document.getElementById('selectAll').checked;

    checkboxes.forEach(cb => {
        cb.checked = selectAll;
        toggleSelection(cb.value, selectAll);
    });

    updateActionButtons();
}

function toggleSelection(id, checked) {
    if (checked) {
        if (!selectedCerts.includes(id)) selectedCerts.push(id);
    } else {
        selectedCerts = selectedCerts.filter(c => c != id);
    }
    updateActionButtons();
}

document.querySelectorAll('.cert-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        toggleSelection(this.value, this.checked);
    });
});

function updateActionButtons() {
    const hasSelection = selectedCerts.length > 0;
    document.getElementById('printBtn').disabled = !hasSelection;
    document.getElementById('emailBtn').disabled = !hasSelection;

    // Show bulk button only if 2+ selected
    document.getElementById('bulkBtn').style.display = selectedCerts.length >= 2 ? 'inline-flex' : 'none';
}

function filterCertificates() {
    const type = document.getElementById('typeFilter').value;
    const search = document.getElementById('searchStudent').value.toLowerCase();
    const rows = document.querySelectorAll('#certificatesTableBody tr');

    let visible = 0;
    rows.forEach(row => {
        const rowType = row.dataset.type;
        const rowStudent = row.dataset.student;

        const matchType = !type || rowType === type;
        const matchSearch = !search || rowStudent.includes(search);

        if (matchType && matchSearch) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('certCount').textContent = visible + ' certificates';
}

async function openGenerateModal() {
    // Load students
    const response = await fetch('/api/certificates/students');
    const students = await response.json();

    const select = document.getElementById('studentSelect');
    select.innerHTML = '<option value="">Select Student</option>' +
        students.map(s => `<option value="${s.id}">${s.name} (${s.belt} belt)</option>`).join('');

    document.getElementById('generateModal').style.display = 'flex';
}

function closeGenerateModal() {
    document.getElementById('generateModal').style.display = 'none';
    document.getElementById('generateForm').reset();
}

async function submitGenerate(e) {
    e.preventDefault();

    const formData = new FormData(e.target);

    try {
        const response = await fetch('/api/certificates/generate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            alert('Certificate generated successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to generate'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to generate certificate');
    }

    return false;
}

async function viewCertificate(id) {
    try {
        const response = await fetch(`/api/certificates/${id}`);
        const data = await response.json();

        document.getElementById('certificateDetails').innerHTML = `
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <img src="${data.student.photo ? '/storage/' + data.student.photo : '/images/default-avatar.png'}"
                         class="w-32 h-32 rounded-full object-cover mx-auto mb-4">
                    <h3 class="text-center font-bold text-xl">${data.student.name}</h3>
                    <p class="text-center text-gray-500">${data.student.belt} belt</p>
                </div>
                <div class="space-y-3">
                    <p><strong>Type:</strong> ${data.type}</p>
                    <p><strong>Title:</strong> ${data.title}</p>
                    <p><strong>Description:</strong> ${data.description || 'N/A'}</p>
                    <p><strong>Issued Date:</strong> ${new Date(data.issued_date).toLocaleDateString()}</p>
                    <p><strong>Issued By:</strong> ${data.issued_by}</p>
                    <p><strong>QR Code:</strong> <code class="bg-gray-100 px-2 py-1 rounded">${data.qr_code}</code></p>
                </div>
            </div>
            <div class="mt-4 text-center">
                ${data.pdf_url ? `
                    <a href="${data.pdf_url}" target="_blank" class="btn-action inline-block">
                        <i class="fa-solid fa-file-pdf"></i> View PDF
                    </a>
                ` : ''}
                <button onclick="verifyLink('${data.verification_url}')" class="btn-action">
                    <i class="fa-solid fa-check-circle"></i> Verify Link
                </button>
            </div>
        `;

        document.getElementById('viewModal').style.display = 'flex';
    } catch (error) {
        alert('Failed to load certificate details');
    }
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

function downloadCertificate(id) {
    window.open(`/certificates/${id}/download`, '_blank');
}

function verifyLink(url) {
    window.open(url, '_blank');
}

function printSelected() {
    if (selectedCerts.length === 0) return;
    window.open(`/certificates/print?ids=${selectedCerts.join(',')}`, '_blank');
}

async function emailSelected() {
    if (selectedCerts.length === 0) return;
    if (!confirm(`Email ${selectedCerts.length} certificate(s)?`)) return;

    for (const id of selectedCerts) {
        try {
            await fetch(`/api/certificates/${id}/email`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
        } catch (e) {
            console.error('Failed to email certificate ' + id);
        }
    }

    alert('Emails sent!');
}

async function bulkGenerate() {
    const examId = prompt('Enter Belt Exam ID for bulk generation:');
    if (!examId) return;

    try {
        const response = await fetch('/api/certificates/bulk-generate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ exam_id: examId })
        });

        const data = await response.json();
        if (data.success) {
            alert(`Generated ${data.count} certificates!`);
            location.reload();
        }
    } catch (error) {
        alert('Bulk generation failed');
    }
}

// Close modals on outside click
window.onclick = function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.style.display = 'none';
    }
}

// Initialize count
filterCertificates();
</script>

</body>
</html>
