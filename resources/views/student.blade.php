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
            <div class="dropdown ">
            <label for="features">Belt</label>
                <select name="features">
                    @foreach ($beltLevels as $belt)
                    <option value="feature1">{{ $belt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="dropdown">
            <label for="features">Status</label>
            <select name="features">
                <option value="feature1">Active</option>
                <option value="feature2">Inactive</option>
            </select>
            </div>
            <div class="dropdown">
            <label for="features">Class</label>
            <select name="features">
                <option value="feature1">Class 1</option>
                <option value="feature2">Class 2</option>
                <option value="feature3">Class 3</option>
            </select>
            </div>
            <div class="dropdown">
            <label for="features">Instructor</label>
            <select name="features">
                <option value="feature1">Instructor 1</option>
                <option value="feature2">Instructor 2</option>
                <option value="feature3">Instructor 3</option>
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

                    <tbody>
                        <tr>
                            <td>Juan Dela Cruz</td>
                            <td><span class="belt yellow">Yellow</span></td>
                            <td><span class="status active">Active</span></td>
                            <td>Maria</td>
                            <td class="balance paid">₱0</td>
                            <td>92%</td>
                            <td><a href="#" class="btn-view" onclick="openModal()">View</a>
</td>
                        </tr>

                        <tr>
                            <td>Ana Cruz</td>
                            <td><span class="belt green">Green</span></td>
                            <td><span class="status active">Active</span></td>
                            <td>Yuji</td>
                            <td class="balance due">₱1,200</td>
                            <td>89%</td>
                            <td><a href="#" class="btn-view" onclick="openModal()">View</a>
</td>
                        </tr>

                        <tr>
                            <td>Tyberiy</td>
                            <td><span class="belt black">Black</span></td>
                            <td><span class="status active">Active</span></td>
                            <td>Ryomen Sukuna</td>
                            <td class="balance due">₱1,000</td>
                            <td>90%</td>
                            <td><a href="#" class="btn-view" onclick="openModal()">View</a>
</td>
                        </tr>

                        <tr>
                            <td>Alfonsa</td>
                            <td><span class="belt yellow">Yellow</span></td>
                            <td><span class="status active">Active</span></td>
                            <td>Gojo Satoru</td>
                            <td class="balance due">₱4,200</td>
                            <td>97%</td>
                            <td><a href="#" class="btn-view" onclick="openModal()">View</a>
</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Modal Overlay -->
<div id="studentModal" class="modal">

    <div class="modal-content">

        <div class="modal-header">
            <h2>Student Profile</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab(this)">Profile</button>
            <button class="tab" onclick="switchTab(this)">Attendance</button>
            <button class="tab" onclick="switchTab(this)">Billing</button>
            <button class="tab" onclick="switchTab(this)">Competition</button>
            <button class="tab" onclick="switchTab(this)">Certificates</button>
            <button class="tab" onclick="switchTab(this)">Progress</button>
            <button class="tab" onclick="switchTab(this)">Chat</button>
            <button class="tab" onclick="switchTab(this)">Documents</button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="tabContent">
            <p><strong>Profile Information</strong></p>
            <p>Name: Juan Dela Cruz</p>
            <p>Belt: Yellow</p>
            <p>Status: Active</p>
        </div>

    </div>

</div>


    </div>
</div>

</body>
</html>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    @vite(['resources/js/app.js'])
        @vite(['resources/js/dashboard.js'])
<script>
function openModal() {
    document.getElementById("studentModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("studentModal").style.display = "none";
}

window.onclick = function(e) {
    const modal = document.getElementById("studentModal");
    if (e.target === modal) {
        modal.style.display = "none";
    }
}

function switchTab(button) {
    // Remove active from all tabs
    document.querySelectorAll(".tab").forEach(tab => {
        tab.classList.remove("active");
    });

    // Add active to clicked tab
    button.classList.add("active");

    // Change content dynamically
    const content = document.getElementById("tabContent");
    content.innerHTML = "<p><strong>" + button.innerText + " Section</strong></p><p>Content for " + button.innerText + " will appear here.</p>";
}
</script>

