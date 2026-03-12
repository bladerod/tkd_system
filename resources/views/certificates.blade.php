<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css'])
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/certificates.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <title>Certificates</title>
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
            <button class="btn-action"><span><i class="fa-solid fa-floppy-disk"></i></span> Generate</button>
            <button class="btn-action"><span><i class="fa-solid fa-print"></i></span> Print</button>
            <button class="btn-action"><span><i class="fa-solid fa-envelope"></span></i> Email</button>
            <button class="btn-action"><span><i class="fa-solid fa-up-right-from-square"></i></span> Verify Link</button>
        </div>

        <!-- Certificates Table -->
        <div class="table-card">
            <div class="table-header">
                <h2>Certificates List</h2>
            </div>

            <div class="table-content">
                <table class="certificate-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>QR</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Juan</td>
                            <td>Belt Promotion</td>
                            <td>September 20, 2025</td>
                            <td>Yes</td>
                            <td>
                                <a href="#" class="btn-view">View</a>
                            </td>
                        </tr>

                        <tr>
                            <td>Dimitri</td>
                            <td>Belt Promotion</td>
                            <td>September 20, 2025</td>
                            <td>Yes</td>
                            <td>
                                <a href="#" class="btn-view">View</a>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>

    </div>
</div>

</body>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/js/dashboard.js'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css'])
</html>
