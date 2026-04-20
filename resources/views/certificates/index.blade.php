<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificates</title>

    @vite(['resources/css/app.css'])
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/certificates.css'])

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@include("includes.navbar")
@include("includes.sidebar")

<div class="main-content">

    <!-- HEADER -->
    <h1 class="text-3xl font-bold mb-4">Certificates</h1>

    <!-- ACTIONS -->
    <div class="flex gap-3 mb-4">
        <button onclick="openModal()" class="btn-action">
            Generate Certificate
        </button>

        <button id="printBtn" disabled class="btn-action">Print</button>
        <button id="emailBtn" disabled class="btn-action">Email</button>

        <button onclick="openVerifyPage()" class="btn-action">
            Verify Link
        </button>
    </div>

    <!-- TABLE -->
    <table class="w-full border">
        <thead class="table-header">
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>Student</th>
                <th>Type</th>
                <th>Date</th>
                <th>QR</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        @forelse ($certificates as $cert)
        <tr>
            <td>
                <input type="checkbox" class="checkbox" value="{{ $cert->id }}">
            </td>

            <td>{{ $cert->student->first_name }} {{ $cert->student->last_name }}</td>

            <td>{{ ucfirst($cert->certificate_type) }}</td>

            <td>{{ \Carbon\Carbon::parse($cert->issued_date)->format('M d, Y') }}</td>

            <td>{{ $cert->qr_code_value ? 'Yes' : 'No' }}</td>

            <td class="flex gap-2">
                <button onclick="viewCert({{ $cert->id }})">View</button>
                <button onclick="downloadCert({{ $cert->id }})">Download</button>
                <button onclick="deleteCert({{ $cert->id }})">Delete</button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">No certificates</td>
        </tr>
        @endforelse
        </tbody>
    </table>

</div>

<!-- ================= MODAL ================= -->
<div id="modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">

    <div style="background:white; padding:20px; width:400px;">
        <h2 class="text-xl mb-3">Generate Certificate</h2>

        <form id="form">

            <!-- STUDENT -->
            <select name="student_id" id="studentSelect" class="w-full border p-2 mb-2" required></select>

            <!-- TEMPLATE (IMPORTANT FIX) -->
            <select name="template_id" id="templateSelect" class="w-full border p-2 mb-2" required>
                <option value="">Select Template</option>
            </select>

            <!-- DESCRIPTION -->
            <textarea name="description" placeholder="Description" class="w-full border p-2 mb-2"></textarea>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="bg-red-500 text-white px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="btn-action">
                    Generate
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= JS ================= -->
<script>

let selected = [];

/* OPEN MODAL */
function openModal(){
    loadStudents();
    loadTemplates();
    document.getElementById('modal').style.display = 'flex';
}

function closeModal(){
    document.getElementById('modal').style.display = 'none';
}

/* LOAD STUDENTS */
function loadStudents(){
    fetch('/api/certificates/students')
    .then(res => res.json())
    .then(data => {
        let html = '<option value="">Select Student</option>';
        data.forEach(s => {
            html += `<option value="${s.id}">${s.name}</option>`;
        });
        document.getElementById('studentSelect').innerHTML = html;
    })
    .catch(err => console.error(err));
}

/* LOAD TEMPLATES */
function loadTemplates(){
    fetch('/api/certificate-templates')
    .then(res => res.json())
    .then(data => {
        let html = '<option value="">Select Template</option>';
        data.forEach(t => {
            html += `<option value="${t.id}">${t.name}</option>`;
        });
        document.getElementById('templateSelect').innerHTML = html;
    })
    .catch(err => console.error(err));
}

/* SUBMIT */
document.getElementById('form').addEventListener('submit', function(e){
    e.preventDefault();

    fetch('/api/certificates', {
        method:'POST',
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            location.reload();
        }
    })
    .catch(err => console.error(err));
});

/* SELECT ALL */
document.getElementById('selectAll').addEventListener('change', function(){
    selected = [];

    document.querySelectorAll('.checkbox').forEach(cb=>{
        cb.checked = this.checked;
        if(this.checked) selected.push(cb.value);
    });

    toggleBtns();
});

/* SINGLE SELECT */
document.querySelectorAll('.checkbox').forEach(cb=>{
    cb.addEventListener('change', function(){
        if(this.checked){
            if(!selected.includes(this.value)){
                selected.push(this.value);
            }
        }else{
            selected = selected.filter(i=>i != this.value);
        }

        document.getElementById('selectAll').checked = false;
        toggleBtns();
    });
});

function toggleBtns(){
    document.getElementById('printBtn').disabled = selected.length === 0;
    document.getElementById('emailBtn').disabled = selected.length === 0;
}

/* ACTIONS */
function viewCert(id){
    window.open(`/certificates/${id}`, '_blank');
}

function downloadCert(id){
    window.open(`/certificates/${id}/download`, '_blank');
}

function deleteCert(id){
    if(!confirm('Delete certificate?')) return;

    fetch(`/api/certificates/${id}`, {
        method:'DELETE',
        headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(()=>location.reload());
}

function openVerifyPage(){
    window.open('/verify', '_blank');
}

</script>

</body>
</html>
