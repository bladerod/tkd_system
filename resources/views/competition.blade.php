<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
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
                        <span class="text-[#1C1C1D] font-medium">Competitions</span>
                    </div>
                    <h1 class="text-4xl font-bold text-[#1C1C1D]">Competitions</h1>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">List of Competitions</h6>
                                    </div>
                                </div>
                                <div style="height: 40vh" class=" p-3" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <div class="justify-evenly float-end">
                                        <button class="bg-[#62b236] p-3 rounded-xl hover:bg-[#6abc3a] font-medium text-white">
                                            Add Competition
                                        </button>
                                    </div>
                                    <div class="mt-10 ms-4">
                                        <!-- Filter Header with Icon -->
                                        <div style="visibility:hidden;" class="flex items-center gap-2 mb-4 ">
                                            <h1 class="font-semibold text-gray-700">Filter Options</h1>
                                        </div>
                                        
                                        <!-- Filter Controls in a Grid Layout -->
                                        <div class="grid grid-cols-12 gap-4 items-end">
                                            
                                            <!-- Table Section -->
                                            <div style="width: 1350%" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                                <!-- Table Header with Title -->
                                                
                                                
                                                <!-- Table -->
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-sm">
                                                        <!-- Table Head -->
                                                        <thead>
                                                            <tr class="bg-gray-50 border-b border-gray-200">
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participants</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Results</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medals</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certificate</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fees</th>
                                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        
                                                        <!-- Table Body -->
                                                        <tbody class="divide-y divide-gray-100">
                                                            <!-- Row 1 - IN -->
                                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Interclub TKD Sparring</td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <div class="flex items-center">
                                                                        <div class="ml-3">
                                                                            <p class="text-sm font-medium text-gray-800">24</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">1st–3rd Place</td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">2 Gold, 2 Silver, 2 Bronze</td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">View</td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <span class="px-2 py-1 font-medium">₱500</span>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Competition">
                                                                        <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            
                                                            <!-- Row 2 - OUT -->
                                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Regional Poomsae Meet</td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <div class="flex items-center">
                                                                        <div class="ml-3">
                                                                            <p class="text-sm font-medium text-gray-800">18</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Final Rankings</td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">3 Gold, 3 Silver, 3 Bronze</td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">View</td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <span class="px-2 py-1 font-medium text-right">₱1,000</span>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <button class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group" title="Edit Competition">
                                                                        <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <!-- Table Footer with Pagination -->
                                                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                                                    <div class="text-xs text-gray-400">
                                                        Showing 1 to 2 of 2 entries
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <button class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-500 hover:bg-gray-50 disabled:opacity-50" disabled>
                                                            Previous
                                                        </button>
                                                        <button class="px-3 py-1 text-xs bg-[#1C1C1D] text-white rounded hover:bg-[#2C2C2D]">
                                                            1
                                                        </button>
                                                        <button class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-500 hover:bg-gray-50">
                                                            2
                                                        </button>
                                                        <button class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-500 hover:bg-gray-50">
                                                            3
                                                        </button>
                                                        <button class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-500 hover:bg-gray-50">
                                                            Next
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
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
    {{-- start of modals --}}


    {{-- end of modals --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/dashboard.js'])
</body>
</html>