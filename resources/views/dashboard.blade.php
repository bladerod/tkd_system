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
                    <!-- Action Buttons -->
                    <div class="grid grid-cols-5 gap-4 mb-8"> 
                        <div>
                            <button command="show-modal" commandfor="dialog" class="button w-full bg-[#1C1C1D] text-white px-4 py-3 rounded-lg hover:bg-[#535353] transition-colors flex items-center justify-center gap-2">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Add Student
                            </button>
                        </div>
                        <div>
                            <button command="show-modal" commandfor="parentDialog"  class="button w-full bg-[#1C1C1D] text-white px-4 py-3 rounded-lg hover:bg-[#535353] transition-colors flex items-center justify-center gap-2">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Add Parent / Guardian
                            </button>
                        </div>
                        <div>
                            <button class="button w-full bg-[#1C1C1D] text-white px-4 py-3 rounded-lg hover:bg-[#535353] transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-file"></i>
                                Record Payment
                            </button>
                        </div>
                        <div>
                            <button class="button w-full bg-[#1C1C1D] text-white px-4 py-3 rounded-lg hover:bg-[#535353] transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-award"></i>
                                Generate Certificate
                            </button>
                        </div>
                        <div>
                            <button class="button w-full bg-[#1C1C1D] text-white px-4 py-3 rounded-lg hover:bg-[#535353] transition-colors flex items-center justify-center gap-2">
                                <i class="fa fa-bullhorn" aria-hidden="true"></i>
                                Send Announcement
                            </button>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-4 gap-6">
                        <!-- Active Students -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-start">
                                <div class="p-3">
                                    <i class="fa fa-user text-7xl text-[#1C1C1D]" aria-hidden="true"></i>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-gray-500 text-sm font-medium mb-1">Active Students</h3>
                                    <p class="text-5xl font-bold text-[#1C1C1D]">186</p>
                                </div>
                            </div>
                        </div>

                        <!-- Active Parents -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-start">
                                <div class="p-3">
                                    <i class="fa fa-users text-7xl text-[#1C1C1D]" aria-hidden="true"></i>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-gray-500 text-sm font-medium mb-1">Active Parents</h3>
                                    <p class="text-5xl font-bold text-[#1C1C1D]">321</p>
                                </div>
                            </div>
                            
                        </div>

                        <!-- Today Attendance -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-start">
                                <div class="p-3">
                                    <i class="fa-solid fa-calendar-check text-7xl text-[#1C1C1D]"></i>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-gray-500 text-sm font-medium mb-1">Today Attendance</h3>
                                    <p class="text-5xl font-bold text-[#1C1C1D]">321</p>
                                </div>
                            </div>
                        </div>

                        <!-- Outstanding Bal -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="flex items-start">
                                <div class="p-3">
                                    <i class="fa-solid fa-credit-card text-7xl text-[#1C1C1D]"></i>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-gray-500 text-sm font-medium mb-1">Outstanding Balance</h3>
                                    <p class="text-5xl font-bold text-[#1C1C1D]">₱245,300</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="grid grid-cols-1">
                        <!-- revenue Chart -->
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                    <div class="card-header pb-0 bg-transparent">
                                        <div class="flex justify-between items-center p-3 ">
                                            <h6 class="text-gray-800 font-semibold ms-[47%] text-2xl">Revenue</h6>
                                            
                                            <!-- Alpine.js Dropdown -->
                                            <div class="relative" x-data="{ open: false, selected: 'Monthly' }">
                                                <!-- Dropdown Button -->
                                                <button @click="open = !open" 
                                                        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-[#1C1C1D] border border-[#1C1C1D] rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#1C1C1D] focus:ring-offset-2 transition-all duration-200"
                                                        type="button">
                                                    <span x-text="selected"></span>
                                                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                                
                                                <!-- Dropdown Menu -->
                                                <ul x-show="open" 
                                                    @click.away="open = false"
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="transform opacity-0 scale-95"
                                                    x-transition:enter-end="transform opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="transform opacity-100 scale-100"
                                                    x-transition:leave-end="transform opacity-0 scale-95"
                                                    class="absolute right-0 z-10 min-w-[160px] mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                                                    <li>
                                                        <a href="#" 
                                                        @click.prevent="selected = 'Monthly'; open = false; window.updateChartPeriod('monthly')" 
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#1C1C1D] transition-colors duration-150 rounded-t-lg"
                                                        :class="{ 'bg-gray-100 text-[#1C1C1D]': selected === 'Monthly' }">
                                                            Monthly
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" 
                                                        @click.prevent="selected = 'Annual'; open = false; window.updateChartPeriod('annual')" 
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#1C1C1D] transition-colors duration-150 rounded-b-lg"
                                                        :class="{ 'bg-gray-100 text-[#1C1C1D]': selected === 'Annual' }">
                                                            Annual
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                        <div class="chart">
                                            <canvas id="chart-monthly-revenue" class="chart-canvas" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Enrollees -->
                        <div class="row mt-4">
                            <div class="col-lg-12 mb-4">
                                <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                    <div class="card-header pb-0 bg-transparent">
                                        <div class="flex justify-between items-center p-3">
                                            <h6 class="text-gray-800 font-semibold ms-[47%] text-2xl">Enrollees</h6>
                                            
                                            <!-- Alpine.js Dropdown -->
                                            <div class="relative" x-data="{ open: false, selected: 'Monthly' }">
                                                <!-- Dropdown Button -->
                                                <button @click="open = !open" 
                                                        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-[#1C1C1D] border border-[#1C1C1D] rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#1C1C1D] focus:ring-offset-2 transition-all duration-200"
                                                        type="button">
                                                    <span x-text="selected"></span>
                                                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                                
                                                <!-- Dropdown Menu -->
                                                <ul x-show="open" 
                                                    @click.away="open = false"
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="transform opacity-0 scale-95"
                                                    x-transition:enter-end="transform opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="transform opacity-100 scale-100"
                                                    x-transition:leave-end="transform opacity-0 scale-95"
                                                    class="absolute right-0 z-10 min-w-[160px] mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                                                    <li>
                                                        <a href="#" 
                                                        @click.prevent="selected = 'Monthly'; open = false; window.updateEnrolleesChartPeriod ('monthly')" 
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#1C1C1D] transition-colors duration-150 rounded-t-lg"
                                                        :class="{ 'bg-gray-100 text-[#1C1C1D]': selected === 'Monthly' }">
                                                            Monthly
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" 
                                                        @click.prevent="selected = 'Annual'; open = false; window.updateEnrolleesChartPeriod('annual')" 
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#1C1C1D] transition-colors duration-150 rounded-b-lg"
                                                        :class="{ 'bg-gray-100 text-[#1C1C1D]': selected === 'Annual' }">
                                                            Annual
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                        <div class="chart">
                                            <canvas id="chart-monthly-enrollees" class="chart-canvas" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                    <!-- Attendance Heatmap -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-gray-800 font-semibold mb-6">Attendance Heatmap</h3>
                        
                        <!-- Days Header -->
                        <div class="grid grid-cols-8 gap-2 mb-4">
                            <div class="text-xs text-gray-500"></div> <!-- Empty cell for alignment -->
                            <div class="text-xs text-gray-500 text-center font-medium">Mon</div>
                            <div class="text-xs text-gray-500 text-center font-medium">Tue</div>
                            <div class="text-xs text-gray-500 text-center font-medium">Wed</div>
                            <div class="text-xs text-gray-500 text-center font-medium">Thu</div>
                            <div class="text-xs text-gray-500 text-center font-medium">Fri</div>
                            <div class="text-xs text-gray-500 text-center font-medium">Sat</div>
                            <div class="text-xs text-gray-500 text-center font-medium">Sun</div>
                        </div>

                        <!-- Heatmap Rows with 3 Orange Colors -->
                        <div class="space-y-2">
                            <!-- 8 AM -->
                            <div class="grid grid-cols-8 gap-2 items-center">
                                <div class="text-xs text-gray-500 font-medium">8 AM</div>
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                            </div>
                            <!-- 10 AM -->
                            <div class="grid grid-cols-8 gap-2 items-center">
                                <div class="text-xs text-gray-500 font-medium">10 AM</div>
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                            </div>
                            <!-- 12 PM -->
                            <div class="grid grid-cols-8 gap-2 items-center">
                                <div class="text-xs text-gray-500 font-medium">12 PM</div>
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                            </div>
                            <!-- 2 PM -->
                            <div class="grid grid-cols-8 gap-2 items-center">
                                <div class="text-xs text-gray-500 font-medium">2 PM</div>
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                            </div>
                            <!-- 4 PM -->
                            <div class="grid grid-cols-8 gap-2 items-center">
                                <div class="text-xs text-gray-500 font-medium">4 PM</div>
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                            </div>
                            <!-- 6 PM -->
                            <div class="grid grid-cols-8 gap-2 items-center">
                                <div class="text-xs text-gray-500 font-medium">6 PM</div>
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                            </div>
                            <!-- 8 PM -->
                            <div class="grid grid-cols-8 gap-2 items-center">
                                <div class="text-xs text-gray-500 font-medium">8 PM</div>
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                            </div>
                            <!-- 10 PM -->
                            <div class="grid grid-cols-8 gap-2 items-center">
                                <div class="text-xs text-gray-500 font-medium">10 PM</div>
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                                <div class="heatmap-cell" style="background-color: #CC5500; width: 100%;"></div> <!-- orange-900 -->
                                <div class="heatmap-cell" style="background-color: #FFE5CC; width: 100%;"></div> <!-- orange-100 -->
                                <div class="heatmap-cell" style="background-color: #FFB366; width: 100%;"></div> <!-- orange-400 -->
                            </div>
                        </div>
                        
                        <!-- Legend -->
                        <div class="flex items-center justify-end gap-4 mt-4">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded" style="background-color: #FFE5CC"></div>
                                <span class="text-xs text-gray-500">Low</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded" style="background-color: #FFB366"></div>
                                <span class="text-xs text-gray-500">Medium</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded" style="background-color: #CC5500"></div>
                                <span class="text-xs text-gray-500">High</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    {{-- start of modals --}}
    {{-- add student modal--}}
    <el-dialog>
        <dialog id="dialog" aria-labelledby="dialog-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
            <el-dialog-backdrop class="fixed inset-0 bg-gray-900/50 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

            <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
                <el-dialog-panel class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl outline -outline-offset-1 outline-white/10 transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-4xl data-closed:sm:translate-y-0 data-closed:sm:scale-95">
                    
                    <!-- Header -->
                    <div class="bg-[#1c1c1d] p-4 text-white flex items-center gap-2">
                        <i class="fa fa-plus text-xl" aria-hidden="true"></i>
                        <h1 class="font-bold text-xl">Add User</h1>
                    </div>
                    
                    <!-- Form Body -->
                    <div class="px-6 pt-6 pb-4 bg-white">
                        <form action="" class="space-y-5">
                            <!-- Branch - Moved to top as it's a primary identifier -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Branch <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                    <option value="" disabled selected>Select Branch</option>
                                    <option value="quezon-city">Quezon City</option>
                                    <option value="bulacan">Bulacan</option>
                                    <option value="caloocan">Caloocan</option>
                                    <option value="manila">Manila</option>
                                </select>
                            </div>

                            <!-- Student Name - 2 columns -->
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Firstname <span class="text-red-500">*</span></label>
                                    <input type="text" placeholder="Ex. Juan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Lastname <span class="text-red-500">*</span></label>
                                    <input type="text" placeholder="Ex. Dela Cruz" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                        <option value="" disabled selected>Select gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                        <option value="prefer-not">Prefer not to say</option>
                                    </select>
                                </div>
                                
                            </div>


                            <!-- Current Belt -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Belt <span class="text-red-500">*</span></label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                        <option value="" disabled selected>Select Belt</option>
                                        <option value="white">White Belt</option>
                                        <option value="yellow">Yellow Belt</option>
                                        <option value="orange">Orange Belt</option>
                                        <option value="green">Green Belt</option>
                                        <option value="purple">Purple Belt</option>
                                        <option value="blue">Blue Belt</option>
                                        <option value="blue-sr">Blue Sr. Belt</option>
                                        <option value="brown">Brown Belt</option>
                                        <option value="brown-sr">Brown Sr. Belt</option>
                                        <option value="red">Red Belt</option>
                                        <option value="jr-black">Jr. Black Belt</option>
                                        <option value="black">Black Belt</option>
                                    </select>
                                    
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Birthday <span class="text-red-500">*</span></label>
                                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                </div>
                            </div>
                            
                            <!-- Medical Information Section -->
                            <div class="">
                                <!-- Medical Notes - Textarea with professional styling -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Medical Notes</label>
                                    <textarea rows="4" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] resize-y min-h-[100px] bg-white placeholder-gray-400"
                                        placeholder="Any medical conditions, injuries, or special needs..."></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Include any important medical information that instructors should know</p>
                                </div>

                                <!-- Allergies -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Allergies</label>
                                    <input type="text" placeholder="e.g., Dust, Pollen, Food allergies" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                </div>
                            </div>

                            <!-- Emergency Contact Section -->
                            <div class="">
                                
                                
                                <div class="grid grid-cols-3 gap-4">
                                    <!-- Emergency Contact Person -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person <span class="text-red-500">*</span></label>
                                        <input type="text" placeholder="Full name" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                    </div>
                                    <!-- Emergency Phone Number -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number <span class="text-red-500">*</span></label>
                                        <input type="tel" placeholder="Ex. 09123456789" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                    </div>
                                    <!-- Relationship to Student -->
                                    <div class="">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Relationship to Student</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                            <option value="" disabled selected>Select relationship</option>
                                            <option value="parent">Parent</option>
                                            <option value="guardian">Legal Guardian</option>
                                            <option value="sibling">Sibling</option>
                                            <option value="relative">Other Relative</option>
                                        </select>
                                    </div>
                                </div>

                                
                            </div>

                            <!-- Guardian and Profile Picture - 2 columns -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Guardian Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Primary Guardian <span class="text-red-500">*</span></label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                        <option value="" disabled selected>Select Guardian</option>
                                        <option value="maria-jose">Maria Jose</option>
                                        <option value="juan-dela-cruz">Juan Dela Cruz</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Primary guardian for communications</p>
                                </div>
                                <div>
                                    <!-- Status-->
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="pt-2">
                                
                                <!-- Profile Picture -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                                    <input type="file" accept=".png,.jpeg,.jpg"
                                        class="w-full text-sm text-gray-500 cursor-pointer 
                                            file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 
                                            file:text-sm file:font-medium file:bg-[#1c1c1d] file:text-white
                                            hover:file:bg-[#2f2f2f] focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]
                                            transition-all duration-200">
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPEG only (max 2MB)</p>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <hr class="m-0 border-t border-gray-200">
                    
                    <!-- Footer Buttons -->
                    <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2">
                        <button type="button"  command="close" commandfor="dialog" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            Close
                        </button>
                        <button type="submit" command="close" commandfor="dialog" class="px-4 py-2 text-sm font-medium text-white bg-[#1c1c1d] rounded-md hover:bg-[#2f2f2f] transition-colors">
                            Add
                        </button>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>
    {{-- end add student modal --}}


    {{-- start Parent model --}}
    <el-dialog>
    <dialog id="parentDialog" aria-labelledby="dialog-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
        <el-dialog-backdrop class="fixed inset-0 bg-gray-900/50 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

        <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
            <el-dialog-panel class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl outline -outline-offset-1 outline-white/10 transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-3xl data-closed:sm:translate-y-0 data-closed:sm:scale-95">
                
                <!-- Header -->
                <div class="bg-[#1c1c1d] p-4 text-white flex items-center gap-2">
                    <i class="fa fa-plus text-xl" aria-hidden="true"></i>
                    <h1 class="font-bold text-xl">Add Parent / Guardian</h1>
                </div>
                
                <!-- Form Body -->
                <div class="px-6 pt-6 pb-4 bg-white">
                    <form action="" class="space-y-5">
                        <!-- Parent Name - 3 columns (matching student modal style) -->
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Firstname <span class="text-red-500">*</span></label>
                                <input type="text" placeholder="Ex. Maria" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lastname <span class="text-red-500">*</span></label>
                                <input type="text" placeholder="Ex. Santos" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                    <option value="" disabled selected>Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                    <option value="prefer-not">Prefer not to say</option>
                                </select>
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Complete Address <span class="text-red-500">*</span></label>
                            <textarea rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] resize-y min-h-[80px] bg-white placeholder-gray-400"
                                placeholder="House/Block/Lot, Street, Barangay, City, Province"></textarea>
                        </div>

                        <!-- Relationship Note -->
                        <div class="">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Relationship Note</label>
                            <textarea rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] resize-y min-h-[80px] bg-white placeholder-gray-400"
                                placeholder="e.g., Mother, Father, Legal Guardian, Aunt - Add any additional notes about the relationship"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Specify relationship to the student(s)</p>
                        </div>

                        <!-- Contact Information -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" placeholder="Ex. 09123456789" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                            </div>
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- ID Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valid ID Upload</label>
                            <input type="file" accept=".png,.jpeg,.jpg,.pdf"
                                class="w-full text-sm text-gray-500 cursor-pointer 
                                    file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 
                                    file:text-sm file:font-medium file:bg-[#1c1c1d] file:text-white
                                    hover:file:bg-[#2f2f2f] focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]
                                    transition-all duration-200">
                            <p class="text-xs text-gray-500 mt-1">PNG, JPEG, PDF only (max 5MB)</p>
                        </div>

                        <!-- Created At (from image - hidden but included for reference) -->
                        <input type="hidden" name="created_at" value="{{ now() }}">

                        <!-- Linked Students -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Associated Students</label>
                            <div class="border border-gray-300 rounded-md p-3 max-h-32 overflow-y-auto">
                                <div class="flex items-center gap-2 mb-2">
                                    <input type="checkbox" id="student1" class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                    <label for="student1" class="text-sm text-gray-700">Juan Dela Cruz (Student ID: STU-2025-001)</label>
                                </div>
                                <div class="flex items-center gap-2 mb-2">
                                    <input type="checkbox" id="student2" class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                    <label for="student2" class="text-sm text-gray-700">Maria Dela Cruz (Student ID: STU-2025-002)</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="student3" class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                    <label for="student3" class="text-sm text-gray-700">Jose Dela Cruz (Student ID: STU-2025-003)</label>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Select students associated with this parent/guardian</p>
                        </div>
                    </form>
                </div>
                
                <hr class="m-0 border-t border-gray-200">
                
                <!-- Footer Buttons -->
                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2">
                    <button type="button" command="close" commandfor="parentDialog" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                    <button type="submit" command="close" commandfor="parentDialog" class="px-4 py-2 text-sm font-medium text-white bg-[#1c1c1d] rounded-md hover:bg-[#2f2f2f] transition-colors">
                        Add
                    </button>
                </div>
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>
{{-- end parent modal --}}

    {{-- end of modals --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    {{-- <script>Chart.register(ChartDataLabels);</script> --}}
    @vite(['resources/js/app.js'])
    @vite(['resources/js/dashboard.js'])
</body>
</html>