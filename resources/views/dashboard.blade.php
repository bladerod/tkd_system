<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrainNova | Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    <!-- Action Buttons -->
                    <div class="grid grid-cols-4 gap-4 mb-8">
                        <div>
                            <button command="show-modal" commandfor="dialog"
                                class="button w-full bg-[#1C1C1D] text-white px-4 py-3 rounded-lg hover:bg-[#535353] transition-colors flex items-center justify-center gap-2">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Add Student
                            </button>
                        </div>
                        <div>
                            <button command="show-modal" commandfor="parentDialog"
                                class="button w-full bg-[#1C1C1D] text-white px-4 py-3 rounded-lg hover:bg-[#535353] transition-colors flex items-center justify-center gap-2">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Add Parent
                            </button>
                        </div>

                        <div>
                            <button
                                class="button w-full bg-[#1C1C1D] text-white px-4 py-3 rounded-lg hover:bg-[#535353] transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-award"></i>
                                Generate Certificate
                            </button>
                        </div>
                        <div>
                            <button command="show-modal" commandfor="announcementDialog"
                                class="button w-full bg-[#1C1C1D] text-white px-4 py-3 rounded-lg hover:bg-[#535353] transition-colors flex items-center justify-center gap-2">
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
                                    <p class="text-5xl font-bold text-[#1C1C1D]">{{ $students->count() }}</p>
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
                                    <p class="text-5xl font-bold text-[#1C1C1D]">{{ $parents->count() }}</p>
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
                                    <p class="text-5xl font-bold text-[#1C1C1D]">{{ $todayAttendance->count() }}</p>
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
                                    <p class="text-5xl font-bold text-[#1C1C1D]">
                                        ₱{{ number_format($outstandingBalance, 2) }}</p>
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
                                            <div class="relative" x-data="{ open: false, selected: 'Weekly' }">
                                                <button @click="open = !open"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-[#1C1C1D] border border-[#1C1C1D] rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#1C1C1D] focus:ring-offset-2 transition-all duration-200"
                                                    type="button">
                                                    <span x-text="selected"></span>
                                                    <svg class="w-4 h-4 transition-transform duration-200"
                                                        :class="{ 'rotate-180': open }" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>

                                                <ul x-show="open" @click.away="open = false"
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="transform opacity-0 scale-95"
                                                    x-transition:enter-end="transform opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="transform opacity-100 scale-100"
                                                    x-transition:leave-end="transform opacity-0 scale-95"
                                                    class="absolute right-0 z-10 min-w-[160px] mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                                                    <li>
                                                        <a href="#"
                                                            @click.prevent="selected = 'Weekly'; open = false; window.updateRevenueChartPeriod('weekly')"
                                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150 rounded-t-lg">
                                                            Weekly (Last 7 Days)
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#"
                                                            @click.prevent="selected = 'Monthly'; open = false; window.updateRevenueChartPeriod('monthly')"
                                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150 rounded-b-lg">
                                                            Monthly (This Year)
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                        <div class="chart">
                                            <canvas id="chart-monthly-revenue" class="chart-canvas"
                                                height="300"></canvas>
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
                                            <div class="relative" x-data="{ open: false, selected: 'Weekly' }">
                                                <!-- Dropdown Button -->
                                                <button @click="open = !open"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-[#1C1C1D] border border-[#1C1C1D] rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#1C1C1D] focus:ring-offset-2 transition-all duration-200"
                                                    type="button">
                                                    <span x-text="selected"></span>
                                                    <svg class="w-4 h-4 transition-transform duration-200"
                                                        :class="{ 'rotate-180': open }" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>

                                                <!-- Dropdown Menu -->
                                                <ul x-show="open" @click.away="open = false"
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="transform opacity-0 scale-95"
                                                    x-transition:enter-end="transform opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="transform opacity-100 scale-100"
                                                    x-transition:leave-end="transform opacity-0 scale-95"
                                                    class="absolute right-0 z-10 min-w-[160px] mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                                                    <li>
                                                        <a href="#"
                                                            @click.prevent="selected = 'Weekly'; open = false; window.updateEnrolleesChartPeriod ('monthly')"
                                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#1C1C1D] transition-colors duration-150 rounded-t-lg"
                                                            :class="{ 'bg-gray-100 text-[#1C1C1D]': selected === 'Weekly' }">
                                                            Weekly (Last 7 Days)
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#"
                                                            @click.prevent="selected = 'Monthly'; open = false; window.updateEnrolleesChartPeriod('annual')"
                                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#1C1C1D] transition-colors duration-150 rounded-b-lg"
                                                            :class="{ 'bg-gray-100 text-[#1C1C1D]': selected === 'Monthly' }">
                                                            Monthly (This Year)
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                        <div class="chart">
                                            <canvas id="chart-monthly-enrollees" class="chart-canvas"
                                                height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Heatmap -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h3 class="text-gray-800 font-semibold mb-6 text-center text-2xl">Attendance Heatmap</h3>

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
                                @foreach($timeLabels as $bucket => $label)
                                    <div class="grid grid-cols-8 gap-2 items-center">
                                        <div class="text-xs text-gray-500 font-medium">{{ $label }}</div>

                                        @for($day = 1; $day <= 7; $day++)
                                            @php
                                                $count = $heatmapData[$bucket][$day];

                                                // Default Color for Zero Attendances
                                                $color = '#f3f4f6'; // Tailwind gray-100

                                                // Dynamic Orange Scaling
                                                if ($count > 0) {
                                                    $percentage = $count / $maxHeatmapCount;

                                                    if ($percentage <= 0.33) {
                                                        $color = '#FFE5CC'; // Low
                                                    } elseif ($percentage <= 0.66) {
                                                        $color = '#FFB366'; // Medium
                                                    } else {
                                                        $color = '#CC5500'; // High
                                                    }
                                                }
                                            @endphp

                                            <div class="heatmap-cell rounded transition-all duration-300 hover:scale-105 cursor-pointer"
                                                style="background-color: {{ $color }}; width: 100%; height: 24px;"
                                                title="{{ $count }} Attendances ({{ $label }})">
                                            </div>
                                        @endfor
                                    </div>
                                @endforeach
                            </div>

                            <!-- Legend -->
                            <div class="flex items-center justify-end gap-4 mt-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded"
                                        style="background-color: #f3f4f6 border: 1px solid #e5e7eb;"></div>
                                    <span class="text-xs text-gray-500">None (0)</span>
                                </div>
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
        <dialog id="dialog" aria-labelledby="dialog-title"
            class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
            <el-dialog-backdrop
                class="fixed inset-0 bg-gray-900/50 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

            <div tabindex="0"
                class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
                <el-dialog-panel
                    class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl outline -outline-offset-1 outline-white/10 transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-4xl data-closed:sm:translate-y-0 data-closed:sm:scale-95">

                    <!-- Header -->
                    <div class="bg-[#1c1c1d] p-4 text-white flex items-center gap-2">
                        <i class="fa fa-plus text-xl" aria-hidden="true"></i>
                        <h1 class="font-bold text-xl">Add Students</h1>
                    </div>

                    <!-- Form Body -->
                    <div class="px-6 pt-6 pb-4 bg-white">
                        <form action="{{ route('dashboard.student.store') }}" method="POST"
                            enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            <!-- Branch - Moved to top as it's a primary identifier -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Branch <span
                                        class="text-red-500">*</span></label>
                                <select name="branch_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                    <option value="" disabled selected>Select branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->code . ' ' . $branch->name . ' ' . $branch->city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Student Name - 2 columns -->
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Firstname <span
                                            class="text-red-500">*</span></label>
                                    <input name="first_name" type="text" placeholder="Ex. Juan"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Lastname <span
                                            class="text-red-500">*</span></label>
                                    <input name="last_name" type="text" placeholder="Ex. Dela Cruz"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span
                                            class="text-red-500">*</span></label>
                                    <select name="gender"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                        <option value="" disabled selected>Select gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">other</option>
                                    </select>
                                </div>

                            </div>


                            <!-- Current Belt -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Belt <span
                                            class="text-red-500">*</span></label>
                                    <select name="belt_level"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                        <option value="" disabled selected>Select Belt</option>
                                        @foreach ($beltlevels as $beltlevel)
                                            <option value="{{ $beltlevel->id }}">{{ $beltlevel->name }}</option>
                                        @endforeach

                                    </select>

                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Birthday <span
                                            class="text-red-500">*</span></label>
                                    <input name="birthdate" type="date"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                </div>
                            </div>

                            <!-- Medical Information Section -->
                            <div class="">
                                <!-- Medical Notes - Textarea with professional styling -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Medical Notes</label>
                                    <textarea rows="4" name="medical_notes"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] resize-y min-h-[100px] bg-white placeholder-gray-400"
                                        placeholder="Any medical conditions, injuries, or special needs..."></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Include any important medical information that
                                        instructors should know</p>
                                </div>

                                <!-- Allergies -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Allergies</label>
                                    <input name="allergies" type="text" placeholder="e.g., Dust, Pollen, Food allergies"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                </div>
                            </div>

                            <!-- Emergency Contact Section -->
                            <div class="">


                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Emergency Contact Person -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person <span
                                                class="text-red-500">*</span></label>
                                        <input name="contact_person" type="text" placeholder="Full name"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                    </div>
                                    <!-- Emergency Phone Number -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number <span
                                                class="text-red-500">*</span></label>
                                        <input name="contact_number" type="tel" placeholder="Ex. 09123456789"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                    </div>
                                    {{-- <!-- Relationship to Student -->
                                    <div class="">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Relationship to
                                            Student</label>
                                        <select
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                            <option value="" disabled selected>Select relationship</option>
                                            <option value="parent">Parent</option>
                                            <option value="guardian">Legal Guardian</option>
                                            <option value="sibling">Sibling</option>
                                            <option value="relative">Other Relative</option>
                                        </select>
                                    </div> --}}
                                </div>
                            </div>

                            <!-- Guardian and Profile Picture - 2 columns -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Guardian Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Primary Guardian <span
                                            class="text-red-500">*</span></label>
                                    <select name="primary_parent_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                        <option value="" disabled selected>Select Guardian</option>
                                        @foreach ($parents as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->fname . ' ' . $parent->lname }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Primary guardian for communications</p>
                                </div>
                                <div>
                                    <!-- Status-->
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Login Credentials -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                            class="text-red-500">*</span></label>
                                    <input name="email" type="email" placeholder="example@gmail.com"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span
                                            class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input id="studentPassword" name="password" type="password"
                                            placeholder="••••••••"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] pr-10">
                                        <button type="button" onclick="toggleStudentPassword()"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                            <i id="studentPasswordIcon" class="fa fa-eye text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">

                                <!-- Profile Picture -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                                    <input type="file" accept=".png,.jpeg,.jpg" class="w-full text-sm text-gray-500 cursor-pointer 
                                            file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 
                                            file:text-sm file:font-medium file:bg-[#1c1c1d] file:text-white
                                            hover:file:bg-[#2f2f2f] focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]
                                            transition-all duration-200">
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPEG only (max 2MB)</p>
                                </div>
                            </div>
                            <hr class="m-0 border-t border-gray-200">
                            <!-- Footer Buttons -->
                            <div class=" px-6 py-3 flex justify-end gap-2">
                                <button type="button" command="close" commandfor="dialog"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                    Close
                                </button>
                                <button type="submit" command="close" commandfor="dialog"
                                    class="px-4 py-2 text-sm font-medium text-white bg-[#1c1c1d] rounded-md hover:bg-[#2f2f2f] transition-colors">
                                    Add
                                </button>
                            </div>
                        </form>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>
    {{-- end add student modal --}}


    {{-- start Parent modal --}}
    <el-dialog>
        <dialog id="parentDialog" aria-labelledby="dialog-title"
            class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
            <el-dialog-backdrop
                class="fixed inset-0 bg-gray-900/50 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

            <div tabindex="0"
                class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
                <el-dialog-panel
                    class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl outline -outline-offset-1 outline-white/10 transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-3xl data-closed:sm:translate-y-0 data-closed:sm:scale-95">

                    <!-- Header -->
                    <div class="bg-[#1c1c1d] p-4 text-white flex items-center gap-2">
                        <i class="fa fa-plus text-xl" aria-hidden="true"></i>
                        <h1 class="font-bold text-xl">Add Parent</h1>
                    </div>

                    <!-- Form Body -->
                    <div class="px-6 pt-6 pb-4 bg-white">
                        <form action="{{ route('parent.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Parent Name - 2 columns -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Firstname <span
                                            class="text-red-500">*</span></label>
                                    <input name="fname" type="text" value="{{ old('fname') }}" placeholder="Ex. Maria"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                    @error('fname')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Lastname <span
                                            class="text-red-500">*</span></label>
                                    <input name="lname" type="text" value="{{ old('lname') }}" placeholder="Ex. Santos"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                    @error('lname')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Complete Address <span
                                        class="text-red-500">*</span></label>
                                <textarea name="address" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] resize-y min-h-[80px] bg-white placeholder-gray-400"
                                    placeholder="House/Block/Lot, Street, Barangay, City, Province">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Relationship Note -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Relationship Note</label>
                                <textarea name="relationship_note" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] resize-y min-h-[60px] bg-white placeholder-gray-400"
                                    placeholder="e.g., Mother, Father, Legal Guardian, Aunt">{{ old('relationship_note') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Specify relationship to the student(s)</p>
                            </div>

                            <!-- Contact Information -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input name="mobile" type="tel" value="{{ old('mobile') }}"
                                        placeholder="Ex. 09123456789"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                    @error('mobile')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input name="email" type="email" value="{{ old('email') }}"
                                        placeholder="example@gmail.com"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Credentials -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                    <input name="password" type="password"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                    <input name="password_confirmation" type="password"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span
                                        class="text-red-500">*</span></label>
                                <select name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                    <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select Status
                                    </option>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ID Upload -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Valid ID Upload</label>
                                <input name="photo_url" type="file" accept=".png,.jpeg,.jpg,.pdf" class="w-full text-sm text-gray-500 cursor-pointer 
                                    file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 
                                    file:text-sm file:font-medium file:bg-[#1c1c1d] file:text-white
                                    hover:file:bg-[#2f2f2f] focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]
                                    transition-all duration-200">
                                <p class="text-xs text-gray-500 mt-1">PNG, JPEG, PDF only (max 5MB)</p>
                                @error('photo_url')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Associated Students -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Associated Students</label>
                                <div class="border border-gray-300 rounded-md p-3 max-h-48 overflow-y-auto bg-gray-50">
                                    @if(isset($students) && count($students) > 0)
                                        @foreach($students as $student)
                                            <div class="flex items-center gap-2 mb-2 hover:bg-gray-100 p-1 rounded">
                                                <input type="checkbox" name="students[]" value="{{ $student->id }}"
                                                    id="student_{{ $student->id }}"
                                                    class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d] cursor-pointer" {{ in_array($student->id, old('students', [])) ? 'checked' : '' }}>
                                                <label for="student_{{ $student->id }}"
                                                    class="text-sm text-gray-700 cursor-pointer flex-1">
                                                    <span class="font-medium">{{ $student->student_name }}</span>
                                                    <span
                                                        class="text-xs text-gray-500 ml-2">({{ $student->student_code }})</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-500 text-center py-4">No active students found. Please
                                            add students first.</p>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Select students associated with this
                                    parent/guardian</p>
                                @error('students')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <hr class="m-0 border-t border-gray-200 mt-4">
                            <!-- Footer Buttons -->
                            <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 mt-4">
                                <button type="button" command="close" commandfor="parentDialog"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                    Close
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-[#1c1c1d] rounded-md hover:bg-[#2f2f2f] transition-colors">
                                    Add Parent
                                </button>
                            </div>
                        </form>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>
    {{-- end parent modal --}}


    {{-- add announcement modal--}}
    <el-dialog>
        <dialog id="announcementDialog" aria-labelledby="dialog-title"
            class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">
            <el-dialog-backdrop
                class="fixed inset-0 bg-gray-900/50 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"></el-dialog-backdrop>

            <div tabindex="0"
                class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0">
                <el-dialog-panel
                    class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl outline -outline-offset-1 outline-white/10 transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-4xl data-closed:sm:translate-y-0 data-closed:sm:scale-95">

                    <!-- Header -->
                    <div class="bg-[#1c1c1d] p-4 text-white flex items-center gap-2">
                        <i class="fa fa-bullhorn" aria-hidden="true"></i>
                        <h1 class="font-bold text-xl">Send Announcement</h1>
                    </div>

                    <!-- Form Body -->
                    <div class="px-6 pt-6 pb-4 bg-white">
                        <form 
                        {{-- id="addAnnouncementForm"  --}}
                        method="POST" action="{{ route('dashboard.announcement.store') }}"
                            class="space-y-5">
                            @csrf

                            <!-- Created By (Hidden - will be set from session) -->
                            <input type="hidden" name="created_by_user_id" value="{{ auth()->user()->id ?? 1 }}">

                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="title" placeholder="Enter announcement title" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                            </div>

                            <!-- Message -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message <span
                                        class="text-red-500">*</span></label>
                                <textarea name="message" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] resize-y min-h-[100px] bg-white placeholder-gray-400"
                                    placeholder="Enter announcement message..."></textarea>
                            </div>

                            <!-- Target Audience -->
                            <div class="">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Target Audience <span
                                            class="text-red-500">*</span></label>
                                    <select name="target_type" id="target_type" required onchange="toggleTargetTypeFields()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                        <option value="" disabled selected>Select target</option>
                                        <option value="all">All</option>
                                        <option value="class">Class</option>
                                        <option value="belt">Belt</option>
                                        <option value="branch">Branch</option>
                                    </select>
                                </div>

                                <!-- Competition Team -->
                                {{-- <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Competition Team</label>
                                    <input type="text" name="competition_team" placeholder="Enter team name"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                                </div> --}}
                            </div>

                            <!-- Class Field (conditional) -->
                            <div id="class_field" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select Class <span
                                        class="text-red-500">*</span></label>
                                <select name="class_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                    <option value="">Select class</option>
                                    @isset($classes)
                                        @forelse($classes as $class)
                                            <option value="{{ $class->id }}">
                                                {{ $class->class_name }}
                                            </option>
                                        @empty
                                            <option value="" disabled>No classes available</option>
                                        @endforelse
                                    @else
                                        <option value="" disabled>Classes not loaded</option>
                                    @endisset
                                </select>
                            </div>

                            <!-- Belt Field (conditional) -->
                            <div id="belt_field" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Belt Level <span
                                        class="text-red-500">*</span></label>
                                <select name="belt_level"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                    <option value="">Select belt level</option>
                                    <option value="White Belt">White Belt</option>
                                    <option value="Yellow Belt">Yellow Belt</option>
                                    <option value="Orange Belt">Orange Belt</option>
                                    <option value="Green Belt">Green Belt</option>
                                    <option value="Blue Belt">Blue Belt</option>
                                    <option value="Brown Belt">Brown Belt</option>
                                    <option value="Black Belt">Black Belt</option>
                                </select>
                            </div>

                            <!-- Branch Field (conditional) -->
                            <div id="branch_field" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select Branch <span
                                        class="text-red-500">*</span></label>
                                <select name="branch_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                    <option value="">Select branch</option>
                                    @isset($branches)
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>

                            <!-- Channel -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Channel <span
                                        class="text-red-500">*</span></label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="channel[]" value="App"
                                            class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                        <span class="text-sm text-gray-700">App</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="channel[]" value="SMS"
                                            class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                        <span class="text-sm text-gray-700">SMS</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="channel[]" value="Email"
                                            class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                        <span class="text-sm text-gray-700">Email</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Expire Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expire Date <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="expire_date" required min="{{ date('Y-m-d') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                            </div>
                            <hr class="opacity-40">
                            <div class=" px-6 py-3 flex justify-end gap-2">
                                <button type="button" command="close" commandfor="dialog"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                    Close
                                </button>
                                <button type="submit" command="close" commandfor="dialog"
                                    class="px-4 py-2 text-sm font-medium text-white bg-[#1c1c1d] rounded-md hover:bg-[#2f2f2f] transition-colors">
                                    Send
                                </button>
                            </div>
                        </form>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>
    {{-- end send announcement modal --}}


    {{-- end of modals --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script>
        window.dynamicEnrolleesData = @json($enrolleesChartData);
        window.dynamicRevenueData = @json($revenueChartData);
    </script>
    {{-- @vite(['resources/js/app.js']) --}}
    @vite(['resources/js/dashboard.js'])
    @vite(['resources/js/navbarDrop.js'])

    <script>
        function toggleStudentPassword() {
            const input = document.getElementById('studentPassword');
            const icon = document.getElementById('studentPasswordIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>