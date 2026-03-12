<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css'])
     @vite(['resources/css/parent.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])
    <title>Parent</title>
</head>
<body>

@include("includes.navbar")
@include("includes.sidebar")

<div class="main-content parents-page">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 mt-1">
        <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
        <span>/</span>
        <span class="text-[#1C1C1D] font-medium">Parent</span>
    </div>
    <h1 class="text-4xl font-bold text-[#1C1C1D] mb-4">Parent</h1>
    <div class="content-card">

        <!-- Page Title -->
        <div class="table-header">
            <h2 class="report-header">Parent List</h2>
        </div>

        <!-- Parent Table -->
        <div class="table-card">
            <div class="table-content">
                <table class="parent-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Children</th>
                            <th>Contact</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($parentList as $parent)
                            <tr>
                                <td>{{ $parent['fname'] }}</td>
                                <td>2</td>
                                <td>{{ $parent['mobile'] }}</td>
                                <td class="balance due text-end">₱1,200</td>
                                <td>
                                    <span class="status active">{{ $parent['status']}}</span>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn-view" onclick="openModal()">View</a>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>Maria Cruz</td>
                            <td>2</td>
                            <td>09423XXXXX</td>
                            <td class="balance due text-end ">₱1,200</td>
                            <td><span class="status active">Active</span></td>
                            <td class="text-center">
                                <a href="#" class="btn-view " onclick="openModal()">View</a>
                            </td>
                        </tr>

                        <tr>
                            <td>John Smith</td>
                            <td>1</td>
                            <td>0932XXXXX</td>
                            <td class="text-end balance due ">₱3,000</td>
                            <td><span class="status active">Active</span></td>
                            <td class="text-center">
                                <a href="#" class="btn-view" onclick="openModal()">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div id="parentModal" class="modal">
        <div class="modal-content">

            <div class="modal-header">
                <h2>Parent Profile</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" onclick="switchTab(this)">Children</button>
                <button class="tab" onclick="switchTab(this)">Family Billing</button>
                <button class="tab" onclick="switchTab(this)">Payments</button>
                <button class="tab" onclick="switchTab(this)">Chat</button>
                <button class="tab" onclick="switchTab(this)">Activity Log</button>
                <button class="tab" onclick="switchTab(this)">Notifications</button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="tabContent">
                <p><strong>Children Information</strong></p>
                <p>Name: Maria Cruz</p>
                <p>Children: 2</p>
                <p>Status: Active</p>
            </div>

        </div>
    </div>

</div>
    @vite(['resources/js/dashboard.js'])
    @vite(['resources/js/app.js'])
</body>
</html>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
<script>
function openModal() {
    document.getElementById("parentModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("parentModal").style.display = "none";
}

window.onclick = function(e) {
    const modal = document.getElementById("parentModal");
    if (e.target === modal) {
        modal.style.display = "none";
    }
}

function switchTab(button) {
    document.querySelectorAll(".tab").forEach(tab => {
        tab.classList.remove("active");
    });

    button.classList.add("active");

    const content = document.getElementById("tabContent");
    content.innerHTML =
        "<p><strong>" + button.innerText + " Section</strong></p>" +
        "<p>Content for " + button.innerText + " will appear here.</p>";
}
</script>
