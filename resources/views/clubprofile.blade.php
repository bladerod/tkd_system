<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD</title>
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
                        <span class="text-[#1C1C1D] font-medium">Club Profile</span>
                    </div>
                    <h1 class="text-4xl font-bold text-[#1C1C1D]">Settings</h1>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Club Profile</h6>
                                    </div>
                                </div>
                                <div class="p-8" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <!-- Club Profile Form - Exact match to image -->
                                    <div class="">
                                        <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3">Basic Club Information</h2>
                                        <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 10px;">
                                        <!-- First row - 3 columns -->
                                        <div class="grid grid-cols-3 gap-4 mb-4">
                                            <!-- Club Name -->
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">
                                                    Club Name<span class="text-red-500 ml-0.5">*</span>
                                                </label>
                                                <input type="text" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] focus:border-[#1C1C1D]">
                                            </div>

                                            <!-- Club Acronym / Short Name -->
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">
                                                    Club Acronym / Short Name
                                                </label>
                                                <input type="text" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] focus:border-[#1C1C1D]">
                                            </div>

                                            <!-- Founded Year -->
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">
                                                    Founded Year
                                                </label>
                                                <input type="text" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] focus:border-[#1C1C1D]">
                                            </div>
                                        </div>
                                        
                                        <!-- Club Description - Full width -->
                                        <div class="mb-4">
                                            <label class="block text-sm text-gray-600 mb-1">
                                                Club Description
                                            </label>
                                            <textarea rows="4" 
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] focus:border-[#1C1C1D] resize-none"></textarea>
                                        </div>
                                        
                                        <!-- Second row - 2 columns -->
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <!-- Email Address -->
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">
                                                    Email Address
                                                </label>
                                                <input type="email" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] focus:border-[#1C1C1D]">
                                            </div>

                                            <!-- Contact Number -->
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">
                                                    Contact Number
                                                </label>
                                                <input type="text" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] focus:border-[#1C1C1D]">
                                            </div>
                                        </div>

                                        <!-- Club Address - Full width -->
                                        <div class="mb-4">
                                            <label class="block text-sm text-gray-600 mb-1">
                                                Club Address
                                            </label>
                                            <textarea rows="3" 
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] focus:border-[#1C1C1D] resize-none"></textarea>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="flex items-center justify-end gap-3 pt-6">
                                            <button type="button" 
                                                    class="px-8 py-2 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-gray-300 transition-colors">
                                                Cancel
                                            </button>
                                            <button type="submit" 
                                                    class="px-8 py-2 text-sm text-white bg-[#1C1C1D] rounded-md hover:bg-[#2f2f2f] focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] transition-colors">
                                                Save
                                            </button>
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