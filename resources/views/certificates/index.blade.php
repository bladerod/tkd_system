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

        <!-- HEADER -->
        <h1 class="text-3xl font-bold mb-4">Certificates</h1>

        <!-- ACTIONS -->
        <div class="flex gap-3 mb-4">
            <button onclick="openModal()" class="btn-action">
                Generate Certificate
            </button>

            <button onclick="printSelected()" id="printBtn" disabled class="btn-action">
                Print
            </button>

            <button onclick="emailSelected()" id="emailBtn" disabled class="btn-action">
                Email
            </button>

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

            <tbody id="tableBody">
                @forelse ($certificates as $cert)
                    <tr>
                        <td>
                            <input type="checkbox" class="checkbox" value="{{ $cert->id }}">
                        </td>

                        <td>{{ $cert->student->fname }} {{ $cert->student->lname }}</td>

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
    <div id="modal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">

        <div style="background:white; padding:20px; width:400px;">
            <h2 class="text-xl mb-3">Generate Certificate</h2>

            <form id="form">
                <select name="student_id" id="studentSelect" class="w-full border p-2 mb-2" required></select>

                <select name="certificate_type" class="w-full border p-2 mb-2" required>
                    <option value="promotion">Belt Promotion</option>
                    <option value="competition">Competition</option>
                </select>

                <input type="text" name="title" placeholder="Title" class="w-full border p-2 mb-2" required>

                <textarea name="description" placeholder="Description" class="w-full border p-2 mb-2"></textarea>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()"
                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-colors">Cancel</button>
                    <button type="submit" class="btn-action">Generate</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= JS ================= -->
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/js/navbarDrop.js'])
    <script>

        let selected = [];

        /* OPEN MODAL */
        function openModal() {
            loadStudents();
            document.getElementById('modal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        /* LOAD STUDENTS */
        function loadStudents() {
            fetch('api/certificates/students')
                .then(res => res.json())
                .then(data => {
                    const options = data.map(s => `<option value="${s.id}">${s.name}</option>`);
                    document.getElementById('studentSelect').innerHTML = '<option value="">Select Student</option>' + options.join('');
                })
                .catch(err => console.error("Could not load students:", err)); // Always good to have a backup!
        }

        /* SUBMIT */
        document.getElementById('form').addEventListener('submit', function (e) {
            e.preventDefault();

            fetch('/api/certificates', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: new FormData(this)
            })
                .then(res => res.json())
                .then(() => location.reload());
        });

        /* SELECT */
        document.getElementById('selectAll').addEventListener('change', function () {
            selected = [];
            document.querySelectorAll('.checkbox').forEach(cb => {
                cb.checked = this.checked;
                if (this.checked) selected.push(cb.value);
            });
            toggleBtns();
        });

        document.querySelectorAll('.checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                if (this.checked) {
                    selected.push(this.value);
                } else {
                    selected = selected.filter(i => i != this.value);
                }
                toggleBtns();
            });
        });

        function toggleBtns() {
            document.getElementById('printBtn').disabled = selected.length === 0;
            document.getElementById('emailBtn').disabled = selected.length === 0;
        }

        /* ACTIONS */
        function viewCert(id) {
            window.open(`/certificates/${id}`);
        }

        function downloadCert(id) {
            window.open(`/certificates/${id}/download`);
        }

        function deleteCert(id) {
            if (!confirm('Delete certificate?')) return;

            fetch(`/api/certificates/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(() => location.reload());
        }

        function openVerifyPage() {
            window.open('/verify');
        }

    </script>

</body>

</html>