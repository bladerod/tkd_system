<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD - Branding Settings</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])
</head>
<body class="bg-gray-50">
    <!-- navbar -->
    @include("includes.navbar")
    <!-- Sidebar -->
    @include('includes.sidebar')
    <!-- Main Content -->
    <div class="container-fluid m-0">
        <div class="row">
             <main class="ml-64 p-6"> 
                <div class="container-fluid">
                    <!-- Breadcrumb -->
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                        <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
                        <span>/</span>
                        <span class="text-[#1C1C1D] font-medium">Settings</span>
                        <span>/</span>
                        <span class="text-[#1C1C1D] font-medium">Branding</span>
                    </div>
                    <h1 class="text-4xl font-bold text-[#1C1C1D]">Settings</h1>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Branding Settings</h6>
                                    </div>
                                </div>
                                <div class="p-8" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <!-- Branding Settings Form -->
                                    <div class="">

                                        <!-- System Appearance -->
                                        <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3">System Appearance</h2>
                                        <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                        <div class="grid grid-cols-1 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">Logo</label>
                                                <input type="file" class="w-full h-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                            </div>
                                            
                                        </div>

                                        <!-- Certificate Branding -->
                                        <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3">Certificate Branding</h2>
                                        <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">Certificate Header Text</label>
                                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                            </div>
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">Certificate Signature Name</label>
                                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">Signature Position</label>
                                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                            </div>
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">Official Seal Upload</label>
                                                <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="flex items-center justify-end gap-3 pt-6">
                                            <button type="button" class="px-8 py-2 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                                            <button type="submit" class="px-8 py-2 text-sm text-white bg-[#1C1C1D] rounded-md hover:bg-[#2f2f2f] transition-colors">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/dashboard.js'])
</body>
</html>